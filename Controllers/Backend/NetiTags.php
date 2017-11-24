<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

use NetiFoundation\Controllers\Backend\AbstractBackendExtJsController;

class Shopware_Controllers_Backend_NetiTags extends AbstractBackendExtJsController
{
    /**
     * Load the backend-Main-Application
     *
     * @return void
     */
    public function indexAction()
    {
        // Load the ExtJs-Application
        $this->View()->loadTemplate('backend/neti_tags/app.js');
    }
}