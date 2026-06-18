<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use TIG\PostNL\Config\Provider\ShippingDuration;

class AddShippingDurationAttribute implements DataPatchInterface
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

        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_shipping_duration') !== false) {
            return $this;
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_shipping_duration',
            [
                'group'                   => 'PostNL',
                'type'                    => 'text',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Shipping Duration',
                'input'                   => 'select',
                'class'                   => '',
                'source'                  => ShippingDuration::class,
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => ShippingDuration::CONFIGURATION_VALUE,
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple',
            ]
        );

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
