<?php

namespace TIG\PostNL\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use TIG\PostNL\Setup\V120\Schema\SalesShipmentGridColumns as SalesShipmentGridColumns120;
use TIG\PostNL\Setup\V120\Schema\SalesOrderGridColumns as SalesOrderGridColumns120;
use TIG\PostNL\Setup\V120\Schema\UpgradeOrderTable as UpgradeOrderTable120;
use TIG\PostNL\Setup\V120\Schema\InstallMatrixRateTable;
use TIG\PostNL\Setup\V130\Schema\UpgradeForeignKeysOrderTable;
use TIG\PostNL\Setup\V131\Schema\UpgradeOrderTable as UpgradeOrderTable131;
use TIG\PostNL\Setup\V131\Schema\UpgradeShipmentTable as UpgradeShipmentTable131;
use TIG\PostNL\Setup\V140\Schema\UpgradeShippingLabelType;
use TIG\PostNL\Setup\V141\Schema\UpgradeOrderTable as UpgradeOrderTable141;
use TIG\PostNL\Setup\V152\Schema\SalesShipmentGridColumns as SalesShipmentGridColumns152;
use TIG\PostNL\Setup\V152\Schema\SalesOrderGridColumns as SalesOrderGridColumns152;
use TIG\PostNL\Setup\V152\Schema\UpgradeOrderTable as UpgradeOrderTable152;
use TIG\PostNL\Setup\V152\Schema\UpgradeShipmentTable;
use TIG\PostNL\Setup\V153\Schema\SalesShipmentGridColumns as SalesShipmentGridColumns153;
use TIG\PostNL\Setup\V161\Schema\UpgradeShipmentLabelTable;
use TIG\PostNL\Setup\V174\Schema\InstallDownpartnerAttributes;
use TIG\PostNL\Setup\V180\Schema\InstallPriorityAttribute;
use TIG\PostNL\Setup\V182\Schema\ModifyConditionNameColumn;
use TIG\PostNL\Setup\V190\Schema\InstallReturnBarcode;
use TIG\PostNL\Setup\V190\Schema\InstallReturnLabel;
use TIG\PostNL\Setup\V191\Schema\InstallStatedAddressOnly;
use TIG\PostNL\Setup\V1125\Schema\InstallSmartReturnBarcode;
use TIG\PostNL\Setup\V1125\Schema\InstallSmartReturnLabel;
use TIG\PostNL\Setup\V1125\Schema\InstallIsSmartReturn;
use TIG\PostNL\Setup\V1125\Schema\InstallSmartReturnEmailSent;
use TIG\PostNL\Setup\V1127\Schema\InstallOrderInsuredTier;
use TIG\PostNL\Setup\V1127\Schema\InstallShipmentInsuredTier;
use TIG\PostNL\Setup\V1130\Schema\InstallOrderAcInformation;
use TIG\PostNL\Setup\V1130\Schema\InstallShipmentAcInformation;
use TIG\PostNL\Setup\V1140\Schema\InstallReturnStatus;

class UpgradeSchema implements UpgradeSchemaInterface
{
    private array $upgradeSchemaObjects;

    public function __construct(
        SalesShipmentGridColumns120 $salesShipmentGridColumns,
        SalesOrderGridColumns120 $salesOrderGridColumns,
        UpgradeOrderTable120 $upgradeOrderTable,
        InstallMatrixRateTable $installMatrixRateTable,
        UpgradeForeignKeysOrderTable $upgradeForeignKeysOrderTable,
        UpgradeOrderTable131 $upgradeOrderTable131,
        UpgradeShipmentTable131 $upgradeShipmentTable131,
        UpgradeShippingLabelType $upgradeShippingLabelType,
        UpgradeOrderTable141 $upgradeOrderTable141,
        SalesShipmentGridColumns152 $salesShipmentGridColumns152,
        SalesOrderGridColumns152 $salesOrderGridColumns152,
        UpgradeOrderTable152 $upgradeOrderTable152,
        UpgradeShipmentTable $upgradeShipmentTable,
        SalesShipmentGridColumns153 $salesShipmentGridColumns153,
        UpgradeShipmentLabelTable $upgradeShipmentLabelTable,
        InstallDownpartnerAttributes $installDownpartnerAttributes,
        InstallPriorityAttribute $installPriorityAttribute,
        ModifyConditionNameColumn $modifyConditionNameColumn,
        InstallReturnBarcode $installReturnBarcode,
        InstallReturnLabel $installReturnLabel,
        InstallStatedAddressOnly $installStatedAddressOnly,
        InstallSmartReturnBarcode $installSmartReturnBarcode,
        InstallSmartReturnLabel $installSmartReturnLabel,
        InstallIsSmartReturn $installIsSmartReturn,
        InstallSmartReturnEmailSent $installSmartReturnEmailSent,
        InstallOrderInsuredTier $installOrderInsuredTier,
        InstallShipmentInsuredTier $installShipmentInsuredTier,
        InstallOrderAcInformation $installOrderAcInformation,
        InstallShipmentAcInformation $installShipmentAcInformation,
        InstallReturnStatus $installReturnStatus
    ) {
        $this->upgradeSchemaObjects = [
            'v1.2.0' => [$salesShipmentGridColumns, $salesOrderGridColumns, $upgradeOrderTable, $installMatrixRateTable],
            'v1.3.0' => [$upgradeForeignKeysOrderTable],
            'v1.3.1' => [$upgradeOrderTable131, $upgradeShipmentTable131],
            'v1.4.0' => [$upgradeShippingLabelType],
            'v1.4.1' => [$upgradeOrderTable141],
            'v1.5.2' => [$salesShipmentGridColumns152, $salesOrderGridColumns152, $upgradeOrderTable152, $upgradeShipmentTable],
            'v1.5.3' => [$salesShipmentGridColumns153],
            'v1.6.1' => [$upgradeShipmentLabelTable],
            'v1.7.4' => [$installDownpartnerAttributes],
            'v1.8.0' => [$installPriorityAttribute],
            'v1.8.2' => [$modifyConditionNameColumn],
            'v1.9.0' => [$installReturnBarcode, $installReturnLabel],
            'v1.9.1' => [$installStatedAddressOnly],
            'v1.12.5' => [$installSmartReturnBarcode, $installSmartReturnLabel, $installIsSmartReturn, $installSmartReturnEmailSent],
            'v1.12.7' => [$installOrderInsuredTier, $installShipmentInsuredTier],
            'v1.13.0' => [$installOrderAcInformation, $installShipmentAcInformation],
            'v1.14.0' => [$installReturnStatus]
        ];
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.2.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.3.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.3.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.3.1', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.3.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.4.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.4.1', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.4.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.5.2', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.5.2'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.5.3', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.5.3'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.6.1', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.6.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.7.4', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.7.4'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.8.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.8.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.8.2', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.8.2'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.9.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.9.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.9.1', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.9.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.12.5', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.12.5'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.12.7', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.12.7'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.13.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.13.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.14.0', '<')) {
            $this->upgradeSchemas($this->upgradeSchemaObjects['v1.14.0'], $setup, $context);
        }

        $setup->endSetup();
    }

    /**
     * @param AbstractColumnsInstaller[] $schemaObjects
     */
    private function upgradeSchemas(
        array $schemaObjects,
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ): void {
        foreach ($schemaObjects as $schema) {
            $schema->install($setup, $context);
        }
    }
}
