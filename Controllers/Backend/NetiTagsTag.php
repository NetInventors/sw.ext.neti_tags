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
            ->where($this->alias . '.deleted = 0')
            ->andWhere('r.tag = ' . $this->alias . '.id')
        ;

        return $qb;
    }


}
