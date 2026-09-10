import {
  isSafePWAEnv,
  isStandalonePWA,
  watchServiceWorkerUpdates,
} from "mazey";
import type { ServiceWorkerUpdateWatcher } from "mazey";
import type { SiteRuntimeConfig } from "./runtime-config";

interface BeforeInstallPromptEvent extends Event {
  prompt(): Promise<void>;
  userChoice: Promise<{ outcome: "accepted" | "dismissed" }>;
}

export function initializePwa(config: SiteRuntimeConfig["pwa"]): void {
  const installButtons = Array.from(
    document.querySelectorAll<HTMLButtonElement>("[data-pwa-install]"),
  );
  const statusRegions = Array.from(
    document.querySelectorAll<HTMLElement>("[data-pwa-status]"),
  );
  const announce = (message: string) =>
    statusRegions.forEach((region) => {
      region.textContent = message;
    });
  let prompt: BeforeInstallPromptEvent | null = null;

  if (isStandalonePWA())
    installButtons.forEach((button) => (button.hidden = true));

  window.addEventListener("beforeinstallprompt", (event) => {
    if (isStandalonePWA()) return;
    event.preventDefault();
    prompt = event as BeforeInstallPromptEvent;
    installButtons.forEach((button) => (button.hidden = false));
  });
  installButtons.forEach((button) => {
    button.addEventListener("click", async () => {
      if (!prompt) return;
      const current = prompt;
      prompt = null;
      button.disabled = true;
      try {
        await current.prompt();
        const choice = await current.userChoice;
        announce(
          choice.outcome === "accepted"
            ? "Installation was accepted."
            : "Installation was dismissed.",
        );
      } catch {
        announce("Use the browser install menu to install this website.");
      } finally {
        button.hidden = true;
      }
    });
  });

  if (!config.enabled || !isSafePWAEnv({ scope: config.scope })) return;
  const register = async () => {
    try {
      const registration = await navigator.serviceWorker.register(
        config.serviceWorkerUrl,
        { scope: config.scope },
      );
      const notice = document.querySelector<HTMLElement>("[data-pwa-update]");
      const updateButton = document.querySelector<HTMLButtonElement>(
        "[data-pwa-update-now]",
      );
      let reloadRequested = false;
      const watcher: ServiceWorkerUpdateWatcher = watchServiceWorkerUpdates(
        registration,
        navigator.serviceWorker,
        {
          onUpdateAvailable: () => {
            if (notice) notice.hidden = false;
            announce(
              `A new version of the ${config.appName} website is available.`,
            );
          },
          onControllerChange: () => {
            if (reloadRequested) window.location.reload();
          },
        },
      );
      updateButton?.addEventListener("click", () => {
        reloadRequested = watcher.activateWaiting();
        if (reloadRequested) {
          updateButton.disabled = true;
          announce("Updating the website now.");
        }
      });
    } catch (error) {
      console.error("Failed to register the mazey.css service worker.", error);
    }
  };

  if (document.readyState === "complete") window.setTimeout(register, 0);
  else window.addEventListener("load", () => void register(), { once: true });
}
