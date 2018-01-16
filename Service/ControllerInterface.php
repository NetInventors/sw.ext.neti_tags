<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

/**
 * Interface ControllerInterface
 *
 * @package NetiTags\Service
 */
interface ControllerInterface
{
    /**
     * @param int    $id
     * @param string $tableName
     *
     * @return string|null
     */
    public function loadRelations($id, $tableName);

    /**
     * @param int          $id
     * @param string       $table
     * @param array|string $tags
     */
    public function persistRelations($id, $table, $tags);
}
