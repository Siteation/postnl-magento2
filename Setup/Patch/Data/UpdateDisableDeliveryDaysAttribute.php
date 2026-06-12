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

    public static function getDependencies(): array
    {
        return [
            AddDisableDeliveryDaysAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Guard: AddDisableDeliveryDaysAttribute is now a declared dependency, but
        // on an existing install that already ran the old InstallData the attribute
        // exists and the dependency patch will have exited early. Either way we check.
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_disable_delivery_days') === false) {
            return $this;
        }

        $eavSetup->updateAttribute(Product::ENTITY, 'postnl_disable_delivery_days', 'default_value', 0);

        return $this;
    }
}
