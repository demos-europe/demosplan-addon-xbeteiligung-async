# Multi-Mandant Support in XBeteiligung

This document describes the multi-mandant (multi-tenant) support implementation in the XBeteiligung Async Addon, enabling a single Beteiligung instance to serve multiple federal states and administrative regions.

## Overview

Multi-mandant support allows one Beteiligung system to:
- Receive messages from multiple Cockpit instances across different federal states
- Automatically route procedures to the correct customer tenant
- Maintain data isolation between different administrative regions
- Scale horizontally to serve the entire German administrative landscape

## Architecture Overview

### Multi-Mandant Architecture

```
┌─────────────────┐
│   NRW Cockpit   │───┐
└─────────────────┘   │
                      │    ┌─────────────────────┐
┌─────────────────┐   ├───▶│  Central            │
│Hamburg Cockpit  │───┤    │  Beteiligung        │
└─────────────────┘   │    │  (Multi-Mandant)    │
                      │    └─────────────────────┘
┌─────────────────┐   │              │
│ Bayern Cockpit  │───┘              ▼
└─────────────────┘           ┌─────────────┐
                              │   Automatic │
                              │   Customer  │
                              │   Routing   │
                              └─────────────┘
                                     │
                    ┌────────────────┼────────────────┐
                    ▼                ▼                ▼
              ┌──────────┐    ┌──────────┐    ┌──────────┐
              │NRW Tenant│    │HH Tenant │    │BY Tenant │
              └──────────┘    └──────────┘    └──────────┘
```

**Features**:
- Single Beteiligung instance serves all federal states
- Automatic customer assignment via AGS mapping
- Centralized infrastructure
- Horizontal scalability

## Key Components

### 1. Multi-Mandant Message Routing

#### Incoming Messages

**Format**: `*.cockpit.#`
- Accepts messages from any mandant
- Wildcard routing for maximum flexibility
- Example: Messages from `nrw.cockpit.*`, `hh.cockpit.*`, `by.cockpit.*` are all received

#### Outgoing Messages

**Format**: Full XBeteiligung routing key
- Example: `bau.beteiligung.bdp.02020000000.bap.01001000000.kommunal.initiieren.0411`
- Contains complete routing information including AGS codes
- Enables precise message delivery to target Cockpit systems

### 2. Automatic Customer Assignment

**Trigger**: 401 messages (initial procedure creation)

**Process**:
1. Extract sender AGS from incoming message XML
2. Parse federal state code from AGS (positions 2-3)
3. Map federal state to customer subdomain
4. Lookup customer entity by subdomain
5. Assign procedure to determined customer

**Example Flow**:
```
Hamburg Message (AGS: 02020000000)
│
├─ Extract Federal State: '02' (Hamburg)
├─ Map to Subdomain: 'hh'
├─ Lookup Customer: Hamburg Customer Entity
└─ Assign Procedure: Set procedure.customer = hamburgCustomer
```

### 3. Configuration

#### Required Parameters

```yaml
# config/parameters_default.yml
addon_xbeteiligung_async_procedure_message_type: 'Kommunal'  # or 'Raumordnung', 'Planfeststellung'
```

No mandant-specific parameters are required - the system automatically handles all federal states.

## Implementation Details

### RabbitMQ Configuration

#### buildIncomingRoutingKey()

```php
private function buildIncomingRoutingKey(): string
{
    return '*.cockpit.#';  // Multi-mandant wildcard
}
```

#### buildOutgoingRoutingKey()

```php
protected function sendRabbitMq(string $xmlString, string $messageType, ?string $procedureId = null): bool
{
    $routingKey = $this->buildOutgoingRoutingKey($messageType, $procedureId);
    // Full XBeteiligung routing key with AGS codes
    // ...
}
```

### Customer Assignment Integration

#### ProcedureCommonFeatures Updates

**Dependencies**:
```php
public function __construct(
    // ... existing dependencies
    protected readonly CustomerServiceInterface $customerService,
    protected readonly XBeteiligungCustomerMappingService $customerMappingService,
    protected readonly XBeteiligungAgsService $agsService,
    // ...
)
```

#### KommunaleProcedureCreater Updates

**Procedure Creation Flow**:
```php
public function createNewKommunalProcedureFromXBeteiligungMessage(
    KommunalInitiieren0401 $xmlObject401
): ProcedureInterface {
    $messageContent = $xmlObject401->getNachrichteninhalt()->getBeteiligung();
    
    // Get customer before transaction
    $customer = $this->getCustomerFromAgsMapping($xmlObject401);

    return $this->transactionService->executeAndFlushInTransaction(
        function () use ($messageContent, $customer) {
            $procedure = $this->createProcedureEntity($messageContent);
            
            // Assign customer based on AGS mapping
            $procedure->setCustomer($customer);
            
            // ... rest of procedure creation
            return $procedure;
        }
    );
}
```

## Federal State Coverage

### Supported Federal States

All 16 German federal states are supported:

| Code | Federal State | Customer Subdomain | Capital |
|------|---------------|-------------------|---------|
| 01 | Schleswig-Holstein | `sh` | Kiel |
| 02 | Hamburg | `hh` | Hamburg |
| 03 | Niedersachsen | `ni` | Hannover |
| 04 | Bremen | `hb` | Bremen |
| 05 | Nordrhein-Westfalen | `nw` | Düsseldorf |
| 06 | Hessen | `he` | Wiesbaden |
| 07 | Rheinland-Pfalz | `rp` | Mainz |
| 08 | Baden-Württemberg | `bw` | Stuttgart |
| 09 | Bayern | `by` | München |
| 10 | Saarland | `sl` | Saarbrücken |
| 11 | Berlin | `be` | Berlin |
| 12 | Brandenburg | `bb` | Potsdam |
| 13 | Mecklenburg-Vorpommern | `mv` | Schwerin |
| 14 | Sachsen | `sn` | Dresden |
| 15 | Sachsen-Anhalt | `st` | Magdeburg |
| 16 | Thüringen | `th` | Erfurt |

### Customer Tenant Structure

Each federal state corresponds to a separate customer tenant:

```
Central Beteiligung Instance
├── Hamburg Tenant (hh)
│   ├── Hamburg Procedures
│   ├── Hamburg Users  
│   └── Hamburg Organizations
├── NRW Tenant (nw)
│   ├── NRW Procedures
│   ├── NRW Users
│   └── NRW Organizations
└── Bayern Tenant (by)
    ├── Bayern Procedures
    ├── Bayern Users
    └── Bayern Organizations
```

## Message Flow Examples

### Example 1: Hamburg Procedure Creation

**Scenario**: Hamburg Cockpit creates procedure, Beteiligung responds

1. **Incoming (Hamburg Cockpit → Beteiligung)**:
   - Routing Key: `hh.cockpit.kommunal.initiieren.0401`
   - Received via: `*.cockpit.#` binding
   - Customer: Extracted from sender AGS → `hh` tenant

2. **Processing**:
   - Procedure created in Hamburg tenant
   - AGS codes stored for response routing

3. **Outgoing (Beteiligung → Hamburg Cockpit)**:
   - Routing Key: `bau.beteiligung.bdp.02020000000.bap.02020000000.kommunal.initiieren.0411`
   - Target: Hamburg Cockpit (same authority that initiated)

### Example 2: Bayern Procedure Creation

**Scenario**: Bayern Cockpit creates procedure, Beteiligung responds

1. **Initial Message**: Bayern Cockpit sends 401 message to Central Beteiligung
2. **Customer Assignment**: Procedure assigned to Bayern tenant (by) based on AGS extraction
3. **Response**: Beteiligung sends 411 response back to Bayern Cockpit
4. **Routing**: Uses proper XBeteiligung routing key with Bayern AGS codes

## Setup Requirements

### Initial Configuration

To enable multi-mandant support, ensure the following:

1. **Customer Tenants Configuration**
   ```sql
   -- Ensure customers exist for all federal states that will send messages
   INSERT INTO customers (subdomain, name) VALUES ('hh', 'Hamburg');
   INSERT INTO customers (subdomain, name) VALUES ('nw', 'Nordrhein-Westfalen');
   INSERT INTO customers (subdomain, name) VALUES ('by', 'Bayern');
   -- Add other federal states as needed
   ```

2. **RabbitMQ Queue Configuration**
   - Configure queue to bind to `*.cockpit.#` pattern
   - Ensure proper permissions for multi-mandant message routing

3. **Testing Setup**
   - Send test 401 messages from different federal states
   - Verify automatic customer assignment works correctly
   - Confirm outgoing routing key generation includes AGS codes



## Security Considerations

### Tenant Isolation

1. **Data Separation**
   - Each customer tenant has isolated data
   - No cross-tenant data leakage
   - Proper authorization checks

2. **Message Validation**
   - Verify sender AGS matches claimed origin
   - Validate message authenticity
   - Prevent tenant spoofing

### Access Control

1. **Customer-Based Permissions**
   - Users can only access their tenant's data
   - Procedures automatically assigned to correct customer
   - No manual customer selection needed

2. **Administrative Oversight**
   - System administrators can view all tenants
   - Audit trail for cross-tenant operations
   - Monitoring for suspicious activity

## Troubleshooting

### Common Multi-Mandant Issues

1. **Wrong Customer Assignment**
   - **Symptoms**: Procedures appear in incorrect tenant
   - **Cause**: Invalid AGS code or mapping error
   - **Debug**: Check AGS extraction and federal state mapping

2. **Message Routing Failures**
   - **Symptoms**: Messages not delivered to target Cockpit
   - **Cause**: Incorrect routing key generation
   - **Debug**: Verify AGS codes and routing key format

3. **Performance Degradation**
   - **Symptoms**: Slow message processing
   - **Cause**: Database overload or inefficient queries
   - **Debug**: Monitor database performance and optimize queries

### Debug Commands

```bash
# Check multi-mandant routing configuration
docker logs xbeteiligung-async | grep "routing key"

# Verify customer assignments
docker exec -it database psql -c "SELECT subdomain, COUNT(*) FROM procedures GROUP BY customer_subdomain;"

# Monitor message distribution
docker logs xbeteiligung-async | grep "federal_state" | sort | uniq -c
```


## Related Documentation

- [XBeteiligung Routing Documentation](XBETEILIGUNG_ROUTING.md)
- [Customer Mapping Documentation](CUSTOMER_MAPPING.md)
- [AGS Service Documentation](AGS_SERVICE.md)
- [Performance Optimization Guide](PERFORMANCE_OPTIMIZATION.md)