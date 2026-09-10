const { existsSync, readFileSync } = require("node:fs");
const path = require("node:path");
const projectConfig = require("../project.config");

const docs = path.resolve(__dirname, "../docs");
const pages = [
  ["index.html", projectConfig.site.pages.home],
  ["playground/index.html", projectConfig.site.pages.playground],
  ["api/index.html", projectConfig.site.pages.api],
];
const fail = (message) => {
  throw new Error(`SEO validation failed: ${message}`);
};

function validateLocalReferences(html, relativePage) {
  for (const match of html.matchAll(/\b(?:href|src)="([^"]+)"/g)) {
    const reference = match[1];
    if (!reference.startsWith(projectConfig.site.basePath)) continue;
    const url = new URL(reference, projectConfig.site.url);
    let relative = decodeURIComponent(
      url.pathname.slice(projectConfig.site.basePath.length),
    );
    if (!relative || relative.endsWith("/")) relative += "index.html";
    if (!existsSync(path.join(docs, relative)))
      fail(`${relativePage} references missing local path ${reference}`);
  }
}

for (const [relative, page] of pages) {
  const file = path.join(docs, relative);
  if (!existsSync(file)) fail(`missing ${relative}`);
  const html = readFileSync(file, "utf8");
  const required = [
    `<title>${page.title}</title>`,
    `name="description" content="${page.description}"`,
    `rel="canonical" href="${page.url}"`,
    `property="og:title" content="${page.title}"`,
    `property="og:url" content="${page.url}"`,
    'type="application/ld+json"',
    'rel="icon"',
    'name="theme-color"',
  ];
  required.forEach((needle) => {
    if (!html.includes(needle)) fail(`${relative} is missing ${needle}`);
  });
  const h1Count = (html.match(/<h1(?:\s|>)/g) ?? []).length;
  if (h1Count !== 1)
    fail(`${relative} must contain exactly one h1; found ${h1Count}`);
  validateLocalReferences(html, relative);
}

const robots = readFileSync(path.join(docs, "robots.txt"), "utf8");
if (!robots.includes(`Sitemap: ${projectConfig.urls.sitemap}`))
  fail("robots.txt has the wrong sitemap URL");
const sitemap = readFileSync(path.join(docs, "sitemap.xml"), "utf8");
const locations = [...sitemap.matchAll(/<loc>([^<]+)<\/loc>/g)].map(
  (match) => match[1],
);
if (locations.length !== new Set(locations).size)
  fail("sitemap has duplicate locations");
const expected = pages.map(([, page]) => page.url);
if (JSON.stringify(locations) !== JSON.stringify(expected))
  fail("sitemap routes do not match the stable public routes");

console.log("SEO artifact validation passed.");
