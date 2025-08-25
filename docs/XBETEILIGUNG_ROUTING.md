# XBeteiligung Routing Key Building

This document describes the XBeteiligung routing key building functionality implemented in the `RabbitMQMessageBroker` class for multi-mandant RabbitMQ message routing.

## Overview

The XBeteiligung routing system supports bidirectional message routing between Cockpit (planning systems) and Beteiligung (participation systems) using dynamically generated routing keys that comply with the XBeteiligung standard.

## Routing Key Formats

### Incoming Messages (from Cockpit)

**Format**: `*.cockpit.#`

- `*` - Wildcard for any mandant/subdomain (multi-mandant support)
- `cockpit` - Source system identifier
- `#` - Wildcard for all message types

### Outgoing Messages (to Cockpit)

**Format**: `{project_type}.beteiligung.{sender_organisation}.{sender_ags}.{receiver_organisation}.{receiver_ags}.{message_type}`

**Example**: `bau.beteiligung.bdp.020200000099.bap.020100000099.kommunal.initiieren.0411`

#### Format Components

| Component | Description | Example Values |
|-----------|-------------|----------------|
| `project_type` | Planning procedure type | `bau` (Kommunal), `rog` (Raumordnung), `pfv` (Planfeststellung) |
| `beteiligung` | Fixed identifier for XBeteiligung messages | `beteiligung` |
| `sender_organisation` | XöV organisation category of sender | `bdp` (Beteiligung system) |
| `sender_ags` | Extended AGS code of sending authority | `020200000099` (Hamburg, Stage) |
| `receiver_organisation` | XöV organisation category of receiver | `bap` (Cockpit system) |
| `receiver_ags` | Extended AGS code of receiving authority | `020100000099` (Schleswig-Holstein, Stage) |
| `message_type` | XBeteiligung message type | `kommunal.initiieren.0411` |

## Organisation Categories

### XöV Organisation Categories

- **BDP** (`bdp`): XöV-DvdvOrganisationskategorie - Beteiligung/Participation systems
- **BAP** (`bap`): Behördenanwendung Planung - Cockpit/Planning systems

### Usage Pattern

- **Outgoing messages**: Beteiligung → Cockpit
  - `sender_organisation`: `bdp` (this system)
  - `receiver_organisation`: `bap` (target Cockpit)
  
- **Incoming messages**: Cockpit → Beteiligung
  - Messages originate from `bap` systems
  - Received via `*.cockpit.#` pattern

## Project Type Mapping

The system maps procedure types to routing prefixes:

```php
private function mapProcedureTypeToRoutingPrefix(string $procedureType): string
{
    return match (strtolower($procedureType)) {
        'kommunal' => 'bau',           // Bauleitplanung
        'raumordnung' => 'rog',        // Raumordnung  
        'planfeststellung' => 'pfv',   // Planfeststellung
        default => throw new InvalidArgumentException(
            sprintf('Unknown procedure message type "%s"', $procedureType)
        )
    };
}
```

## AGS Code Extraction

### Source of AGS Codes

AGS (Amtlicher Gemeindeschlüssel) codes are extracted from:

1. **Incoming 401 messages**: Parsed from XML message headers during procedure creation
2. **Stored audit records**: Retrieved from audit XML for outgoing message routing

### Extended AGS Code Structure (XöV-Entsprechung)

Extended AGS codes follow the XöV-Entsprechung format:

- **Structure**: `{konstanter Prefix}{federal_state}{ags_rest}{Umgebungspostfix}`
- **Production Example**: `021206434000` (Neuhardenberg, Brandenburg, Production)
- **Stage Example**: `021206434099` (Neuhardenberg, Brandenburg, Stage)
  - `02`: Constant prefix
  - `12`: Federal state code (Brandenburg)
  - `064340`: Rest of AGS-Entsprechung from "12 0 64 340"
  - `00`: Production environment postfix
  - `99`: Stage/Test environment postfix

### Federal State Mapping

Federal state codes (positions 2-3) are used for customer assignment:

| Code | Federal State | Customer Subdomain |
|------|---------------|-------------------|
| 01 | Schleswig-Holstein | `sh` |
| 02 | Hamburg | `hh` |
| 03 | Niedersachsen | `ni` |
| 04 | Bremen | `hb` |
| 05 | Nordrhein-Westfalen | `nw` |
| 06 | Hessen | `he` |
| 07 | Rheinland-Pfalz | `rp` |
| 08 | Baden-Württemberg | `bw` |
| 09 | Bayern | `by` |
| 10 | Saarland | `sl` |
| 11 | Berlin | `be` |
| 12 | Brandenburg | `bb` |
| 13 | Mecklenburg-Vorpommern | `mv` |
| 14 | Sachsen | `sn` |
| 15 | Sachsen-Anhalt | `st` |
| 16 | Thüringen | `th` |

## Implementation Details

### Key Classes

#### RabbitMQMessageBroker

**Location**: `src/Tools/RabbitMQMessageBroker.php`

**Key Methods**:
- `buildIncomingRoutingKey()`: Returns `*.cockpit.#` for multi-mandant incoming messages
- `buildOutgoingRoutingKey($messageType, $procedureId)`: Builds dynamic outgoing routing keys
- `getProjectType()`: Determines project type from configuration
- `mapProcedureTypeToRoutingPrefix($procedureType)`: Maps procedure types to routing prefixes

#### XBeteiligungAgsService

**Location**: `src/Logic/XBeteiligungAgsService.php`

**Key Methods**:
- `getAgsCodesForRouting($procedureId)`: Extracts AGS codes from stored audit XML
- `extractAgsCodesFromXmlObject($xmlObject)`: Parses AGS codes from XML message objects

### Configuration Parameters

```yaml
# config/parameters_default.yml
addon_xbeteiligung_async_procedure_message_type: 'Kommunal'  # or 'Raumordnung', 'Planfeststellung'
```

### Constants

```php
// RabbitMQMessageBroker class constants
private const XOEV_ORGANISATION_SENDER = 'bdp';    // Beteiligung system
private const XOEV_ORGANISATION_RECEIVER = 'bap';  // Cockpit system
```

## Message Flow Examples

### Example 1: Kommunal Procedure Creation Response

**Scenario**: Hamburg Beteiligung responds to Schleswig-Holstein Cockpit (Stage Environment)

- **Procedure Type**: Kommunal (Bauleitplanung)
- **Sender AGS**: `020200000099` (Hamburg, Stage)
- **Receiver AGS**: `020100000099` (Schleswig-Holstein, Stage)
- **Message Type**: `kommunal.initiieren.0411`

**Generated Routing Key**: 
```
bau.beteiligung.bdp.020200000099.bap.020100000099.kommunal.initiieren.0411
```

### Example 2: Statement Submission

**Scenario**: Bayern Beteiligung sends statement to NRW Cockpit (Stage Environment)

- **Procedure Type**: Kommunal
- **Sender AGS**: `020900000099` (Bayern, Stage) 
- **Receiver AGS**: `020500000099` (NRW, Stage)
- **Message Type**: `allgemein.stellungnahme.neuabgegeben.0701`

**Generated Routing Key**:
```
bau.beteiligung.bdp.020900000099.bap.020500000099.allgemein.stellungnahme.neuabgegeben.0701
```

## Error Handling

### Missing AGS Codes

If AGS codes cannot be found for a procedure, the system:

1. Logs an error with procedure ID and message type
2. Throws `InvalidArgumentException` with descriptive message
3. **Does not send the message** (fail-safe behavior)

**Error Message Example**:
```
Cannot build routing key: No AGS codes found for procedure 12345-67890-abcdef
```

### Invalid Procedure Types

If an unknown procedure type is configured:

1. Throws `InvalidArgumentException` during routing key building
2. Lists valid procedure types in error message

**Error Message Example**:
```
Unknown procedure message type "invalid_type". Valid values: Kommunal, Raumordnung, Planfeststellung
```

## Logging

### Successful Routing Key Generation

```php
$this->logger->info('Built XBeteiligung outgoing routing key', [
    'routingKey' => $routingKey,
    'procedureId' => $procedureId,
    'projectType' => $projectType,
    'senderOrganisation' => 'bdp',
    'receiverOrganisation' => 'bap',
    'senderAgs' => $agsData['sender'],
    'receiverAgs' => $agsData['receiver']
]);
```

### Routing Key Build Failures

```php
$this->logger->error('Cannot send message: Failed to build dynamic routing key', [
    'procedureId' => $procedureId,
    'messageType' => $messageType,
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString()
]);
```

## Multi-Mandant Support

### Previous Behavior (Single-Mandant)

- **Incoming**: `{specific_mandant}.cockpit.#`  
- **Outgoing**: `{project_prefix}` (simple project prefix)

### Current Behavior (Multi-Mandant)

- **Incoming**: `*.cockpit.#` (accepts from any mandant)
- **Outgoing**: Full XBeteiligung routing key with AGS codes

### Migration Impact

- Existing single-mandant installations automatically upgraded to multi-mandant
- No configuration changes required
- Backward compatibility maintained for message processing

## Testing

### Unit Tests

**File**: `tests/Logic/XBeteiligungCustomerMappingServiceTest.php`

- Tests AGS code extraction and federal state mapping
- Validates customer subdomain mapping for all 16 German federal states
- Tests error handling for invalid AGS codes


## Troubleshooting

### Common Issues

1. **Missing AGS codes in audit XML**
   - **Cause**: Original 401 message not properly stored
   - **Solution**: Verify audit service is correctly storing incoming messages

2. **Invalid procedure type configuration**
   - **Cause**: Wrong value in `addon_xbeteiligung_async_procedure_message_type`
   - **Solution**: Use one of: `Kommunal`, `Raumordnung`, `Planfeststellung`

3. **Routing key build failures**
   - **Cause**: Missing or invalid AGS codes
   - **Solution**: Check audit records for procedure and verify AGS extraction


## Related Documentation

### Internal Documentation

- [Customer Mapping Documentation](CUSTOMER_MAPPING.md)
- [Multi-Mandant Support Documentation](MULTI_MANDANT_SUPPORT.md)
- [Audit Infrastructure Documentation](AUDIT_INFRASTRUCTURE.md)

### RabbitMQ Strategy Documents

- [RabbitMQ Routing Strategy Conclusion](../rabbitmq-routing-strategy-conclusion.md) - Overall routing strategy for DiPlan ecosystem
- [RabbitMQ Topic Exchange Conclusion](../rabbitmq-topic-exchange-conclusion.md) - Technical foundation for topic-based routing

### Implementation Planning

- [AGS to Customer Mapping Implementation Plan](../AGS_TO_CUSTOMER_MAPPING_IMPLEMENTATION_PLAN.md) - Detailed implementation roadmap