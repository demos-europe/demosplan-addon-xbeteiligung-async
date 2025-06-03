# Changelog

## UNRELEASED

- XSD Namespace Consistency Fix
  Fixed namespace mismatch between XBeteiligung baukasten and XBau kernmodul XSD files that was preventing successful xsd2php code
  generation.

  Problem: The xbeteiligung-baukasten.xsd file was importing kernmodul with namespace
  http://www.xleitstelle.de/xbau/kernmodul/1/2/1/1, but the kernmodul files were using http://www.xleitstelle.de/xbau/kernmodul/1/2/1.

  Solution: Updated both kernmodul XSD files to use the expected /1/2/1/1 namespace:
- xbau-kernmodul-datentypen.xsd - updated xmlns:xbauk and targetNamespace
- xbau-kernmodul-codes.xsd - updated xmlns:xbauk and targetNamespace

- PHPUnit 11 Compatibility Fix
  Updated test suite to be compatible with PHPUnit 11:
  - Made data provider methods static as required by PHPUnit 11+
  - Refactored MockFactoryTest from extending TestCase to using dependency injection pattern
  - Fixed constructor parameter issues and method visibility
  - Cleaned up unnecessary property declarations in test classes


###  v0.15 (2025-05-27)
- fix getting map default projection label
- add procedure origin information to ProcedureMessageController::showNewImportableProcedureMessages api response as
  k3 needs to know if the procedure was created within dplan or if the procedure was originally created within cockpit
- Ensure that config params use `addon_xbeteiligung_async_` prefix

###  v0.14 (2025-05-13)
- Do not send wmsUrl if procedure has no enabled layer.
- Add REST API endpoint `/addon/xbeteiligung/procedure/create` that accepts XML payload directly
- Simplified API accepts raw XML content rather than requiring JSON with messageTypeCode
- The REST API processes messages using the same logic as the RabbitMQ implementation and returns XML responses
- Uses a custom `X-Addon-XBeteiligung-Authorization` header for authentication to avoid interfering with the core application
- Added new parameters for rabbitmq addon_xbeteiligung_async_rabbitMqQueueName,
  addon_xbeteiligung_async_rabbitMqRequestIdGet and addon_xbeteiligung_async_rabbitMqRequestIdSend
- Added creation of 701 messages
- Removed duplicated code
- Enhance logging and error handling
- bug fixing
- Symfony 6 compatibility

## v0.13 (2025-04-08)
- enhance logging
- set the iteration (Durchgangsnummer) by getting it from the procedure
- fix bugs (namespace errors, wrong paths, 409 was not created, $messageClass was missing
  for 401, 402, 409, 301, 302 and 309)

## v0.12 (2025-04-04)
- bumped required demosplan-addon version to v0.51
- fetch orga by 401 included name and set its planners as authorized
- update the standard to 1.3 (new xsd files), mapping of every xsd namespace to
  on php namespace
- generate new schema yml files and php classes from xsd files
- Update the current addon code to be functional with the new 1.3 standard.
- Update XBeteiligungService
- Update ReadMe
- Add handling of public participation procedure phases and institution procedure phases
  for incoming 401 messages
- extract map data from given epsg:4326 territory-polygon and introduce fitting tests.

## v0.11 (2025-02-28)
- add rabbitMQ package
- implement rabbitMQ in services.yml
- adjust AddonMaintenanceEventInterface in eventSubscriber
- create some classes to restructure xbeteiligung-async to can to work on comming stories in future

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
