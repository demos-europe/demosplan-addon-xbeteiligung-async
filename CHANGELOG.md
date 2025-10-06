# Changelog

## UNRELEASED
**Error Code List Fix for NOK Messages (DPLAN-16732)**
- Fix incorrect error type (Fehlerart) codes in NOK messages according to XBeteiligung XSD schema
- Update error codes from invalid values (0200, 0300) to correct XSD-compliant value '3000' (Fehler)
- Add proper listURI and listVersionID attributes to CodeFehlerartType in error responses
## v0.39 (2025-10-06)
**Customer Mapping and Routing Configuration (DPLAN-16733)**
- Add support for AGS codes 98 and 99 in customer mapping for test environments
- Replace hardcoded XÖEV address prefixes with dynamic project-specific prefixes
- Update routing key builder to use project-specific prefixes (bdp/rog/pfv) based on procedure type

## v0.38 (2025-10-06)
**Statement Message Factory Refactoring (DPLAN-15365)**
- Extract phase handling logic to new `PhaseBuilder` class with project-specific phase type detection
- Extract statement author (Verfasser) creation to new `VerfasserBuilder` class with improved personal data handling
- Refactor `StatementMessageFactory` by extracting complex logic into specialized builder classes
- Improve statement text processing by properly handling HTML content (replace line breaks with spaces before stripping tags)
- Add comprehensive unit tests for PhaseBuilder and VerfasserBuilder components
- Remove unused methods from StatementAction value object to simplify codebase

## v0.37 (2025-10-02)
- Remove enum-based procedure phase mapping system (DPLAN-16438)
- Delete InstitutionParticipationPhase, PublicParticipationPhase, and ProcedurePhaseKey enums
- Simplify procedure phase handling by using hardcoded 'configuration' phase for all procedures

## v0.36 (2025-09-30)
- Territory display fix for XML-processed geltungsbereich
- Automatic GIS layer creation from flaechenabgrenzungsUrl in XBeteiligung messages
- Support for MultiPolygon geometries

## v0.35 (2025-09-25)
- fix rabbitMQ message send error for unbound procedures

## v0.34 (2025-09-23)
**Planfeststellung Message Support (DPLAN-16438)**
- Add support for Planfeststellung 0201 message creation in XBeteiligungEventSubscriber
- Enable automatic 201 message generation for new procedures when `feature_procedure_message_pln_create` permission is enabled
- Extend KommunaleProcedureCreater to handle both Kommunal and Planfeststellung message types with union type support
- Add PlanfeststellungInitiieren0201 class integration for K3 system communication

**RabbitMQ Exchange Configuration**
- Refactor exchange configuration from hardcoded parameter to dynamic generation based on project type
- Add getRabbitMqExchange() method for project-specific exchange naming (bau.beteiligung, rog.beteiligung, pfv.beteiligung)
- Remove hardcoded 'bau.beteiligung' exchange in favor of configurable project-based exchanges

**XML Namespace Fixes**
- Fix XML validation failures for Planfeststellung messages by correcting YAML namespace configurations
- Comment out namespace entries for anlage elements to ensure XSD compliance with form="unqualified" specification
- Update README documentation with comprehensive namespace configuration guidance

**Attachment Data Extraction Foundation**
- Add AnlagenExtractor service for extracting attachment metadata from XBeteiligung messages
- Implement AnlageValueObject for structured attachment data representation
- Note: This provides the foundation for attachment handling - actual processing implementation pending

**Test Infrastructure**
- Fix KommunaleProcedureHandlerFactory constructor dependency issues
- Add AnlagenExtractor mock support to test factory
- Resolve PHPUnit test failures with proper dependency injection

- improve rest api documentation

## v0.33 (2025-09-12)
- fix token length check
- improve logging

## v0.32 (2025-09-12)
- fix mysql 5.7 compatibility

## v0.31 (2025-09-12)
- keep php 8.1 compatibility

## v0.30 (2025-09-12)
- technical release

## v0.29 (2025-09-12)
- Enforce minimum auth token length for rest api access
**XBeteiligung Standard Update Version 1.2**
- The 1.2 version seems to be a living standard with ongoing updates.
- Updated XSD files to the latest versions available on ado.

**XSD Validation Fixes**
- Fixed XSD validation failures in XBeteiligung message factory by implementing default fallback codes
- Resolved empty code field issues in StatementMessageFactory mapping functions:
  - `statusDerStellungnahme()`: Default fallback to '1000' (neue Stellungnahme) 
  - `getArtOfStatement()`: Default fallback to '9999' (sonstiges)
  - `getArtOfFeedback()`: Default fallback to '1000' (E-Mail)
  - `getPriority()`: Default fallback to '3' (nicht vergeben)
  - `getAbwaegungVorschlag()`: Default fallback to '5000' (Die Stellungnahme wird zur Kenntnis genommen)
- Fixed Raumordnung procedure phase mapping with fallback to '5000' (Konfiguration betroffene Öffentlichkeit)
- Added comprehensive logging for unknown mapping values with fallback information
- All fallback codes comply with XBeteiligung XSD enumeration requirements

## v0.28 (2025-09-10)
**Routing Key Architecture Implementation**
- Add routing key field to XBeteiligungMessageAudit entity for complete message traceability
- Implement comprehensive routing key parsing and building system with XBeteiligungRoutingKeyParser and XBeteiligungOutgoingRoutingKeyBuilder services
- Replace XML-based AGS extraction with routing key-based customer identification and message routing
- Add complete unit test coverage for routing key services with extensive data providers and validation
- Create IncomingMessageData DTO and RoutingKeyComponents value object for structured routing key handling
- Update message processing architecture to preserve routing keys throughout the entire workflow
- Enhance audit system to store both incoming and outgoing routing keys for complete audit trails

## v0.27 (2025-09-10)
**XBeteiligung Phase Code Updates (DPLAN-16588)**
- Updated participation phase codes: 5300/4300 → 5200/4200
- Renamed phase 'earlyparticipation' to 'renewparticipation' for institution procedures
- Added new 'discussiondate' phase (code 5400/4400) for both participation types

## v0.26 (2025-09-05)
- adjust rabbitmq exchange

## v0.25 (2025-09-05)
**Bug Fixes**
- Fixed division by zero error in WMS URL generation when bounding box has zero width
- Added comprehensive error logging with try-catch-rethrow blocks in event handlers:
  - `newProcedureCreated()`: Logs errors during procedure creation events before rethrowing
  - `onProcedureChanged()`: Logs errors during procedure update/delete events before rethrowing
  - `handleStatementCreatedEvent()`: Logs errors during statement creation events before rethrowing
- Added `WMS_DEFAULT_WIDTH`, `DIMENSION_WIDTH`, and `DIMENSION_HEIGHT` constants to replace magic strings
- Enhanced logging for debugging collapsed/invalid bounding boxes

## v0.24 (2025-09-04)
**Features**
- Added configurable procedure type name support via `addon_xbeteiligung_async_procedure_type_name` parameter

**Bug Fixes**
- Fixed "invalid procedureTypeId value" error by implementing parameter-based procedure type name configuration

## v0.23 (2025-09-03)
**Bug Fixes**
- Fixed uninitialized typed property error in ResponseValue class

## v0.22 (2025-09-03)
**Statement ID Processing Improvements**
- Added `removeStatementIdPrefix()` method to remove `ID_` prefix from statement IDs
- Applied prefix removal to both 0711 (OK) and 0721 (NOK) statement response processing
- Added `STATEMENT_ID_PREFIX` constant to avoid magic strings
- Fixed statement ID length issues for database storage

**Routing Key Processing Improvements**
- Enhanced `XBeteiligungRoutingService::buildOutgoingRoutingKey()` to handle test environment routing keys
- Added special handling for `xyz:0001` AGS codes in test environment 
- Improved routing key format generation for both test and production environments
- Fixed routing key structure to match expected format: `{project}.beteiligung.{agsPart}.{messageType}`

**Kommunale Procedure Update Implementation (DPLAN-15682)**
- Complete error handling in `KommunaleProcedureUpdater`
- Moved `getErrorType()` method to `ProcedureCommonFeatures` base class
- Updated to use `ProcedureServiceInterface::updateProcedureObject()` with
direct EntityManager transactions
- Replaced `determineMessageContextAndDelegateAction()` with streamlined
`processXmlMessage()` method

## v0.21 (2025-08-29)
**RabbitMQ Direct Operations Implementation**

**DirectMessageConsumer & DirectMessagePublisher:**

  - New service implementing direct AMQP queue consumption using `basic_get()`
  to eliminate RPC timeout issues that were causing 30-second AMQPTimeoutException errors.
  - New service for publishing messages directly using `basic_publish()` with persistent
  messaging (delivery_mode: 2) instead of problematic RPC calls.

**Complete RPC Elimination:**

  - Removed timeout-prone RPC request-response pattern from `XBeteiligungMessageTransport`
  and `RabbitMQMessageBroker`.

**Direct XML Processing:**
  - Added `processXmlMessage()` method for handling XML messages without intermediate
  array conversion, returning nullable ResponseValue for acknowledgment-only messages.

**Statement Response Handling:**

  - Implemented proper processing for 711/721 statement acknowledgment messages with audit
  correlation using `findOriginalOutgoing701MessageByStatementId()`.

**Queue Name Mapping:**

  - Added configuration mapping for procedure types to their respective queues
  (bau.beteiligung, pfv.beteiligung, rog.beteiligung).

**Refactor RabbitMQMessageBroker following Symfony best practices (DPLAN-15764)**
  
  **Architecture Improvements:**
  - Extracted XBeteiligungConfiguration for type-safe configuration management
  - Created XBeteiligungRoutingService for routing key logic separation
  - Added XBeteiligungMessageTransport for clean RabbitMQ communication abstraction
  - Implemented XBeteiligungMessageProcessor for centralized message processing
  - Reduced main class complexity from 370 lines to 140 lines (-62%)
  
  **Benefits:**
  - Single Responsibility Principle compliance across all services
  - Enhanced testability with clear service boundaries
  - Type-safe configuration replacing magic string parameters
  - Improved maintainability and reduced coupling
  - Preserved all existing functionality while following Symfony patterns

## v0.20.2 (2025-08-20)
- add logging and add uuid for $requestId

## v0.20.1 (2025-08-20)
- fix (refs DPLAN-15681): Fix XML namespace validation for anlage elements
  - Fixed namespace configuration issue in BeteiligungRaumordnungType that caused `<xbeteiligung:anlage>` elements instead of `<anlage>`
  - Enhanced all XBeteiligung tests to include attachment validation, improving test coverage for namespace issues  
  - Updated README with comprehensive namespace configuration documentation for future standard updates
  - All XBeteiligung service tests now pass XSD validation with proper anlage element structure

## v0.20 (2025-08-05)
- adjust migrations

## v0.19 (2025-08-04)
- Add rabbitmq timeout parameter

## v0.18 (2025-08-04)
- Add comprehensive XBeteiligung message audit infrastructure (DPLAN-16006)
  
  **Core Infrastructure:**
  - New XBeteiligungMessageAudit entity with comprehensive audit tracking
  - XBeteiligungMessageAuditRepository for efficient data access
  - XBeteiligungAuditService providing centralized audit operations
  - Database migration with optimized indexes for high-performance querying
  
  **Complete Message Coverage:**
  - Cockpit (RabbitMQ) incoming messages: procedure creation (401) with planId extraction
  - Cockpit outgoing responses: OK/NOK acknowledgments (411/421) with message linking
  - Statement messages: new statement submissions (701) with statement ID tracking  
  - K3 system messages: procedure lifecycle messages (401/402/409/301/302/309)
  
  **Audit Features:**
  - Status lifecycle tracking: pending → processed/sent/failed
  - Full XML content preservation with metadata (procedure ID, plan ID, statement ID, target system)
  - Message relationship tracking via responseToMessageId for complete audit trails
  - Timestamp tracking (created_at, processed_at, sent_at) for performance analysis
  - Error details capture for failed message processing
  - Configurable via `addon_xbeteiligung_async_enable_audit` parameter (default: true)
  
  **Code Quality Improvements:**
  - Replace magic strings with service constants across codebase
  - Improve method naming clarity (getProcedureMessage → getXmlContent)
  - Remove redundant wrapper methods and unused constructor dependencies
  - Enhanced constant naming consistency
  
  **Documentation & Testing:**
  - Unit test coverage for XBeteiligungAuditService
  - Comprehensive technical documentation with message flow details

- Add dynamic AGS-based routing key system for RabbitMQ communication (DPLAN-15764)
  
  **Dynamic Routing Implementation:**
  - Replace static project prefix routing with dynamic AGS (Amtlicher Gemeindeschlüssel) extraction
  - Outgoing routing: `{project_type}.beteiligung.{sender_organisation}.{sender_ags}.{receiver_organisation}.{receiver_ags}.{message_type}`
  - Incoming routing: `*.cockpit.#` (multi-mandant support)
  - Example: `bau.beteiligung.bdp.020200000099.bap.020100000099.kommunal.initiieren.0411`
  - Project type mapping: Kommunal→bau, Raumordnung→rog, Planfeststellung→pfv
  
  **AGS Data Management:**
  - New XBeteiligungAgsService for dynamic AGS extraction from audit XML
  - Direct AGS extraction from procedure audit messages without database storage
  - Performance-optimized XML parsing with XÖV-compliant AGS code validation
  - Clean separation of concerns between audit storage and AGS extraction
  
  **Multi-tenant Configuration:**
  - Multi-mandant incoming routing with `*.cockpit.#` pattern
  - Remove legacy static routing key parameters
  - Fail-fast error handling with comprehensive logging
  - XÖV-compliant routing key format implementation

## v0.17 (2025-06-30)
- Change xbeteiligung standard from 1.3 to 1.2
- Changed the primary namespace for this addon to XLeitstelle xBeteiligung (xleitstelle.de/xbeteiligung/12)
  as we implement the xBeteiligung standard for public participation workflows.
- Use schema validation within getXmlObject method used in production for test xsds as well.
- Standardize XML namespace handling and improve readability
    - Updated all 28 YAML metadata files (10 input + 18 response messages) to use consistent namespace prefixes
    - Replaced auto-generated namespace prefixes (like `ns-625090a5`) with clean, readable prefixes (`g2g:`, `behoerde:`, `kommunikation:`, `xsi:`)
    - Corrected XML Schema instance namespace prefix from `xs:` to `xsi:` across all response message files
    - Added comprehensive `xml_namespaces` configuration to prevent JMS Serializer from generating random namespace prefixes
    - Removed inconsistent manual namespace handling in favor of unified JMS Serializer approach
    - Updated documentation with proper namespace configuration examples for both input and response messages
- Add PATCH REST endpoint `/addon/xbeteiligung/procedure/update` for XBeteiligung procedure updates
- Refactor and eliminate code duplication between create and update methods in XBeteiligungRestController
- Enhance test coverage with comprehensive PATCH endpoint tests

## v0.10.7 (2025-06-14)

### Fixed
- fix projection definition validation to handle empty projection labels in WMS URL generation

## v0.10.6 (2025-05-16)
- Symfony 6 compatibility
- fix creation of X09 messages
- fix getting map default projection label

## v0.10.5 (2025-05-07)
 - update API responses to include customer information in procedure messages endpoint
 - modify procedure message endpoint to return XML directly with proper Content-Type header

###  v0.16 (2025-06-13)
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
  - Updated test XML namespace from `xbeteiligung/12` to `xbeteiligung/1/3` and version from 1.1 to 1.3 to match XSD updates

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
