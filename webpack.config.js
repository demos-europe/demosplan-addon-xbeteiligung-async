// webpack.config.js
const DemosPlanAddon = require('@demos-europe/demosplan-addon-client-builder')

// Get the base configuration
const config = DemosPlanAddon.build(
  'demosplan-addon-xbeteiligung-async',
  {
  }
)

module.exports = config
