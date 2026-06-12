# PostNL Magento 2 — Known Issues & Overhaul Backlog

Issues are grouped by priority. Items marked **FIXED** have already been addressed in this fork.

---

## Critical — Breaks fresh installation

### 1. `postnl_disable_delivery_days` attribute missing from `InstallData` **FIXED**
**File:** `Setup/InstallData.php`

On a fresh install Magento only calls `InstallData::install()`, not `UpgradeData::upgrade()`. The `postnl_disable_delivery_days` attribute was only registered in `UpgradeData` v1.9.1 (`V191/Data/InstallDisableDeliveryDaysAttribute`), so it was never created on new deployments.

**Fix applied:** Added `InstallDisableDeliveryDaysAttribute` to `InstallData::__construct()` and its installer array.

---

### 2. Declarative patches crash when EAV attributes don't exist yet **FIXED**
**Files:**
- `Setup/Patch/Data/UpdateDefaultValueForDirationAttribute.php`
- `Setup/Patch/Data/UpdateDisableDeliveryDaysAttribute.php`

Both patches call `EavSetup::updateAttribute()` against attributes that may not exist when the patches fire. In Magento 2.3+ declarative data patches can execute before old-style `InstallData`/`UpgradeData` scripts, causing a fatal exception:

> Attribute with ID: "postnl_shipping_duration" does not exist

`getDependencies()` returns `[]` in both patches, giving Magento no ordering hint.

**Fix applied:** Added an `EavSetup::getAttributeId()` guard in both patches; if the attribute doesn't exist the patch returns early (the attribute will be created with the correct default by `InstallData` anyway).

---

## PHP 8.1–8.5 Compatibility

### 3. `in_array()` called without strict-type flag (51 occurrences)
`in_array($needle, $haystack)` uses loose comparison by default. Since PHP 8.0 type juggling rules changed (e.g. `0 == "string"` is now `false`), and PHP 8.1 deprecated implicit int→string coercions, all calls that compare typed values should pass `true` as third argument.

The single **already-fixed** case in `ResetBEDefaultOption.php` compared integer option IDs against values that could be returned as strings from config — a concrete type-coercion risk.

**To do:** Audit all 51 remaining call-sites and add `, true` where the haystack contains a known type. The highest-risk files are:
- `Webservices/Api/CutoffTimes.php:45` — compares day strings vs `explode()` result
- `Config/Provider/LoggingConfiguration.php:30` — compares log level values
- `Observer/TIGPostNLShipmentSaveAfter/CreatePostNLShipment.php:237` — compares shipping method strings
- `Model/Carrier/Validation/Country.php:30` — compares country codes

---

### 4. `in_array()` strict fix in `ResetBEDefaultOption` **FIXED**
**File:** `Setup/Patch/Data/ResetBEDefaultOption.php:77`

`$removedOptions` contains integers; config values are read as strings. Without `true` the comparison silently coerces, producing wrong results on PHP 8.1+.

**Fix applied:** Added `, true` as third argument.

---

### 5. `list()` construct should use short array destructuring (2 remaining occurrences)
`list()` still works in PHP 8.x but is considered legacy style. Prefer `[]` destructuring for consistency with modern PHP.

**Files:**
- `Service/Shipment/Label/Merge/A4Merger.php:118`
- `Helper/Data.php:104`

`Controller/International/Address.php:58` has already been updated in this fork.

---

### 6. Untyped class properties throughout Setup patches
All Setup patch classes declare injected dependencies with `@var` docblocks but no native type declarations. PHP 7.4+ supports typed properties, which eliminates the `@var` noise and catches injection mismatches at runtime.

**Example pattern to change:**
```php
// Before
/** @var EavSetupFactory */
private $eavSetupFactory;

// After
private EavSetupFactory $eavSetupFactory;
```

Affects all files under `Setup/Patch/Data/` and `Setup/Patch/Schema/`.

---

### 7. `\Zend_Db_Exception` referenced in `@throws` docblocks
**File:** `Setup/AbstractTableInstaller.php` (multiple methods), `Setup/AbstractColumnsInstaller.php:91`

`Zend_Db_Exception` is from Zend Framework 1, which was removed in Magento 2.4.6. These `@throws` annotations are stale and could mislead developers — replace with `\Exception` or the actual Laminas/Magento equivalent.

---

## Setup Architecture

### 8. Mixed old-style and declarative setup — should fully migrate to declarative patches
The module uses both `InstallData`/`UpgradeData` (deprecated since Magento 2.3) and declarative `DataPatch` classes side by side. This is the root cause of the ordering bugs above.

**Recommendation:** Convert all `Setup/V*/Data/*.php` installers into proper `Setup/Patch/Data/*.php` patch classes with explicit `getDependencies()` chains. The old-style scripts can then be removed. This is a significant but well-defined refactor.

---

### 9. Misleading copy-pasted constructor docblock
**File:** `Setup/Patch/Data/UpdateDefaultValueForDirationAttribute.php:24`

The constructor is documented as `UpdateDisableDeliveryDaysAttribute constructor.` — clearly copy-pasted from the sibling class.

---

## Code Quality

### 10. `@codingStandardsIgnoreLine` suppressions should be reviewed
Several files suppress coding-standards checks with `// @codingStandardsIgnoreLine` (e.g. `Setup/V141/Data/ShippingDurationAttribute.php:30`). During the overhaul these should be reviewed and either the underlying issue fixed or the suppression scoped more precisely.

---

### 11. Loose equality comparisons (`==`/`!=`) in web-service layer
Several files in `Webservices/` use loose equality where strict equality is safe and more explicit:
- `Webservices/Rest.php` — HTTP method comparisons
- `Webservices/Endpoints/SentDate.php:206` — `$deliveryDate == null` should be `=== null`
- `Webservices/Api/Exception.php:127` — `$xml == ''` should be `=== ''`
- `Setup/Uninstall.php:42,48` — console input comparisons

---

### 12. `ResetBEDefaultOption::$removedOptions` is untyped
```php
private $removedOptions = [4944, 4952, ...];
```
Should be declared as `private array $removedOptions`.

---

## Tests

### 13. No test coverage for fresh-install data patch path
The bugs in issues #1 and #2 were not caught because integration tests only cover the upgrade path (existing installation). A test that bootstraps the module from scratch (no prior `setup_module` entry) should be added.
