<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\AddCustomProductAttributes;
use TIG\PostNL\Test\TestCase;

class AddCustomProductAttributesTest extends TestCase
{
    protected $instanceClass = AddCustomProductAttributes::class;

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

    public function testApplyAddsFreshAttributes(): void
    {
        // Attribute does not exist yet — fresh install
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_product_type')
            ->willReturn(false);

        $this->eavSetup->expects($this->exactly(3))
            ->method('addAttribute');

        $this->getInstance()->apply();
    }

    public function testApplySkipsWhenAttributesAlreadyExist(): void
    {
        // Attribute already present — existing install running upgrade
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_product_type')
            ->willReturn(42);

        $this->eavSetup->expects($this->never())
            ->method('addAttribute');

        $this->getInstance()->apply();
    }

    public function testGetDependenciesIsEmpty(): void
    {
        $this->assertSame([], AddCustomProductAttributes::getDependencies());
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
