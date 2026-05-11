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
    :class="{ '[&_input]:border-status-failed': hasAttemptedSubmit && addonPayload.isDuplicate }"
    :label="{ text: Translator.trans('procedure.phase.code') }"
    :model-value="phaseCode"
    @update:model-value="handleCodeInput"
  />
</template>

<script>
import {
  fetchAllPhaseCodes,
  invalidatePhaseCodesCache,
  isDuplicateCode
} from '../../utils/phaseCodes'

export default {
  name: 'PhaseCodeFormField',

  emits: ['addonEvent:emit'],

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },

    /*
     * Controlled by core's `hasAttemptedSubmit` state.
     * Enables validation styling only after a submit attempt.
     */
    hasAttemptedSubmit: {
      type: Boolean,
      default: false,
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
        isDuplicate: isDuplicateCode(trimmedCode, null),
        parentRelationshipName: 'phaseDefinition',
        resourceType: 'XBeteiligungPhaseDefinitionCode',
        value: trimmedCode,
      }
    },
  },

  methods: {
    handleCodeInput (value) {
      this.phaseCode = value
      this.$emit('addonEvent:emit', {
        name: 'change',
        payload: this.addonPayload,
      })
    },
  },

  /*
   * Initialize cache for empty table case. Without this, `isDuplicate`
   * would compare against an empty cache.
   */
  created () {
    fetchAllPhaseCodes(this.demosplanUi.dpApi)
      .catch(err => {
        if (err?.data?.meta?.messages) {
          this.demosplanUi.handleResponseMessages(err.data.meta)
        } else {
          dplan.notify.error(Translator.trans('error.api.generic'))
        }
      })
  },

  /*
   * Form is unmounted on cancel and on successful create. In the success
   * case the cache is stale (new code was added); on cancel it is fine.
   * Invalidating in both cases costs at most one extra fetch on the next
   * read, in exchange for not needing core to signal which case happened.
   */
  beforeDestroy () {
    invalidatePhaseCodesCache()
  },
}
</script>
