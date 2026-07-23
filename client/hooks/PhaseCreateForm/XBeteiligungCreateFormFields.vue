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
    :invalid="hasAttemptedSubmit && addonPayload.isDuplicate"
    :label="{ text: Translator.trans('procedure.phase.code') }"
    :model-value="phaseCode"
    class="mb-4"
    @update:model-value="handleCodeInput"
  />

  <component
    :is="demosplanUi.DpSelect"
    id="portalPhase"
    :label="{ text: Translator.trans('portal.procedure.phase') }"
    :options="options"
    :selected="dcatApPluStandardCodeId"
    required
    @select="handlePortalPhaseChange"
  />
</template>

<script>
import { fetchDcatApPluCodeOptions } from '../../utils/fetchDcatApPluCodeOptions'
import { handleFetchError } from '../../utils/handleFetchError'
import {
  fetchAllPhaseMappings,
  invalidatePhaseMappingCache,
  isCockpitCodeDuplicate,
  trimCockpitCodeOrNull,
} from '../../utils/phaseMappingCache'

export default {
  name: 'XBeteiligungCreateFormFields',

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

  emits: ['addonEvent:emit'],

  data () {
    return {
      dcatApPluStandardCodeId: '',
      options: [],
      phaseCode: '',
    }
  },

  computed: {
    addonPayload () {
      const normalizedCockpitCode = trimCockpitCodeOrNull(this.phaseCode)

      return {
        attributes: {
          xBeteiligungStandardCode: normalizedCockpitCode,
        },
        isDuplicate: isCockpitCodeDuplicate(normalizedCockpitCode, null),
        parentRelationshipName: 'phaseDefinition', // Relationship core attaches the new phase to after creating it
        relationships: {
          dcatApPluStandardCode: {
            data: { type: 'XBeteiligungDcatApPluStandardCode', id: this.dcatApPluStandardCodeId },
          },
        },
        resourceType: 'XBeteiligungPhaseDefinitionCodeMapping',
        savedRowPayload: {
          dcatApPluStandardCodeId: this.dcatApPluStandardCodeId,
          mappingId: null,  // The mapping row does not exist yet; core fills in its id after the create POST
          xBeteiligungStandardCode: normalizedCockpitCode,
        },
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

    /*
     * Initialize the code cache for the duplicate check and load the DCAT
     * option list. Without the cache init, `isDuplicate` would compare
     * against an empty cache.
     */
    handleFetch () {
      return Promise.all([
        fetchAllPhaseMappings(this.demosplanUi.dpApi),
        fetchDcatApPluCodeOptions(this.demosplanUi.dpApi).then(options => {
          this.options = options
        }),
      ])
        .catch(err => handleFetchError(err, this.demosplanUi))
    },

    handlePortalPhaseChange (value) {
      this.dcatApPluStandardCodeId = value
      this.$emit('addonEvent:emit', {
        name: 'change',
        payload: this.addonPayload,
      })
    },
  },

  created () {
    this.handleFetch()
  },

  /*
   * Form is unmounted on cancel and on successful create. In the success
   * case the cache is stale (new mapping was added); on cancel it is fine.
   * Invalidating in both cases costs at most one extra fetch on the next
   * read, in exchange for not needing core to signal which case happened.
   */
  beforeUnmount () {
    invalidatePhaseMappingCache()
  },
}
</script>
