<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\MigrateConfigurationPaths;
use TIG\PostNL\Setup\Patch\Data\RemoveEveningBeConfig;
use TIG\PostNL\Test\TestCase;

class RemoveEveningBeConfigTest extends TestCase
{
    protected $instanceClass = RemoveEveningBeConfig::class;

    private \PHPUnit\Framework\MockObject\MockObject $moduleDataSetup;
    private \PHPUnit\Framework\MockObject\MockObject $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->getFakeMock(AdapterInterface::class, true);
        $this->moduleDataSetup = $this->getFakeMock(ModuleDataSetupInterface::class, true);
        $this->moduleDataSetup->method('getTable')->willReturnArgument(0);
        $this->moduleDataSetup->method('getConnection')->willReturn($this->connection);
    }

    public function getInstance(array $args = [])
    {
        return parent::getInstance($args + [
            'moduleDataSetup' => $this->moduleDataSetup,
        ]);
    }

    public function testApplyDeletesBothEveningBeKeys(): void
    {
        $this->connection->expects($this->exactly(2))
            ->method('delete');

        $this->getInstance()->apply();
    }

    public function testApplyIsIdempotent(): void
    {
        // DELETE on already-absent rows is a safe no-op, so running twice is fine
        $this->connection->expects($this->exactly(4))
            ->method('delete');

        $instance = $this->getInstance();
        $instance->apply();
        $instance->apply();
    }

    public function testGetDependenciesRequiresMigrateConfigurationPaths(): void
    {
        $this->assertSame(
            [MigrateConfigurationPaths::class],
            RemoveEveningBeConfig::getDependencies()
        );
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
