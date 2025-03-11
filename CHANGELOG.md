# Changelog

## UNRELEASED

## v0.11 (2025-03-10)
- update the standard to 1.3 (new xsd files)
- generate new schema yml files and php classes from xsd files
- Update the current addon code to be functional with the new 1.3 standard.

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
