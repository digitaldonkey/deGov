const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
  watch: true,
  entry: {
    main: [
        // deGov theme
      './scss/main.scss',
      // Bootstrap
      './node_modules/@fortawesome/fontawesome-free/js/all.js',
      './node_modules/bootstrap/js/src/util.js',
      './node_modules/bootstrap/js/src/carousel.js',
      './node_modules/bootstrap/js/src/dropdown.js',
      './node_modules/bootstrap/js/src/alert.js',
      './node_modules/bootstrap/js/src/collapse.js',
      // Font Awesome
      './node_modules/@fortawesome/fontawesome-free/css/all.css',
    ],
    install: [
        './scss/install.scss',
        './javascript/favicon_animation.js',
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: "[name].css",
      chunkFilename: "[id].css"
    }),
    new CopyWebpackPlugin([{
      context: `${__dirname}/node_modules/@fortawesome/fontawesome-free/webfonts`,
      from: '*',
      to: `${__dirname}/webfonts/`
    }])
  ],
  output: {
    path: path.join(__dirname, '/webpack-dist/'),
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: [/(node_modules)/],
        use: [{
          loader: 'babel-loader',
          options: {
            babelrc: './.babelrc'
          }
        }]
      },
      {
        test: [/\.scss$/, /\.css$/],
        exclude: [/install\.scss/],
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { url: false, sourceMap: true } },
          { loader: 'sass-loader', options: { sourceMap: true } }
        ],
      },
      {
          test: [/install\.scss$/],
          use: [
              MiniCssExtractPlugin.loader,
              { loader: 'css-loader', options: { url: false, sourceMap: true } },
              { loader: 'sass-loader', options: { sourceMap: true } }
          ],
      }
    ]
  }
};
