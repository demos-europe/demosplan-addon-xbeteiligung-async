/*
 * The DCAT-AP-PLU codelist is a fixed, read-only set of 7 rows seeded via
 * migration — it never changes at runtime, so the cache never needs
 * invalidating. Shared between the portal-phase cell and
 * the create-form field.
 */
let cachedOptions = null
let requestInProgress = null

export function fetchDcatApPluStandardCodes (dpApi) {
  if (cachedOptions !== null) {
    return Promise.resolve(cachedOptions)
  }

  if (requestInProgress) {
    return requestInProgress
  }

  const url = Routing.generate('api_resource_list', {
    resourceType: 'XBeteiligungDcatApPluStandardCode',
    fields: {
      XBeteiligungDcatApPluStandardCode: 'description',
    },
  })

  requestInProgress = dpApi.get(url)
    .then(({ data }) => {
      cachedOptions = data.data.map(item => ({
        label: item.attributes.description,
        value: item.id,
      }))

      return cachedOptions
    })
    .finally(() => {
      requestInProgress = null
    })

  return requestInProgress
}
