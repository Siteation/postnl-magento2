<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddInternationalLetterboxAttributes implements DataPatchInterface
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

        // Guard on the first of the two attributes — both are created atomically.
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_max_qty_international') !== false) {
            return $this;
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_max_qty_international',
            [
                'group'                   => 'PostNL',
                'type'                    => 'int',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Maximum quantity International Packet',
                'input'                   => 'text',
                'class'                   => '',
                'source'                  => '',
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => 0,
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple,grouped,bundle',
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_max_qty_international_letterbox',
            [
                'group'                   => 'PostNL',
                'type'                    => 'int',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Maximum quantity International Letterbox Package',
                'input'                   => 'text',
                'class'                   => '',
                'source'                  => '',
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => 0,
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple,grouped,bundle',
            ]
        );

        return $this;
    }

    public static function getDependencies(): array
    {
        return [
            AddMaxQtyLetterboxPackageAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }
}
