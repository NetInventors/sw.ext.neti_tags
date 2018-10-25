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

        $qbr = $this->modelManager->getRepository(Tag::class)
                                  ->createQueryBuilder('t');
        $qbr->leftJoin('t.relations', 'relations')
            ->leftJoin('relations.tableRegistry', 'tableRegistry')
            ->andWhere(
                $qbr->expr()->in('relations.relationId', ':ids'),
                $qbr->expr()->eq('tableRegistry.tableName',
                    $qbr->expr()->literal($this->relationCollector->getByAlias($relation)->getTableName())
                )
            )->setParameter('ids', $ids);

        self::$tagsCache[$relation] = \array_merge(self::$tagsCache[$relation], $qbr->getQuery()->getArrayResult());
    }
}