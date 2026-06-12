<?php

namespace TIG\PostNL\Setup\V174\Schema;

use \TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallDownpartnerAttributes extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';
    
    protected array $columns = [
        'downpartner_id',
        'downpartner_location',
        'downpartner_barcode',
    ];
    
    public function installDownpartnerIdColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 32,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Downpartner ID',
            'after'    => 'confirmed'
        ];
    }
    
    public function installDownpartnerLocationColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 16,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Downpartner Location',
            'after'    => 'downpartner_id'
        ];
    }
    
    public function installDownpartnerBarcodeColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 32,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Downpartner Barcode',
            'after'    => 'downpartner_location'
        ];
    }
}
