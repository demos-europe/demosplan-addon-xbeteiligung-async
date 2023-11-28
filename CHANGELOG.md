# Changelog

## UNRELEASED

- add release script
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
