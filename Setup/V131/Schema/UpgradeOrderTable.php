<?php

namespace TIG\PostNL\Setup\V131\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class UpgradeOrderTable extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_order';

    protected array $columns = [
        'ac_characteristic',
        'ac_option'
    ];

    /**
     * Installs the Location AgentCode Characteristic Column
     * @return array
     */
    public function installAcCharacteristicColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 3,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'AC Characteristic',
            'after'    => 'type',
        ];
    }

    /**
     * Installs the Location AgentCode Option Column
     * @return array
     */
    public function installAcOptionColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 3,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'AC Option',
            'after'    => 'ac_characteristic',
        ];
    }
}
