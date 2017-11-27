<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;

/**
 * Class View
 * @package NetiTags\Subscriber
 */
class View implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDir;

    /**
     * View constructor.
     * @param string          $pluginDir
     * @throws \Exception
     */
    public function __construct(
        $pluginDir
    ) {
        $this->pluginDir  = $pluginDir;
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
        $args->getSubject()->View()->addTemplateDir($this->pluginDir . '/Resources/views/');
    }
}