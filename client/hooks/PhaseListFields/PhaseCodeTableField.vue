<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <span>{{ procedurePhaseCode }}</span>
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

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },

    phaseId: {
      type: String,
      required: true,
    },
  },

  data () {
    return {
      procedurePhaseCode: '',
    }
  },

  methods: {
    fetchPhaseCodes () {
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
              phaseCodesByPhaseId[phaseId] = item.attributes.code
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
  },

  created () {
    this.fetchPhaseCodes().then(byPhaseId => {
      if (this.phaseId in byPhaseId) {
        this.procedurePhaseCode = byPhaseId[this.phaseId]
      }
    })
  },
}
</script>
