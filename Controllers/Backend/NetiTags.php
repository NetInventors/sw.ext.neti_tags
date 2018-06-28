<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

use NetiFoundation\Controllers\Backend\AbstractBackendExtJsController;
use NetiTags\Struct\PluginConfig;

/**
 * Class Shopware_Controllers_Backend_NetiTags
 */
class Shopware_Controllers_Backend_NetiTags extends AbstractBackendExtJsController
{
    /**
     * Load the backend-Main-Application
     *
     * @return void
     * @throws Exception
     */
    public function indexAction()
    {
        // Load the ExtJs-Application
        $this->View()->loadTemplate('backend/neti_tags/app.js');

        $this->View()->assign('neti_tags_plugin_config', array(
            'deleteprotecting' => $this->getPluginConfig()->isDeleteprotecting()
        ));
    }

    /**
     * @return PluginConfig
     * @throws Exception
     */
    protected function getPluginConfig()
    {
        return $this->container->get('neti_foundation.plugin_manager_config')->getPluginConfig('NetiTags');
    }
}
