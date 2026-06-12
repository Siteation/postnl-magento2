<?php

namespace TIG\PostNL\Setup\V153\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class SalesShipmentGridColumns extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'sales_shipment_grid';

    protected array $columns = [
        'tig_postnl_barcode',
    ];

    /**
     * @return array
     */
    public function installTigPostnlBarcodeColumn()
    {
        return [
            'type' => Table::TYPE_TEXT,
            'nullable' => true,
            'comment' => 'PostNL Barcode',
            'after' => 'tig_postnl_product_code',
        ];
    }
}
