<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use NetiTags\Service\Tag\Relations\RelationsInterface;

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
     * @var RelationsInterface
     */
    protected $orderRelation;

    /**
     * Backend constructor.
     *
     * @param string            $pluginDir
     * @param RelationsInterface $orderRelation
     */
    public function __construct(
        $pluginDir,
        RelationsInterface $orderRelation
    ) {
        $this->pluginDir     = $pluginDir;
        $this->orderRelation = $orderRelation;
    }

    /**
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatch'                     => array('onPostDispatch', 10),
            'Enlight_Controller_Action_PostDispatch_Backend_Customer'    => 'onPostDispatchCustomerStream',
            'Enlight_Controller_Action_PostDispatch_Backend_Order'       => 'onPostDispatchOrder',
            'Shopware\Models\Order\Repository::filterListQuery::replace' => 'onFilterListQuery'
        );
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function onFilterListQuery(\Enlight_Hook_HookArgs $args)
    {
        /**
         * @var \Shopware\Components\Model\QueryBuilder $builder
         */
        $builder = $args->get('builder');
        $filters = $args->get('filters');

        $filterProperties = array_flip(array_column($filters, 'property'));
        if (isset($filterProperties['attribute.netiTags'])) {
            $filter = $filters[$filterProperties['attribute.netiTags']];
            unset($filters[$filterProperties['attribute.netiTags']]);

            $relations = $this->orderRelation->getRelations($filter['value']);

            $filters[] = $builder->expr()->in('orders.id', $relations);
        }

        return $args->getSubject()->executeParent(
            $args->getMethod(),
            array(
                $builder,
                $filters
            )
        );
    }

    public function onPostDispatchOrder(\Enlight_Event_EventArgs $args)
    {
        /**
         * @var \Shopware_Controllers_Backend_Order       $subject
         * @var \Enlight_Controller_Request_RequestHttp   $request
         * @var \Enlight_Controller_Response_ResponseHttp $response
         */
        $subject  = $args->getSubject();
        $request  = $args->get('subject')->Request();
        $response = $args->get('subject')->Response();
        if (! $request->isDispatched() || $response->isException()) {
            return;
        }

        $module = $request->getModuleName();
        if ('backend' !== $module) {
            return;
        }

        /**
         * @var \Enlight_View_Default $view
         */
        $view = $subject->View();

        $view->addTemplateDir(
            $this->pluginDir . '/Resources/views/'
        );

        $view->extendsTemplate('backend/neti_tags/extensions/view/order/list/filter.js');
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onPostDispatchCustomerStream(\Enlight_Event_EventArgs $args)
    {
        /**
         * @var \Shopware_Controllers_Backend_CustomerStream $subject
         * @var \Enlight_Controller_Request_RequestHttp      $request
         * @var \Enlight_Controller_Response_ResponseHttp    $response
         */
        $subject  = $args->getSubject();
        $request  = $args->get('subject')->Request();
        $response = $args->get('subject')->Response();
        if (! $request->isDispatched() || $response->isException()) {
            return;
        }

        $module = $request->getModuleName();
        if ('backend' !== $module) {
            return;
        }

        /**
         * @var \Enlight_View_Default $view
         */
        $view = $subject->View();

        $view->addTemplateDir(
            $this->pluginDir . '/Resources/views/'
        );

        $view->extendsTemplate('backend/neti_tags/extensions/view/customer/customer_stream/listing.js');
        $view->extendsTemplate('backend/neti_tags/extensions/view/model/customer/quick_view.js');
        $view->extendsTemplate('backend/neti_tags/extensions/view/main/customer_list_filter.js');
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
            'backend/neti_tags/extensions/view/base/attribute/field.js',
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
            'backend/neti_tags/extensions/view/base/attribute/product_stream/field.js',
            'backend/neti_tags/extensions/view/base/attribute/product_stream/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/customer_stream/field.js',
            'backend/neti_tags/extensions/view/base/attribute/customer_stream/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/order/field.js',
            'backend/neti_tags/extensions/view/base/attribute/order/field/handler.js',
            'backend/neti_tags/extensions/view/base/attribute/form.js',
        );
    }
}
