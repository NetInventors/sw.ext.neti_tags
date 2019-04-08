<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   shopware_stable
 * @author     hrombach
 */

namespace NetiTags\Service;

use Doctrine\ORM\AbstractQuery;
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

        // TODO: Warmup runs only once per relation, disregarding the IDs. This can be heavily improved.
        if (!isset(self::$tagsCache[$relation])) {
            // $this->warmupTagsCache($ids, $relation);
            $this->warmupTagsCacheHotfix($ids, $relation);
        }

        $relationCache = &self::$tagsCache[$relation];

        $return = [];
        // Previously the keys in the $relationCache variable have been incremental, so the isset made no sense.
        // This is why we have adjusted the warmupTagsCache function.
        // TODO: Move this into warmup or find a different solution, so that not every single ID is being fetched seperately.
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

    public function warmupTagsCacheHotfix(array $ids, $relation)
    {
        if (\count($ids) < 1) {
            return;
        }

        if (!isset(self::$tagsCache[$relation])) {
            self::$tagsCache[$relation] = [];
        }

        $qbr = $this->modelManager->getRepository(Tag::class)
                                  ->createQueryBuilder('t')->addSelect('relations.relationId');
        $qbr->leftJoin('t.relations', 'relations')
            ->leftJoin('relations.tableRegistry', 'tableRegistry')
            ->andWhere(
                $qbr->expr()->in('relations.relationId', ':ids'),
                $qbr->expr()->eq('tableRegistry.tableName',
                    $qbr->expr()->literal($this->relationCollector->getByAlias($relation)->getTableName())
                )
            )->setParameter('ids', $ids);

        foreach ($qbr->getQuery()->getArrayResult() as $item) {
            $relationId = $item['relationId'];
            unset($item['relationId']);
            // TODO: This way is not the nicest one, please find a better solution.
            // @see custom/plugins/NetiTags/Service/Tag/Relations/Article.php:331 and so on
            self::$tagsCache[$relation][$relationId][] = [
                'id' => $item[0]['id'],
                'title' => $item[0]['title'],
            ];
        }
    }
}