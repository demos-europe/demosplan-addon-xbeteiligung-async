<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    v-if="isLoading"
    :is="demosplanUi.DpLoading"
    hide-label
  />

  <div
    v-else-if="isEditing"
    class="flex items-center gap-1"
  >
    <component
      :is="demosplanUi.DpInput"
      id="phaseCodeInput"
      :invalid="hasAttemptedSubmit && addonPayload.isDuplicate"
      :model-value="phaseCodeDraft"
      @update:model-value="handleCodeInput"
    />

    <component
      :is="demosplanUi.DpButton"
      :disabled="!phaseCodeDraft.trim()"
      :text="Translator.trans('remove')"
      icon="x"
      variant="subtle"
      hide-text
      @click="removeCode"
    />
  </div>

  <span v-else>{{ currentPhaseCode }}</span>
</template>

<script>
import {
  fetchAllPhaseCodes,
  invalidatePhaseCodesCache,
  isDuplicateCode,
} from '../../utils/phaseCodes'

export default {
  name: 'PhaseCodeTableField',

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

  emits: ['addonEvent:emit'],

  data () {
    return {
      fetchedCode: '',
      fetchedResourceId: null,
      isLoading: true,
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
        isDuplicate: isDuplicateCode(trimmedCode, this.currentResourceId),
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
        this.startEditing()
      } else {
        this.phaseCodeDraft = ''
      }
    },
  },

  methods: {
    handleCodeInput (value) {
      this.phaseCodeDraft = value
      this.$emit('addonEvent:emit', {
        name: 'edit-change',
        payload: this.addonPayload,
      })
    },

    handleFetch () {
      return fetchAllPhaseCodes(this.demosplanUi.dpApi)
        .then(byPhaseId => {
          const entry = byPhaseId[this.phaseId]

          if (entry) {
            this.fetchedCode = entry.code
            this.fetchedResourceId = entry.resourceId
          }
        })
        .catch(err => {
          if (err?.data?.meta?.messages) {
            this.demosplanUi.handleResponseMessages(err.data.meta)
          } else {
            dplan.notify.error(Translator.trans('error.api.generic'))
          }
        })
        .finally(() => {
          this.isLoading = false
        })
    },

    removeCode () {
      this.handleCodeInput('')
    },

    /*
     * Refresh the shared code cache at the start of an edit session so the
     * duplicate check sees current backend state at submit time. Needed
     * because `savedRowPayload` updates don't reliably carry across the
     * addon's Vue boundary, so the cache drifts out of sync over the session.
     */
    startEditing () {
      this.isLoading = true
      invalidatePhaseCodesCache()

      this.handleFetch().then(() => {
        this.phaseCodeDraft = this.currentPhaseCode
        this.$emit('addonEvent:emit', {
          name: 'edit-start',
          payload: this.addonPayload,
        })
      })
    },
  },

  created () {
    this.handleFetch()
  },
}
</script>
