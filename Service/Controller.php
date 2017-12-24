<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

use NetiTags\Service\Tag\RelationCollectorInterface;

/**
 * Class Controller
 *
 * @package NetiTags\Service
 */
class Controller implements ControllerInterface
{
    /**
     * @var RelationCollectorInterface
     */
    protected $relationCollector;

    /**
     * Controller constructor.
     *
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        RelationCollectorInterface $relationCollector
    ) {
        $this->relationCollector = $relationCollector;
    }

    /**
     * @param int    $id
     * @param string $tableName
     *
     * @return string|null
     */
    public function loadRelations($id, $tableName)
    {
        $relationHandler = $this->relationCollector->getByTableName(
            $tableName
        );

        if (empty($relationHandler)) {
            return;
        }

        $relations = $relationHandler->loadRelation($id);
        if (empty($relations)) {
            return;
        }

        return sprintf('|%s|', implode('|', $relations));
    }

    /**
     * @param int          $id
     * @param string       $table
     * @param array|string $tags
     */
    public function persistRelations($id, $table, $tags)
    {
        $relationHandler = $this->relationCollector->getByTableName($table);
        if (empty($relationHandler)) {
            return;
        }

        if (! is_array($tags)) {
            $tags = explode('|', trim($tags, '|'));
        }

        $tags = array_map('intval', $tags);

        $relationHandler->persistRelations($tags, (int) $id);
    }
}
