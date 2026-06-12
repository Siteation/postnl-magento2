<?php

namespace TIG\PostNL\Setup\V110\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class SalesOrderGridColumns extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'sales_order_grid';

    protected array $columns = [
        'tig_postnl_ship_at',
    ];

    /**
     * @return array
     */
    public function installTigPostnlShipAtColumn()
    {
        return [
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => null,
            'comment' => 'When is this shipment due for sending',
            'after' => 'shipping_information',
        ];
    }
}
