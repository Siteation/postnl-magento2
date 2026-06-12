<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateDisableDeliveryDaysAttribute implements DataPatchInterface
{
    private EavSetupFactory $eavSetupFactory;
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Guard against fresh installs where the attribute may not exist yet.
        // postnl_disable_delivery_days is created by InstallData/UpgradeData v1.9.1
        // which can run after declarative patches on some Magento versions.
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_disable_delivery_days') === false) {
            return $this;
        }

        $eavSetup->updateAttribute(Product::ENTITY, 'postnl_disable_delivery_days', 'default_value', 0);

        return $this;
    }
}
