<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddMaxQtyLetterboxPackageAttribute implements DataPatchInterface
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

        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_max_qty_letterbox') !== false) {
            return $this;
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_max_qty_letterbox',
            [
                'group'                   => 'PostNL',
                'type'                    => 'int',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Maximum quantity Letterbox Package',
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
                'note'                    => 'Maximum quantity for Letterbox Package.',
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
