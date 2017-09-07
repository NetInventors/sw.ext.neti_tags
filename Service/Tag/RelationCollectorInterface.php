<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

use NetiTags\Service\Tag\Relations\RelationsInterface;

/**
 * Interface RelationCollectorInterface
 *
 * @package NetiTags\Service\Tag
 */
interface RelationCollectorInterface
{
    /**
     * @param string $alias
     *
     * @return RelationsInterface
     */
    public function getByAlias($alias);

    /**
     * @param RelationsInterface $relation
     * @param string             $alias
     */
    public function add(RelationsInterface $relation, $alias);
}
