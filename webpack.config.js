const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { genCustomConsole } = require('mazey');

const wpCon = genCustomConsole('[webpack]')
const ENTRY = process.env.ENTRY || 'unknown';
wpCon.log(`ENTRY: ${ENTRY}`);

module.exports = {
  mode: 'production',
  entry: {
    [ENTRY]: `./src/z-temporary/${ENTRY}.js`
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'lib'),
  },
  watchOptions: {
    ignored: /node_modules/,
    aggregateTimeout: 300,
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: 'css-loader',
          },
          {
            loader: "sass-loader",
            options: {
              api: "modern",
              // Prefer `dart-sass`
              implementation: require("sass"),
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: `${ENTRY}.css`,
    }),
    new webpack.DefinePlugin({
      ENTRY: JSON.stringify(ENTRY),
    }),
  ],
};
