<?php

namespace TIG\PostNL\Setup\V191\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallStatedAddressOnly extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_order';

    protected array $columns = [
        'is_stated_address_only'
    ];

    public function installIsStatedAddressOnlyColumn()
    {
        return [
            'type'     => Table::TYPE_BOOLEAN,
            'nullable' => false,
            'default'  => 0,
            'comment'  => 'Customer selected Stated Address Only in frontend',
            'after' => 'is_pakjegemak',
        ];
    }
}
