<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Bundle\AttributeBundle\Service\DataLoader;
use Shopware\Bundle\AttributeBundle\Service\DataPersister as CoreService;
use Shopware\Bundle\AttributeBundle\Service\TableMapping;
use Doctrine\DBAL\Connection;

/**
 * Class AttributeDataPersister
 *
 * @package NetiTags\Service\Decorations
 */
class AttributeDataPersister extends CoreService
{
    /**
     * @var CoreService
     */
    protected $coreService;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var TableMapping
     */
    protected $mapping;

    /**
     * @var DataLoader
     */
    protected $dataLoader;

    /**
     * @var RelationCollectorInterface
     */
    protected $relationCollector;

    /**
     * AttributeDataPersister constructor.
     *
     * @param CoreService                $coreService
     * @param Connection                 $connection
     * @param TableMapping               $mapping
     * @param DataLoader                 $dataLoader
     * @param RelationCollectorInterface $relationCollector
     */
    public function __construct(
        CoreService $coreService,
        Connection $connection,
        TableMapping $mapping,
        DataLoader $dataLoader,
        RelationCollectorInterface $relationCollector
    ) {
        parent::__construct($connection, $mapping, $dataLoader);
        $this->coreService       = $coreService;
        $this->connection        = $connection;
        $this->mapping           = $mapping;
        $this->dataLoader        = $dataLoader;
        $this->relationCollector = $relationCollector;
    }

    /**
     * @param array      $data
     * @param string     $table
     * @param int|string $foreignKey
     */
    public function persist($data, $table, $foreignKey)
    {
        $this->persistRelations($data, $table, $foreignKey);

        $this->coreService->persist($data, $table, $foreignKey);
    }

    /**
     * @param array  $data
     * @param string $table
     * @param int    $foreignKey
     */
    private function persistRelations(array &$data, $table, $foreignKey)
    {
        $relationHandler = $this->relationCollector->getByAttributeTableName($table);
        if (empty($relationHandler)) {
            return;
        }

        $tags = $data['neti_tags'];
        if (! is_array($tags)) {
            $tags = explode('|', trim($tags, '|'));
        }

        $tags = array_map('intval', $tags);

        $relationHandler->persistRelations($tags, (int) $foreignKey);
    }

    /**
     * @param string $table
     * @param int    $sourceForeignKey
     * @param int    $targetForeignKey
     */
    public function cloneAttribute($table, $sourceForeignKey, $targetForeignKey)
    {
        $this->coreService->cloneAttribute($table, $sourceForeignKey, $targetForeignKey);
    }

    /**
     * @param string $table
     * @param int    $sourceForeignKey
     * @param int    $targetForeignKey
     */
    public function cloneAttributeTranslations($table, $sourceForeignKey, $targetForeignKey)
    {
        $this->coreService->cloneAttributeTranslations($table, $sourceForeignKey, $targetForeignKey);
    }
}
