const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
  mode: 'development',
  entry: {
    'js/app' : './src/js/app.js',
    'js/inicio' : './src/js/inicio.js',
    'js/marcas/index' : './src/js/marcas/index.js',
    'js/accesorios/index' : './src/js/accesorios/index.js',
    'js/equipos/index' : './src/js/equipos/index.js',
    'js/asignaciones/index' : './src/js/asignaciones/index.js',
    'js/asignaciones/administracion' : './src/js/asignaciones/administracion.js',
    'js/destacamentos/index' : './src/js/destacamentos/index.js',
    'js/mapa/index' : './src/js/mapa/index.js',
    'js/mantenimiento/index' : './src/js/mantenimiento/index.js',
    'js/equipos/modificacion' : './src/js/equipos/modificacion.js',
    'js/reportes/reportes' : './src/js/reportes/reportes.js',

  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader
            },
            'css-loader',
            'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpe?g|gif)$/,
        type: 'asset/resource',
      },
    ]
  }
};