var Encore = require('@symfony/webpack-encore')

Encore
// the project directory where compiled assets will be stored
  .setOutputPath('public/build/')
  // the public path used by the web server to access the previous directory
  .setPublicPath('/build')
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  // uncomment to create hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  .enableTypeScriptLoader()

  // uncomment to define the assets of the project
  .addEntry('js/app', './assets/js/app.js')
  .addStyleEntry('css/app', './assets/sass/app.scss')

  // uncomment if you use Sass/SCSS files
  .enableSassLoader()
  .enablePostCssLoader((options) => {
    options.config = {
      path: 'postcss.config.js'
    }
  })

  // .configureBabel(function (babelConfig) {
  //   // add additional presets
  //   babelConfig.presets.push('es2017')
  //
  //   // no plugins are added by default, but you can add some
  //   // babelConfig.plugins.push('styled-jsx/babel');
  // })

  // uncomment for legacy applications that require $/jQuery as a global variable
  .autoProvidejQuery()
  .autoProvideVariables({
    $: 'jquery',
    jQuery: 'jquery',
    'window.jQuery': 'jquery',
    axios: 'axios'
  })

module.exports = Encore.getWebpackConfig()

