<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\AddShippingDurationAttribute;
use TIG\PostNL\Setup\Patch\Data\UpdateDefaultValueForDirationAttribute;
use TIG\PostNL\Test\TestCase;

class UpdateDefaultValueForDirationAttributeTest extends TestCase
{
    protected $instanceClass = UpdateDefaultValueForDirationAttribute::class;

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

    public function testApplyUpdatesDefaultValueWhenAttributeExists(): void
    {
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_shipping_duration')
            ->willReturn(12);

        $this->eavSetup->expects($this->once())
            ->method('updateAttribute');

        $this->getInstance()->apply();
    }

    public function testApplySkipsWhenAttributeDoesNotExist(): void
    {
        // Guard protects against edge case where dependency patch exited early.
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_shipping_duration')
            ->willReturn(false);

        $this->eavSetup->expects($this->never())
            ->method('updateAttribute');

        $this->getInstance()->apply();
    }

    public function testGetDependenciesRequiresShippingDurationAttribute(): void
    {
        $this->assertSame(
            [AddShippingDurationAttribute::class],
            UpdateDefaultValueForDirationAttribute::getDependencies()
        );
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
