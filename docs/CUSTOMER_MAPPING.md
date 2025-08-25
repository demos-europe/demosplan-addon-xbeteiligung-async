# XBeteiligung Customer Mapping Service

This document describes the customer mapping functionality that automatically assigns procedures to the correct DemosPlan customer tenant based on AGS (Amtlicher Gemeindeschlüssel) codes extracted from XBeteiligung messages.

## Overview

The `XBeteiligungCustomerMappingService` enables multi-mandant support by mapping German administrative region codes (AGS) to specific customer subdomains, ensuring that procedures created from XBeteiligung 401 messages are automatically assigned to the correct tenant.

## Key Concepts

### AGS Codes (Amtlicher Gemeindeschlüssel)

AGS codes are official German administrative identifiers that uniquely identify municipalities and administrative regions. In the XBeteiligung context, they use the extended XöV-Entsprechung format.

**Structure**: `{konstanter Prefix}{federal_state}{ags_rest}{Umgebungspostfix}`

**Example**: `020200000099` (Hamburg, Stage)
- Positions 0-1: `02` (Constant prefix)
- Positions 2-3: `02` (Hamburg federal state code)  
- Positions 4-9: `000000` (Rest of AGS-Entsprechung)
- Positions 10-11: `99` (Stage environment postfix)

### Federal State Extraction

The service extracts the federal state code from positions 2-3 of the AGS code and maps it to a customer subdomain.

## Federal State to Customer Mapping

| Federal State Code | Federal State | Customer Subdomain |
|-------------------|---------------|-------------------|
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

## Service Implementation

### Class Location

**File**: `src/Logic/XBeteiligungCustomerMappingService.php`

### Key Methods

#### extractFederalStateCode(string $agsCode): string

Extracts the federal state code from an AGS code.

**Parameters**:
- `$agsCode`: XöV-Kennung-Code (minimum 4 digits)

**Returns**: Two-digit federal state code

**Throws**: `InvalidArgumentException` for invalid codes

**Example**:
```php
$service->extractFederalStateCode('020200000099'); // Returns '02'
$service->extractFederalStateCode('020500000099'); // Returns '05'  
```

#### mapAgsToCustomerSubdomain(string $agsCode): string

Maps an AGS code to a customer subdomain.

**Parameters**:
- `$agsCode`: XöV-Kennung-Code

**Returns**: Customer subdomain string

**Throws**: `InvalidArgumentException` for invalid codes or unmapped federal states

**Example**:
```php
$service->mapAgsToCustomerSubdomain('020200000099'); // Returns 'hh' (Hamburg, Stage)
$service->mapAgsToCustomerSubdomain('020500000099'); // Returns 'nw' (NRW, Stage)
```

#### getCustomerByAgsCode(string $agsCode): CustomerInterface

Gets a customer entity by AGS code.

**Parameters**:
- `$agsCode`: XöV-Kennung-Code

**Returns**: CustomerInterface entity

**Throws**: `Exception` if customer not found

**Example**:
```php
$customer = $service->getCustomerByAgsCode('020200000099');
echo $customer->getId(); // Returns Hamburg customer ID
```

## Usage in Procedure Creation

### Integration Point

**File**: `src/Logic/Kommunale/KommunaleProcedureCreater.php`

The customer mapping service is integrated into the procedure creation workflow for 401 messages (initial procedure creation).

### Workflow

1. **AGS Extraction**: Extract AGS codes from incoming 401 message XML
2. **Customer Mapping**: Map sender AGS to customer subdomain using federal state code
3. **Customer Lookup**: Find customer entity by subdomain
4. **Assignment**: Set customer on newly created procedure
5. **Logging**: Log successful mapping or errors

### Code Example

```php
private function getCustomerFromAgsMapping(KommunalInitiieren0401 $xmlObject401): CustomerInterface
{
    try {
        // Extract AGS codes from XML
        $agsCodes = $this->agsService->extractAgsCodesFromXmlObject($xmlObject401);
        $senderAgs = $agsCodes['sender'];

        if (null !== $senderAgs) {
            // Map AGS to customer
            $customer = $this->customerMappingService->getCustomerByAgsCode($senderAgs);

            $this->logger->info('Successfully mapped AGS code to customer for 401 message', [
                'senderAgs' => $senderAgs,
                'customerId' => $customer->getId(),
                'messageType' => '401'
            ]);

            return $customer;
        }

        throw new RuntimeException('No sender AGS code found in 401 message');
    } catch (Exception $exception) {
        $this->logger->error('Failed to get customer based on AGS mapping', [
            'errorMessage' => $exception->getMessage(),
            'exception' => $exception,
            'messageType' => '401'
        ]);
        throw $exception;
    }
}
```

## Message Type Behavior

### 401 Messages (Initial Procedure Creation)

- **Customer Assignment**: Uses AGS mapping to determine customer
- **Source**: Sender AGS from message XML headers
- **Behavior**: Procedure creation fails if customer mapping fails

### 402+ Messages (Procedure Updates)

- **Customer Assignment**: Uses existing procedure context
- **Source**: Procedure ID from message content
- **Behavior**: No AGS mapping required

## Error Handling

### Invalid AGS Codes

**Validation Rules**:
- Minimum 4 digits required
- Must contain only numeric characters
- Federal state code must be valid (01-16)

**Error Examples**:
```php
// Too short
$service->mapAgsToCustomerSubdomain('01');
// Throws: XöV-Kennung-Code must be at least 4 characters long

// Invalid federal state
$service->mapAgsToCustomerSubdomain('029900000099'); 
// Throws: No subdomain mapping found for federal state code: 99
```

### Customer Not Found

If a customer cannot be found for a mapped subdomain:

```php
try {
    $customer = $service->getCustomerByAgsCode('020200000099');
} catch (Exception $e) {
    // Customer 'hh' not found in system
    // Procedure creation will fail
}
```

## Logging

### Successful Mapping

```php
$this->logger->info('Successfully mapped XöV-Entsprechung to customer', [
    'xoev_code' => '020200000099',
    'subdomain' => 'hh',
    'customer_id' => 'customer-uuid-123'
]);
```

### Mapping Failures

```php
$this->logger->error('Customer not found for XöV-Entsprechung', [
    'xoev_code' => '020200000099',
    'subdomain' => 'hh', 
    'error' => 'Customer with subdomain "hh" not found'
]);
```

### Debug Extraction

```php
$this->logger->debug('Extracted federal state code from XöV-Entsprechung', [
    'xoev_code' => '020200000099',
    'federal_state_code' => '02'
]);
```

## Configuration

### Service Dependencies

The service requires:

1. **CustomerServiceInterface**: For customer lookup by subdomain
2. **LoggerInterface**: For logging mapping operations

### Service Registration

**File**: `config/services.yml`

```yaml
DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungCustomerMappingService:
    arguments:
        $customerService: '@DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface'
        $logger: '@logger'
    tags:
        - { name: monolog.logger, channel: xbeteiligung }
```

## Testing

### Unit Tests

**File**: `tests/Logic/XBeteiligungCustomerMappingServiceTest.php`

**Test Coverage**:
- Federal state code extraction for all 16 German states
- AGS to subdomain mapping validation
- Customer lookup integration testing
- Error handling for invalid codes
- Edge cases and boundary conditions

**Example Test Cases**:
```php
// Valid mapping tests
$this->assertSame('hh', $service->mapAgsToCustomerSubdomain('02020000000'));
$this->assertSame('by', $service->mapAgsToCustomerSubdomain('02091000000'));

// Error handling tests  
$this->expectException(InvalidArgumentException::class);
$service->mapAgsToCustomerSubdomain('01'); // Too short
```


## Multi-Mandant Benefits

- **Automatic Assignment**: Procedures automatically assigned based on origin
- **Multi-Mandant Support**: Single Beteiligung instance serves multiple regions
- **Scalable Architecture**: Easy addition of new federal states/customers
- **Error Prevention**: Prevents procedures from landing in wrong tenant
- **Centralized Management**: Single system handles all federal state communications

## Troubleshooting

### Common Issues

1. **Customer Not Found**
   - **Symptom**: Procedure creation fails with customer lookup error
   - **Cause**: Customer with mapped subdomain doesn't exist
   - **Solution**: Ensure customer exists with correct subdomain

2. **Invalid AGS Codes**
   - **Symptom**: InvalidArgumentException during mapping
   - **Cause**: Malformed AGS codes in incoming messages
   - **Solution**: Validate message format and AGS code structure

3. **Missing Federal State Mapping**
   - **Symptom**: No subdomain mapping error
   - **Cause**: Unknown federal state code in AGS
   - **Solution**: Add mapping for new federal state codes

### Debug Steps

1. **Check AGS Extraction**:
   ```php
   $agsCodes = $agsService->extractAgsCodesFromXmlObject($xmlObject);
   var_dump($agsCodes['sender']); // Should be valid AGS code
   ```

2. **Test Federal State Extraction**:
   ```php
   $federalState = $service->extractFederalStateCode($agsCode);
   var_dump($federalState); // Should be '01'-'16'
   ```

3. **Verify Customer Exists**:
   ```php
   $subdomain = $service->mapAgsToCustomerSubdomain($agsCode);
   $customer = $customerService->findCustomerBySubdomain($subdomain);
   var_dump($customer); // Should not be null
   ```

## Error Handling Strategy

The service uses a **fail-fast** approach:
- Procedure creation fails immediately if customer cannot be determined
- Prevents orphaned procedures in wrong tenants
- Ensures data consistency across multi-mandant system

## Related Documentation

- [XBeteiligung Routing Documentation](XBETEILIGUNG_ROUTING.md)
- [Multi-Mandant Support Documentation](MULTI_MANDANT_SUPPORT.md)
