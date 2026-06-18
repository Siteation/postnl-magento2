<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use TIG\PostNL\Config\Provider\ProductType;

class AddCustomProductAttributes implements DataPatchInterface
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

        // Guard: all three attributes are created atomically — skip the whole patch if the
        // first one already exists (idempotent for existing installs that ran InstallData).
        if ($eavSetup->getAttributeId(Product::ENTITY, 'postnl_product_type') !== false) {
            return $this;
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_product_type',
            [
                'group'                   => 'PostNL',
                'type'                    => 'text',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Type',
                'input'                   => 'select',
                'class'                   => '',
                'source'                  => ProductType::class,
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => 'regular',
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple',
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_parcel_count',
            [
                'group'                   => 'PostNL',
                'type'                    => 'int',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Parcel count',
                'input'                   => 'text',
                'class'                   => 'validate-greater-than-zero',
                'source'                  => '',
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => 1,
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple',
                'note'                    => 'When sending Extra@Home types, this value is used to calculate the ' .
                                             'coli amount. When using other types this value will be ignored.',
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'postnl_parcel_volume',
            [
                'group'                   => 'PostNL',
                'type'                    => 'int',
                'backend'                 => '',
                'frontend'                => '',
                'label'                   => 'Volume',
                'input'                   => 'text',
                'class'                   => 'validate-greater-than-zero',
                'source'                  => '',
                'global'                  => EavAttribute::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => false,
                'default'                 => 1,
                'searchable'              => false,
                'filterable'              => false,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true,
                'unique'                  => false,
                'apply_to'                => 'simple',
                'note'                    => 'When sending Extra@Home types, this field is mandatory. '.
                                             'Enter as cubic centimeters like 30000.',
            ]
        );

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
