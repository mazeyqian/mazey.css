const packageConfig = require("./webpack.config");

module.exports = {
  ...packageConfig,
  mode: "development",
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
