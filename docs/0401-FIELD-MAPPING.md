# Complete Field Mapping: 0401 XML → DemosPlan

## Legend

| Symbol | Meaning |
|--------|---------|
| **USED** | Actively processed and stored in dplan |
| **ROUTING** | Only used for message routing/response generation |
| **STORED** | Extracted and persisted as metadata, but does not affect the procedure phase directly |
| **IGNORED** | Parsed but not further utilized |
| **NOT READ** | Not read by the code at all |

---

## 1. Root Attributes

| XML Field | Status | Usage |
|-----------|--------|-------|
| `produkt` | NOT READ | Required field per XSD, but not evaluated |
| `produkthersteller` | NOT READ | Required field per XSD, not evaluated |
| `standard` | NOT READ | Required field per XSD, not evaluated |
| `version` | NOT READ | Required field per XSD, not evaluated |

---

## 2. Message Header (`bn-g2g:nachrichtenkopf.g2g`)

| XML Field | Status | Usage in dplan |
|-----------|--------|----------------|
| `identifikation.nachricht > nachrichtenUUID` | ROUTING | Stored in audit table; used for response correlation |
| `identifikation.nachricht > nachrichtentyp > code` | ROUTING | Determines the message handler (401/402/409) |
| `identifikation.nachricht > nachrichtentyp > name` | NOT READ | — |
| `identifikation.nachricht > erstellungszeitpunkt` | NOT READ | — |
| **`leser > kennung`** | **ROUTING** | `XBeteiligungAgsService`: Stored as **receiver AGS** for routing key of responses |
| `leser > verzeichnisdienst` | NOT READ | — |
| `leser > name` | NOT READ | — |
| `leser > erreichbarkeit` | NOT READ | — |
| **`autor > kennung`** | **ROUTING** | `XBeteiligungAgsService`: Stored as **sender AGS** for routing key of responses |
| `autor > verzeichnisdienst` | NOT READ | — |
| `autor > name` | NOT READ | — |
| `autor > erreichbarkeit` | NOT READ | No mapping to contact person or similar |

---

## 3. Message Content > Participation (Main Data)

| XML Field | Status | dplan Field | Code Location |
|-----------|--------|------------|---------------|
| **`vorgangsID`** | IGNORED | Parsed but not stored in the procedure | Not read by `ProcedureDataExtractor::extract()` |
| **`akteurVorhaben > veranlasser > name > name`** | **USED** | Lookup: `orga.name` → **Organization assignment** + their users to the procedure | `ProcedureDataExtractor::extract()` → `KommunaleProcedureCreater::createProcedureEntity()` |
| `akteurVorhaben > weitereAkteure` | NOT READ | — | — |
| **`planID`** | **USED** | `_procedure.xta_plan_id` (xtaPlanId) | `ProcedureDataExtractor::extract()` → `createProcedureArrayFormatFromBeteiligungType()` |
| **`planname`** | **USED** | `_procedure._p_name` (r_name) | `ProcedureDataExtractor::extract()` → `createProcedureArrayFormatFromBeteiligungType()` |
| `arbeitstitel` | NOT READ | — | Not in `ProcedureDataExtractor::extract()` |
| `planartKommunal` | NOT READ | — | Not extracted |
| **`verfahrensschrittKommunal > code`** | **STORED** | Extracted via `ProcedurePhaseExtractor::getSpecificVerfahrensschrittType()`, passed to `ProcedurePhaseData`, and persisted via `ProcedurePhaseCodeDetector::storeExternalProcedurePhaseCodes()`. Phase itself is still **always set to `configuration`**. | `ProcedurePhaseExtractor::extract()` → `ProcedurePhaseCodeDetector` |
| `durchgang` (at participation level) | NOT READ | — | — |
| `verfahrensartKommunal` | NOT READ | — | — |
| **`beschreibungPlanungsanlass`** | **USED** | `_procedure._p_desc` (r_desc) **AND** `_procedure._p_external_desc` (r_externalDesc) | `ProcedureDataExtractor::extract()` → `createProcedureArrayFormatFromBeteiligungType()` |
| **`flaechenabgrenzungUrl`** | **USED** | GIS layer is created: `gis_layer.url`, `gis_layer.layers`, Name="Planzeichnung" | `ProcedureDataExtractor::extract()` → `XBeteiligungGisLayerManager::processUrl()` |
| **`geltungsbereich`** (GeoJSON) | **USED** | `procedure_settings.territory` (FeatureCollection, transformed EPSG:4326→3857), `procedure_settings.bounding_box`, `procedure_settings.map_extent` | `XBeteiligungMapService::setMapData()` → `KommunaleProcedureCreater::createNewKommunalProcedureFromXBeteiligungMessage()` |
| **`raeumlicheBeschreibung`** | NOT READ | — | Not in `ProcedureDataExtractor::extract()` |
| `beteiligungURL` | NOT READ | — | — |

---

## 4. beteiligungOeffentlichkeit (Public Participation)

| XML Field | Status | dplan Field | Code Location |
|-----------|--------|------------|---------------|
| `beteiligungsID` | NOT READ | — | — |
| **`verfahrensteilschrittKommunal`** | **STORED** | Code extracted and stored in `ProcedurePhaseData`, persisted via `ProcedurePhaseCodeDetector` | `ProcedurePhaseExtractor::getCodeOeffentlichkeitVerfahrensteilschritt()` |
| **`durchgang`** | **USED** | `procedure_phase.iteration` (public participation) | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| `bekanntmachung` | NOT READ | — | — |
| `aktuelleMitteilung` | NOT READ | — | — |
| **`zeitraum > beginn`** | **USED** | `_procedure.public_participation_start_date` | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| **`zeitraum > ende`** | **USED** | `_procedure.public_participation_end_date` | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| `zeitraum > zusatz` | NOT READ | — | — |
| **`anlagen > anlage`** (per document): | | | |
| ↳ `anlageart > code` | **USED** | Attachment type code in AnlageValueObject | `AnlagenExtractor::createAnlageValueObject()` |
| ↳ `anlageart > name` | **USED** | Attachment type name → **document category** (Element.title) | `AnlagenExtractor::createAnlageValueObject()` → `XBeteiligungAttachmentService::ensureDocumentCategory()` |
| ↳ `mimeType > code` | **USED** | Stored in AnlageValueObject | `AnlagenExtractor::createAnlageValueObject()` |
| ↳ `anhangOderVerlinkung > anhang > dokumentid` | **USED** | In AnlageValueObject | `AnlagenExtractor::processAnhangData()` |
| ↳ `anhangOderVerlinkung > anhang > dateiname` | **USED** | `single_document.r_title` + filename when saving | `AnlagenExtractor::processAnhangData()` → `XBeteiligungAttachmentService::processSingleAttachment()` |
| ↳ `anhangOderVerlinkung > anhang` (Base64 content) | **USED** | File is saved via `FileService::saveBinaryFileContent()` → `SingleDocument` is created | `XBeteiligungAttachmentService::saveAttachment()` |
| ↳ `anhangOderVerlinkung > anhang[filesize]` | NOT READ | — | — |
| ↳ `anhangOderVerlinkung > anhang[hashValue]` | NOT READ | No hash verification | — |
| ↳ `anhangOderVerlinkung > uriVerlinkung` | **USED** | URL in AnlageValueObject (but no download) | `AnlagenExtractor::processVerlinkungData()` |
| ↳ `versionsnummer` | **USED** | In AnlageValueObject | `AnlagenExtractor::createAnlageValueObject()` |
| ↳ `datum` | **USED** | In AnlageValueObject | `AnlagenExtractor::createAnlageValueObject()` |
| ↳ `bezeichnung` | **USED** | In AnlageValueObject | `AnlagenExtractor::createAnlageValueObject()` |
| **`beteiligungKommunalOeffentlichkeitArt`** | **STORED** | Code extracted via `getCodeOeffentlichkeitVerfahrensschritt()`, stored in `ProcedurePhaseData`, persisted via `ProcedurePhaseCodeDetector`. Also gates whether phase is set in `ProcedureCommonFeatures::setProcedurePhase()`. | `ProcedurePhaseExtractor::getCodeOeffentlichkeitVerfahrensschritt()` |
| `veroeffentlichungszeitraum` | NOT READ | — | — |

---

## 5. beteiligungTOEB (Public Agency Participation)

| XML Field | Status | dplan Field | Code Location |
|-----------|--------|------------|---------------|
| `beteiligungsID` | NOT READ | — | — |
| **`durchgang`** | **USED** | `procedure_phase.iteration` (institutional participation) | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| `bekanntmachung` | NOT READ | — | — |
| `aktuelleMitteilung` | NOT READ | — | — |
| **`zeitraum > beginn`** | **USED** | `_procedure._p_start_date` (public agency start date) | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| **`zeitraum > ende`** | **USED** | `_procedure._p_end_date` (public agency end date) | `ProcedurePhaseExtractor::extract()` → `ProcedureCommonFeatures::setProcedurePhase()` |
| `zeitraum > zusatz` | NOT READ | — | — |
| **`anlagen > anlage`** | **USED** | Same as public participation: files are saved, SingleDocuments are created | `AnlagenExtractor::extractToebAttachments()` |
| **`beteiligungKommunalTOEBArt`** | **STORED** | Code extracted via `getCodeBeteiligungTOEBVerfahrensschritt()`, stored in `ProcedurePhaseData`, persisted via `ProcedurePhaseCodeDetector`. Also gates whether phase is set in `ProcedureCommonFeatures::setProcedurePhase()`. | `ProcedurePhaseExtractor::getCodeBeteiligungTOEBVerfahrensschritt()` |
| `veroeffentlichungszeitraum` | NOT READ | — | — |

---

## 6. Indirect Assignments (not from XML content)

| Source | dplan Field | Mechanism |
|--------|------------|-----------|
| **Routing Key Header** (`X-Addon-XBeteiligung-RoutingKey`) | `_procedure.customer` | Federal state code from routing key → `XBeteiligungCustomerMappingService` → customer assignment |
| `veranlasser > name` (org lookup) | `_procedure.orga`, `_procedure.orgaName`, `agencyMainEmailAddress` | Organization found by name → `orga.id`, `orga.name`, `orga.email2` |
| `veranlasser > name` (user lookup) | `_procedure.authorizedUsers` | First user of the organization with role `PLANNING_AGENCY_ADMIN` or `HEARING_AUTHORITY_ADMIN` → becomes procedure creator |
| Configuration `procedureTypeName` | `_procedure.procedure_type` | From `XBeteiligungConfiguration` → ProcedureType by name |
| Hardcoded | `_procedure.public_participation_phase` = **`configuration`** | `ProcedurePhaseExtractor::extract()` always passes `CONFIGURATION_PHASE` to `ProcedurePhaseData` |
| Hardcoded | `_procedure.phase` (public agency) = **`configuration`** | `ProcedurePhaseExtractor::extract()` always passes `CONFIGURATION_PHASE` to `ProcedurePhaseData` |
| Hardcoded | `_procedure.master` = **`false`** | `createProcedureArrayFormatFromBeteiligungType()` |
| Hardcoded | `_procedure.copymaster` | Master template ID from system |

---

## Summary

### Actively Used (13 fields)

- `veranlasser > name` → Organization assignment (critical! Name must exactly match the organization in BOBSH)
- `planID` → xtaPlanId
- `planname` → Procedure name
- `beschreibungPlanungsanlass` → Description + external description
- `geltungsbereich` → Map extent (territory, bbox, mapExtent)
- `flaechenabgrenzungUrl` → GIS layer
- `zeitraum beginn/ende` (public) → Public participation deadlines
- `zeitraum beginn/ende` (public agency) → Public agency deadlines
- `durchgang` (public + public agency) → Iteration number
- `anlagen` (both sections) → Documents/files

### Stored as Metadata (5 fields)

These are extracted, passed through `ProcedurePhaseData`, and persisted via `ProcedurePhaseCodeDetector::storeExternalProcedurePhaseCodes()`. They gate conditional phase-setting logic in `ProcedureCommonFeatures::setProcedurePhase()`, but the actual phase key remains `configuration`.

- `verfahrensschrittKommunal > code` → External procedure phase code
- `beteiligungKommunalOeffentlichkeitArt` → Public participation phase sub-code
- `beteiligungKommunalTOEBArt` → Public agency participation phase sub-code
- `verfahrensteilschrittKommunal` (public) → Public participation sub-step code
- `verfahrensteilschrittKommunal` (TOEB) → Public agency participation sub-step code

### Routing Only (3 fields)

- `autor > kennung` → Sender AGS for responses
- `leser > kennung` → Receiver AGS for responses
- `nachrichtenUUID` → Audit correlation

### Ignored / Not Read (~15 fields)

- `arbeitstitel`, `planartKommunal`, `verfahrensartKommunal`, `raeumlicheBeschreibung`
- `bekanntmachung`, `aktuelleMitteilung`, `veroeffentlichungszeitraum`
- `beteiligungsID`
- Entire `autor` block (name, reachability) - except `kennung`
- Entire `leser` block (name, reachability) - except `kennung`
- `vorgangsID`
- All root attributes (`produkt`, `produkthersteller`, etc.)
- `zeitraum > zusatz`, `anhang[filesize]`, `anhang[hashValue]`

---

## Important Notes

1. **Phase is always `configuration`**: Regardless of the `verfahrensschrittKommunal` code, the procedure is always created in the `configuration` phase. The external phase codes are stored via `ProcedurePhaseCodeDetector` and used to conditionally gate phase-setting in `ProcedureCommonFeatures::setProcedurePhase()`, but the phase key itself remains `configuration`. The phase must be changed manually or via a 0402 update.

2. **Organization name must match exactly**: The `veranlasser > name` is used for an exact string comparison with `orga.name` in the database. Any deviations (whitespace, umlauts, capitalization) will cause the assignment to fail.

3. **Documents from both sections end up in the same procedure**: Attachments from `beteiligungOeffentlichkeit` and `beteiligungTOEB` are both assigned to the procedure, but placed in separate document categories (based on `anlageart > name`).

4. **No hash verification**: The `hashValue` of attachments is not checked. No integrity verification of transferred files takes place.

5. **Customer assignment is based on routing key**: Not via XML content, but via the HTTP header `X-Addon-XBeteiligung-RoutingKey`. The federal state code in the routing key determines the customer.
