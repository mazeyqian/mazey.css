const assert = require("node:assert/strict");
const { mkdtempSync, readFileSync, rmSync, writeFileSync } = require("node:fs");
const { tmpdir } = require("node:os");
const path = require("node:path");
const test = require("node:test");
const projectConfig = require("../project.config");
const {
  createManifest,
  fingerprint,
  renderServiceWorker,
} = require("../scripts/build-pages");

test("manifest identity and scope remain project-specific", () => {
  const manifest = createManifest();
  assert.equal(manifest.id, "/mazey.css/");
  assert.equal(manifest.start_url, "/mazey.css/");
  assert.equal(manifest.scope, "/mazey.css/");
  assert.deepEqual(
    manifest.icons.map((icon) => icon.sizes),
    ["192x192", "512x512", "512x512"],
  );
});

test("service-worker rendering replaces only project build tokens", () => {
  const source = readFileSync(
    path.resolve(__dirname, "../site/service-worker.js"),
    "utf8",
  );
  const rendered = renderServiceWorker(source, "test-version");
  assert.equal(rendered.includes("__PWA_"), false);
  assert.match(rendered, /const PROJECT_BASE = "\/mazey\.css\/"/);
  assert.match(rendered, /const CACHE_NAME = `\$\{CACHE_PREFIX\}test-version`/);
  assert.match(rendered, /request\.method !== "GET"/);
  assert.match(rendered, /url\.origin !== self\.location\.origin/);
  assert.match(rendered, new RegExp(projectConfig.pwa.cachePrefix));
  assert.match(rendered, /MAX_RUNTIME_CACHE_ENTRIES = 96/);
  assert.match(rendered, /isPackageStylesheet/);
  assert.match(rendered, /runtimeKeys/);
});

test("worker-source changes invalidate the Pages fingerprint", () => {
  const directory = mkdtempSync(path.join(tmpdir(), "mazey-css-pages-"));
  try {
    writeFileSync(path.join(directory, "index.html"), "stable page");
    const first = fingerprint(directory, [
      { name: "site/service-worker.js", contents: "worker version one" },
    ]);
    const second = fingerprint(directory, [
      { name: "site/service-worker.js", contents: "worker version two" },
    ]);
    assert.notEqual(first, second);
  } finally {
    rmSync(directory, { recursive: true, force: true });
  }
});

test("theme source delegates persistence and resolution to Mazey", () => {
  const source = readFileSync(
    path.resolve(__dirname, "../site/theme.ts"),
    "utf8",
  );
  assert.match(source, /resolveThemePreference/);
  assert.match(source, /setThemePreference/);
  assert.match(source, /listenMediaQueryChanges/);
  assert.doesNotMatch(source, /localStorage\.setItem/);
  assert.match(source, /error instanceof TypeError/);
});

test("the playground install control exists before React initializes", () => {
  const html = readFileSync(
    path.resolve(__dirname, "../examples/index.html"),
    "utf8",
  );
  const app = readFileSync(
    path.resolve(__dirname, "../examples/App.tsx"),
    "utf8",
  );
  assert.match(html, /data-pwa-install/);
  assert.doesNotMatch(app, /data-pwa-install/);
});
