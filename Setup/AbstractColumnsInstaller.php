<?php

namespace TIG\PostNL\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

abstract class AbstractColumnsInstaller implements InstallSchemaInterface
{
    const TABLE_NAME = null;

    protected ?Table $table = null;
    protected ?SchemaSetupInterface $setup = null;
    protected array $columns = [];
    protected ?AdapterInterface $connection = null;
    protected ?array $columnsList = null;

    /**
     * @throws LocalizedException
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->setup = $setup;
        $this->connection = $setup->getConnection();
        $this->table = $setup->getTable(static::TABLE_NAME);

        if (!$setup->tableExists($this->table)) {
            throw new LocalizedException(__('Table %1 does not exists', static::TABLE_NAME));
        }

        foreach ($this->columns as $column) {
            $this->installColumn($column);
        }
    }

    protected function installColumn(string $columnName): void
    {
        if ($this->columnExists($columnName)) {
            return;
        }

        $methodName = $this->getMethodName($columnName);

        /** @var array $arguments */
        $arguments = $this->$methodName();

        $connection = $this->setup->getConnection();
        $connection->addColumn(
            $this->table,
            $columnName,
            $arguments
        );
    }

    protected function saveTable(): AdapterInterface
    {
        $connection = $this->setup->getConnection();

        return $connection->createTable($this->table);
    }

    protected function columnExists(string $columnName): bool
    {
        if ($this->columnsList === null) {
            $this->columnsList = $this->connection->describeTable($this->table);
        }

        return array_key_exists($columnName, $this->columnsList);
    }

    /**
     * Converts this_column_name to installThisColumnNameColumn
     */
    protected function getMethodName(string $columnName): string
    {
        $methodName = str_replace('_', ' ', $columnName);
        $methodName = ucwords($methodName);
        $methodName = str_replace(' ', '', $methodName);
        $methodName = 'install' . $methodName . 'Column';

        return $methodName;
    }
}
