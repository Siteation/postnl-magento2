<?php

namespace TIG\PostNL\Setup\V1125\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallIsSmartReturn extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';

    protected array $columns = [
        'is_smart_return'
    ];

    /**
     * @return array
     */
    public function installIsSmartReturnColumn()
    {
        return [
            'type'     => Table::TYPE_BOOLEAN,
            'default'  => 0,
            'nullable' => false,
            'comment'  => 'Is Smart Return'
        ];
    }
}
