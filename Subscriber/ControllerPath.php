<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;
use NetiFoundation\Service\PluginManager\License;

/**
 * Class ControllerPath
 * @package NetiTags\Subscriber
 */
class ControllerPath implements SubscriberInterface
{
    /**
     * @var boolean
     */
    private $validLicense;

    /**
     * @var string
     */
    private $pluginDir;

    /**
     * ControllerPath constructor.
     *
     * @param License $licenseService
     * @param string  $pluginDir
     * @throws \Exception
     */
    public function __construct(
        License $licenseService,
        $pluginDir
    ) {
        $this->validLicense = License::class === get_class($licenseService) ?
            $licenseService->checkLicense($this, false) : false;

        $this->pluginDir = $pluginDir;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_NetiSurcharge'  => 'onGetControllerPathBackend',
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function onGetControllerPathBackend()
    {
        if (! $this->validLicense) {
            return;
        }

        return $this->pluginDir . '/Controllers/Backend/NetiSurcharge.php';
    }
}