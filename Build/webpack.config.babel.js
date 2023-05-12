import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';

module.exports = {
  mode: process.env.NODE_ENV,
  entry: [
    path.resolve(__dirname, './../Resources/Private/Assets/JavaScript/CookieConsent.js'),
    path.resolve(__dirname, './../Resources/Private/Assets/Scss/Styles.scss')
  ],
  output: {
    path: path.resolve(__dirname, './../Resources/Public'),
    filename: './JavaScript/cookie_consent.js',
    chunkFilename: './JavaScript/Chunks/[name].js'
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './Stylesheet/cookie_consent.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              sourceMap: true,
              importLoaders: 2
            }
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true
            }
          },
        ],
      }
    ]
  }
};

if ('development' === process.env.NODE_ENV) {
  module.exports['devtool'] = 'source-map';
  module.exports['watch'] = true;
}
