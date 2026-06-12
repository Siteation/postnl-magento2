<?php

namespace TIG\PostNL\Setup\V120\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class SalesShipmentGridColumns extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'sales_shipment_grid';

    protected array $columns = [
        'tig_postnl_product_code',
    ];

    /**
     * @return array
     */
    public function installTigPostnlProductCodeColumn()
    {
        return [
            'type' => Table::TYPE_INTEGER,
            'length' => 11,
            'nullable' => true,
            'default' => null,
            'comment' => 'PostNL product code',
            'after' => 'tig_postnl_ship_at',
        ];
    }
}
