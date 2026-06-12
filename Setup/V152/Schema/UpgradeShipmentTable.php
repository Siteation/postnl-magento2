<?php

namespace TIG\PostNL\Setup\V152\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class UpgradeShipmentTable extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';

    protected array $columns = [
        'confirmed'
    ];

    public function installConfirmedColumn()
    {
        return [
            'type' => Table::TYPE_BOOLEAN,
            'default' => 0,
            'nullable' => false,
            'comment' => 'PostNL Confirmed',
            'after' => 'confirmed_at',
        ];
    }
}
