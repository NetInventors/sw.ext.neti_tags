<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   shopware_stable
 * @author     hrombach
 */

namespace NetiTags\Service;

use NetiTags\Models\Relation as TagRelation;
use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Components\Model\ModelManager;

class TagsCache
{
    /**
     * @var array
     */
    private static $tagsCache = [];

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
     * @param ModelManager               $modelManager
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        ModelManager $modelManager,
        RelationCollectorInterface $relationCollector
    ) {
        $this->modelManager      = $modelManager;
        $this->relationCollector = $relationCollector;
    }

    /**
     * @param array  $ids
     * @param string $relation
     *
     * @return array
     */
    public function searchTagsCache(array $ids, $relation)
    {
        if (\count($ids) < 1) {
            return [];
        }

        $warmedUp = false;
        if (!isset(self::$tagsCache[$relation])) {
            $warmedUp = true;
            $this->warmupTagsCache($ids, $relation);
        }

        $relationCache = &self::$tagsCache[$relation];

        if (
            !$warmedUp
            && \count($newIds = \array_diff($ids, \array_keys($relationCache))) > 0
        ) {
            $this->warmupTagsCache($newIds, $relation);
        }

        $return = \array_intersect_key($relationCache, \array_flip($ids));

        /**
         * @TODO: I'm unsure about removing this.
         * On one hand, it changes the structure of the returned array in some
         * cases, which is dirty. On the other hand, removing it will break
         * usages that rely on this behavior and I have no idea where those might be.
         */
        if (\count($return) === 1) {
            $return = (array)\reset($return);
        }

        return $return;
    }

    public function warmupTagsCache(array $ids, $relation)
    {
        if (\count($ids) < 1) {
            return;
        }

        if (!isset(self::$tagsCache[$relation])) {
            self::$tagsCache[$relation] = [];
        }

        $qbr = $this->modelManager->getRepository(TagRelation::class)
                                  ->createQueryBuilder('r')
                                  ->select('t.title', 't.id', 'r.relationId');
        $qbr->leftJoin('r.tag', 't')
            ->leftJoin('r.tableRegistry', 'tr')
            ->where(
                $qbr->expr()->in('r.relationId', ':ids'),
                $qbr->expr()->eq('tr.tableName',
                    $qbr->expr()->literal($this->relationCollector->getByAlias($relation)->getTableName())
                )
            )->setParameter('ids', $ids);

        $tagsCache = &self::$tagsCache[$relation];
        foreach ($qbr->getQuery()->getArrayResult() as $item) {
            $relationId = $item['relationId'];
            unset($item['relationId']);
            $tagsCache[$relationId][] = $item;
        }
    }
}