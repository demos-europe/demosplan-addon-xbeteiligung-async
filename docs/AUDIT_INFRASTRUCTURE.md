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

### Cockpit (RabbitMQ)
1. Incoming: `received/cockpit/pending` → `processed` (when procedure created)
2. Outgoing: `sent/cockpit/pending` → `sent` or `failed`

### K3 (REST API) 
1. Created: `sent/k3/pending` → `sent` (when K3 fetches)

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
$auditRecords = $auditService->findAuditRecordsByProcedureAndTargetSystem($procedureId, $targetSystem);
$originalMessage = $auditService->findOriginalIncoming401Message($procedureId);
$auditRecord = $repository->get($auditId);
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