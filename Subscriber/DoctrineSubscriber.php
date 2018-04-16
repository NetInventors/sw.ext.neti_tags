<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Subscriber;

use Doctrine\Common\EventSubscriber as BaseEventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use NetiTags\Models\Tag;
use Shopware\Components\ContainerAwareEventManager;

/**
 * Class DoctrineSubscriber
 *
 * @package NetiTags\Subscriber
 */
class DoctrineSubscriber implements BaseEventSubscriber
{
    /**
     * @var ContainerAwareEventManager
     */
    protected $eventManager;

    /**
     * EventSubscriber constructor.
     *
     * @param ContainerAwareEventManager $eventManager
     */
    public function __construct(
        ContainerAwareEventManager $eventManager
    ) {
        $this->eventManager = $eventManager;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::preRemove,
        );
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof Tag) {
            $this->onRemoveTag($entity, $eventArgs);
        }
    }

    /**
     * @param Tag                $entity
     * @param LifecycleEventArgs $eventArgs
     */
    private function onRemoveTag(Tag $entity, LifecycleEventArgs $eventArgs)
    {
        $this->eventManager->notify(
            'NetiTags_Tag_Remove',
            array(
                'tag' => $entity,
            )
        );
    }
}
