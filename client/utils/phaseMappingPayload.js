import { isCockpitCodeDuplicate } from './phaseMappingCache'
import { getDraft } from './phaseMappingEditDraft'

/*
 * Builds the payload emitted to core on edit-start and edit-change.
 *
 * Both the cockpit code and portal phase table cells edit the same record
 * through a shared draft. Because of that, each cell emits the same complete
 * payload, which is built from the merged draft instead of the cell's local
 * state.
 */
export function buildMappingPayload (phaseId) {
  const draft = getDraft(phaseId) || {}

  return {
    attributes: {
      xBeteiligungStandardCode: draft.xBeteiligungStandardCode ?? null,
    },
    changeDetectionValue: `${draft.xBeteiligungStandardCode ?? ''}::${draft.dcatApPluStandardCodeId ?? ''}`,
    isDuplicate: isCockpitCodeDuplicate(draft.xBeteiligungStandardCode, draft.mappingId),
    mappingId: draft.mappingId ?? null,
    phaseId,
    relationships: {
      dcatApPluStandardCode: {
        data: { type: 'XBeteiligungDcatApPluStandardCode', id: draft.dcatApPluStandardCodeId ?? null },
      },
    },
    resourceType: 'XBeteiligungPhaseDefinitionCodeMapping',
    savedRowPayload: {
      dcatApPluStandardCodeId: draft.dcatApPluStandardCodeId ?? null,
      mappingId: draft.mappingId ?? null,
      xBeteiligungStandardCode: draft.xBeteiligungStandardCode ?? null,
    },
  }
}
