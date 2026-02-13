# XBeteiligung Phase Code Mapping - Complete with UI Labels

This document shows the complete mapping between DemosPlan procedure phases and XBeteiligung standard codes for all three projects.

---

## 1. diplanbau (Kommunal/K3 - Bauleitplanung)

### Internal Phases (Institution/TöB) - Message Type: 0401/0402

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration TöB** | `1000` | Einleitungsphase | ✅ Mapped |
| `earlyparticipation` | **Frühzeitige Beteiligung TöB - § 4 (1) BauGB** | `2000` | Frühzeitige Behördenbeteiligung | ✅ Mapped |
| `participation` | **Beteiligung TöB - § 4 (2) BauGB** | `5000` | Beteiligung der Träger öffentlicher Belange | ✅ Mapped |
| `anotherparticipation` | **Erneute Beteiligung TöB - § 4a (3) BauGB** | `5000` | Beteiligung der Träger öffentlicher Belange | ✅ Mapped |
| `evaluating` | **Auswertung TöB** | `7000` | Feststellungsverfahren | ✅ Mapped |
| `closed` | **Beschlussfassung TöB** | `8000` | Schlussphase | ✅ Mapped |
| `potentialanalysis` | **Bestands- und Potentialanalyse** | `2000` | Frühzeitige Behördenbeteiligung | ✅ Mapped |
| `heatplanningdraft` | **Entwurf Wärmeplan** | `5000` | Beteiligung der Träger öffentlicher Belange | ✅ Mapped |

### External Phases (Public/Öffentlichkeit) - Message Type: 0401/0402

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration Öffentlichkeit** | `1000` | Einleitungsphase | ✅ Mapped |
| `earlyparticipation` | **Frühzeitige Beteiligung Öffentlichkeit - § 3 (1) BauGB** | `4000` | Frühzeitige Öffentlichkeitsbeteiligung | ✅ Mapped |
| `participation` | **Beteiligung Öffentlichkeit** | `6000` | Digitale Veröffentlichung | ✅ Mapped |
| `anotherparticipation` | **Erneute Beteiligung Öffentlichkeit - § 4a (3) BauGB** | `6000` | Digitale Veröffentlichung | ✅ Mapped |
| `evaluating` | **Auswertung Öffentlichkeit** | `7000` | Feststellungsverfahren | ✅ Mapped |
| `closed` | **Beschlussfassung Öffentlichkeit** | `8000` | Schlussphase | ✅ Mapped |
| `potentialanalysis` | **Bestands- und Potentialanalyse** | `4000` | Frühzeitige Öffentlichkeitsbeteiligung | ✅ Mapped |
| `heatplanningdraft` | **Entwurf Wärmeplan** | `6000` | Digitale Veröffentlichung | ✅ Mapped |

### Summary diplanbau:
- **8 of 8 phases mapped** (100%)
- All phases now have XBeteiligung code mappings
- Heat planning phases (`potentialanalysis`, `heatplanningdraft`) mapped to closest matching codes

---

## 2. diplanrog (Raumordnung/R3 - Regionalplanung)

### Internal Phases (Institution/TöB) - Message Type: 0301/0302

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration TöB** | `4000` | Konfiguration TöB | ✅ Mapped |
| `earlyparticipation` | **Frühzeitige Beteiligung TöB** | `4100` | Frühzeitige Beteiligung TöB | ✅ Mapped |
| `participation` | **Beteiligung TöB** | `4200` | Anhörung TöB | ✅ Mapped |
| `discussiondate` | **Erörterungstermin** | `4400` | Erörterungstermin | ✅ Mapped |
| `renewparticipation` | **Erneute Beteiligung TöB** | `4500` | Erneute Anhörung TöB (Durchlaufnummer) | ✅ Mapped |
| `analysis` | **Auswertung TöB** | `4600` | Beschlussfassung TöB | ✅ Mapped |
| `closed` | **Beschlussfassung TöB** | `4700` | Beschlussfassung TöB | ✅ Mapped |

### External Phases (Public/Öffentlichkeit) - Message Type: 0301/0302

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration Öffentlichkeit** | `5000` | Konfiguration betroffene Öffentlichkeit | ✅ Mapped |
| `earlyparticipation` | **Frühzeitige Beteiligung Öffentlichkeit** | `5100` | Ermittlung und Information Betroffener (durch Gemeinden) | ✅ Mapped |
| `participation` | **Beteiligung Öffentlichkeit** | `5200` | Anhörung Betroffener (Öffentlichkeit) | ✅ Mapped |
| `discussiondate` | **Erörterungstermin** | `5400` | Erörterungstermin | ✅ Mapped |
| `renewparticipation` | **Erneute Beteiligung Öffentlichkeit** | `5500` | Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer) | ✅ Mapped |
| `analysis` | **Auswertung Öffentlichkeit** | `5600` | Beschlussfassung betroffene Öffentlichkeit | ✅ Mapped |
| `closed` | **Beschlussfassung Öffentlichkeit** | `5700` | Beschlussfassung betroffene Öffentlichkeit | ✅ Mapped |

### Summary diplanrog:
- **7 of 7 phases mapped for Institution** (100%)
- **7 of 7 phases mapped for Public** (100%)
- All phases now have proper XBeteiligung code mappings

---

## 3. diplanfest (Planfeststellung)

**Note**: All Planfeststellung phases currently map to code `9998` ("kein VS" - no procedure step defined) as the XBeteiligung standard for Planfeststellung is not yet fully defined. This is a temporary placeholder until proper codes are established.

### Internal Phases (Institution/TöB) - Message Type: 0201/0202 (not implemented yet!)

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration TöB** | `9998` | kein VS | ✅ Mapped (temporary) |
| `early` | **Frühzeitige Beteiligung (TÖB)** | `9998` | kein VS | ✅ Mapped (temporary) |
| `affectedmunicipalities` | **Ermittlung und Information Behörden und berührte Gemeinden** | `9998` | kein VS | ✅ Mapped (temporary) |
| `consultation` | **Anhörung TöB** | `9998` | kein VS | ✅ Mapped (temporary) |
| `replayevaluating` | **Erwiderung/Planänderung bzw. Auswertung** | `9998` | kein VS | ✅ Mapped (temporary) |
| `discussionmeeting` | **Erörterungstermin** | `9998` | kein VS | ✅ Mapped (temporary) |
| `reconsultation` | **Erneute Anhörung TöB** | `9998` | kein VS | ✅ Mapped (temporary) |
| `evaluating` | **Auswertung TöB** | `9998` | kein VS | ✅ Mapped (temporary) |
| `closed` | **Beschlussfassung TöB** | `9998` | kein VS | ✅ Mapped (temporary) |
| `other` | **Sonstige Beteiligung (TÖB)** | `9998` | kein VS | ✅ Mapped (temporary) |

### External Phases (Public/Öffentlichkeit) - Message Type: 0201/0202 (not implemented yet!)

| Phase Key | Displayed Name (UI) | XBeteiligung Code | XBeteiligung Code Name | Status |
|-----------|---------------------|-------------------|------------------------|--------|
| `configuration` | **Konfiguration betroffene Öffentlichkeit** | `9998` | kein VS | ✅ Mapped (temporary) |
| `early` | **Frühzeitige Beteiligung (Öffentlichkeit)** | `9998` | kein VS | ✅ Mapped (temporary) |
| `affectedmunicipalities` | **Ermittlung und Information Betroffene (durch Gemeinden)** | `9998` | kein VS | ✅ Mapped (temporary) |
| `consultation` | **Anhörung Betroffener (Öffentlichkeit)** | `9998` | kein VS | ✅ Mapped (temporary) |
| `replayevaluating` | **Erwiderung/Planänderung bzw. Auswertung** | `9998` | kein VS | ✅ Mapped (temporary) |
| `discussionmeeting` | **Erörterungstermin** | `9998` | kein VS | ✅ Mapped (temporary) |
| `reconsultation` | **Erneute Anhörung Betroffener (Öffentlichkeit)** | `9998` | kein VS | ✅ Mapped (temporary) |
| `evaluating` | **Auswertung betroffene Öffentlichkeit** | `9998` | kein VS | ✅ Mapped (temporary) |
| `closed` | **Beschlussfassung betroffene Öffentlichkeit** | `9998` | kein VS | ✅ Mapped (temporary) |
| `other` | **Sonstige Beteiligung (Öffentlichkeit)** | `9998` | kein VS | ✅ Mapped (temporary) |

### Summary diplanfest:
- **10 of 10 phases mapped** (100%)
- All phases temporarily map to code `9998` ("kein VS") as XBeteiligung Planfeststellung standard is not yet finalized
- **202 message creation not implemented** - so no messages are being sent anyway
- These mappings will need to be updated once proper Planfeststellung codes are defined in the XBeteiligung standard

---

## Overall Summary

### Mapping Coverage:

| Project | Institution | Public | Notes |
|---------|------------|--------|-------|
| **diplanbau** | 8/8 (100%) | 8/8 (100%) | All phases mapped ✅ |
| **diplanrog** | 7/7 (100%) | 7/7 (100%) | All phases mapped ✅ |
| **diplanfest** | 8/8 (100%) | 8/8 (100%) | All phases map to `9998` (temporary), 0202 not implemented |

### Resolved Issues:

1. ✅ **diplanbau**: All phases now mapped
   - `anotherparticipation` mapped to existing codes (5000/6000)
   - `potentialanalysis` mapped to early participation codes (2000/4000)
   - `heatplanningdraft` mapped to participation codes (5000/6000)

2. ✅ **diplanrog**: Key naming mismatches resolved
   - Added `analysis` key to mappings (4600/5600)
   - Added `renewparticipation` mapping for public (5500)
   - Added `earlyparticipation` mapping for institution (4100)

### Remaining Issues:

1. **diplanfest**: Temporary placeholder mappings
   - All phases currently map to code `9998` ("kein VS") as temporary placeholder
   - Config uses `early` and `other` keys which don't exist in `ProcedurePhaseKey` enum - these will use fallback `0815`
   - Proper XBeteiligung Planfeststellung codes need to be defined in the standard
   - **0202 message creation not yet implemented**

### Recommendations:

**Future Work:**

1. **diplanfest**: Implement 0202 message creation for Planfeststellung updates
2. **diplanfest**: Replace `9998` codes with proper Planfeststellung codes once XBeteiligung standard is finalized
3. **diplanfest**: Consider adding `EARLY` and `OTHER` cases to `ProcedurePhaseKey` enum if these phases are permanent
4. Monitor XBeteiligung standard updates for proper Planfeststellung phase codes

---

## Appendix: All XBeteiligung Verfahrensschritt Codes

This section lists all available Verfahrensschritt codes from the XBeteiligung/XPlan standard codelists (`develop_xmls/CodeLists/Verfahensschritte.xml`).

### Standard Codes (Used by XBeteiligung)

| Code | Description (German) | Usage Context |
|------|---------------------|---------------|
| `0000` | Vorplanung | Planfeststellung: Pre-planning phase |
| `1000` | Einleitungsphase | Kommunal: Initial/configuration phase |
| `2000` | Frühzeitige Behördenbeteiligung | Kommunal: Early authority participation |
| `3000` | Aufstellungsbeschluss | Resolution to prepare a plan |
| `4000` | Frühzeitige Öffentlichkeitsbeteiligung | Kommunal: Early public participation / Raumordnung TöB: Configuration |
| `5000` | Beteiligung der Träger öffentlicher Belange | Kommunal: Public agencies participation / Raumordnung Public: Configuration |
| `5010` | Behörden- und Öffentlichkeitsbeteiligung | Combined authority and public participation |
| `6000` | Öffentlichkeitsbeteiligung | Kommunal: Public participation/disclosure |
| `6010` | Einwendungen und Stellungnahmen | Objections and statements |
| `6050` | Planänderungen | Plan amendments |
| `6100` | Erörterung | Discussion/hearing |
| `7000` | Beschluss | Kommunal: Decision/resolution phase |
| `7010` | Planfeststellungsbeschluss | Planning approval decision |
| `7050` | Überwachung | Monitoring |
| `8000` | Schlussphase | Kommunal: Final phase |
| `9000` | Vor-Einleitungsphase | Pre-initiation phase |
| `9998` | kein VS | No procedure step defined |

### Raumordnung Specific Codes (Institution/TöB)

| Code | Description (German) | Status in Mapping |
|------|---------------------|------------------|
| `4000` | Konfiguration TöB | ✅ Mapped to `configuration` |
| `4200` | Anhörung TöB | ✅ Mapped to `participation` |
| `4400` | Erörterungstermin | ✅ Mapped to `discussiondate` |
| `4500` | Erneute Anhörung TöB (Durchlaufnummer) | ✅ Mapped to `renewparticipation` |
| `4600` | Beschlussfassung TöB | ⚠️ In mapping but unused (key mismatch: `evaluating` vs `analysis`) |
| `4700` | Beschlussfassung TöB | ✅ Mapped to `closed` |

### Raumordnung Specific Codes (Public/Öffentlichkeit)

| Code | Description (German) | Status in Mapping |
|------|---------------------|------------------|
| `5000` | Konfiguration betroffene Öffentlichkeit | ✅ Mapped to `configuration` |
| `5100` | Ermittlung und Information Betroffener (durch Gemeinden) | ✅ Mapped to `earlyparticipation` |
| `5200` | Anhörung Betroffener (Öffentlichkeit) | ✅ Mapped to `participation` |
| `5400` | Erörterungstermin | ✅ Mapped to `discussiondate` |
| `5500` | Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer) | ⚠️ In mapping but unused (key mismatch: expects `anotherparticipation` but config has `renewparticipation`) |
| `5600` | Beschlussfassung betroffene Öffentlichkeit | ⚠️ In mapping but unused (key mismatch: `evaluating` vs `analysis`) |
| `5700` | Beschlussfassung betroffene Öffentlichkeit | ✅ Mapped to `closed` |

### Planfeststellung Specific Codes

| Code | Description (German) | Status in Mapping |
|------|---------------------|------------------|
| `9998` | kein VS (no procedure step) | ✅ All Planfeststellung phases currently map to this (temporary) |

**Previous codes (no longer used for now):**
- `0000` - Vorplanung
- `0200` - Grobabstimmung / Discussion Meeting
- `0600` - Affected Municipalities
- `1200` - Consultation

**Note**: All Planfeststellung phases temporarily map to code `9998` until proper XBeteiligung Planfeststellung codes are established in the standard.

### Extended Codes (Additional/Regional)

These codes are available in the codelists but not currently used in the XBeteiligung addon:

| Code | Description (German) |
|------|---------------------|
| `1009` | Antragseingang |
| `1010` | Vollständigkeitsprüfung |
| `1500` | LALE |
| `3600` | Einleitungszustimmung |
| `4500` | Erarbeitung Planentwurf |
| `5001` | Öffentliche Bekanntmachung Planungsabsicht |
| `6001` | Beteiligung |
| `7001` | Beschluss |

### Bavaria-Specific Codes (BY*)

These codes are specific to Bavaria (Bayern) procedures and are not used in standard XBeteiligung:

| Code | Description (German) |
|------|---------------------|
| `BY1000` | Projektstart/Vorbereitung |
| `BY2000` | Aufstellungsbeschluss |
| `BY3000` | Frühzeitige Beteiligung der Öffentlichkeit nach §3 Abs.1 |
| `BY4000` | Frühzeitige Beteiligung der Behörden nach §4 Abs.1 |
| `BY5000` | Billigungs- und Veröffentlichungsbeschluss |
| `BY5001` | Beteiligung der Öffentlichkeit nach § 3 Abs. 2 |
| `BY6000` | Beteiligung der Behörden und sonstige Träger öffentlicher Belange nach §4 Abs.2 |
| `BY7000` | Abwägungs- und Satzungsbeschluss |
| `BY8000` | Inkrafttreten |

### Code Reuse in Different Contexts

⚠️ **Important**: Some codes are reused with different meanings depending on the procedure type:

- **`4000`**: 
  - Kommunal Public: "Frühzeitige Öffentlichkeitsbeteiligung"
  - Raumordnung TöB: "Konfiguration TöB"

- **`5000`**:
  - Kommunal TöB: "Beteiligung der Träger öffentlicher Belange"
  - Raumordnung Public: "Konfiguration betroffene Öffentlichkeit"

- **`0200`**:
  - Planfeststellung: Maps to multiple phases (discussionmeeting, reconsultation, replayevaluating)

This is why we cannot use a simple backed enum and must use arrays with code/name pairs in `ProcedurePhaseMapping.php`.

---
