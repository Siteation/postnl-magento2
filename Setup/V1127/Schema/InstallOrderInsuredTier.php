<?php

namespace TIG\PostNL\Setup\V1127\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallOrderInsuredTier extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_order';

    protected array $columns = [
        'insured_tier'
    ];

    /**
     * @return array
     */
    public function installInsuredTierColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Extra Cover Insured Tier'
        ];
    }
}
