<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use NetiTags\Models\Relation;
use NetiTags\Models\TableRegistry;
use NetiTags\Models\Tag;
use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Detail;
use Shopware\Models\Attribute\Article as DetailAttribute;

/**
 * Class ProductStream
 *
 * @package NetiTags\Service\Tag\Relations
 */
class ProductStream implements RelationsInterface
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_product_streams';

    /**
     * @var string
     */
    const ENTITY_NAME = 'Shopware\Models\ProductStream\ProductStream';

    /**
     * @var  string
     */
    const ATTRIBUTE_TABLE_NAME = 's_product_streams_attributes';

    /**
     * @var string
     */
    private $alias;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @var \Enlight_Components_Snippet_Manager
     */
    protected $snippets;

    /**
     * @var TableRegistryInterface
     */
    protected $tableRegistry;

    /**
     * Article constructor.
     *
     * @param ModelManager                        $modelManager
     * @param \Enlight_Components_Snippet_Manager $snippets
     * @param TableRegistryInterface              $tableRegistry
     */
    public function __construct(
        ModelManager $modelManager,
        \Enlight_Components_Snippet_Manager $snippets,
        TableRegistryInterface $tableRegistry
    ) {
        $this->modelManager  = $modelManager;
        $this->snippets      = $snippets;
        $this->tableRegistry = $tableRegistry;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/product_stream')
            ->get('name', 'Product stream');
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return self::ENTITY_NAME;
    }

    public function getAttributeTableName()
    {
        return self::ATTRIBUTE_TABLE_NAME;
    }

    /**
     * @return array
     */
    public function getAttributeTableConfig()
    {
        return array();
    }

    /**
     * @param string      $search
     * @param string      $association
     * @param int         $offset
     * @param int         $limit
     * @param string|null $id
     * @param array       $filter
     * @param array       $sort
     *
     * @return array
     */
    public function searchAssociation($search, $association, $offset, $limit, $id = null, $filter = [], $sort = [])
    {
        $qbr = $this->modelManager->getRepository(\Shopware\Models\ProductStream\ProductStream::class)->createQueryBuilder('t');

        $qbr->select(array(
            't.id',
            't.name',
        ));

        if (! empty($search)) {
            $this->addSearch($search, $qbr);
        }

        if (! empty($filter) && $id === null) {
            $qbr->addFilter($filter);
        }

        if (! empty($sort) && $id === null) {
            $qbr->addOrderBy($sort);
        }

        $qbr->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = $this->getQueryPaginator($qbr);
        $data      = $paginator->getIterator()->getArrayCopy();

        return array(
            'success' => true,
            'data'    => $data,
            'total'   => $paginator->count()
        );
    }

    /**
     * @param array $relations
     *
     * @return Relation[]
     * @throws ValidationException
     */
    public function resolveRelations(array $relations)
    {
        $repository     = $this->modelManager->getRepository(\Shopware\Models\ProductStream\ProductStream::class);
        $relationModels = array();
        foreach ($relations as $relation) {
            $model = $repository->find($relation['id']);
            if (empty($model)) {
                continue;
            }

            $tableRegistryModel = $this->tableRegistry->getByTableName($this->getTableName());
            if (empty($tableRegistryModel)) {
                continue;
            }

            if (empty($model->getAttribute())) {
                $attribute = new \Shopware\Models\Attribute\ProductStream();
                $this->modelManager->persist($attribute);
                $model->setAttribute($attribute);
            }

            $relationModel = new Relation();
            $relationModel->setRelationId($model->getId())
                ->setTableRegistry($tableRegistryModel);

            $violations = $this->modelManager->validate($model);
            if ((bool) $violations->count()) {
                throw new ValidationException($violations);
            }

            $this->modelManager->persist($relationModel);

            $relationModels[] = $relationModel;
        }

        return $relationModels;
    }

    /**
     * @param array $relation
     *
     * @return array|null
     */
    public function fetchRelations(array $relation)
    {
        $qbr = $this->modelManager->getRepository(\Shopware\Models\ProductStream\ProductStream::class)->createQueryBuilder('t');

        $qbr->select(array(
            't.id',
            't.name',
        ));

        $qbr->andWhere(
            $qbr->expr()->eq('t.id', $relation['relationId'])
        );

        $result = $qbr->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (empty($result)) {
            return;
        }

        return $result;
    }

    /**
     * @param int $relationId
     *
     * @return array
     */
    public function loadRelation($relationId)
    {
        $qbr = $this->getTagsQuery($relationId);
        $qbr->select(array(
            't.id'
        ));

        $results = $qbr->getQuery()->getArrayResult();

        return array_column($results, 'id');
    }

    /**
     * @param array $data
     * @param int   $relationId
     */
    public function persistRelations(array $data, $relationId)
    {
        $tableRegistryId = $this->getTableRegistrationIdForTable();
        if (empty($tableRegistryId)) {
            return;
        }

        $qbr = $this->modelManager->getRepository(Relation::class)->createQueryBuilder('t');
        $qbr->delete();
        $qbr->andWhere(
            $qbr->expr()->eq('t.relationId', $relationId),
            $qbr->expr()->eq('t.tableRegistryId', $tableRegistryId)
        );

        $qbr->getQuery()->execute();

        $relations = array();
        foreach ($data as $tagId) {
            $relation = new Relation();
            $relation->setRelationId($relationId)
                ->setTagId($tagId)
                ->setTableRegistryId($tableRegistryId);
            $this->modelManager->persist($relation);

            $relations[] = $relation;
        }

        $this->modelManager->flush($relations);
    }

    /**
     * @param int $relationId
     *
     * @return array|null
     */
    public function getTags($relationId)
    {
        $tableRegistryId = $this->getTableRegistrationIdForTable();
        if (empty($tableRegistryId)) {
            return null;
        }

        $qbr = $this->getTagsQuery($relationId);
        $qbr->select(array(
            't.id',
            't.title'
        ));

        try {
            $results = $qbr->getQuery()->getArrayResult();
        } catch (\Exception $e) {
            return null;
        }

        if (empty($results)) {
            return null;
        }

        return $results;
    }

    /**
     * @param int $relationId
     *
     * @return QueryBuilder
     */
    private function getTagsQuery($relationId)
    {
        $qbr = $this->modelManager->getRepository(Tag::class)->createQueryBuilder('t');
        $qbr->leftJoin('t.relations', 'relations');
        $qbr->leftJoin('relations.tableRegistry', 'tableRegistry');

        $qbr->andWhere(
            $qbr->expr()->eq('relations.relationId', $relationId),
            $qbr->expr()->eq('tableRegistry.tableName', $qbr->expr()->literal($this->getTableName()))
        );

        return $qbr;
    }

    /**
     * @return int
     */
    private function getTableRegistrationIdForTable()
    {
        $qbr = $this->modelManager->getRepository(TableRegistry::class)->createQueryBuilder('t');
        $qbr->select('t.id');
        $qbr->andWhere(
            $qbr->expr()->eq('t.tableName', $qbr->expr()->literal($this->getTableName()))
        );

        return (int) $qbr->getQuery()->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $builder
     * @param int          $hydrationMode
     *
     * @return Paginator
     */
    private function getQueryPaginator(
        QueryBuilder $builder,
        $hydrationMode = AbstractQuery::HYDRATE_ARRAY
    ) {
        $query = $builder->getQuery();
        $query->setHydrationMode($hydrationMode);

        return $this->modelManager->createPaginator($query);
    }

    /**
     * @param string       $search
     * @param QueryBuilder $qbr
     *
     * @return QueryBuilder
     */
    private function addSearch($search, QueryBuilder $qbr)
    {
        $where  = array();
        $fields = array(
            't.name'
        );
        foreach ($fields as $fieldName) {
            $where[] = $fieldName . ' LIKE :search';
        }
        $qbr->andWhere(implode(' OR ', $where));
        $qbr->setParameter('search', '%' . $search . '%');

        return $qbr;
    }
}
