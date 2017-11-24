<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

/**
 * Class Backend
 *
 * @package NetiTags\Subscriber
 */
class Backend implements SubscriberInterface
{
    /**
     * @var string
     */
    protected $pluginDir;

    /**
     * @var \Enlight_Template_Manager
     */
    protected $templateManager;

    /**
     * Backend constructor.
     *
     * @param string  $pluginDir
     */
    public function __construct(
        $pluginDir
    ) {
        $this->pluginDir = $pluginDir;
    }

    /**
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatch' => array('onPostDispatch', 10),
        );
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onPostDispatch(Enlight_Event_EventArgs $args)
    {
        /**
         * @var \Enlight_Controller_Request_RequestHttp   $request
         * @var \Enlight_Controller_Response_ResponseHttp $response
         */
        $request  = $args->get('subject')->Request();
        $response = $args->get('subject')->Response();
        if (! $request->isDispatched() || $response->isException()) {
            return;
        }

        $module = $request->getModuleName();
        if ('backend' === $module) {
            /**
             * @var \Enlight_View_Default $view
             */
            $view       = $args->get('subject')->View();
            $controller = strtolower($request->getControllerName());
            $action     = strtolower($request->getActionName());

            $view->addTemplateDir(
                $this->pluginDir . '/Resources/views/'
            );

            if ('base' === $controller && 'index' === $action) {
                foreach ($this->getBaseIndexTemplates() as $template) {
                    $view->extendsTemplate($template);
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function getBaseIndexTemplates()
    {
        return array(
            'backend/neti_tags/extensions/view/base/attribute/article/field.js',
            'backend/neti_tags/extensions/view/base/attribute/article/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/customer/field.js',
            'backend/neti_tags/extensions/view/base/attribute/customer/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/blog/field.js',
            'backend/neti_tags/extensions/view/base/attribute/blog/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/category/field.js',
            'backend/neti_tags/extensions/view/base/attribute/category/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/cms/field.js',
            'backend/neti_tags/extensions/view/base/attribute/cms/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/form.js',
        );
    }
}
