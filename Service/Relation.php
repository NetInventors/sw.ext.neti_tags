<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

use NetiTags\Service\Tag\RelationCollectorInterface;

/**
 * Class Relation
 *
 * @package NetiTags\Service
 */
class Relation implements RelationInterface
{
    /**
     * @var RelationCollectorInterface
     */
    protected $relationCollector;

    /**
     * Relation constructor.
     *
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        RelationCollectorInterface $relationCollector
    ) {
        $this->relationCollector = $relationCollector;
    }

    /**
     * @param string $alias
     * @param int    $relationId
     *
     * @return array|null
     */
    public function getTags($alias, $relationId)
    {
        $relationHandler = $this->relationCollector->getByAlias($alias);
        if (null === $relationHandler) {
            return null;
        }

        try {
            $tags = $relationHandler->getTags((int) $relationId);
        } catch (\Exception $e) {
            return null;
        }

        if (empty($tags)) {
            return null;
        }

        return $tags;
    }
}
