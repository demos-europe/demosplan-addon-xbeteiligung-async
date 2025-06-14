# Changelog

## UNRELEASED

### Fixed
- fix projection definition validation to handle empty projection labels in WMS URL generation

## v0.10.6 (2025-05-16)
- Symfony 6 compatibility
- fix creation of X09 messages
- fix getting map default projection label

## v0.10.5 (2025-05-07)
 - update API responses to include customer information in procedure messages endpoint
 - modify procedure message endpoint to return XML directly with proper Content-Type header

## v0.10.4 (2025-05-05)
 - swap coords for CRS + special projections instead of SRS
 - return at least the default german basemap

## v0.10.3 (2025-05-02)
- technical release without any changes

## v0.10.2 (2025-05-02)
- provide default boundingbox

## v0.10.1 (2025-04-30)
- generate wms url without relying on basemap as baseLayer.
- include layers within the url.
- set CRS/SRS taking the used wms version into account
- use http query builder to automatically url_encode the Parameters used and for better
  visibility.
- reproject procedureSettingsCoords always stored in EPSG:3857 to the desired projection

## v0.10 (2025-01-13)
- refactor XBeteiligungProcedureChanged Event Listener and PlanningDocumentsLinkCreator
- circumvent removing entities from the unitOfWork computed change set after adding them onFlush

## v0.9 (2024-12-20)
- fix addon version constraint to allow any minor version higher than 0.30

## v0.8 (2024-11-18)
- Log xml messages in default log level info

## v0.7 (2024-11-15)
- add public detail url always to X01 and X02 messages

## v0.6.1 (2024-04-12)
- fix missing documents in 0302 messages

## v0.6 (2024-04-12)
- implement 03xx procedure messages
- introduce new config parameter and new permissions for creation
of different procedure messages (03xx, 04xx)

## v0.5 (2024-02-09)
- use stable addon installer version
- add release script

## v0.4.0 (2023-11-27)
- adjust usages of constants of ElementsInterface.php

## v0.3.1 (2023-11-09)

### Fixed
- BBox might be empty during procedure update or creation

## v0.3.0 (2023-11-09)

### Added
- feature (refs T35051): add files to procedure message
- feature add doctrine EventListener for onFlush event
for detecting procedure changes

### Removed
- feature remove PostProcedureUpdatedEvent for detecting procedure changes

## v0.2.0 (2023-10-11)

### Added
- feature (refs T35088): add missing properties that are relevant for a 402 procedure-update message.

## v0.1.0 (2023-10-11)

Initial release.
