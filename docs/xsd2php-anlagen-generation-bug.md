# xsd2php Anlagen Generation Bug

## Background

The PHP classes and JMS serializer metadata in this repository are generated from XSD schema files
using the `goetas-webservices/xsd2php` library. When regenerating classes after an XSD update,
a specific bug in `xsd2php` causes incorrect code generation for the `anlagen` element.

**This bug requires a manual fix after every regeneration.**

---

## The XSD Structure

In `xbeteiligung-baukasten.xsd`, `anlagen` is defined as follows within types like
`BeteiligungKommunalOeffentlichkeit` (line 389):

```xml
<!-- Outer element: maxOccurs="unbounded" — multiple <anlagen> wrappers are allowed -->
<xs:element name="anlagen"
             minOccurs="0"
             maxOccurs="unbounded"
             type="xbeteiligung:Anlagen">
```

The `Anlagen` complex type (line 89) contains:

```xml
<!-- Inner element: maxOccurs="unbounded" — multiple <anlage> items per wrapper -->
<xs:complexType name="Anlagen">
    <xs:sequence>
        <xs:element name="anlage"
                    maxOccurs="unbounded"
                    type="xbeteiligung:MetadatenAnlage"
                    form="unqualified"/>
    </xs:sequence>
</xs:complexType>
```

Both levels are `unbounded`. Valid XML may therefore look like either:

```xml
<!-- Format A: single wrapper, multiple children (most common) -->
<ns5:anlagen>
    <anlage>...</anlage>
    <anlage>...</anlage>
</ns5:anlagen>

<!-- Format B: multiple wrappers, one child each (also XSD-valid) -->
<ns5:anlagen><anlage>...</anlage></ns5:anlagen>
<ns5:anlagen><anlage>...</anlage></ns5:anlagen>
```

---

## The xsd2php Bug

The bug is in `AbstractConverter::isArrayNestedElement()` (vendor file
`goetas-webservices/xsd2php/src/AbstractConverter.php`, line 233):

```php
protected function isArrayNestedElement(Type $type)
{
    if ($type instanceof ComplexType
        && !$type->getParent()
        && !$type->getAttributes()
        && count($type->getElements()) === 1)  // Anlagen has exactly one child ✓
    {
        $elements = $type->getElements();
        return $this->isArrayElement(reset($elements)); // anlage is unbounded ✓
    }
}
```

When processing the `anlagen` element:

1. The generator sees that the `Anlagen` type has exactly one child (`anlage`) which is `unbounded`
2. It treats `Anlagen` as a "nested array" and **collapses it**, looking through the wrapper
3. It generates `array<MetadatenAnlageType>` with `inline: false, entry_name: anlage`
4. **It never checks whether the outer `anlagen` element itself is also `unbounded`**

When the outer element is `unbounded`, collapsing is wrong. The generated code only handles
a **single** `<anlagen>` wrapper. When multiple `<anlagen>` wrappers are present in the XML,
JMS Serializer only processes the first one — all subsequent wrappers and their `<anlage>`
children are silently lost.

---

## The Correct Mapping

Each `<anlagen>` element must map to an `AnlagenType` object. The fix uses `inline: true`
so JMS Serializer collects every repeated `<anlagen>` element into an array:

### JMS Metadata YML (before — wrong)

```yaml
anlagen:
    type: array<MetadatenAnlageType>
    xml_list:
        inline: false       # expects exactly ONE <anlagen> wrapper
        entry_name: anlage
```

### JMS Metadata YML (after — correct)

```yaml
anlagen:
    type: array<AnlagenType>
    xml_list:
        inline: true        # collects every <anlagen> element
        entry_name: anlagen
        namespace: 'https://www.xleitstelle.de/xbeteiligung/12'
        skip_when_empty: true
```

### PHP class property (before — wrong)

```php
/** @var MetadatenAnlageType[] $anlagen */
private $anlagen = null;

public function addToAnlagen(MetadatenAnlageType $anlage): self { ... }
public function getAnlagen(): ?array { ... }  // returns MetadatenAnlageType[]
public function setAnlagen(?array $anlagen): self { ... }
```

### PHP class property (after — correct)

```php
/** @var AnlagenType[] $anlagen */
private $anlagen = null;

public function addToAnlagen(AnlagenType $anlage): self { ... }
public function getAnlagen(): ?array { ... }  // returns AnlagenType[]
public function setAnlagen(?array $anlagen): self { ... }
```

---

## Files That Require Manual Fix After Regeneration

### Types using `Anlagen` → fix to `AnlagenType`

| PHP Class | JMS Metadata YML |
|---|---|
| `BeteiligungKommunalOeffentlichkeitType.php` | `Schema.XBeteiligung.BeteiligungKommunalOeffentlichkeitType.yml` |
| `BeteiligungKommunalTOEBType.php` | `Schema.XBeteiligung.BeteiligungKommunalTOEBType.yml` |
| `BeteiligungRaumordnungType.php` | `Schema.XBeteiligung.BeteiligungRaumordnungType.yml` |
| `BeteiligungPlanfeststellungOeffentlichkeitType.php` | `Schema.XBeteiligung.BeteiligungPlanfeststellungOeffentlichkeitType.yml` |
| `BeteiligungPlanfeststellungTOEBType.php` | `Schema.XBeteiligung.BeteiligungPlanfeststellungTOEBType.yml` |
| `StellungnahmeType.php` | `Schema.XBeteiligung.StellungnahmeType.yml` |

### Types using `AnlagenLink` → fix to `AnlagenLinkType`

| PHP Class | JMS Metadata YML |
|---|---|
| `BeteiligungKommunalDBType.php` | `Schema.XBeteiligung.BeteiligungKommunalDBType.yml` |
| `BeteiligungPlanfeststellungDBType.php` | `Schema.XBeteiligung.BeteiligungPlanfeststellungDBType.yml` |
| `BeteiligungRaumordnungDBType.php` | `Schema.XBeteiligung.BeteiligungRaumordnungDBType.yml` |

---

## Impact on AnlagenExtractor

Because `getAnlagen()` now returns `AnlagenType[]` instead of `MetadatenAnlageType[]`,
`AnlagenExtractor::processAttachmentArray()` must flatten the wrapper array:

```php
// Before (wrong — only iterates MetadatenAnlageType directly)
private function processAttachmentArray(?array $anlagenArray): array
{
    foreach ($anlagenArray as $anlage) { // $anlage was MetadatenAnlageType
        $anlagen[] = $this->createAnlageValueObject($anlage);
    }
}

// After (correct — flattens AnlagenType[] wrappers)
private function processAttachmentArray(?array $anlagenArray): array
{
    foreach ($anlagenArray as $anlagenWrapper) { // $anlagenWrapper is AnlagenType
        foreach ($anlagenWrapper->getAnlage() as $anlage) { // $anlage is MetadatenAnlageType
            $anlagen[] = $this->createAnlageValueObject($anlage);
        }
    }
}
```

---

## Impact on PlanningDocumentsLinkCreator (Outgoing XML)

`PlanningDocumentsLinkCreator::getPlanningDocuments()` builds the `anlagen` for outgoing messages
(401, 402, 301). It used to return `MetadatenAnlageType[]` directly. After the fix it must
return `AnlagenType[]` — all documents wrapped in a single `AnlagenType` container:

```php
// Before (wrong — returns unwrapped MetadatenAnlageType[])
/** @return null|array<int, MetadatenAnlageType> */
public function getPlanningDocuments(...): ?array
{
    // ...collect $planningDocuments (MetadatenAnlageType[])...
    return 0 < count($planningDocuments) ? $planningDocuments : null;
}

// After (correct — returns AnlagenType[] with one wrapper)
/** @return null|array<int, AnlagenType> */
public function getPlanningDocuments(...): ?array
{
    // ...collect $planningDocuments (MetadatenAnlageType[])...
    if (0 === count($planningDocuments)) {
        return null;
    }
    $anlagenWrapper = new AnlagenType();
    $anlagenWrapper->setAnlage($planningDocuments);
    return [$anlagenWrapper];
}
```

`XBeteiligungService` calls `setAnlagen($this->planningDocumentsLinkCreator->getPlanningDocuments($procedure))`
and does **not** need changes, as `setAnlagen()` now correctly receives `AnlagenType[]`.

---

## Summary of Steps After Every XSD Regeneration

1. In all **9 PHP classes** listed above: replace `MetadatenAnlageType` → `AnlagenType`
   (or `MetadatenAnlageLinkType` → `AnlagenLinkType` for DB types) in the `$anlagen` property
2. In all **9 JMS metadata YMLs**: change `type`, `inline`, `entry_name`, and add `namespace`
   as shown above
3. Verify `AnlagenExtractor::processAttachmentArray()` still uses the two-level iteration
4. Verify `PlanningDocumentsLinkCreator::getPlanningDocuments()` still wraps items in `AnlagenType`
