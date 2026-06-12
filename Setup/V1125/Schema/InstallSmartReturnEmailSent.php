<?php

namespace TIG\PostNL\Setup\V1125\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallSmartReturnEmailSent extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';


    protected array $columns = [
        'smart_return_email_sent'
    ];

    /**
     * @return array
     */
    public function installSmartReturnEmailSentColumn()
    {
        return [
            'type'     => Table::TYPE_BOOLEAN,
            'nullable' => false,
            'default'  => 0,
            'comment'  => 'Smart Return Email Sent',
            'after'    => 'smart_return_barcode',
        ];
    }
}
