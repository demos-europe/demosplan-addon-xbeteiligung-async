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
  >
    <component
      :is="demosplanUi.DpMultiselect"
      id="portalPhase"
      :allow-empty="false"
      :options="options"
      :value="selectedOption"
      label="label"
      track-by="value"
      @input="handleSelect"
    />
  </div>

  <span v-else>{{ currentPortalPhase }}</span>
</template>

<script>
import { fetchDcatApPluCodeOptions } from '../../utils/fetchDcatApPluCodeOptions'
import { handleFetchError } from '../../utils/handleFetchError'
import { fetchAllPhaseMappings } from '../../utils/phaseMappingCache'
import {
  clearDraft,
  initDraft,
  setDraftField,
} from '../../utils/phaseMappingEditDraft'
import { buildMappingPayload } from '../../utils/phaseMappingPayload'

export default {
  name: 'PortalPhaseTableField',

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
      options: [],
      selectedId: null,
    }
  },

  computed: {
    // Phase reference for the current Dcat-code
    currentPortalPhase () {
      return this.options.find(option => option.value === this.currentMapping?.dcatApPluStandardCodeId)?.label || ''
    },

    currentMapping () {
      return this.savedRowPayload || this.fetchedMapping
    },

    selectedOption () {
      return this.options.find(option => option.value === this.selectedId) || null
    },
  },

  watch: {
    isEditing (newValue) {
      if (newValue) {
        initDraft(this.phaseId, this.currentMapping)
        this.selectedId = this.currentMapping?.dcatApPluStandardCodeId ?? null
        this.$emit('addonEvent:emit', {
          name: 'edit-start',
          payload: buildMappingPayload(this.phaseId),
        })
      } else {
        clearDraft(this.phaseId)
      }
    },
  },

  methods: {
    handleSelect (option) {
      this.selectedId = option?.value ?? null
      setDraftField(this.phaseId, 'dcatApPluStandardCodeId', this.selectedId)
      this.$emit('addonEvent:emit', {
        name: 'edit-change',
        payload: buildMappingPayload(this.phaseId),
      })
    },
  },

  created () {
    this.isLoading = true

    Promise.all([
      fetchDcatApPluCodeOptions(this.demosplanUi.dpApi),
      fetchAllPhaseMappings(this.demosplanUi.dpApi),
    ])
      .then(([options, mappingsByPhaseId]) => {
        this.options = options
        this.fetchedMapping = mappingsByPhaseId[this.phaseId] || null
      })
      .catch(err => handleFetchError(err, this.demosplanUi))
      .finally(() => {
        this.isLoading = false
      })
  },
}
</script>
