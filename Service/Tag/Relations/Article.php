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
     * @var ModelManager
     */
    private $modelManager;

    /**
     * Article constructor.
     *
     * @param ModelManager $modelManager
     */
    public function __construct(
        ModelManager $modelManager
    ) {
        $this->modelManager = $modelManager;
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
