<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Subscriber;

use Doctrine\ORM\Query;
use Enlight\Event\SubscriberInterface;
use NetiTags\Models\Relation as TagRelationModel;
use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\QueryBuilder;

/**
 * Class CustomerSubscriber
 *
 * @package NetiTags\Subscriber
 */
class CustomerSubscriber implements SubscriberInterface
{
    /**
     * @var TableRegistryInterface
     */
    protected $tableRegistry;

    /**
     * CustomerSubscriber constructor.
     *
     * @param TableRegistryInterface $tableRegistry
     */
    public function __construct(
        TableRegistryInterface $tableRegistry
    ) {
        $this->tableRegistry = $tableRegistry;
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
            'Shopware_Controllers_Backend_CustomerQuickView::getListQuery::after'        => 'afterGetListQuery',
            'Shopware_Controllers_Backend_CustomerQuickView::getFilterConditions::after' => 'afterGetFilterConditions',
        );
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function afterGetListQuery(\Enlight_Hook_HookArgs $args)
    {
        $registeredTable = $this->tableRegistry->getByTableName('s_user');
        if (null === $registeredTable) {
            return;
        }

        /**
         * @var QueryBuilder $query
         */
        $query = $args->getReturn();
        $query->leftJoin(
            TagRelationModel::class,
            'tagRelations',
            Query\Expr\Join::WITH,
            'tagRelations.relationId = customer.id 
                AND tagRelations.tableRegistryId = :tableRegistryId'
        );
        $query->setParameter(':tableRegistryId', $registeredTable->getId());

        $args->setReturn($query);
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function afterGetFilterConditions(\Enlight_Hook_HookArgs $args)
    {
        $conditions = $args->getReturn();
        $filters    = $args->get('filters');

        foreach ($filters as $filter) {
            if ('tags' !== $filter['property']) {
                continue;
            }

            $filter['property'] = 'tagRelations.tagId';
            $conditions[]       = $filter;
        }

        $args->setReturn($conditions);
    }
}
