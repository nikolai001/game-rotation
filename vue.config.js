const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  publicPath: process.env.NODE_ENV === 'production'
    ? 'https://raahauge.eu/game-rotation/'
    : '',
  outputDir: 'game-rotation',
  transpileDependencies: true
})
