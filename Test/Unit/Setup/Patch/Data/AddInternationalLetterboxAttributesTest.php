<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\AddInternationalLetterboxAttributes;
use TIG\PostNL\Setup\Patch\Data\AddMaxQtyLetterboxPackageAttribute;
use TIG\PostNL\Test\TestCase;

class AddInternationalLetterboxAttributesTest extends TestCase
{
    protected $instanceClass = AddInternationalLetterboxAttributes::class;

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

    public function testApplyAddsBothFreshAttributes(): void
    {
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_max_qty_international')
            ->willReturn(false);

        // Both postnl_max_qty_international and postnl_max_qty_international_letterbox
        $this->eavSetup->expects($this->exactly(2))
            ->method('addAttribute');

        $this->getInstance()->apply();
    }

    public function testApplySkipsWhenFirstAttributeAlreadyExists(): void
    {
        // Guard is on the first attribute only — if it exists, both are assumed present.
        $this->eavSetup->method('getAttributeId')
            ->with(Product::ENTITY, 'postnl_max_qty_international')
            ->willReturn(88);

        $this->eavSetup->expects($this->never())
            ->method('addAttribute');

        $this->getInstance()->apply();
    }

    public function testGetDependenciesRequiresLetterboxPackageAttribute(): void
    {
        $this->assertSame(
            [AddMaxQtyLetterboxPackageAttribute::class],
            AddInternationalLetterboxAttributes::getDependencies()
        );
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
