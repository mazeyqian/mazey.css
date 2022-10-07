const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const ENTRY = process.env.ENTRY;
console.log(`ENTRY: ${ENTRY}`);

module.exports = {
  mode: 'production',
  entry: {
    [ENTRY]: `./src/${ENTRY}.js`
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'lib'),
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          "style-loader",
          // mini-css-extract-plugin's loader only takes the output of css-loader as its input. 
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: 'css-loader',
            options: { modules: true },
          },
          {
            loader: "sass-loader",
            options: {
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
      // filename: `../style.min.css`
      filename: `${ENTRY}.css`,
    }),
    new HtmlWebpackPlugin({
      filename: path.resolve(__dirname, `lib/${ENTRY}.html`),
      template: path.resolve(__dirname, 'src/template/index.html'),
      inject: true,
      chunksSortMode: 'auto'
    }),
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['lib'],
    }),
  ],
};
