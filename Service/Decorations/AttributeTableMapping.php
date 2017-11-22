<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use Doctrine\DBAL\Connection;
use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Bundle\AttributeBundle\Service\TableMapping as CoreService;

/**
 * Class AttributeTableMapping
 *
 * @package NetiTags\Service\Decorations
 */
class AttributeTableMapping extends CoreService
{
    /**
     * @var array
     */
    private $tables;

    /**
     * @var RelationCollectorInterface
     */
    private $relationCollector;

    /**
     * @var CoreService
     */
    private $coreService;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * AttributeTableMapping constructor.
     *
     * @param CoreService                $coreService
     * @param Connection                 $connection
     * @param RelationCollectorInterface $relationCollector
     * @param                            $pathToTableEntityMappingFile
     */
    public function __construct(
        CoreService $coreService,
        Connection $connection,
        RelationCollectorInterface $relationCollector,
        $pathToTableEntityMappingFile
    ) {
        parent::__construct($connection);
        $this->coreService       = $coreService;
        $this->connection        = $connection;
        $this->relationCollector = $relationCollector;
        $this->tables            = include $pathToTableEntityMappingFile;

        $this->addTables();
    }

    /**
     * @param $table
     *
     * @return string
     */
    public function getTableForeignKey($table)
    {
        $result = parent::getTableForeignKey($table);
        if (empty($result)) {
            $this->addTables();

            $result = $this->tables[$table]['foreignKey'];
        }

        return $result;
    }

    /**
     * @param string $table
     * @param string $name
     *
     * @return bool
     * @throws \Exception
     */
    public function isIdentifierColumn($table, $name)
    {
        try {
            return parent::isIdentifierColumn($table, $name);
        } catch (\Exception $e) {
            $this->addTables();

            if (! array_key_exists($table, $this->tables)) {
                throw new \Exception(sprintf("Table %s is no attribute table", $table));
            }
            $config      = $this->tables[$table];
            $identifiers = isset($config['identifiers']) ? $config['identifiers'] : [];
            $columns     = array_map('strtolower', $identifiers);

            return in_array(strtolower($name), $columns);
        }
    }

    /**
     * @param string $table
     * @param string $name
     *
     * @return bool
     * @throws \Exception
     */
    public function isCoreColumn($table, $name)
    {
        try {
            return parent::isCoreColumn($table, $name);
        } catch (\Exception $e) {
            $this->addTables();

            if (! array_key_exists($table, $this->tables)) {
                throw new \Exception(sprintf("Table %s is no attribute table", $table));
            }
            $config         = $this->tables[$table];
            $coreAttributes = isset($config['coreAttributes']) ? $config['coreAttributes'] : [];
            $columns        = array_map('strtolower', $coreAttributes);

            return in_array(strtolower($name), $columns);
        }
    }

    /**
     * @param $table
     *
     * @return null|string
     */
    public function getTableModel($table)
    {
        $result = parent::getTableModel($table);
        if (empty($result)) {
            $this->addTables();

            if (! array_key_exists($table, $this->tables)) {
                return null;
            }

            $result = $this->tables[$table]['model'];
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getAttributeTables()
    {
        $this->addTables();

        return array_filter($this->tables, function ($table) {
            return ! $table['readOnly'];
        });
    }

    /**
     * @param string $table
     *
     * @return bool
     */
    public function isAttributeTable($table)
    {
        $result = parent::isAttributeTable($table);
        if (true !== $result) {
            $this->addTables();

            $result = array_key_exists($table, $this->tables);
        }

        return $result;
    }

    /**
     * @param string $table
     *
     * @return array
     * @throws \Exception
     */
    public function getDependingTables($table)
    {
        try {
            return parent::getDependingTables($table);
        } catch (\Exception $e) {
            $this->addTables();

            if (! $this->isAttributeTable($table)) {
                throw new \Exception(sprintf("Table %s is no supported attribute table"));
            }

            return $this->tables[$table]['dependingTables'];
        }
    }

    /**
     *
     */
    private function addTables()
    {
        foreach ($this->relationCollector->getAll() as $relation) {
            $attributeTableName = $relation->getAttributeTableName();
            if (empty($attributeTableName)) {
                continue;
            }

            if (isset($this->tables[$attributeTableName])) {
                continue;
            }

            $this->tables[$attributeTableName] = $relation->getAttributeTableConfig();
        }
    }
}
