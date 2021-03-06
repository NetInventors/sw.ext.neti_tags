<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

class Shopware_Controllers_Backend_NetiTagsTag
    extends \NetiFoundation\Controllers\Backend\AbstractBackendApplicationController
{
    /**
     * @var string
     */
    protected $model = 'NetiTags\Models\Tag';

    /**
     * @param string|null $id
     *
     * @return array
     */
    public function delete($id)
    {
        try {
            return parent::delete($id);
        } catch (\Exception $exception) {
            return ['success' => false, 'error' => $exception->getMessage()];
        }
    }

    /**
     * @return \Shopware\Components\Model\QueryBuilder
     */
    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->select(array(
            $this->alias . '.id',
            $this->alias . '.title',
            $this->alias . '.enabled',
            'COUNT(relations) as relationCount'
        ));
        $builder->groupBy($this->alias . '.id');
        $builder->leftJoin($this->alias . '.relations', 'relations');

        return $builder;
    }

    /**
     * @param int $id
     *
     * @return \Doctrine\ORM\QueryBuilder|\Shopware\Components\Model\QueryBuilder
     */
    protected function getDetailQuery($id)
    {
        $qbr = parent::getDetailQuery($id);

        $qbr->addSelect(array('relations', 'tableRegistry'));
        $qbr->leftJoin($this->alias . '.relations', 'relations');
        $qbr->leftJoin('relations.tableRegistry', 'tableRegistry');

        return $qbr;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws Exception
     */
    protected function getAdditionalDetailData(array $data)
    {
        $data              = parent::getAdditionalDetailData($data);
        $data['relations'] = $this->fetchRelations($data['relations']);

        return $data;
    }

    /**
     * @param array $relations
     *
     * @return array
     * @throws Exception
     */
    private function fetchRelations($relations)
    {
        $result            = array();
        $relationCollector = $this->container->get('neti_tags.service.tag.relation_collector');
        foreach ($relations as $value) {
            $relationHandler = $relationCollector->getByTableName($value['tableRegistry']['tableName']);
            if (empty($relationHandler)) {
                continue;
            }

            $alias = $relationHandler->getAlias();
            if (! isset($result[$alias])) {
                $result[$alias] = array();
            }

            $relation = $relationHandler->fetchRelations($value);
            if (empty($relation)) {
                continue;
            }

            $result[$alias][] = $relation;
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws Exception
     */
    protected function resolveExtJsData($data)
    {
        $data              = parent::resolveExtJsData($data);
        $relationCollector = $this->container->get('neti_tags.service.tag.relation_collector');
        if (isset($data['relations'])) {
            $allRelations = array();
            foreach ($data['relations'] as $alias => &$relations) {
                if (empty($relations)) {
                    continue;
                }

                $relationHandler = $relationCollector->getByAlias($alias);
                if (empty($relationHandler)) {
                    continue;
                }

                $allRelations = array_merge($allRelations, $relationHandler->resolveRelations($relations));
            }

            $data['relations'] = $allRelations;
        }

        return $data;
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
        try {
            $result = $this->container->get('neti_tags.service.tag.association')->searchAssociation(
                $search,
                $association,
                $offset,
                $limit,
                $id,
                $filter,
                $sort
            );
        } catch (\Exception $e) {
            $result = array(
                'success' => false,
                'error'   => $e->getMessage()
            );
        }

        if (empty($result)) {
            $result = parent::searchAssociation(
                $search,
                $association,
                $offset,
                $limit,
                $id,
                $filter,
                $sort
            );
        }

        return $result;
    }

}
