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
      :invalid="hasAttemptedSubmit && isDuplicate"
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

  <span v-else>{{ currentCockpitCode }}</span>
</template>

<script>
import { handleFetchError } from '../../utils/handleFetchError'
import {
  fetchAllPhaseMappings,
  invalidatePhaseMappingCache,
  isCockpitCodeDuplicate,
  trimCockpitCodeOrNull,
  updateCachedPhaseMapping,
} from '../../utils/phaseMappingCache'
import {
  clearDraft,
  getDraft,
  initDraft,
  setDraftField,
} from '../../utils/phaseMappingEditDraft'
import { buildMappingPayload } from '../../utils/phaseMappingPayload'

export default {
  name: 'CockpitCodeTableField',

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
      fetchedMapping: null,
      isLoading: true,
      phaseCodeDraft: '',
    }
  },

  computed: {
    currentCockpitCode () {
      return this.currentMapping?.xBeteiligungStandardCode
    },

    currentMapping () {
      return this.savedRowPayload || this.fetchedMapping
    },

    /*
     * Depends on `phaseCodeDraft` so error styling updates on each keystroke.
     * The emitted payload recomputes its own `isDuplicate`.
     */
    isDuplicate () {
      return isCockpitCodeDuplicate(this.phaseCodeDraft, getDraft(this.phaseId)?.mappingId)
    },
  },

  watch: {
    isEditing (newValue) {
      if (newValue) {
        /*
         * Invalidate cache and reload mapping to avoid stale cache after code deletion
         * (so that the deleted value passes the duplicate check and may be reused)
         */
        invalidatePhaseMappingCache()
        fetchAllPhaseMappings(this.demosplanUi.dpApi)
          .catch(err => handleFetchError(err, this.demosplanUi))

        initDraft(this.phaseId, this.currentMapping)
        this.phaseCodeDraft = this.currentMapping?.xBeteiligungStandardCode || ''
        this.$emit('addonEvent:emit', {
          name: 'edit-start',
          payload: buildMappingPayload(this.phaseId),
        })
      } else {
        clearDraft(this.phaseId)
        this.phaseCodeDraft = ''
      }
    },

    /*
     * Core pushes a fresh payload after each successful save. Write it back
     * into the shared cache so the cross-row duplicate check never compares
     * against the stale values fetched when the table first loaded.
     */
    savedRowPayload (newPayload) {
      if (newPayload) {
        updateCachedPhaseMapping(this.phaseId, newPayload)
      }
    },
  },

  methods: {
    handleCodeInput (value) {
      this.phaseCodeDraft = value
      setDraftField(this.phaseId, 'xBeteiligungStandardCode', trimCockpitCodeOrNull(value))
      this.$emit('addonEvent:emit', {
        name: 'edit-change',
        payload: buildMappingPayload(this.phaseId),
      })
    },

    removeCode () {
      this.handleCodeInput('')
    },
  },

  created () {
    fetchAllPhaseMappings(this.demosplanUi.dpApi)
      .then(mappingsByPhaseId => {
        this.fetchedMapping = mappingsByPhaseId[this.phaseId] || null
      })
      .catch(err => handleFetchError(err, this.demosplanUi))
      .finally(() => {
        this.isLoading = false
      })
  },
}
</script>
