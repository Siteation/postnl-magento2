<?php

namespace TIG\PostNL\Setup\V152\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class SalesOrderGridColumns extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'sales_order_grid';

    protected array $columns = [
        'tig_postnl_confirmed',
    ];

    /**
     * @return array
     */
    public function installTigPostnlConfirmedColumn()
    {
        return [
            'type' => Table::TYPE_BOOLEAN,
            'default' => 0,
            'nullable' => false,
            'comment' => 'PostNL Confirmed',
            'after' => 'tig_postnl_product_code',
        ];
    }
}
