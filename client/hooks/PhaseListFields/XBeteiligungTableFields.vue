<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    :is="selectedComponent"
    :demosplan-ui="demosplanUi"
    :has-attempted-submit="hasAttemptedSubmit"
    :is-editing="isEditing"
    :phase-id="phaseId"
    :saved-row-payload="savedRowPayload"
    @addonEvent:emit="$emit('addonEvent:emit', $event)"
  />
</template>

<script>
import CockpitCodeTableField from './CockpitCodeTableField'
import PortalPhaseTableField from './PortalPhaseTableField'

export default {
  name: 'XBeteiligungTableFields',

  components: {
    CockpitCodeTableField,
    PortalPhaseTableField,
  },

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },

    hasAttemptedSubmit: {
      type: Boolean,
      default: false,
    },

    isEditing: {
      type: Boolean,
      default: false,
    },

    isPortalPhase: {
      type: Boolean,
      default: false,
    },

    phaseId: {
      type: String,
      required: true,
    },

    savedRowPayload: {
      type: Object,
      default: null,
    },
  },

  emits: ['addonEvent:emit'],

  computed: {
    selectedComponent () {
      return this.isPortalPhase ? PortalPhaseTableField : CockpitCodeTableField
    },
  },
}
</script>
