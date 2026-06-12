<?php

namespace TIG\PostNL\Setup\V1125\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallSmartReturnBarcode extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';

    protected array $columns = [
        'smart_return_barcode'
    ];

    /**
     * @return array
     */
    public function installSmartReturnBarcodeColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'length'   => 32,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'Smart Return Barcode'
        ];
    }
}
