import {
  listenMediaQueryChanges,
  resolveThemePreference,
  setThemePreference,
} from "mazey";
import type { ResolvedTheme, ThemePreference } from "mazey";

const SYSTEM_QUERY = "(prefers-color-scheme: dark)";

function selectedPreference(
  result: ReturnType<typeof resolveThemePreference>,
): ThemePreference {
  return result.label === "System" ? "system" : result.value;
}

function sessionResolution(
  preference: ThemePreference,
  media: MediaQueryList | null,
): ResolvedTheme {
  if (preference !== "system") return preference;
  return media?.matches ? "dark" : "light";
}

export function initializeTheme(storageKey: string): () => void {
  const root = document.documentElement;
  if (root.dataset.themeReady === "true") return () => undefined;

  let media: MediaQueryList | null = null;
  try {
    media = window.matchMedia(SYSTEM_QUERY);
  } catch {
    media = null;
  }

  const initial = resolveThemePreference(storageKey);
  let preference = selectedPreference(initial);
  let storageAvailable = true;

  const apply = (resolved: ResolvedTheme) => {
    root.dataset.bsTheme = resolved;
    root.dataset.theme = resolved;
    root.style.colorScheme = resolved;
    document
      .querySelectorAll<HTMLSelectElement>("[data-theme-select]")
      .forEach((select) => {
        select.value = preference;
      });
    const meta = document.querySelector<HTMLMetaElement>(
      'meta[name="theme-color"][data-theme-color]',
    );
    if (meta) {
      meta.content =
        resolved === "dark"
          ? (meta.dataset.themeColorDark ?? meta.content)
          : (meta.dataset.themeColorLight ?? meta.content);
    }
  };

  const resolveCurrent = () =>
    storageAvailable
      ? resolveThemePreference(storageKey).value
      : sessionResolution(preference, media);

  const handleChange = (event: Event) => {
    const target = event.target;
    if (!(target instanceof HTMLSelectElement)) return;
    if (!target.matches("[data-theme-select]")) return;
    const next = target.value as ThemePreference;
    try {
      storageAvailable = setThemePreference(storageKey, next);
    } catch (error) {
      if (!(error instanceof TypeError)) throw error;
      target.value = preference;
      apply(resolveCurrent());
      return;
    }
    preference = next;
    apply(resolveCurrent());
  };
  const handleSystemChange = () => {
    if (preference === "system") apply(resolveCurrent());
  };

  root.dataset.themeReady = "true";
  apply(initial.value);
  document.addEventListener("change", handleChange);
  const stopMedia = listenMediaQueryChanges(media, handleSystemChange);

  return () => {
    document.removeEventListener("change", handleChange);
    stopMedia();
    delete root.dataset.themeReady;
  };
}
