const DemosPlanAddon = require('@demos-europe/demosplan-addon-client-builder')

// Get the base configuration
const config = DemosPlanAddon.build(
  'demosplan-addon-xbeteiligung-async',
  {
    XBeteiligungCreateFormFields: DemosPlanAddon.resolve(
      'client/hooks/PhaseCreateForm/XBeteiligungCreateFormFields.vue'
    ),
    XBeteiligungTableFields: DemosPlanAddon.resolve(
      'client/hooks/PhaseListFields/XBeteiligungTableFields.vue'
    ),
  }
)

module.exports = config
