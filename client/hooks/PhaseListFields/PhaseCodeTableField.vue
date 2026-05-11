<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <div
    v-if="isEditing"
    class="flex items-center gap-1"
  >
    <component
      :is="demosplanUi.DpInput"
      id="phaseCodeInput"
      :class="{ '[&_input]:border-status-failed': addonPayload.isDuplicate }"
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
  isDuplicateCode,
  removePhaseCode,
  updateOrCreatePhaseCode,
} from '../../utils/phaseCodes'

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
        this.phaseCodeDraft = this.currentPhaseCode
        this.$emit('addonEvent:emit', {
          name: 'edit-start',
          payload: this.addonPayload,
        })
      } else {
        this.phaseCodeDraft = ''
      }
    },

    /*
     * Keep the shared cache in sync with whatever core just persisted, so
     * other rows' duplicate checks reflect this row's new state without a
     * re-fetch. Fires only on a successful save (core writes to the prop
     * after the request resolves).
     */
    savedRowPayload (newPayload) {
      if (newPayload === null) {
        return
      }

      if (newPayload.resourceId === null) {
        removePhaseCode(this.phaseId)
      } else {
        updateOrCreatePhaseCode(this.phaseId, newPayload.code, newPayload.resourceId)
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

    removeCode () {
      this.handleCodeInput('')
    },
  },

  created () {
    fetchAllPhaseCodes(this.demosplanUi.dpApi)
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
  },
}
</script>
