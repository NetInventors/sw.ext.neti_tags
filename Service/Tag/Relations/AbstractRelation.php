<?php
/**
 * @category NetiTags
 * @author   bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Model\QueryBuilder;
use NetiTags\Models\Tag;
use NetiTags\Models\Relation;
use NetiTags\Models\TableRegistry;
use Shopware\Components\Api\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class AbstractRelation
 *
 * @package NetiTags\Service\Tag\Relations
 */
abstract class AbstractRelation implements RelationsInterface
{
    const TABLE_NAME = '';
    const ENTITY_NAME = '';
    const ATTRIBUTE_TABLE_NAME = '';
    const ATTRIBUTE_ENTITY_NAME = '';
    const ENTITY_FIELDS = array();

    /**
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * @var TableRegistryInterface
     */
    protected $tableRegistry;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return self
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    public function getEntityName()
    {
        return static::ENTITY_NAME;
    }

    public function getAttributeTableName()
    {
        return static::ATTRIBUTE_TABLE_NAME;
    }

    /**
     * @return array
     */
    public function getAttributeTableConfig()
    {
        return array();
    }

    /**
     * @param array $value
     *
     * @return array
     */
    protected function transformData(array $value)
    {
        return $value;
    }

    /**
     * @param string $search
     * @param string $association
     * @param int    $offset
     * @param int    $limit
     * @param null   $id
     * @param array  $filter
     * @param array  $sort
     *
     * @return array
     */
    public function searchAssociation(
        $search,
        $association,
        $offset,
        $limit,
        $id = null,
        $filter = [],
        $sort = []
    ) {
        $qbr = $this->buildQuery();

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
        $data      = array_map(array($this, 'transformData'), $data);

        return array(
            'success' => true,
            'data'    => $data,
            'total'   => $paginator->count(),
        );
    }

    /**
     * @return QueryBuilder
     */
    protected function buildQuery() {
        /** @var QueryBuilder $qbr */
        $qbr = $this->modelManager->getRepository(static::ENTITY_NAME)->createQueryBuilder('t');

        $qbr->select(static::ENTITY_FIELDS);

        return $qbr;
    }

    /**
     * @param array $relations
     *
     * @return Relation[]
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws ValidationException
     */
    public function resolveRelations(array $relations)
    {
        $attributeClassName = static::ATTRIBUTE_ENTITY_NAME;

        /** @var EntityRepository $repository */
        $repository = $this->modelManager->getRepository(static::ENTITY_NAME);
        /** @var array $relationModels */
        $relationModels = [];
        /** @var array $relation */
        foreach ($relations as $relation) {
            /** @var \Shopware\Models\Order\Order $model */
            $model = $repository->find($relation['id']);
            if (null === $model) {
                continue;
            }

            /** @var TableRegistry $tableRegistryModel */
            $tableRegistryModel = $this->tableRegistry->getByTableName($this->getTableName());
            if (!$tableRegistryModel instanceof TableRegistry) {
                continue;
            }

            if (null === $model->getAttribute()) {
                $attribute = new $attributeClassName();
                $this->modelManager->persist($attribute);
                $model->setAttribute($attribute);
            }

            /** @var Relation $relationModel */
            $relationModel = new Relation();
            $relationModel->setRelationId($model->getId())
                ->setTableRegistry($tableRegistryModel);

            /** @var ConstraintViolationListInterface $violations */
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fetchRelations(array $relation)
    {
        $qbr = $this->buildQuery();

        $qbr->andWhere(
            $qbr->expr()->eq('t.id', $relation['relationId'])
        );

        $result = $qbr->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (empty($result)) {
            return null;
        }

        return $this->transformData($result);
    }

    /**
     * @param int $relationId
     *
     * @return array|null
     */
    public function loadRelation($relationId)
    {
        /** @var QueryBuilder $qbr */
        $qbr = $this->getTagsQuery($relationId);
        $qbr->select([
            't.id',
        ]);

        /** @var array $results */
        $results = $qbr->getQuery()->getArrayResult();

        return array_column($results, 'id');
    }

    /**
     * @param array $data
     * @param int   $relationId
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistRelations(array $data, $relationId)
    {
        $tableRegistryId = $this->getTableRegistrationIdForTable();
        if (0 === $tableRegistryId) {
            return;
        }

        /** @var QueryBuilder $qbr */
        $qbr = $this->modelManager->getRepository(Relation::class)->createQueryBuilder('t');
        $qbr->delete();
        $qbr->andWhere(
            $qbr->expr()->eq('t.relationId', $relationId),
            $qbr->expr()->eq('t.tableRegistryId', $tableRegistryId)
        );

        $qbr->getQuery()->execute();

        /** @var array $relations */
        $relations = [];
        foreach ($data as $tagId) {
            /** @var Relation $relation */
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

        /** @var QueryBuilder $qbr */
        $qbr = $this->getTagsQuery($relationId);
        $qbr->select(array(
            't.id',
            't.title',
        ));

        try {
            $results = $qbr->getQuery()->getArrayResult();
        } catch (\Exception $e) {
            $results = null;
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
        /** @var QueryBuilder $qbr */
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    private function getTableRegistrationIdForTable()
    {
        /** @var QueryBuilder $qbr */
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
        /** @var \Doctrine\ORM\Query $query */
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
        /** @var array $where */
        $where = [];

        foreach (static::ENTITY_FIELDS as $fieldName) {
            if(false !== strpos($fieldName, ' AS ')) {
                $fieldName = explode(' AS ', $fieldName);
                $fieldName = reset($fieldName);
            }

            $where[] = $fieldName . ' LIKE :search';
        }
        $qbr->andWhere(implode(' OR ', $where));
        $qbr->setParameter('search', '%' . $search . '%');

        return $qbr;
    }

    /**
     * @return string
     */
    abstract public function getName();
}
