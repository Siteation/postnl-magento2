<?php

namespace TIG\PostNL\Setup\V190\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallReturnBarcode extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';

    protected array $columns = [
        'return_barcode'
    ];

    public function installReturnBarcodeColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 32,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Return Barcode'
        ];
    }
}
