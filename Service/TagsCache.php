<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   shopware_stable
 * @author     hrombach
 */

namespace NetiTags\Service;

use NetiTags\Models\Tag;
use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Components\Model\ModelManager;

class TagsCache
{
    /**
     * @var array
     */
    private static $tagsCache = [];

    /**
     * @var Relation
     */
    private $relationService;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @var RelationCollectorInterface
     */
    private $relationCollector;

    /**
     * TagsCache constructor.
     *
     * @param Relation                   $relationService
     * @param ModelManager               $modelManager
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        Relation $relationService,
        ModelManager $modelManager,
        RelationCollectorInterface $relationCollector
    ) {
        $this->relationService   = $relationService;
        $this->modelManager      = $modelManager;
        $this->relationCollector = $relationCollector;
    }

    public function searchTagsCache(array $ids, $relation)
    {
        if (!isset(self::$tagsCache[$relation])) {
            $this->warmupTagsCache($ids, $relation);
        }

        $relationCache = &self::$tagsCache[$relation];

        $return = [];
        foreach ($ids as $id) {
            if (!isset($relationCache[$id])) {
                $relationCache[$id] = $this->relationService->getTags($relation, $id);
            }

            $return[$id] = $relationCache[$id];
        }

        return $return;
    }

    public function warmupTagsCache(array $ids, $relation)
    {
        $qbr = $this->modelManager->getRepository(Tag::class)->createQueryBuilder('t');
        $qbr->leftJoin('t.relations', 'relations');
        $qbr->leftJoin('relations.tableRegistry', 'tableRegistry');

        $qbr->andWhere(
            $qbr->expr()->in('relations.relationId', $ids),
            $qbr->expr()->eq('tableRegistry.tableName',
                $qbr->expr()->literal($this->relationCollector->getByAlias($relation)->getTableName())
            )
        );

        self::$tagsCache[$relation] = $qbr->getQuery()->getArrayResult();
    }
}