<?php

namespace TIG\PostNL\Setup\V141\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class UpgradeOrderTable extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_order';

    protected array $columns = [
        'shipping_duration'
    ];

    public function installShippingDurationColumn()
    {
        return [
            'type' => Table::TYPE_INTEGER,
            'length' => 11,
            'nullable' => true,
            'default' => null,
            'comment' => 'Shipping Duration',
            'after' => 'delivery_date',
        ];
    }
}
