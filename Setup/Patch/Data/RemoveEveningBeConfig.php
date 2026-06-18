<?php

namespace TIG\PostNL\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use TIG\PostNL\Config\Provider\ShippingOptions;

class RemoveEveningBeConfig implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply(): self
    {
        $table = $this->moduleDataSetup->getTable('core_config_data');
        $connection = $this->moduleDataSetup->getConnection();

        // DELETE is naturally idempotent — rows that are already gone simply match zero rows.
        $connection->delete($table, 'path = \'' . ShippingOptions::XPATH_SHIPPING_OPTION_EVENING_BE_ACTIVE . '\'');
        $connection->delete($table, 'path = \'' . ShippingOptions::XPATH_SHIPPING_OPTION_EVENING_BE_FEE . '\'');

        return $this;
    }

    public static function getDependencies(): array
    {
        return [
            MigrateConfigurationPaths::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }
}
