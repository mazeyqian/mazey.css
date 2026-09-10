const { deepFreeze } = require("mazey");
const pkg = require("./package.json");

const siteUrl = new URL(pkg.homepage);
const basePath = siteUrl.pathname.endsWith("/")
  ? siteUrl.pathname
  : `${siteUrl.pathname}/`;
const githubUrl = "https://github.com/chengchuu/mazey.css";
const npmUrl = `https://www.npmjs.com/package/${pkg.name}`;
const shortName = "mazey.css";
const pages = {
  home: {
    title: "mazey.css - Reusable CSS and Sass Styles",
    description:
      "Reusable CSS and Sass styles for normalized pages, responsive layouts, links, and Confluence content.",
    url: siteUrl.href,
  },
  playground: {
    title: "mazey.css Playground - Preview Published Stylesheets",
    description:
      "Interactively preview the public mazey.css stylesheet bundles with representative semantic HTML.",
    url: new URL("playground/", siteUrl).href,
  },
  api: {
    title: "mazey.css Stylesheet API Reference",
    description:
      "Reference for the public mazey.css stylesheet entry points, Sass utility modules, and documented selectors.",
    url: new URL("api/", siteUrl).href,
  },
};
const theme = {
  storageKey: "mazey-css-theme",
  colorPrimary: "#2563eb",
  colorLight: "#f8fafc",
  colorDark: "#0f172a",
};
const icons = [
  { file: "logo-192x192.png", sizes: "192x192", purpose: "any" },
  { file: "logo-512x512.png", sizes: "512x512", purpose: "any" },
  {
    file: "logo-maskable-512x512.png",
    sizes: "512x512",
    purpose: "maskable",
  },
];
const software = {
  "@type": "SoftwareSourceCode",
  name: pkg.name,
  description: pages.home.description,
  url: pages.home.url,
  codeRepository: githubUrl,
  downloadUrl: npmUrl,
  license: `${githubUrl}/blob/main/LICENSE`,
  programmingLanguage: ["CSS", "Sass"],
};

module.exports = deepFreeze({
  package: {
    name: pkg.name,
    version: pkg.version,
    installCommand: `npm install ${pkg.name}`,
  },
  brand: { displayName: pkg.name, shortName },
  urls: {
    github: githubUrl,
    npm: npmUrl,
    license: `${githubUrl}/blob/main/LICENSE`,
    sitemap: new URL("sitemap.xml", siteUrl).href,
  },
  assets: {
    faviconFile: "logo-32x32.png",
    logoFile: "logo-192x192.png",
    openGraphFile: "logo-open-graph-1200x630.png",
  },
  site: { url: siteUrl.href, basePath, pages, theme },
  seo: {
    software,
    openGraphImage: {
      url: new URL("images/logo-open-graph-1200x630.png", siteUrl).href,
      width: 1200,
      height: 630,
      type: "image/png",
      alt: "mazey.css logo on an abstract blue and purple background.",
    },
  },
  pwa: {
    name: "mazey.css stylesheet reference",
    shortName,
    description: pages.home.description,
    backgroundColor: theme.colorLight,
    themeColor: theme.colorPrimary,
    manifestUrl: `${basePath}manifest.webmanifest`,
    serviceWorkerUrl: `${basePath}service-worker.js`,
    cachePrefix: "mazey-css-site-",
    icons: icons.map((icon) => ({
      ...icon,
      type: "image/png",
      src: `${basePath}images/${icon.file}`,
    })),
  },
  stylesheets: [
    {
      name: "index",
      path: "lib/index.css",
      importPath: "mazey.css",
      description: "Package-root normalization stylesheet.",
    },
    {
      name: "base",
      path: "lib/base.css",
      importPath: "mazey.css/lib/base.css",
      description: "Responsive base layout and semantic status variants.",
    },
    {
      name: "blogbase",
      path: "lib/blogbase.css",
      importPath: "mazey.css/lib/blogbase.css",
      description: "Blog color variables, header, footer, and dark theme.",
    },
    {
      name: "link",
      path: "lib/link.css",
      importPath: "mazey.css/lib/link.css",
      description: "Compact link-list and tiny-box presentation.",
    },
    {
      name: "confluence",
      path: "lib/confluence.css",
      importPath: "mazey.css/lib/confluence.css",
      description: "Confluence-oriented table, code, and document styles.",
    },
  ],
});
