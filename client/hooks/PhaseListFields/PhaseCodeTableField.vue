<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    :is="demosplanUi.DpInput"
    v-if="isEditing"
    id="phaseCodeInput"
    :model-value="phaseCodeDraft"
    @update:model-value="handleCodeInput"
  />

  <span v-else>{{ currentPhaseCode }}</span>
</template>

<script>
/*
 * Shared across every <PhaseCodeTableField> instance on the page so the codes are only
 * fetched once. All rows mount in the same tick — the first one triggers the
 * request, the rest reuse the same in-flight promise (via `requestInProgress`)
 * and read from `cachedPhaseCodes` when it resolves. Stays null on failure so
 * the next mount can retry.
 */
let cachedPhaseCodes = null
let requestInProgress = null

export default {
  name: 'PhaseCodeTableField',

  emits: ['addonEvent:emit'],

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },

    isEditing: {
      type: Boolean,
      default: false,
    },

    phaseId: {
      type: String,
      required: true,
    },

    /*
    * Set by core after a successful save so the cell reflects the new value
    * without remount. `null` means "core has not pushed anything yet"; the
    * cell uses its own fetched values instead.
    */
    savedRowPayload: {
      type: Object,
      default: null,
    },
  },

  data () {
    return {
      fetchedCode: '',
      fetchedResourceId: null,
      phaseCodeDraft: '',
    }
  },

  computed: {
    addonPayload () {
      const trimmedCode = this.phaseCodeDraft.trim()

      return {
        attributes: {
          code: trimmedCode,
        },
        parentRelationshipName: 'phaseDefinition',
        phaseId: this.phaseId,
        resourceId: this.currentResourceId,
        resourceType: 'XBeteiligungPhaseDefinitionCode',
        value: trimmedCode,
      }
    },

    currentPhaseCode () {
      return this.savedRowPayload ? this.savedRowPayload.code : this.fetchedCode
    },

    currentResourceId () {
      return this.savedRowPayload ? this.savedRowPayload.resourceId : this.fetchedResourceId
    },
  },

  watch: {
    isEditing (newValue) {
      if (newValue) {
        this.phaseCodeDraft = this.currentPhaseCode
        this.$emit('addonEvent:emit', {
          name: 'edit-start',
          payload: this.addonPayload,
        })
      } else {
        this.phaseCodeDraft = ''
      }
    },
  },

  methods: {
    fetchAllPhaseCodes () {
      /*
       * Reuse the cache only if it already knows the phase. New phases added
       * after the initial load won't be in there, so refetch in that case.
       */
      if (cachedPhaseCodes && this.phaseId in cachedPhaseCodes) {
        return Promise.resolve(cachedPhaseCodes)
      }

      if (requestInProgress) {
        return requestInProgress
      }

      const url = Routing.generate('api_resource_list', {
        resourceType: 'XBeteiligungPhaseDefinitionCode',
        fields: {
          XBeteiligungPhaseDefinitionCode: ['code', 'phaseDefinition'].join(','),
        },
        include: 'phaseDefinition',
      })

      requestInProgress = this.demosplanUi.dpApi.get(url)
        .then(({ data }) => {
          cachedPhaseCodes = data.data.reduce((phaseCodesByPhaseId, item) => {
            const phaseId = item.relationships?.phaseDefinition?.data?.id
            if (phaseId) {
              phaseCodesByPhaseId[phaseId] = {
                code: item.attributes.code,
                resourceId: item.id,
              }
            }

            return phaseCodesByPhaseId
          }, {})

          return cachedPhaseCodes
        })
        .catch(err => {
          if (err?.data?.meta?.messages) {
            this.demosplanUi.handleResponseMessages(err.data.meta)
          } else {
            dplan.notify.error(Translator.trans('error.api.generic'))
          }

          return {}
        })
        .finally(() => {
          requestInProgress = null
        })

      return requestInProgress
    },

    handleCodeInput (value) {
      this.phaseCodeDraft = value
      this.$emit('addonEvent:emit', {
        name: 'edit-change',
        payload: this.addonPayload,
      })
    },
  },

  created () {
    this.fetchAllPhaseCodes().then(byPhaseId => {
      const entry = byPhaseId[this.phaseId]
      if (entry) {
        this.fetchedCode = entry.code
        this.fetchedResourceId = entry.resourceId
      }
    })
  },
}
</script>
