<?php

namespace TIG\PostNL\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

abstract class AbstractTableInstaller implements InstallSchemaInterface
{
    const TABLE_NAME = null;

    protected ?Table $table = null;
    protected ?SchemaSetupInterface $setup = null;

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->setup = $setup;

        if (!$setup->tableExists(static::TABLE_NAME)) {
            $this->createTable();
            $this->defineTable();
            $this->saveTable();
        }
    }

    protected function createTable(): Table
    {
        $connection = $this->setup->getConnection();
        $this->table = $connection->newTable($this->setup->getTable(static::TABLE_NAME));

        return $this->table;
    }

    abstract protected function defineTable(): void;

    /** @throws \Exception */
    protected function addEntityId(): void
    {
        $this->table->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Entity ID'
        );
    }

    /** @throws \Exception */
    protected function addDate(string $name, string $comment, bool $nullable = true, $default = null): void
    {
        $this->table->addColumn(
            $name,
            Table::TYPE_DATE,
            null,
            [
                'identity' => false,
                'unsigned' => false,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    /** @throws \Exception */
    protected function addTimestamp(string $name, string $comment, bool $nullable = true, $default = null): void
    {
        $this->table->addColumn(
            $name,
            Table::TYPE_TIMESTAMP,
            null,
            [
                'identity' => false,
                'unsigned' => false,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    /** @throws \Exception */
    protected function addInt(
        string $name,
        string $comment,
        bool $nullable = true,
        bool $unsigned = false,
        $default = null
    ): void {
        $this->table->addColumn(
            $name,
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => false,
                'unsigned' => $unsigned,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    protected function addForeignKey(
        string $ref_table,
        string $ref_table_field,
        string $table,
        string $table_field,
        string $onDelete = Table::ACTION_CASCADE
    ): void {
        $this->table->addForeignKey(
            $this->setup->getFkName($table, $table_field, $ref_table, $ref_table_field),
            $table_field,
            $this->setup->getTable($ref_table),
            $ref_table_field,
            $onDelete
        );
    }

    /** @throws \Exception */
    protected function addIndex(array $fields, string $indexType = AdapterInterface::INDEX_TYPE_UNIQUE): void
    {
        $this->table->addIndex(
            $this->setup->getIdxName($this->table->getName(), $fields, $indexType),
            $fields,
            ['type' => $indexType]
        );
    }

    /** @throws \Exception */
    protected function addText(
        string $name,
        string $comment,
        int $length = 255,
        bool $nullable = true,
        $default = null
    ): void {
        $this->table->addColumn(
            $name,
            Table::TYPE_TEXT,
            $length,
            [
                'identity' => false,
                'unsigned' => false,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    /** @throws \Exception */
    protected function addBlob(string $name, string $comment, bool $nullable = true, $default = null): void
    {
        $this->table->addColumn(
            $name,
            Table::TYPE_BLOB,
            null,
            [
                'identity' => false,
                'unsigned' => false,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    /** @throws \Exception */
    protected function addDecimal(
        string $name,
        string $comment,
        string $size = '15,4',
        bool $nullable = true,
        $default = null
    ): void {
        $this->table->addColumn(
            $name,
            Table::TYPE_DECIMAL,
            $size,
            [
                'identity' => false,
                'unsigned' => false,
                'nullable' => $nullable,
                'primary' => false,
                'default' => $default,
            ],
            $comment
        );
    }

    protected function saveTable(): AdapterInterface
    {
        return $this->setup->getConnection()->createTable($this->table);
    }
}
