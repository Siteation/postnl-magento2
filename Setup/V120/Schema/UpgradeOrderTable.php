<?php

namespace TIG\PostNL\Setup\V120\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class UpgradeOrderTable extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_order';

    protected array $columns = [
        'parcel_count'
    ];

    public function installParcelCountColumn()
    {
        return [
            'type' => Table::TYPE_INTEGER,
            'length' => 11,
            'nullable' => true,
            'default' => null,
            'comment' => 'Parcel Count',
            'after' => 'product_code',
        ];
    }
}
