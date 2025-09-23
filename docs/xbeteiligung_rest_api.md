# XBeteiligung REST API Documentation

## Overview

This document describes the REST API endpoints for external integrators that enable processing XBeteiligung messages via HTTP requests. The endpoints accept XML payload structure and return XML responses.

## Authentication

All requests to the API must include a valid authentication token in the custom **X-Addon-XBeteiligung-Authorization** header:

```
X-Addon-XBeteiligung-Authorization: Bearer your-token-here
```

This custom header is used specifically for XBeteiligung addon authentication to avoid interference with the core application's authentication mechanisms.

The token value must match the `addon_xbeteiligung_async_rest_authentication` parameter value configured in the application parameters. The token must be at least 7 characters long.

## Endpoints

### Create Procedure

**POST** `/addon/xbeteiligung/procedure/create`

This endpoint processes XBeteiligung messages for procedure creation (message code 401).

### Update Procedure

**PATCH** `/addon/xbeteiligung/procedure/update`

This endpoint processes XBeteiligung messages for procedure updates (message codes 402/302/202).

## Request Format

The request body should contain the raw XML message content directly. No JSON wrapping is required.

**Content-Type**: `application/xml` (recommended) or `text/xml`

### Example Request Structure

```xml
<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
    <!-- XML content -->
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
```

The API will automatically detect the message type from the XML content using pattern matching.

## Response Format

The response will be an XML string with a `Content-Type: application/xml` header.

### Success Response

```xml
HTTP/1.1 200 OK
Content-Type: application/xml

<xbeteiligung:beteiligung2Planung.BeteiligungKommunalNeuOK.0411>
    <!-- Response XML content -->
</xbeteiligung:beteiligung2Planung.BeteiligungKommunalNeuOK.0411>
```

## Error Handling

The API returns the following HTTP status codes:

- `200 OK`: The request was successful and processed
- `400 Bad Request`: The request format is invalid, empty payload, or the message cannot be parsed
- `401 Unauthorized`: Invalid, missing, or too short authentication token
- `500 Internal Server Error`: An error occurred while processing the request

Error responses include a descriptive message explaining the error.

### Error Response Examples

#### Unauthorized Access
```
HTTP/1.1 401 Unauthorized

Unauthorized
```

#### Invalid Request
```
HTTP/1.1 400 Bad Request

Empty request payload
```

#### Processing Error
```
HTTP/1.1 500 Internal Server Error

Error processing procedure creation request: [detailed error message]
```

## Supported Message Types

The API supports the following XBeteiligung message types:

### Kommunal (Municipal) Messages
- `kommunal.Initiieren.0401` - Create new kommunale procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungKommunalNeu\.0401/i`
- `kommunal.Aktualisieren.0402` - Update kommunale procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungKommunalAktualisieren\.0402/i`
- `kommunal.Loeschen.0409` - Delete kommunale procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungKommunalLoeschen\.0409/i`

### Raumordnung (Spatial Planning) Messages
- `raumordnung.Initiieren.0301` - Create new raumordnung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungNeu\.0301/i`
- `raumordnung.Aktualisieren.0302` - Update raumordnung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungAktualisieren\.0302/i`
- `raumordnung.Loeschen.0309` - Delete raumordnung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungLoeschen\.0309/i`

### Planfeststellung (Planning Approval) Messages
- `planfeststellung.Initiieren.0201` - Create new planfeststellung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungNeu\.0201/i`
- `planfeststellung.Aktualisieren.0202` - Update planfeststellung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungAktualisieren\.0202/i`
- `planfeststellung.Loeschen.0209` - Delete planfeststellung procedure
  - Pattern: `/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungLoeschen\.0209/i`

## Message Type Detection

The API uses pattern matching to automatically identify the message type from the XML content. It first tries to match specific XML element patterns, then falls back to searching for message codes (0401, 0402, etc.) anywhere in the XML content.

## Examples

### Create Kommunal Procedure (0401)

```http
POST /addon/xbeteiligung/procedure/create
Content-Type: application/xml
X-Addon-XBeteiligung-Authorization: Bearer your-secure-token

<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
    <xbeteiligung:nachrichtenkopf>
        <xbeteiligung:identifikation>
            <xbeteiligung:nachrichtenUUID>550e8400-e29b-41d4-a716-446655440000</xbeteiligung:nachrichtenUUID>
            <xbeteiligung:nachrichtentyp>0401</xbeteiligung:nachrichtentyp>
            <xbeteiligung:absender>sender-id</xbeteiligung:absender>
            <xbeteiligung:empfaenger>receiver-id</xbeteiligung:empfaenger>
            <xbeteiligung:zeitstempelNachrichten>2025-09-23T10:30:00+02:00</xbeteiligung:zeitstempelNachrichten>
        </xbeteiligung:identifikation>
    </xbeteiligung:nachrichtenkopf>
    <xbeteiligung:nachrichteninhalt>
        <!-- Procedure data -->
    </xbeteiligung:nachrichteninhalt>
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
```

### Update Procedure (0402)

```http
PATCH /addon/xbeteiligung/procedure/update
Content-Type: application/xml
X-Addon-XBeteiligung-Authorization: your-secure-token

<xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>
    <xbeteiligung:nachrichtenkopf>
        <xbeteiligung:identifikation>
            <xbeteiligung:nachrichtenUUID>550e8400-e29b-41d4-a716-446655440001</xbeteiligung:nachrichtenUUID>
            <xbeteiligung:nachrichtentyp>0402</xbeteiligung:nachrichtentyp>
            <xbeteiligung:absender>sender-id</xbeteiligung:absender>
            <xbeteiligung:empfaenger>receiver-id</xbeteiligung:empfaenger>
            <xbeteiligung:zeitstempelNachrichten>2025-09-23T10:35:00+02:00</xbeteiligung:zeitstempelNachrichten>
        </xbeteiligung:identifikation>
    </xbeteiligung:nachrichtenkopf>
    <xbeteiligung:nachrichteninhalt>
        <!-- Updated procedure data -->
    </xbeteiligung:nachrichteninhalt>
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>
```

## Integration Guide

### For External Systems

1. **Configure Authentication**: Obtain the authentication token from your DemosPlan administrator
2. **Choose Endpoint**: Use `/create` for new procedures (401 messages) or `/update` for modifications (402/302/202 messages)
3. **Prepare XML**: Send raw XBeteiligung XML directly in request body
4. **Handle Response**: Process the XML response according to XBeteiligung standard
5. **Error Handling**: Implement retry logic for 5xx errors, fix request for 4xx errors

### Testing

You can test the endpoints using curl:

```bash
curl -X POST \
  http://your-demoplan-host/addon/xbeteiligung/procedure/create \
  -H 'Content-Type: application/xml' \
  -H 'X-Addon-XBeteiligung-Authorization: Bearer your-token' \
  -d @your-message.xml
```
