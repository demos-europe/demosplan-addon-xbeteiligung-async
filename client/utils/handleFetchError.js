/*
 * Shared error handler for the addon's data fetches: surface backend response
 * messages when the API returns them, otherwise show a generic notification.
 */
export function handleFetchError (err, demosplanUi) {
  if (err?.data?.meta?.messages) {
    demosplanUi.handleResponseMessages(err.data.meta)
  } else {
    dplan.notify.error(Translator.trans('error.api.generic'))
  }
}
