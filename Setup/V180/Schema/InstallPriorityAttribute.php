<?php

namespace TIG\PostNL\Setup\V180\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallPriorityAttribute extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';
    
    protected array $columns = [
        'shipment_country'
    ];
    
    public function installShipmentCountryColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 12,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Country',
            'after'    => 'shipment_type'
        ];
    }
}
