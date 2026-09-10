const PROJECT_BASE = "__PWA_PROJECT_BASE__";
const CACHE_PREFIX = "__PWA_CACHE_PREFIX__";
const CACHE_NAME = `${CACHE_PREFIX}__PWA_CACHE_VERSION__`;
const MAX_RUNTIME_CACHE_ENTRIES = 96;
const APP_SHELL = [
  PROJECT_BASE,
  `${PROJECT_BASE}playground/`,
  `${PROJECT_BASE}api/`,
  `${PROJECT_BASE}manifest.webmanifest`,
  `${PROJECT_BASE}assets/shared.css`,
  `${PROJECT_BASE}assets/shared.js`,
  `${PROJECT_BASE}assets/home.js`,
  `${PROJECT_BASE}assets/playground.css`,
  `${PROJECT_BASE}assets/playground.js`,
  `${PROJECT_BASE}assets/api.js`,
  `${PROJECT_BASE}package-styles/index.css`,
];
const APP_SHELL_PATHS = new Set(APP_SHELL);

function cacheable(response) {
  return response.ok && response.status === 200 && response.type !== "opaque";
}

async function store(request, response) {
  if (!cacheable(response)) return;
  try {
    const cache = await caches.open(CACHE_NAME);
    await cache.put(request, response.clone());
    await trimCache(cache);
  } catch {
    // Cache Storage is best-effort.
  }
}

async function trimCache(cache) {
  const keys = await cache.keys();
  const runtimeKeys = keys.filter((request) => {
    const url = new URL(request.url);
    return !APP_SHELL_PATHS.has(`${url.pathname}${url.search}`);
  });
  await Promise.all(
    runtimeKeys
      .slice(0, Math.max(0, runtimeKeys.length - MAX_RUNTIME_CACHE_ENTRIES))
      .map((request) => cache.delete(request)),
  );
}

async function networkFirst(request) {
  try {
    const response = await fetch(request);
    await store(request, response);
    return response;
  } catch (error) {
    const cached = await caches.match(request, { cacheName: CACHE_NAME });
    if (cached) return cached;
    if (request.mode === "navigate") {
      const home = await caches.match(PROJECT_BASE, { cacheName: CACHE_NAME });
      if (home) return home;
    }
    throw error;
  }
}

async function cacheFirst(request) {
  const cached = await caches.match(request, { cacheName: CACHE_NAME });
  if (cached) return cached;
  const response = await fetch(request);
  await store(request, response);
  return response;
}

self.addEventListener("install", (event) => {
  event.waitUntil(
    (async () => {
      try {
        const cache = await caches.open(CACHE_NAME);
        const responses = await Promise.all(
          APP_SHELL.map((url) => fetch(url, { cache: "reload" })),
        );
        if (responses.some((response) => !cacheable(response))) {
          throw new Error("A required app-shell response was not cacheable.");
        }
        await Promise.all(
          responses.map((response, index) =>
            cache.put(APP_SHELL[index], response),
          ),
        );
      } catch (error) {
        await caches.delete(CACHE_NAME).catch(() => undefined);
        throw error;
      }
    })(),
  );
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((names) =>
        Promise.all(
          names
            .filter(
              (name) => name.startsWith(CACHE_PREFIX) && name !== CACHE_NAME,
            )
            .map((name) => caches.delete(name)),
        ),
      )
      .then(() => self.clients.claim()),
  );
});

self.addEventListener("message", (event) => {
  if (event.data?.type === "SKIP_WAITING") self.skipWaiting();
});

self.addEventListener("fetch", (event) => {
  const { request } = event;
  const url = new URL(request.url);
  const isPackageStylesheet =
    url.pathname.startsWith(`${PROJECT_BASE}package-styles/`) &&
    url.pathname.endsWith(".css");
  if (
    request.method !== "GET" ||
    url.origin !== self.location.origin ||
    !url.pathname.startsWith(PROJECT_BASE) ||
    url.pathname.endsWith(".map")
  )
    return;

  if (
    request.mode === "navigate" ||
    isPackageStylesheet ||
    ["document", "script", "style"].includes(request.destination)
  )
    event.respondWith(networkFirst(request));
  else if (["font", "image"].includes(request.destination))
    event.respondWith(cacheFirst(request));
});
