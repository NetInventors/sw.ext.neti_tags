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
     * @return \Shopware\Components\Model\QueryBuilder
     */
    protected function getListQuery()
    {
        /** @var \Shopware\Components\Model\QueryBuilder $qb */
        $qb = parent::getListQuery();

        $qb->select()
            ->leftJoin($this->alias . '.relations', 'r')
            ->where($this->alias . '.deleted = 0');

        return $qb;
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
                'message' => $e->getMessage()
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
