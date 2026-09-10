const {
  cpSync,
  existsSync,
  mkdirSync,
  readFileSync,
  readdirSync,
  rmSync,
  statSync,
  writeFileSync,
} = require("node:fs");
const { createHash } = require("node:crypto");
const path = require("node:path");
const projectConfig = require("../project.config");

const root = path.resolve(__dirname, "..");

function requirePath(file) {
  if (!existsSync(file))
    throw new Error(`Required Pages source is missing: ${file}`);
}

function allFiles(directory) {
  return readdirSync(directory).flatMap((name) => {
    const file = path.join(directory, name);
    return statSync(file).isDirectory() ? allFiles(file) : [file];
  });
}

function fingerprint(directory, additionalSources = []) {
  const hash = createHash("sha256");
  allFiles(directory)
    .filter(
      (file) => !file.endsWith("service-worker.js") && !file.endsWith(".map"),
    )
    .sort()
    .forEach((file) => {
      const name = path.relative(directory, file).replaceAll(path.sep, "/");
      const contents = readFileSync(file);
      hash.update(`${Buffer.byteLength(name)}:${name}${contents.byteLength}:`);
      hash.update(contents);
    });
  additionalSources.forEach(({ name, contents }) => {
    hash.update(
      `${Buffer.byteLength(name)}:${name}${Buffer.byteLength(contents)}:`,
    );
    hash.update(contents);
  });
  return hash.digest("hex").slice(0, 16);
}

function createManifest() {
  return {
    name: projectConfig.pwa.name,
    short_name: projectConfig.pwa.shortName,
    description: projectConfig.pwa.description,
    id: projectConfig.site.basePath,
    start_url: projectConfig.site.basePath,
    scope: projectConfig.site.basePath,
    display: "standalone",
    background_color: projectConfig.pwa.backgroundColor,
    theme_color: projectConfig.pwa.themeColor,
    icons: projectConfig.pwa.icons.map(({ src, sizes, type, purpose }) => ({
      src,
      sizes,
      type,
      purpose,
    })),
  };
}

function renderServiceWorker(source, version) {
  const replacements = {
    __PWA_PROJECT_BASE__: projectConfig.site.basePath,
    __PWA_CACHE_PREFIX__: projectConfig.pwa.cachePrefix,
    __PWA_CACHE_VERSION__: version,
  };
  return Object.entries(replacements).reduce((output, [token, value]) => {
    if (!output.includes(token))
      throw new Error(`Service worker token is missing: ${token}`);
    return output.replaceAll(token, value);
  }, source);
}

function buildPages() {
  const dist = path.join(root, "dist-dev");
  const docs = path.join(root, "docs");
  const images = path.join(root, "images");
  const styles = path.join(docs, "package-styles");
  [
    path.join(dist, "index.html"),
    path.join(dist, "playground", "index.html"),
    path.join(dist, "api", "index.html"),
    path.join(root, "site", "service-worker.js"),
    ...projectConfig.pwa.icons.map((icon) => path.join(images, icon.file)),
  ].forEach(requirePath);

  rmSync(docs, { recursive: true, force: true });
  mkdirSync(docs, { recursive: true });
  cpSync(dist, docs, { recursive: true });
  cpSync(images, path.join(docs, "images"), { recursive: true });
  mkdirSync(styles, { recursive: true });
  projectConfig.stylesheets.forEach((stylesheet) => {
    const source = path.join(root, stylesheet.path);
    requirePath(source);
    cpSync(source, path.join(styles, `${stylesheet.name}.css`));
  });

  writeFileSync(
    path.join(docs, "manifest.webmanifest"),
    `${JSON.stringify(createManifest(), null, 2)}\n`,
  );
  writeFileSync(
    path.join(docs, "robots.txt"),
    `User-agent: *\nAllow: /\n\nSitemap: ${projectConfig.urls.sitemap}\n`,
  );
  const locations = [
    projectConfig.site.pages.home.url,
    projectConfig.site.pages.playground.url,
    projectConfig.site.pages.api.url,
  ];
  writeFileSync(
    path.join(docs, "sitemap.xml"),
    `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${locations.map((url) => `  <url><loc>${url}</loc></url>`).join("\n")}\n</urlset>\n`,
  );
  writeFileSync(path.join(docs, ".nojekyll"), "");

  const workerSource = readFileSync(
    path.join(root, "site", "service-worker.js"),
    "utf8",
  );
  writeFileSync(
    path.join(docs, "service-worker.js"),
    renderServiceWorker(
      workerSource,
      fingerprint(docs, [
        { name: "site/service-worker.js", contents: workerSource },
      ]),
    ),
  );
}

if (require.main === module) buildPages();

module.exports = {
  buildPages,
  createManifest,
  fingerprint,
  renderServiceWorker,
};
