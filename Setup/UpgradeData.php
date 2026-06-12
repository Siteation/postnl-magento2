<?php

namespace TIG\PostNL\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\V120\Data\CustomProductAttributes;
use TIG\PostNL\Setup\V141\Data\ShippingDurationAttribute;
use TIG\PostNL\Setup\V160\Data\ConfigurationData;
use TIG\PostNL\Setup\V172\Data\UpdateCustomProductAttributes;
use TIG\PostNL\Setup\V181\Data\UpdateConfigData;
use TIG\PostNL\Setup\V191\Data\InstallDisableDeliveryDaysAttribute;
use TIG\PostNL\Setup\V194\Data\InstallMaximumQuantityLetterboxPackage;
use TIG\PostNL\Setup\V1180\Data\InstallLetterboxPackages;

class UpgradeData implements UpgradeDataInterface
{
    private array $upgradeDataObjects;

    public function __construct(
        CustomProductAttributes $customProductAttributes,
        ShippingDurationAttribute $shippingDurationAttribute,
        ConfigurationData $configurationData,
        UpdateCustomProductAttributes $updateCustomProductAttributes,
        UpdateConfigData $updateConfigData,
        InstallDisableDeliveryDaysAttribute $installDisableDeliveryDaysAttribute,
        InstallMaximumQuantityLetterboxPackage $installMaximumQuantityLetterboxPackage,
        InstallLetterboxPackages $installLetterboxPackages
    ) {
        $this->upgradeDataObjects = [
            'v1.2.0' => [$customProductAttributes],
            'v1.4.1' => [$shippingDurationAttribute],
            'v1.6.0' => [$configurationData],
            'v1.7.2' => [$updateCustomProductAttributes],
            'v1.8.1' => [$updateConfigData],
            'v1.9.1' => [$installDisableDeliveryDaysAttribute],
            'v1.9.4' => [$installMaximumQuantityLetterboxPackage],
            'v1.18.0' => [$installLetterboxPackages]
        ];
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.2.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.4.1', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.4.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.6.0', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.6.0'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.7.2', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.7.2'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.8.1', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.8.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.9.1', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.9.1'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.9.4', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.9.4'], $setup, $context);
        }

        if (version_compare($context->getVersion(), '1.18.0', '<')) {
            $this->upgradeData($this->upgradeDataObjects['v1.18.0'], $setup, $context);
        }

        $setup->endSetup();
    }

    /**
     * @param AbstractDataInstaller[] $installSchemaObjects
     */
    private function upgradeData(
        array $installSchemaObjects,
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ): void {
        foreach ($installSchemaObjects as $installer) {
            $installer->install($setup, $context);
        }
    }
}
