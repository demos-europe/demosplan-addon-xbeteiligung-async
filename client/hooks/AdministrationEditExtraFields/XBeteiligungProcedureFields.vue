<license>
(c) 2010-present DEMOS plan GmbH.

This file is part of the package demosplan,
for more information see the license file.

All rights reserved
</license>

<template>
  <component
    :is="demosplanUi.DpInput"
    id="xBeteiligungPlanId"
    :label="{
      text: Translator.trans('xbeteiligung.plan.id.label'),
      hint: Translator.trans('xbeteiligung.plan.id.hint')
    }"
    :model-value="planId"
    @update:model-value="handlePlanIdInput"
  />
</template>

<script>
export default {
  name: 'XBeteiligungProcedureFields',

  props: {
    demosplanUi: {
      type: Object,
      required: true,
    },

    procedureId: {
      type: String,
      required: true,
    },
  },

  emits: ['addonEvent:emit'],

  data () {
    return {
      planId: '',
    }
  },

  computed: {
    planIdUrl () {
      return `${Routing.getBaseUrl()}/api/3.0/XBeteiligungProcedurePlanId/${this.procedureId}`
    },
  },

  mounted () {
    this.fetchPlanId()
  },

  methods: {
    // Load the planID currently stored on the procedure
    fetchPlanId () {
      return this.demosplanUi.dpApi.get(this.planIdUrl)
        .then(({ data }) => {
          this.planId = data.data.attributes.xtaPlanId ?? ''
        })
        .catch(error => {
          console.error('Could not load the planID of the procedure', error)
        })
    },

    handlePlanIdInput (value) {
      this.planId = value

      this.$emit('addonEvent:emit', {
        name: 'change',
        payload: {
          attributes: {
            xtaPlanId: value.trim(),
          },
          id: this.procedureId,
          method: 'PATCH',
          resourceType: 'XBeteiligungProcedurePlanId',
          url: this.planIdUrl, // Full path, core sends the request to it as it is
        },
      })
    },
  },
}
</script>
