const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("node:path");
const webpack = require("webpack");
const projectConfig = require("./project.config");

const productionPages = process.env.GITHUB_PAGES === "true";
const publicPath = productionPages ? projectConfig.site.basePath : "/";
const { pages, theme } = projectConfig.site;
const social = projectConfig.seo.openGraphImage;
const projectPaths = {
  HOME_PATH: publicPath,
  PLAYGROUND_PATH: `${publicPath}playground/`,
  API_PATH: `${publicPath}api/`,
};
const templateParameters = {
  ...projectPaths,
  API_DESCRIPTION: pages.api.description,
  API_JSON_LD: JSON.stringify({
    "@context": "https://schema.org",
    "@type": "TechArticle",
    name: pages.api.title,
    description: pages.api.description,
    url: pages.api.url,
    about: projectConfig.seo.software,
  }),
  API_TITLE: pages.api.title,
  API_URL: pages.api.url,
  FAVICON_URL: `${publicPath}images/${projectConfig.assets.faviconFile}`,
  GITHUB_URL: projectConfig.urls.github,
  INSTALL_COMMAND: projectConfig.package.installCommand,
  LICENSE_URL: projectConfig.urls.license,
  LOGO_URL: `${publicPath}images/${projectConfig.assets.logoFile}`,
  MANIFEST_URL: productionPages ? projectConfig.pwa.manifestUrl : null,
  NPM_URL: projectConfig.urls.npm,
  OPEN_GRAPH_IMAGE_ALT: social.alt,
  OPEN_GRAPH_IMAGE_HEIGHT: social.height,
  OPEN_GRAPH_IMAGE_TYPE: social.type,
  OPEN_GRAPH_IMAGE_URL: social.url,
  OPEN_GRAPH_IMAGE_WIDTH: social.width,
  PACKAGE_NAME: projectConfig.package.name,
  PLAYGROUND_DESCRIPTION: pages.playground.description,
  PLAYGROUND_JSON_LD: JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: pages.playground.title,
    description: pages.playground.description,
    url: pages.playground.url,
    about: projectConfig.seo.software,
  }),
  PLAYGROUND_TITLE: pages.playground.title,
  PLAYGROUND_URL: pages.playground.url,
  ROOT_DESCRIPTION: pages.home.description,
  ROOT_JSON_LD: JSON.stringify({
    "@context": "https://schema.org",
    ...projectConfig.seo.software,
  }),
  ROOT_TITLE: pages.home.title,
  SITE_URL: projectConfig.site.url,
  STYLESHEETS: projectConfig.stylesheets,
  THEME_COLOR_DARK: theme.colorDark,
  THEME_COLOR_LIGHT: theme.colorLight,
  THEME_COLOR_PRIMARY: theme.colorPrimary,
};
const runtimeConfig = {
  basePath: publicPath,
  packageName: projectConfig.package.name,
  themeStorageKey: theme.storageKey,
  pwa: {
    appName: projectConfig.brand.displayName,
    enabled: productionPages,
    scope: projectConfig.site.basePath,
    serviceWorkerUrl: projectConfig.pwa.serviceWorkerUrl,
  },
};

module.exports = {
  mode: "development",
  entry: {
    shared: path.resolve(__dirname, "site/shared.ts"),
    home: {
      import: path.resolve(__dirname, "site/index.ts"),
      dependOn: "shared",
    },
    playground: {
      import: path.resolve(__dirname, "examples/index.tsx"),
      dependOn: "shared",
    },
    api: { import: path.resolve(__dirname, "site/api.ts"), dependOn: "shared" },
  },
  output: {
    clean: true,
    filename: "assets/[name].js",
    path: path.resolve(__dirname, "dist-dev"),
    publicPath,
  },
  devServer: {
    host: "0.0.0.0",
    port: 8080,
    static: [
      { directory: path.resolve(__dirname, "dist-dev") },
      { directory: path.resolve(__dirname, "images"), publicPath: "/images" },
      {
        directory: path.resolve(__dirname, "lib"),
        publicPath: "/package-styles",
      },
    ],
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: { loader: "ts-loader", options: { transpileOnly: true } },
        exclude: /node_modules/,
      },
      {
        test: /\.css$/i,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
    ],
  },
  plugins: [
    new webpack.DefinePlugin({
      __SITE_RUNTIME_CONFIG__: JSON.stringify(runtimeConfig),
    }),
    new MiniCssExtractPlugin({ filename: "assets/[name].css" }),
    new HtmlWebpackPlugin({
      filename: "index.html",
      template: path.resolve(__dirname, "site/index.html"),
      chunks: ["shared", "home"],
      inject: "body",
      templateParameters,
    }),
    new HtmlWebpackPlugin({
      filename: "playground/index.html",
      template: path.resolve(__dirname, "examples/index.html"),
      chunks: ["shared", "playground"],
      inject: "body",
      templateParameters,
    }),
    new HtmlWebpackPlugin({
      filename: "api/index.html",
      template: path.resolve(__dirname, "site/api.html"),
      chunks: ["shared", "api"],
      inject: "body",
      templateParameters,
    }),
  ],
  resolve: { extensions: [".tsx", ".ts", ".js"] },
  performance: { maxAssetSize: 600000, maxEntrypointSize: 600000 },
};
