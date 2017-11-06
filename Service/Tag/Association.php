<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

/**
 * Class Association
 *
 * @package NetiTags\Service\Tag
 */
class Association implements AssociationInterface
{
    /**
     * @var RelationCollectorInterface
     */
    private $relationCollector;

    /**
     * Association constructor.
     *
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        RelationCollectorInterface $relationCollector
    ) {
        $this->relationCollector = $relationCollector;
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
        $associationHandler = $this->relationCollector->getByAlias($association);
        if (empty($associationHandler)) {
            return array();
        }

        return $associationHandler->searchAssociation($search, $association, $offset, $limit, $id, $filter, $sort);
    }
}
