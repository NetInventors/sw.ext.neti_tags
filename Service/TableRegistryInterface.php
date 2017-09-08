<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

use Shopware\Models\Plugin\Plugin;

/**
 * Interface TableRegistryInterface
 *
 * @package NetiTags\Service
 */
interface TableRegistryInterface
{
    /**
     * @param string $tableName
     *
     * @return \NetiTags\Models\TableRegistry|null
     */
    public function getByTableName($tableName);

    /**
     * @param string $title
     * @param string $tableName
     * @param Plugin $plugin
     *
     * @return bool
     * @throws \Exception
     */
    public function register($title, $tableName, Plugin $plugin);

    /**
     * @param string $tableName
     * @param Plugin $plugin
     *
     * @return bool
     * @throws \Exception
     */
    public function unregister($tableName, Plugin $plugin);
}
