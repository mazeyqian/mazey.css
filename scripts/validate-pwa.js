const { existsSync, readFileSync } = require("node:fs");
const path = require("node:path");
const projectConfig = require("../project.config");

const docs = path.resolve(__dirname, "../docs");
const fail = (message) => {
  throw new Error(`PWA validation failed: ${message}`);
};
const requireFile = (relative) => {
  const file = path.join(docs, relative);
  if (!existsSync(file)) fail(`missing ${relative}`);
  return file;
};
const pngSize = (file) => {
  const buffer = readFileSync(file);
  if (buffer.toString("ascii", 1, 4) !== "PNG") fail(`${file} is not a PNG`);
  return [buffer.readUInt32BE(16), buffer.readUInt32BE(20)];
};

const manifest = JSON.parse(
  readFileSync(requireFile("manifest.webmanifest"), "utf8"),
);
if (
  manifest.start_url !== projectConfig.site.basePath ||
  manifest.scope !== projectConfig.site.basePath
)
  fail("manifest start_url and scope must match the Pages base path");
for (const icon of projectConfig.pwa.icons) {
  const relative = `images/${icon.file}`;
  const [width, height] = pngSize(requireFile(relative));
  const expected = Number(icon.sizes.split("x")[0]);
  if (width !== expected || height !== expected)
    fail(
      `${relative} dimensions are ${width}x${height}, expected ${icon.sizes}`,
    );
}
for (const relative of [
  "index.html",
  "playground/index.html",
  "api/index.html",
]) {
  const html = readFileSync(requireFile(relative), "utf8");
  if (!html.includes(`rel="manifest" href="${projectConfig.pwa.manifestUrl}"`))
    fail(`${relative} has no production manifest link`);
  if (!html.includes("data-theme-select"))
    fail(`${relative} has no theme selector`);
}
const worker = readFileSync(requireFile("service-worker.js"), "utf8");
if (worker.includes("__PWA_"))
  fail("service worker still contains build tokens");
if (!worker.includes(`const PROJECT_BASE = "${projectConfig.site.basePath}"`))
  fail("service worker has the wrong scope base");
if (
  !worker.includes('request.method !== "GET"') ||
  !worker.includes("url.origin !== self.location.origin")
)
  fail("service worker request guards are missing");
if (
  !worker.includes("MAX_RUNTIME_CACHE_ENTRIES = 96") ||
  !worker.includes("isPackageStylesheet") ||
  !worker.includes("trimCache")
)
  fail("service worker runtime-cache guards are missing");

console.log("PWA artifact validation passed.");
