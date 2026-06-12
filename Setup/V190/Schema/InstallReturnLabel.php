<?php

namespace TIG\PostNL\Setup\V190\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallReturnLabel extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment_label';

    protected array $columns = [
        'return_label'
    ];

    public function installReturnLabelColumn()
    {
        return [
            'type'     => Table::TYPE_BOOLEAN,
            'nullable' => false,
            'default'  => 0,
            'comment'  => 'Return Label',
            'after' => 'product_code',
        ];
    }
}
