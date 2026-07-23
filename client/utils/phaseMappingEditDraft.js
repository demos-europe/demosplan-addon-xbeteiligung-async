/*
 * Shared, mutable object letting the two cells that edit one mapping (cockpit
 * code + portal phase) see each other's changes. Deliberately not reactive —
 * each cell re-reads the other field before emitting, so nothing observes it.
 */
const draftsByPhaseId = {}

export function clearDraft (phaseId) {
  delete draftsByPhaseId[phaseId]
}

export function getDraft (phaseId) {
  return draftsByPhaseId[phaseId] || null
}

/*
 * Idempotent seed: does nothing if a draft already exists for phaseId,
 * so whichever cell's edit-start fires first wins and the second is a no-op.
 */
export function initDraft (phaseId, mapping) {
  if (phaseId in draftsByPhaseId) {
    return
  }

  draftsByPhaseId[phaseId] = { ...mapping }
}

export function setDraftField (phaseId, field, value) {
  draftsByPhaseId[phaseId] = {
    ...draftsByPhaseId[phaseId],
    [field]: value,
  }
}
