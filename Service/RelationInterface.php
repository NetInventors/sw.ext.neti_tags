<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

/**
 * Interface RelationInterface
 *
 * @package NetiTags\Service
 */
interface RelationInterface
{
    /**
     * @param string $alias
     * @param int    $relationId
     *
     * @return array|null
     */
    public function getTags($alias, $relationId);
}
