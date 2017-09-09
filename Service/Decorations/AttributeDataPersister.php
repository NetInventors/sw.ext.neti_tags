<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

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
     * AttributeDataPersister constructor.
     *
     * @param CoreService  $coreService
     * @param Connection   $connection
     * @param TableMapping $mapping
     * @param DataLoader   $dataLoader
     */
    public function __construct(
        CoreService $coreService,
        Connection $connection,
        TableMapping $mapping,
        DataLoader $dataLoader
    ) {
        parent::__construct($connection, $mapping, $dataLoader);
        $this->coreService = $coreService;
        $this->connection  = $connection;
        $this->mapping     = $mapping;
        $this->dataLoader  = $dataLoader;
    }

    /**
     * @param array      $data
     * @param string     $table
     * @param int|string $foreignKey
     */
    public function persist($data, $table, $foreignKey)
    {
        $this->coreService->persist($data, $table, $foreignKey);
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
