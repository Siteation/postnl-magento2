<?php
namespace TIG\PostNL\Setup\V1130\Schema;

use TIG\PostNL\Setup\AbstractColumnsInstaller;
use Magento\Framework\DB\Ddl\Table;

class InstallShipmentAcInformation extends AbstractColumnsInstaller
{
    const TABLE_NAME = 'tig_postnl_shipment';

    protected array $columns = [
        'ac_information'
    ];

    /**
     * @return array
     */
    public function installAcInformationColumn()
    {
        return [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'default'  => null,
            'comment'  => 'AC Information',
            'after'    => 'ac_option',
        ];
    }
}
