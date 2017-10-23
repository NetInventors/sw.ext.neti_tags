<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use NetiTags\Models\Relation;
use NetiTags\Models\TableRegistry;
use NetiTags\Models\Tag;
use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Model\QueryBuilder;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class Customer
 * @package NetiTags\Service\Tag\Relations
 */
class Customer implements RelationsInterface
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_user';

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_user_attributes';

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
     * Customer constructor.
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/customer')
            ->get('name', 'Customer');
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
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
    public function getTableName()
    {
        return self::TABLE_NAME;
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
    public function searchAssociation($search, $association, $offset, $limit, $id = null, $filter = [], $sort = [])
    {
        /** @var QueryBuilder $qbr */
        $qbr = $this->modelManager->getRepository(\Shopware\Models\Customer\Customer::class)->createQueryBuilder('t');

        $qbr->select([
            't.id',
            't.number',
            't.groupKey',
            't.email',
            't.firstname',
            't.lastname',
        ]);

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
        $data      = array_map(function ($value) {
            $value['name'] = sprintf('%s %s (%s)', $value['firstname'], $value['lastname'], $value['number']);

            return $value;
        }, $data);

        return array(
            'success' => true,
            'data'    => $data,
            'total'   => $paginator->count(),
        );
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
        /** @var EntityRepository $repository */
        $repository = $this->modelManager->getRepository(\Shopware\Models\Customer\Customer::class);
        /** @var array $relationModels */
        $relationModels = [];
        /** @var array $relation */
        foreach ($relations as $relation) {
            /** @var \Shopware\Models\Customer\Customer $model */
            $model = $repository->find($relation['id']);
            if (!$model instanceof \Shopware\Models\Customer\Customer) {
                continue;
            }

            /** @var TableRegistry $tableRegistryModel */
            $tableRegistryModel = $this->tableRegistry->getByTableName($this->getTableName());
            if (!$tableRegistryModel instanceof TableRegistry) {
                continue;
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
        /** @var QueryBuilder $qbr */
        $qbr = $this->modelManager->getRepository(\Shopware\Models\Customer\Customer::class)->createQueryBuilder('t');

        $qbr->select([
            't.id',
            't.number',
            't.groupKey',
            't.email',
            't.firstname',
            't.lastname',
        ]);

        $qbr->andWhere(
            $qbr->expr()->eq('t.id', $relation['relationId'])
        );

        $result = $qbr->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (empty($result)) {
            return null;
        }

        $result['name'] = sprintf('%s %s (%s)', $result['firstname'], $result['lastname'], $result['number']);

        return $result;
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
        if (empty($tableRegistryId)) {
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
            return;
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
        $where  = [];
        /** @var array $fields */
        $fields = [
            't.id',
            't.number',
            't.groupKey',
            't.email',
            't.firstname',
            't.lastname',
        ];

        /** @var string $fieldName */
        foreach ($fields as $fieldName) {
            $where[] = $fieldName . ' LIKE :search';
        }
        $qbr->andWhere(implode(' OR ', $where));
        $qbr->setParameter('search', '%' . $search . '%');

        return $qbr;
    }
}
