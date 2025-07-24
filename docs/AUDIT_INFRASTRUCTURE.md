# XBeteiligung Message Audit Infrastructure

Audit infrastructure for XBeteiligung message processing (DPLAN-16006).

## Components

- **XBeteiligungMessageAudit** - Entity storing audit records
- **XBeteiligungMessageAuditRepository** - Data access layer
- **XBeteiligungAuditService** - Central audit service

## Configuration

```yaml
addon_xbeteiligung_async_enable_audit: true  # Enable audit (default: true)
```

## Database Schema

Key fields:
- `direction` - 'received' or 'sent'
- `target_system` - 'cockpit' or 'k3'
- `message_type` - e.g., 'kommunal.Initiieren.0401'
- `message_content` - Full XML content
- `procedure_id` - Added when procedure created
- `plan_id` - Extracted from XML
- `response_to_message_id` - Links response messages to original
- `statement_id` - Statement ID for 0701 messages
- `status` - 'pending', 'processed', 'sent', 'failed'
- `error_details` - Error information for failed messages
- `created_at`, `processed_at`, `sent_at` - Timestamp tracking

## Message Flow

The XBeteiligung system implements two distinct communication flows with comprehensive audit tracking.

### Audit Record Notation

Audit records are described using the format `direction/targetSystem/status`:

- **Direction**: 
  - `received` - Messages coming into demosplan from external systems
  - `sent` - Messages going out from demosplan to external systems

- **Target System**:
  - `cockpit` - External planning systems via RabbitMQ
  - `k3` - K3 system via REST API

- **Status**:
  - `pending` - Initial status (processing not yet started/completed)
  - `processed` - Incoming message successfully processed (procedure created)
  - `sent` - Outgoing message successfully transmitted
  - `failed` - Processing or transmission failed

Example: `received/cockpit/pending` = Message received from Cockpit system, not yet processed

### Cockpit (RabbitMQ) Flow - Bidirectional External System Communication

**Incoming Message Processing:**
1. **Message Reception** (`RabbitMQMessageBroker::processMessages()`)
   - RabbitMQ client polls for messages with routing keys
   - Initial audit record: `received/cockpit/pending` (default status for received messages)
   - planId extracted from XML and stored in audit record

2. **Message Processing** (`XBeteiligungService::determineMessageContextAndDelegateAction()`)
   - XML parsed and message type determined from `messageTypeCode`
   - **Status Update**: `pending` → `processed` via `markAsProcessed()`
   - **Success**: Procedure ID linked via `updateAuditWithProcedureId()`
   - **Failure**: Status becomes `failed` via `markAsFailed()` with error details

**Outgoing Response Processing:**
1. **Response Generation**
   - OK/NOK response created based on processing results
   - New audit record: `sent/cockpit/pending` 
   - Links to original message via `responseToMessageId`

2. **RabbitMQ Transmission** (`sendRabbitMq()`)
   - **Success**: Status `pending` → `sent` via `markAsSent()`
   - **Failure**: Status `pending` → `failed` via `markAsFailed()`

**Statement Messages (701):**
- Triggered by `StatementCreatedEventInterface`
- Audit record: `sent/cockpit/pending` with statement ID
- Same transmission flow as responses

### K3 (REST API) Flow - Event-Driven Message Generation

**Message Creation:**
1. **Trigger Events** (`XBeteiligungEventSubscriber`)
   - `PostNewProcedureCreatedEventInterface` → 401/301 messages
   - Procedure updates → 402/302/409/309 messages

2. **Message Storage**
   - Messages stored in `ProcedureMessage` entity
   - Audit record: `sent/k3/pending` 
   - Initial status always `pending` for K3 messages

3. **Delivery Tracking**
   - `markK3MessageAsDelivered()` validates and updates: `pending` → `sent`
   - Includes validation for direction (`sent`), target system (`k3`), and current status

**K3 REST API Access (ProcedureMessageController):**
- `/api/procedure_message/{procedureMessageId}` (GET) - K3 fetches specific message
- `/api/new/procedure_message/ids` (GET) - K3 gets list of available messages  
- `/api/procedure_message/delete/{procedureMessageId}` (GET) - Mark message as deleted
- `/api/procedure_message/error/{procedureMessageId}` (GET) - Mark message as error
- Authentication via `authToken` header using `addon_xbeteiligung_async_api_token` parameter

### Status Transition Summary

```
Received Messages (Cockpit only):
pending → processed → (linked to procedure)

Sent Messages (Cockpit & K3):
pending → sent/failed

Statement Messages (Cockpit only):  
pending → sent/failed (with statement ID tracking)
```

## API Usage

```php
// Create audit records
$auditService->auditReceivedMessage($xmlContent, $messageType, $planId, $procedureId, $responseToMessageId);
$auditService->auditSentMessage($xmlContent, $messageType, $procedureId, $planId, $responseToMessageId, $statementId);
$auditService->auditK3Message($xmlContent, $messageType, $procedureId, $planId, $saveOnFlush);

// Update status
$auditService->markAsProcessed($auditId, $procedureId);
$auditService->markAsSent($auditId);
$auditService->markAsFailed($auditId, $errorDetails);

// K3 specific operations
$success = $auditService->markK3MessageAsDelivered($auditId); // Returns bool

// Update existing audit records
$auditService->updateAuditWithProcedureId($auditId, $procedureId);

// Query operations
$originalMessage = $auditService->findOriginalIncoming401Message($procedureId);
$auditRecord = $repository->get($auditId);
$auditRecords = $repository->findByProcedureIdAndTargetSystem($procedureId, $targetSystem);
```

## Supported Message Types

**Cockpit (RabbitMQ):**
- `kommunal.Initiieren.0401`
- `allgemein.stellungnahme.Neuabgegeben.0701`
- `kommunal.Initiieren.OK.0411`, `kommunal.Initiieren.NOK.0421` (responses)

**K3 (REST API):**
- `kommunal.Initiieren.0401`, `kommunal.Aktualisieren.0402`, `kommunal.Loeschen.0409`
- `raumordnung.Initiieren.0301`, `raumordnung.Aktualisieren.0302`, `raumordnung.Loeschen.0309`

## Status Values

- `pending` - Initial status
- `processed` - Incoming message processed (procedure created)
- `sent` - Successfully delivered
- `failed` - Processing/delivery failed

## Implementation Details

### saveOnFlush Parameter
Use `$saveOnFlush = true` when auditing during Doctrine flush events to avoid infinite loops:
```php
// During flush events (e.g., in saveProcedureMessageOnFlush)
$auditService->auditK3Message($content, $type, $procedureId, $planId, true);
```

### Error Handling
- All methods gracefully handle missing audit records without throwing exceptions
- Failed operations are logged with appropriate context
- K3 delivery marking includes comprehensive validation

### Message Relationships
- `response_to_message_id` links OK/NOK responses to original 401 messages
- `statement_id` tracks statements for 701 messages
- Use `findOriginalIncoming401Message()` to find the correct original message for responses

## Technical Notes

- Migration: `src/DoctrineMigrations/2025/06/Version20250627120000.php`
- All commonly queried fields are indexed for performance
- Full XML content stored but not indexed (LONGTEXT)
- Unit tests: `tests/Logic/XBeteiligungAuditServiceUnitTest.php`
- Integration points: RabbitMQMessageBroker, XBeteiligungService, ProcedureMessageController
