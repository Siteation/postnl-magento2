<?php

namespace TIG\PostNL\Test\Unit\Setup\Patch\Data;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use TIG\PostNL\Setup\Patch\Data\MigrateConfigurationPaths;
use TIG\PostNL\Test\TestCase;

class MigrateConfigurationPathsTest extends TestCase
{
    protected $instanceClass = MigrateConfigurationPaths::class;

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

    public function testApplyUpdatesAllConfigPaths(): void
    {
        // 30 old → new path mappings defined in the patch
        $this->connection->expects($this->exactly(30))
            ->method('update');

        $this->getInstance()->apply();
    }

    public function testApplyIsIdempotent(): void
    {
        // Running twice is safe: UPDATE on a non-existent old path matches zero rows
        $this->connection->expects($this->exactly(60))
            ->method('update');

        $instance = $this->getInstance();
        $instance->apply();
        $instance->apply();
    }

    public function testGetDependenciesIsEmpty(): void
    {
        $this->assertSame([], MigrateConfigurationPaths::getDependencies());
    }

    public function testGetAliasesIsEmpty(): void
    {
        $this->assertSame([], $this->getInstance()->getAliases());
    }
}
