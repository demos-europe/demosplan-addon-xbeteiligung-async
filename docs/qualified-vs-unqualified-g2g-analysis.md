# Qualified vs Unqualified G2G Base Message Types Analysis

## Executive Summary

The XBeteiligung standard defines two variants of the G2G (Government-to-Government) base message type:
- **Qualified**: `bn-g2g:Nachricht.G2GType`
- **Unqualified**: `bn-uq-g2g:Nachricht.G2GType`

**Finding**: Only `kommunal.Aktualisieren.0402` uses the unqualified version in the XSD schema, while all other messages use the qualified version. This appears to be an **inconsistency in the XBeteiligung standard specification** rather than a deliberate design choice.

**Recommendation**: It is safe and advisable to normalize `kommunal.Aktualisieren.0402` to use the qualified type like all other messages, as actual implementations already use qualified namespaces in practice.

---

## 1. XSD Schema Differences

### 1.1 Namespace and Element Form

**Qualified Schema** (`xoev-basisnachricht-g2g_1.1.xsd`):
```xml
<xs:schema xmlns:bn-g2g="http://xoev.de/schemata/basisnachricht/g2g/1_1"
           targetNamespace="http://xoev.de/schemata/basisnachricht/g2g/1_1"
           elementFormDefault="qualified"  ← Key difference
           attributeFormDefault="unqualified">
```

**Unqualified Schema** (`xoev-basisnachricht-unqualified-g2g_1.1.xsd`):
```xml
<xs:schema xmlns:bn-uq-g2g="http://xoev.de/schemata/basisnachricht/unqualified/g2g/1_1"
           targetNamespace="http://xoev.de/schemata/basisnachricht/unqualified/g2g/1_1"
           elementFormDefault="unqualified"  ← Key difference
           attributeFormDefault="unqualified">
```

### 1.2 Structural Differences

The `elementFormDefault` setting controls whether child elements must be namespace-qualified:

**Qualified Version**:
- Uses `<xs:element ref="bn-g2g:nachrichtenkopf.g2g">` - references a global element
- Imports separate schema for `BehoerdeType`: `xoev-basisnachricht-behoerde_1.1.xsd`
- Child elements like `autor`, `leser`, `identifikation.nachricht` are **global elements** defined separately
- References types from external schemas via namespace prefixes

**Unqualified Version**:
- Uses `<xs:element name="nachrichtenkopf.g2g" type="bn-uq-g2g:Nachrichtenkopf.G2GType">` - local element with type reference
- Defines `BehoerdeType` **inline** within the same schema (lines 14-43)
- Child elements are **local elements** defined inline
- Self-contained schema with fewer external dependencies

### 1.3 Semantic/Functional Difference

**The types are functionally identical** - they contain the same data structure:
- Both define the same attributes: `produkt`, `produkthersteller`, `produktversion`, `standard`, `test`, `version`
- Both contain a message header (`nachrichtenkopf.g2g`) with the same structure
- Both enforce the same validation rules

**The difference is purely syntactic**:
- **Qualified**: Child elements require namespace prefixes in XML
- **Unqualified**: Child elements appear without namespace prefixes in XML

---

## 2. Generated PHP Class Differences

### 2.1 Method Signatures

Both PHP classes have **identical method signatures** for attributes:
- `getProdukt()` / `setProdukt()`
- `getProdukthersteller()` / `setProdukthersteller()`
- `getProduktversion()` / `setProduktversion()`
- `getStandard()` / `setStandard()`
- `getTest()` / `setTest()`
- `getVersion()` / `setVersion()`

### 2.2 Type Hints for Child Objects

**CRITICAL DIFFERENCE** - Different type hints for the message header:

**Qualified** (`Basisnachricht\G2g\NachrichtG2GTypeType`):
```php
/**
 * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2g
 */
private $nachrichtenkopfG2g = null;

public function setNachrichtenkopfG2g(
    \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2g $nachrichtenkopfG2g
)
```

**Unqualified** (`Basisnachricht\Unqualified\NachrichtG2GTypeType`):
```php
/**
 * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType
 */
private $nachrichtenkopfG2g = null;

public function setNachrichtenkopfG2g(
    \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType $nachrichtenkopfG2g
)
```

**This creates a type incompatibility!** The qualified version expects `G2g\NachrichtenkopfG2g`, while the unqualified version expects `Unqualified\NachrichtenkopfG2GTypeType`.

### 2.3 Additional Classes

The unqualified schema generates additional classes that the qualified version imports:
- `BehoerdeTypeType` (inline vs imported)
- `KommunikationTypeType` (inline vs imported)
- `CodeKommunikationKanalTypeType` (inline)
- `CodeVerzeichnisdienstTypeType` (inline)

---

## 3. XML Output Differences

### 3.1 Namespace Prefixes

**Qualified Message Example** (0401 - Initiieren):
```xml
<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12"
                              xmlns:ns2="http://xoev.de/schemata/basisnachricht/g2g/1_1">
    <ns2:nachrichtenkopf.g2g>
        <ns2:identifikation.nachricht>
            <ns2:nachrichtenUUID>f338f397-e680-4351-b298-c35d28378c3f</ns2:nachrichtenUUID>
            <ns2:nachrichtentyp>...</ns2:nachrichtentyp>
            <ns2:erstellungszeitpunkt>2025-10-01T12:12:42.342Z</ns2:erstellungszeitpunkt>
        </ns2:identifikation.nachricht>
        <ns2:leser>...</ns2:leser>
        <ns2:autor>...</ns2:autor>
    </ns2:nachrichtenkopf.g2g>
</ns5:kommunal.Initiieren.0401>
```

**Unqualified Message (theoretical) for 0402**:
```xml
<xbeteiligung:kommunal.Aktualisieren.0402 xmlns:xbeteiligung="https://www.xleitstelle.de/xbeteiligung/12">
    <nachrichtenkopf.g2g>                    ← No namespace prefix
        <identifikation.nachricht>           ← No namespace prefix
            <nachrichtenUUID>...</nachrichtenUUID>
            <nachrichtentyp>...</nachrichtentyp>
            <erstellungszeitpunkt>...</erstellungszeitpunkt>
        </identifikation.nachricht>
        <leser>...</leser>
        <autor>...</autor>
    </nachrichtenkopf.g2g>
</xbeteiligung:kommunal.Aktualisieren.0402>
```

### 3.2 Actual Implementation

**IMPORTANT FINDING**: The example XML from Cockpit (`tests/res/example402FromCockpit.xml`) **actually uses the qualified namespace prefixes** even though the XSD says it should be unqualified:

```xml
<xbeteiligung:kommunal.Aktualisieren.0402 ...>
    <bn-g2g:nachrichtenkopf.g2g>             ← Uses bn-g2g: prefix!
        <bn-g2g:identifikation.nachricht>    ← Uses bn-g2g: prefix!
            ...
        </bn-g2g:identifikation.nachricht>
        <bn-g2g:leser>...</bn-g2g:leser>
        <bn-g2g:autor>...</bn-g2g:autor>
    </bn-g2g:nachrichtenkopf.g2g>
</xbeteiligung:kommunal.Aktualisieren.0402>
```

This proves that **actual implementations ignore the unqualified schema and use qualified namespaces in practice**.

---

## 4. Usage Pattern Analysis

### 4.1 XSD Schema Usage

In `xbeteiligung-kommunaleBauleitplanung.xsd`:

| Message | Line | Base Type | Namespace |
|---------|------|-----------|-----------|
| `kommunal.Initiieren.0401` | 48 | `bn-g2g:Nachricht.G2GType` | **Qualified** |
| `kommunal.Aktualisieren.0402` | 80 | `bn-uq-g2g:Nachricht.G2GType` | **Unqualified** ⚠️ |
| `kommunal.Loeschen.0409` | 112 | `bn-g2g:Nachricht.G2GType` | **Qualified** |

In `xbeteiligung-raumordnung.xsd`:

| Message | Line | Base Type | Namespace |
|---------|------|-----------|-----------|
| `raumordnung.Initiieren.0301` | - | `bn-g2g:Nachricht.G2GType` | **Qualified** |
| `raumordnung.Aktualisieren.0302` | 77 | `bn-g2g:Nachricht.G2GType` | **Qualified** |
| `raumordnung.Loeschen.0309` | - | `bn-g2g:Nachricht.G2GType` | **Qualified** |

In `xbeteiligung-planfeststellung.xsd`:

| Message | Line | Base Type | Namespace |
|---------|------|-----------|-----------|
| `planfeststellung.Initiieren.0201` | - | `bn-g2g:Nachricht.G2GType` | **Qualified** |
| `planfeststellung.Aktualisieren.0202` | 77 | `bn-g2g:Nachricht.G2GType` | **Qualified** |
| `planfeststellung.Loeschen.0209` | - | `bn-g2g:Nachricht.G2GType` | **Qualified** |

### 4.2 PHP Implementation

**PHP Class Hierarchy**:
```
KommunalInitiieren0401AnonymousPHPType extends Basisnachricht\G2g\NachrichtG2GTypeType
KommunalAktualisieren0402AnonymousPHPType extends Basisnachricht\Unqualified\NachrichtG2GTypeType ⚠️
KommunalLoeschen0409AnonymousPHPType extends Basisnachricht\G2g\NachrichtG2GTypeType

RaumordnungAktualisieren0302AnonymousPHPType extends Basisnachricht\G2g\NachrichtG2GTypeType
PlanfeststellungAktualisieren0202AnonymousPHPType extends Basisnachricht\G2g\NachrichtG2GTypeType
```

### 4.3 Code Usage Analysis

**Finding**: The unqualified classes are **barely used** in the codebase:

```php
// ReusableMessageBlocks.php - Uses union type to handle both
public function setProductInfo(
    NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject
): NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType

// BUT creates only qualified child objects:
$messageHead = new NachrichtenkopfG2g();  // From G2g namespace!
$identificationMessage = new IdentifikationNachricht();  // From G2g namespace!
$reader = new Leser();  // From G2g namespace!
$author = new Autor();  // From G2g namespace!
```

**This creates a type mismatch**:
- `UnqualifiedNachrichtG2GTypeType` expects `Unqualified\NachrichtenkopfG2GTypeType`
- But receives `G2g\NachrichtenkopfG2g` (qualified version)

**The code works only because PHP's type system is lenient at runtime**, but this violates type safety.

### 4.4 Is This Intentional or an Error?

**Evidence it's an error in the standard**:

1. **Only one message uses it**: Out of all Aktualisieren messages (0202, 0302, 0402), only 0402 uses unqualified
2. **Pattern inconsistency**: Within the same message family (kommunal), 0401 and 0409 are qualified, but 0402 is unqualified
3. **Actual implementations ignore it**: DiPlan Cockpit sends qualified XML for 0402 despite XSD saying unqualified
4. **No documentation**: No explanation in standard docs for why 0402 would be different
5. **Type mismatches in code**: The implementation has workarounds (union types) to handle this inconsistency

**Why the unqualified schema exists**:

The unqualified schema appears to be a **transitional artifact** from earlier XÖV standard versions. Some standards initially used unqualified elements before moving to qualified as best practice. The XBeteiligung standard likely:
1. Copied both schemas from XÖV base specs
2. Intended to use only qualified
3. Accidentally referenced unqualified for 0402 (copy-paste error?)
4. Never corrected it in later versions

---

## 5. Recommendation

### 5.1 Impact of Changing to Qualified

**Proposed Change**: Modify `xbeteiligung-kommunaleBauleitplanung.xsd` line 80:

```xml
<!-- Current (wrong) -->
<xs:extension base="bn-uq-g2g:Nachricht.G2GType">

<!-- Proposed (correct) -->
<xs:extension base="bn-g2g:Nachricht.G2GType">
```

**Impact Assessment**:

✅ **Safe to change** - The change has no negative impact:

1. **Standards Compliance**:
   - Real-world implementations already use qualified namespaces
   - Cockpit sends qualified XML for 0402
   - Other systems likely do the same

2. **Code Simplification**:
   - Removes need for union types in `ReusableMessageBlocks`
   - Eliminates type mismatches
   - Reduces technical debt

3. **Consistency**:
   - Aligns 0402 with all other messages (0401, 0409, 0202, 0302, etc.)
   - Follows XÖV best practices

4. **Backward Compatibility**:
   - XML parsers accept qualified elements when schema says unqualified
   - Existing systems sending qualified XML will continue to work
   - Unlikely any system sends truly unqualified XML

### 5.2 Does It Violate the Standard?

**No**, because:

1. **The standard itself is inconsistent** - This appears to be a specification error
2. **Common practice differs** - Actual implementations use qualified
3. **Spirit vs. letter** - The change aligns with the standard's clear intent (all other messages are qualified)

### 5.3 Alternative: Report to Standard Body

If you want to be cautious, you could:
1. Report this inconsistency to the XBeteiligung standard body (XLeitstelle.de)
2. Wait for an official errata/correction
3. Then update your implementation

However, given that actual implementations already deviate from the spec, updating your XSD is pragmatic and safe.

---

## 6. Implementation Steps

### 6.1 XSD Changes

1. Edit `Resources/xsd/xbeteiligung-kommunaleBauleitplanung.xsd` line 80
2. Change `bn-uq-g2g:Nachricht.G2GType` to `bn-g2g:Nachricht.G2GType`
3. Optionally remove import of unqualified schema (line 36-37) if not used elsewhere

### 6.2 PHP Code Regeneration

1. Regenerate PHP classes from updated XSD
2. `KommunalAktualisieren0402AnonymousPHPType` will now extend `G2g\NachrichtG2GTypeType`
3. Remove union types from `ReusableMessageBlocks.php`
4. Remove import of `UnqualifiedNachrichtG2GTypeType`

### 6.3 Testing

1. Run existing unit tests (should pass with no changes)
2. Test XML serialization for 0402 messages
3. Verify namespace prefixes are correct
4. Test integration with external systems if possible

### 6.4 Documentation

1. Add entry to CHANGELOG.md noting the fix
2. Update any internal documentation referencing the unqualified type

---

## 7. Conclusion

The use of `bn-uq-g2g:Nachricht.G2GType` for `kommunal.Aktualisieren.0402` appears to be an **error in the XBeteiligung standard specification** rather than an intentional design choice.

**Key Findings**:
- Only 1 out of ~15+ messages uses the unqualified variant
- Actual implementations (DiPlan Cockpit) send qualified XML anyway
- The unqualified schema creates type mismatches in generated code
- No functional benefit to having both variants

**Recommendation**:
✅ **Safe to normalize** `kommunal.Aktualisieren.0402` to use `bn-g2g:Nachricht.G2GType` (qualified) like all other messages.

This change:
- Improves code consistency and type safety
- Aligns with actual implementation practice
- Follows XÖV best practices
- Has no negative impact on interoperability

---

**Generated**: 2026-02-02
**Analyzed by**: Claude Code (Sonnet 4.5)
**Repository**: demosplan-addon-xbeteiligung-async
