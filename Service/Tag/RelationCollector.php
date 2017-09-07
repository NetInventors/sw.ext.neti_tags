<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

use NetiTags\Service\Tag\Relations\RelationsInterface;

/**
 * Class RelationCollector
 *
 * @package NetiTags\Service\Tag
 */
class RelationCollector implements RelationCollectorInterface
{
    /**
     * @var RelationsInterface[]
     */
    private $relations;

    /**
     * @param string $alias
     *
     * @return RelationsInterface
     */
    public function getByAlias($alias)
    {
        if (isset($this->relations[$alias])) {
            return $this->relations[$alias];
        }
    }

    /**
     * @param RelationsInterface $relation
     * @param string             $alias
     */
    public function add(RelationsInterface $relation, $alias)
    {
        $this->relations[$alias] = $relation;
    }
}
