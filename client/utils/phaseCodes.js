/*
 * Shared cache of every saved phase code. Both the create-form field and
 * the inline-edit field read from it to check whether a typed code
 * already exists on another phase.
 *
 * The cache lives at module scope: declaring `let cachedPhaseCodes = null`
 * up here means the variable exists once for the whole addon bundle. Every
 * import of this file sees the same `cachedPhaseCodes` value.
 */
let cachedPhaseCodes = null
let requestInProgress = null

export function fetchAllPhaseCodes (dpApi) {
  if (cachedPhaseCodes !== null) {
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

  requestInProgress = dpApi.get(url)
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
    .finally(() => {
      requestInProgress = null
    })

  return requestInProgress
}

function getCachedPhaseCodes () {
  return cachedPhaseCodes || {}
}

export function invalidatePhaseCodesCache () {
  cachedPhaseCodes = null
  requestInProgress = null
}

/*
 * True when another phase already has the same code. Comparison is
 * case-sensitive; empty value never counts.
 *
 * `excludeResourceId` tells the check to ignore one entry — the field's
 * own saved code. Without it, the field being edited would compare its
 * value against itself in the cache and flag it as a duplicate.
 */
export function isDuplicateCode (value, excludeResourceId) {
  const trimmed = (value || '').trim()

  if (trimmed === '') {
    return false
  }

  return Object.values(getCachedPhaseCodes()).some(entry =>
    entry.code === trimmed && entry.resourceId !== excludeResourceId,
  )
}

export function removePhaseCode (phaseId) {
  if (cachedPhaseCodes === null) {
    return
  }

  delete cachedPhaseCodes[phaseId]
}

export function updateOrCreatePhaseCode (phaseId, code, resourceId) {
  if (cachedPhaseCodes === null) {
    cachedPhaseCodes = {}
  }

  cachedPhaseCodes[phaseId] = { code, resourceId }
}
