<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use TIG\PostNL\Config\Provider\ShippingDuration;

class UpdateDefaultValueForDirationAttribute implements DataPatchInterface
{
    private EavSetupFactory $eavSetupFactory;
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param EavSetupFactory          $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
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
            AddShippingDurationAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Guard against fresh installs where InstallData has not yet run (declarative
        // patches can execute before old-style InstallData in some Magento versions).
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_shipping_duration') === false) {
            return $this;
        }

        $eavSetup->updateAttribute(
            Product::ENTITY,
            'postnl_shipping_duration',
            'default_value',
            ShippingDuration::CONFIGURATION_VALUE
        );

        return $this;
    }
}
