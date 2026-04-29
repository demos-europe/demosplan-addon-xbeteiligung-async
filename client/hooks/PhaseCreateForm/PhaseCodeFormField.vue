<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    :is="demosplanUi.DpInput"
    id="phaseCode"
    :label="{ text: Translator.trans('procedure.phase.code') }"
    :model-value="phaseCode"
    @update:model-value="onPhaseCodeChange"
  />
</template>

<script>
export default {
  name: 'PhaseCodeFormField',

  emits: ['addonEvent:emit'],

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },
  },

  data () {
    return {
      phaseCode: '',
    }
  },

  computed: {
    addonPayload () {
      const trimmedCode = this.phaseCode.trim()

      return {
        attributes: {
          code: trimmedCode,
        },
        parentRelationshipName: 'phaseDefinition',
        resourceType: 'XBeteiligungPhaseDefinitionCode',
        value: trimmedCode,
      }
    },
  },

  methods: {
    onPhaseCodeChange (value) {
      this.phaseCode = value
      this.$emit('addonEvent:emit', {
        name: 'change',
        payload: this.addonPayload,
      })
    },
  },
}
</script>
