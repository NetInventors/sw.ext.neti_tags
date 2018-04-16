<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Subscriber;

use Enlight\Event\SubscriberInterface;
use NetiTags\Models\Tag;
use NetiFoundation\Service\PluginManager\Config;
use NetiTags\Struct\PluginConfig;

/**
 * Class TagsSubscriber
 *
 * @package NetiTags\Subscriber
 */
class TagsSubscriber implements SubscriberInterface
{
    /**
     * @var Config
     */
    protected $pluginConfigService;

    /**
     * @var \Shopware_Components_Snippet_Manager
     */
    protected $snippetManager;

    /**
     * TagsSubscriber constructor.
     *
     * @param Config                               $pluginConfigService
     * @param \Shopware_Components_Snippet_Manager $snippetManager
     */
    public function __construct(
        Config $pluginConfigService,
        \Shopware_Components_Snippet_Manager $snippetManager
    ) {
        $this->pluginConfigService = $pluginConfigService;
        $this->snippetManager      = $snippetManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (position defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     * <code>
     * return array(
     *     'eventName0' => 'callback0',
     *     'eventName1' => array('callback1'),
     *     'eventName2' => array('callback2', 10),
     *     'eventName3' => array(
     *         array('callback3_0', 5),
     *         array('callback3_1'),
     *         array('callback3_2')
     *     )
     * );
     *
     * </code>
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'NetiTags_Tag_Remove' => 'onRemoveTag'
        );
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     *
     * @throws \Exception
     */
    public function onRemoveTag(\Enlight_Event_EventArgs $args)
    {
        /**
         * @var Tag $tag
         */
        $tag = $args->get('tag');

        if (
            true !== $this->getConfig()->isDeletprotecting()
            || 0 === $tag->getRelations()->count()
        ) {
            return;
        }

        $snipetsNamespace = $this->snippetManager->getNamespace('plugins/neti_tags/backend/overview');

        throw new \Exception(
            $snipetsNamespace->get(
                'grid_controller/deleteprotecting_exception',
                'Attention, this tag is linked to at least one more element, and therefore cannot delete!'
            )
        );
    }

    /**
     * @return PluginConfig
     */
    protected function getConfig()
    {
        return $this->pluginConfigService->getPluginConfig($this);
    }
}
