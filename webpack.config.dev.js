const packageConfig = require("./webpack.config");

module.exports = {
  ...packageConfig,
  mode: "development",
  entry: Object.fromEntries(
    ["base", "blogbase", "confluence", "index", "link"].map((name) => [
      name,
      `./src/z-temporary/${name}.js`,
    ]),
  ),
  devServer: {
    host: "127.0.0.1",
    port: 4132,
    static: false,
    hot: false,
    liveReload: false,
    client: false,
    headers: {
      "Access-Control-Allow-Origin": "*",
      "Cross-Origin-Resource-Policy": "cross-origin",
    },
    devMiddleware: {
      publicPath: "/",
    },
  },
};
