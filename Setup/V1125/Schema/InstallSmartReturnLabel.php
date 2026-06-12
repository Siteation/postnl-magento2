<?php

namespace TIG\PostNL\Setup\V1125\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallSmartReturnLabel extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment_label';

    protected array $columns = [
        'smart_return_label'
    ];

    /**
     * @return array
     */
    public function installSmartReturnLabelColumn()
    {
        return [
            'type'     => Table::TYPE_BOOLEAN,
            'nullable' => false,
            'default'  => 0,
            'comment'  => 'Smart Return Label',
            'after'    => 'return_label',
        ];
    }
}
