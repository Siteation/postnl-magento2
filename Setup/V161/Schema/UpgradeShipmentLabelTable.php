<?php

namespace TIG\PostNL\Setup\V161\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class UpgradeShipmentLabelTable extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment_label';

    protected array $columns = [
        'product_code'
    ];

    public function installProductCodeColumn()
    {
        return [
            'type' => Table::TYPE_INTEGER,
            'length' => 4,
            'nullable' => true,
            'default' => null,
            'comment' => 'Product Code',
            'after' => 'type',
        ];
    }
}
