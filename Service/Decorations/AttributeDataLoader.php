<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Bundle\AttributeBundle\Service\DataLoader as CoreService;
use Shopware\Bundle\AttributeBundle\Service\TableMapping;
use Doctrine\DBAL\Connection;

/**
 * Class AttributeDataLoader
 *
 * @package NetiTags\Service\Decorations
 */
class AttributeDataLoader extends CoreService
{
    /**
     * @var CoreService
     */
    protected $coreService;

    /**
     * @var TableMapping
     */
    protected $mapping;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var RelationCollectorInterface
     */
    protected $relationCollector;

    /**
     * AttributeDataLoader constructor.
     *
     * @param CoreService                $coreService
     * @param TableMapping               $mapping
     * @param Connection                 $connection
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        CoreService $coreService,
        TableMapping $mapping,
        Connection $connection,
        RelationCollectorInterface $relationCollector
    ) {
        parent::__construct($connection, $mapping);
        $this->coreService       = $coreService;
        $this->mapping           = $mapping;
        $this->connection        = $connection;
        $this->relationCollector = $relationCollector;
    }

    /**
     * @param string $table
     * @param string $foreignKey
     *
     * @return array
     */
    public function load($table, $foreignKey)
    {
        $data = $this->coreService->load($table, $foreignKey);

        if (is_array($data)) {
            $this->loadRelations($table, $data, $foreignKey);
        }

        return $data;
    }

    /**
     * @param string $table
     * @param array  $data
     * @param int    $foreignKey
     */
    private function loadRelations($table, array &$data, $foreignKey)
    {
        $relationHandler = $this->relationCollector->getByAttributeTableName($table);
        if (empty($relationHandler)) {
            return;
        }

        $relations = $relationHandler->loadRelation((int) $foreignKey);

        if (empty($relations)) {
            $data['neti_tags'] = array();

            return;
        }

        $data['neti_tags'] = sprintf('|%s|', implode('|', $relations));
    }

    /**
     * @param string $table
     * @param int    $foreignKey
     *
     * @return array[]
     */
    public function loadTranslations($table, $foreignKey)
    {
        return $this->coreService->loadTranslations($table, $foreignKey);
    }

}
