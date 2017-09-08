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
     * @return string
     */
    public function getTableName();

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
}
