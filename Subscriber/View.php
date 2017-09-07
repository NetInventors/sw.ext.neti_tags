<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;
use NetiFoundation\Service\PluginManager\ConfigInterface;
use NetiFoundation\Service\PluginManager\License;
use NetiSurcharge\Struct\PluginConfig;

/**
 * Class View
 * @package NetiTags\Subscriber
 */
class View implements SubscriberInterface
{
    /**
     * @var boolean
     */
    private $validLicense;

    /**
     * @var PluginConfig
     */
    private $pluginConfig;

    /**
     * @var string
     */
    private $pluginDir;

    /**
     * View constructor.
     * @param License         $licenseService
     * @param ConfigInterface $configService
     * @param string          $pluginDir
     * @throws \Exception
     */
    public function __construct(
        License $licenseService,
        ConfigInterface $configService,
        $pluginDir
    ) {
        $this->validLicense = License::class === get_class($licenseService) ?
            $licenseService->checkLicense($this, false) : false;

        $this->pluginConfig = $configService->getPluginConfig($this);
        $this->pluginDir    = $pluginDir;
    }

    /**
     * @return array - The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure' => 'registerTemplateDir',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function registerTemplateDir(\Enlight_Controller_ActionEventArgs $args)
    {
        if (!($this->validLicense && $this->pluginConfig->isActiveForSubshop())) {
            return;
        }

        $args->getSubject()->View()->addTemplateDir($this->pluginDir . '/Resources/views/');
    }
}