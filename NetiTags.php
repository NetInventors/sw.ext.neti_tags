<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

/**
 * Class NetiTags
 *
 * @package NetiTags
 */
class NetiTags extends Plugin
{
//    /**
//     * This is overridden to clear all caches if a plugin has View components
//     *
//     * @param ActivateContext $context
//     */
//    public function activate(ActivateContext $context)
//    {
//        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
//    }
//
//    /**
//     * This is overridden to clear all caches if a plugin has View components
//     *
//     * @param DeactivateContext $context
//     */
//    public function deactivate(DeactivateContext $context)
//    {
//        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
//    }
//
//    /**
//     * This is overridden to clear all caches if a plugin has View components
//     *
//     * @param UninstallContext $context
//     */
//    public function uninstall(UninstallContext $context)
//    {
//        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
//    }
}