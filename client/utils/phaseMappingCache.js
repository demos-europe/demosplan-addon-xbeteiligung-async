/*
 * Cache of every saved phase mapping, keyed by phase ID.
 * Main job: let the cockpit-code fields check for duplicates (isCockpitCodeDuplicate)
 * without a request per keystroke. It also carries the dcat code ID per phase,
 * so the portal-phase field can read/save its value from the same cache too.
 */
let cachedPhaseMappings = null
let requestInProgress = null

export function fetchAllPhaseMappings (dpApi) {
  if (cachedPhaseMappings !== null) {
    return Promise.resolve(cachedPhaseMappings)
  }

  if (requestInProgress) {
    return requestInProgress
  }

  const url = Routing.generate('api_resource_list', {
    resourceType: 'XBeteiligungPhaseDefinitionCodeMapping',
    fields: {
      XBeteiligungPhaseDefinitionCodeMapping: ['xBeteiligungStandardCode', 'dcatApPluStandardCode', 'phaseDefinition'].join(','),
    },
    include: 'dcatApPluStandardCode,phaseDefinition',
  })

  requestInProgress = dpApi.get(url)
    .then(({ data }) => {
      cachedPhaseMappings = data.data.reduce((mappingsByPhaseId, item) => {
        const phaseId = item.relationships?.phaseDefinition?.data?.id

        if (phaseId) {
          mappingsByPhaseId[phaseId] = {
            mappingId: item.id,
            xBeteiligungStandardCode: item.attributes.xBeteiligungStandardCode,
            dcatApPluStandardCodeId: item.relationships?.dcatApPluStandardCode?.data?.id ?? null,
          }
        }

        return mappingsByPhaseId
      }, {})

      return cachedPhaseMappings
    })
    .finally(() => {
      requestInProgress = null
    })

  return requestInProgress
}

function getCachedPhaseMappings () {
  return cachedPhaseMappings || {}
}

export function invalidatePhaseMappingCache () {
  cachedPhaseMappings = null
  requestInProgress = null
}

/*
 * Update the cached mapping after a successful save.
 * This keeps duplicate checks using the latest data instead of the values
 * loaded when the table was first opened. If the cache is not initialized,
 * do nothing—the next fetch will load fresh data from the server.
 */
export function updateCachedPhaseMapping (phaseId, mapping) {
  if (cachedPhaseMappings === null) {
    return
  }

  cachedPhaseMappings[phaseId] = {
    mappingId: mapping.mappingId ?? null,
    xBeteiligungStandardCode: mapping.xBeteiligungStandardCode ?? null,
    dcatApPluStandardCodeId: mapping.dcatApPluStandardCodeId ?? null,
  }
}

/*
 * Comparison is case-sensitive; empty value never counts.
 *
 * `excludeMappingId` tells the check to ignore the field's own saved code.
 * Without it, the field being edited would compare its value against itself.
 */
export function isCockpitCodeDuplicate (value, excludeMappingId) {
  const trimmed = (value || '').trim()

  if (trimmed === '') {
    return false
  }

  return Object.values(getCachedPhaseMappings()).some(entry =>
    entry.xBeteiligungStandardCode === trimmed && entry.mappingId !== excludeMappingId,
  )
}


// Used by both the create form and the table-edit cell
export function trimCockpitCodeOrNull (value) {
  return (value || '').trim() || null
}

