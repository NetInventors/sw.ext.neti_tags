<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Models\Relation;

/**
 * Interface AssociationsInterface
 *
 * @package NetiTags\Service\Tag\Associations
 */
interface RelationsInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $alias
     *
     * @return self
     */
    public function setAlias($alias);

    /**
     * @return string
     */
    public function getAlias();

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return string
     */
    public function getEntityName();

    /**
     * @return string
     */
    public function getAttributeTableName();

    /**
     * @return array
     */
    public function getAttributeTableConfig();

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
    public function searchAssociation($search, $association, $offset, $limit, $id = null, $filter = [], $sort = []);

    /**
     * @param array $relations
     *
     * @return Relation[]
     */
    public function resolveRelations(array $relations);

    /**
     * @param array $relation
     *
     * @return array|null
     */
    public function fetchRelations(array $relation);

    /**
     * @param int $relationId
     *
     * @return array|null
     */
    public function loadRelation($relationId);

    /**
     * @param array $data
     * @param int   $relationId
     */
    public function persistRelations(array $data, $relationId);

    /**
     * @param int $relationId
     *
     * @return array|null
     */
    public function getTags($relationId);
}
