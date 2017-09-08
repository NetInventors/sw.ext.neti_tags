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
use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Detail;

/**
 * Class Article
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Article implements RelationsInterface
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_articles_details';

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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/relations/article')
            ->get('name', 'Article');
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
        $qbr = $this->modelManager->getRepository(Detail::class)->createQueryBuilder('t');

        $qbr->select(array(
            't.id',
            't.number',
            'article.name'
        ));

        $qbr->innerJoin('t.article', 'article');

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
            $value['name'] = sprintf('%s (%s)', $value['name'], $value['number']);

            return $value;
        }, $data);

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
        $repository     = $this->modelManager->getRepository(Detail::class);
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
    public function loadRelation(array $relation)
    {
        $qbr = $this->modelManager->getRepository(Detail::class)->createQueryBuilder('t');

        $qbr->select(array(
            't.id',
            't.number',
            'article.name'
        ));

        $qbr->innerJoin('t.article', 'article');
        $qbr->andWhere(
            $qbr->expr()->eq('t.id', $relation['relationId'])
        );

        $result = $qbr->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (empty($result)) {
            return;
        }

        $result['name'] = sprintf('%s (%s)', $result['name'], $result['number']);

        return $result;
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
            't.number', 'article.name'
        );
        foreach ($fields as $fieldName) {
            $where[] = $fieldName . ' LIKE :search';
        }
        $qbr->andWhere(implode(' OR ', $where));
        $qbr->setParameter('search', '%' . $search . '%');

        return $qbr;
    }
}
