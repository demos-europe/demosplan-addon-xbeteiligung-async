const DemosPlanAddon = require('@demos-europe/demosplan-addon-client-builder')

// Get the base configuration
const config = DemosPlanAddon.build(
  'demosplan-addon-xbeteiligung-async',
  {
    PhaseCodeFormField: DemosPlanAddon.resolve(
      'client/hooks/PhaseCreateForm/PhaseCodeFormField.vue'
    ),
    PhaseCodeTableField: DemosPlanAddon.resolve(
      'client/hooks/PhaseListFields/PhaseCodeTableField.vue'
    ),
  }
)

module.exports = config
