# Siteation fork — changes vs. upstream `tig/postnl-magento2`

This is the **Siteation** maintenance fork of the official PostNL Magento 2 extension
([`tig/postnl-magento2`](https://github.com/tig-nl/postnl-magento2)), based on upstream **v1.25.0**.

It exists to fix a fresh-install bug, make the module clean on **PHP 8.1 → 8.4**, and finish the
half-done migration from legacy `InstallData`/`UpgradeData` scripts to declarative
`DataPatchInterface` classes. Behaviour for merchants is unchanged — these are correctness,
compatibility, and maintainability changes only.

> The full, itemised backlog (with file:line references and rationale per item) lives in
> [`issues.md`](./issues.md). This file is the human-readable summary and the install/test guide.

---

## What changed

### 1. Critical — fresh installs were broken
- **`postnl_disable_delivery_days` was never created on a new shop.** It was only registered in
  `UpgradeData` v1.9.1, but Magento runs `InstallData::install()` (not `UpgradeData::upgrade()`) on a
  first-time install, so the attribute silently never existed on new deployments. It is now created on
  both fresh installs and upgrades (see §3 — it is now a declarative patch with a proper dependency
  chain, so ordering is guaranteed).
- **Declarative patches could fatal on order-of-execution.** `UpdateDefaultValueForDirationAttribute`
  and `UpdateDisableDeliveryDaysAttribute` called `EavSetup::updateAttribute()` on attributes that may
  not exist yet, throwing *"Attribute … does not exist"*. They now declare the attribute-creating
  patches as explicit `getDependencies()`, so Magento always creates before it updates.

### 2. PHP 8.1 → 8.4 compatibility
- Added the strict `true` flag to **51** `in_array()` call-sites (PHP 8 changed type-juggling rules;
  loose `in_array` could return wrong results when comparing int IDs against string config values).
- Replaced loose `==`/`!=` with strict `===`/`!==` in the `Webservices/` layer and `Setup/Uninstall`.
- Replaced legacy `list()` with short `[]` array destructuring.
- Added native typed properties (replacing `@var` docblocks) across all Setup classes.
- Replaced stale `@throws \Zend_Db_Exception` docblocks (Zend FW1 was removed in Magento 2.4.6).
- Result: the `Setup/` tree now has **zero** `@codingStandardsIgnoreLine` suppressions.

### 3. Setup architecture — fully declarative data patches
The module previously mixed deprecated `InstallData`/`UpgradeData` with modern `DataPatch` classes,
which is the root cause of the ordering bugs above. All eight legacy `Setup/V*/Data/*.php` installers
were converted to `DataPatchInterface` classes under `Setup/Patch/Data/`, and
`InstallData.php`, `UpgradeData.php`, `AbstractDataInstaller.php` and every `Setup/V*/Data/` directory
were removed. (Schema installers under `Setup/V*/Schema/` are unchanged in behaviour — only
type-hardened.)

New patches and their dependency order:

| Patch | Depends on | Creates / does |
|-------|------------|----------------|
| `AddCustomProductAttributes` | — | `postnl_product_type`, `postnl_parcel_count`, `postnl_parcel_volume` |
| `AddShippingDurationAttribute` | `AddCustomProductAttributes` | `postnl_shipping_duration` |
| `MigrateConfigurationPaths` | — | renames legacy config paths |
| `UpdateCustomProductAttributeDefaults` | `AddCustomProductAttributes` | default values for parcel attrs |
| `RemoveEveningBeConfig` | `MigrateConfigurationPaths` | deletes superseded BE config keys |
| `AddDisableDeliveryDaysAttribute` | `AddCustomProductAttributes` | `postnl_disable_delivery_days` |
| `AddMaxQtyLetterboxPackageAttribute` | `AddCustomProductAttributes` | `postnl_max_qty_letterbox` |
| `AddInternationalLetterboxAttributes` | `AddMaxQtyLetterboxPackageAttribute` | international letterbox attrs |

**Idempotency / upgrade safety:** these patch classes are *new* and therefore unknown to
`setup_patch_history` on existing shops, so they **will run once** on the next `setup:upgrade` for
every install. Each is safe to re-run:
- EAV patches early-return when `EavSetup::getAttributeId()` shows the attribute already exists.
- Config patches use naturally idempotent `UPDATE` / `DELETE`.

So an **existing** shop keeps its data (patches no-op on already-present attributes) and a **fresh**
shop gets every attribute in the correct order.

### 4. Tests
Added unit tests for all data patches under `Test/Unit/Setup/Patch/Data/`. Each covers the
fresh-install path (attribute absent → created), the idempotent re-run path (attribute present →
no-op), and the `getDependencies()` / `getAliases()` contracts.

---

## Installing this fork for live testing

The fork keeps the upstream package name `tig/postnl-magento2`, so you point Composer at the fork's
Git repo and require a branch instead of a tagged release.

1. In your project `composer.json`, add the fork as a VCS repository:
   ```json
   "repositories": {
       "siteation-postnl": {
           "type": "vcs",
           "url": "https://github.com/Siteation/postnl-magento2"
       }
   }
   ```
2. Require the branch that contains the **complete** overhaul (see the branch map below):
   ```bash
   composer require "tig/postnl-magento2:dev-test/fresh-install-patch-coverage"
   ```
   Composer rewrites `dev-<branch>` to a `dev-*` constraint; the VCS repo takes precedence over
   Packagist because the package name matches.
3. Deploy as usual:
   ```bash
   bin/magento setup:upgrade
   bin/magento setup:di:compile        # production mode
   bin/magento cache:flush
   ```
4. Run the bundled unit tests against the module:
   ```bash
   vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist \
       vendor/tig/postnl-magento2/Test/Unit/Setup/Patch/Data
   ```

> Local development in this repo uses a Composer **path** repository (the package is symlinked from
> `package-source/siteation/postnl-magento2`), so edits apply immediately without a `composer update`.
> The VCS instructions above are only for deploying to a separate live/staging server.

---

## Branch map (state of the fork on GitHub)

The overhaul was developed as a stack of three branches and merged with a chain of PRs, which left
`master` only **partially** updated. Until you cut a final release, use a feature branch, not `master`.

| Branch | Contains | Use it for |
|--------|----------|------------|
| `master` | **PHP 8.x compatibility only** (PR #1). Still has old `InstallData.php`; **missing** the declarative-patch migration and tests. | ❌ Not the complete fork — do not deploy yet |
| `refactor/php81-compatibility-overhaul` | Same as master + a merge of declarative-patches | intermediate |
| `refactor/declarative-patches` | Everything (declarative patches merged in) | complete (merge-based history) |
| **`test/fresh-install-patch-coverage`** | **Everything** — PHP 8.x compat + declarative patches + tests, linear history | ✅ **Test this on live** |

**When you are ready to finalise** (not yet — you wanted to live-test first): open one PR from
`test/fresh-install-patch-coverage` → `master` so `master` becomes the complete fork, then tag a
release (e.g. `1.25.0-siteation.1`) and switch your live `composer require` to that tag.
