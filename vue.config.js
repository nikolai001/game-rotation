const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  publicPath: process.env.NODE_ENV === 'production'
    ? 'https://raahauge.eu/game-rotation/'
    : '',
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost:80',
        changeOrigin: true,
        pathRewrite: { '^/api': '' },
        logLevel: 'debug',
      },
    },
  },
  outputDir: 'game-rotation',
  transpileDependencies: true
})
