<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\AddCustomProductAttributes;
use TIG\PostNL\Setup\Patch\Data\UpdateCustomProductAttributeDefaults;
use TIG\PostNL\Test\TestCase;

class UpdateCustomProductAttributeDefaultsTest extends TestCase
{
    protected $instanceClass = UpdateCustomProductAttributeDefaults::class;

    private \PHPUnit\Framework\MockObject\MockObject $eavSetup;
    private \PHPUnit\Framework\MockObject\MockObject $eavSetupFactory;
    private \PHPUnit\Framework\MockObject\MockObject $moduleDataSetup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eavSetup = $this->getFakeMock(EavSetup::class, true);
        $this->eavSetupFactory = $this->getFakeMock(EavSetupFactory::class, true);
        $this->eavSetupFactory->method('create')->willReturn($this->eavSetup);
        $this->moduleDataSetup = $this->getFakeMock(ModuleDataSetupInterface::class, true);
    }

    public function getInstance(array $args = [])
    {
        return parent::getInstance($args + [
            'eavSetupFactory' => $this->eavSetupFactory,
            'moduleDataSetup' => $this->moduleDataSetup,
        ]);
    }

    public function testApplyUpdatesDefaultValues(): void
    {
        // Attributes exist (as guaranteed by the AddCustomProductAttributes dependency)
        $this->eavSetup->method('getEntityTypeId')->willReturn('catalog_product');
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_parcel_count')
            ->willReturn(10);

        $this->eavSetup->expects($this->exactly(2))
            ->method('updateAttribute');

        $this->getInstance()->apply();
    }

    public function testApplySkipsWhenAttributesMissing(): void
    {
        // Defensive guard: should not happen in practice (dependency ensures creation first),
        // but the patch returns early without error if the attribute is absent.
        $this->eavSetup->method('getEntityTypeId')->willReturn('catalog_product');
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_parcel_count')
            ->willReturn(false);

        $this->eavSetup->expects($this->never())
            ->method('updateAttribute');

        $this->getInstance()->apply();
    }

    public function testGetDependenciesRequiresCustomProductAttributes(): void
    {
        $this->assertSame(
            [AddCustomProductAttributes::class],
            UpdateCustomProductAttributeDefaults::getDependencies()
        );
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
