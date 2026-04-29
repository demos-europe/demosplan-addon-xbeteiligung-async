<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    :is="demosplanUi.DpDataTable"
    :header-fields="headerFields"
    :items="phaseDefinitionsWithCodes"
    density="spacious"
    track-by="id"
    has-borders
    is-resizable
  />
</template>

<script>
export default {
  name: 'ProcedurePhasesCockpitCodes',

  props: {
    audience: {
      type: String,
      required: true,
    },

    demosplanUi: {
      type: Object,
      required: true,
    },

    headerFields: {
      type: Array,
      required: true,
    },

    phaseDefinitions: {
      type: Array,
      required: true,
    },
  },

  data () {
    return {
      phaseCodeByPhaseDefinitionId: {},
    }
  },

  computed: {
    phaseDefinitionsWithCodes () {
      return this.phaseDefinitions.map(phase => ({
        ...phase,
        phaseCode: this.phaseCodeByPhaseDefinitionId[phase.id] ?? '',
      }))
    },
  },

  methods: {
    fetchPhaseCodes () {
      const url = Routing.generate('api_resource_list', {
        resourceType: 'XBeteiligungPhaseDefinitionCode',
        fields: {
          XBeteiligungPhaseDefinitionCode: [
            'code',
            'phaseDefinition',
          ].join(','),
        },
        include: 'phaseDefinition',
      })

      this.demosplanUi.dpApi.get(url)
        .then(({ data }) => {
          this.phaseCodeByPhaseDefinitionId = data.data.reduce((acc, item) => {
            const phaseId = item.relationships?.phaseDefinition?.data?.id
            if (phaseId) {
              acc[phaseId] = item.attributes.code
            }

            return acc
          }, {})
        })
        .catch(err => {
          if (err?.data?.meta?.messages) {
            this.demosplanUi.handleResponseMessages(err.data.meta)
          } else {
            dplan.notify.error(Translator.trans('error.api.generic'))
          }
        })
    },
  },

  created () {
    this.fetchPhaseCodes()
  },
}
</script>
