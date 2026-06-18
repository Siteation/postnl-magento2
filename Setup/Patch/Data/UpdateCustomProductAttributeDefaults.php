<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateCustomProductAttributeDefaults implements DataPatchInterface
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

    public function apply(): self
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityType = $eavSetup->getEntityTypeId('catalog_product');

        // Guard: if the attribute doesn't exist yet (shouldn't happen given the dependency
        // on AddCustomProductAttributes, but defensive in case of partial state).
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_parcel_count') === false) {
            return $this;
        }

        $eavSetup->updateAttribute($entityType, 'postnl_parcel_count', 'default_value', 0);
        $eavSetup->updateAttribute($entityType, 'postnl_parcel_volume', 'default_value', 0);

        return $this;
    }

    public static function getDependencies(): array
    {
        return [
            AddCustomProductAttributes::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }
}
