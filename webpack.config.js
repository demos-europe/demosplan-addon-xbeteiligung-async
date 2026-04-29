// webpack.config.js
const DemosPlanAddon = require('@demos-europe/demosplan-addon-client-builder')

// Get the base configuration
const config = DemosPlanAddon.build(
  'demosplan-addon-xbeteiligung-async',
  {
    ProcedurePhasesCockpitCodes: DemosPlanAddon.resolve(
      'client/hooks/PhasesTableWithCodes/ProcedurePhasesCockpitCodes.vue'
    ),
    PhaseCodeFormField: DemosPlanAddon.resolve(
      'client/hooks/PhaseCreateForm/PhaseCodeFormField.vue'
    ),
  }
)

module.exports = config
