<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Models\Tag;
use NetiTags\Service\Tag\RelationCollectorInterface;
use Shopware\Bundle\AttributeBundle\Service\DataLoader as CoreService;
use Shopware\Bundle\AttributeBundle\Service\TableMapping;
use Doctrine\DBAL\Connection;
use Shopware\Components\Model\ModelManager;

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
     * @var ModelManager
     */
    protected $em;

    /**
     * AttributeDataLoader constructor.
     *
     * @param CoreService $coreService
     * @param TableMapping $mapping
     * @param Connection $connection
     * @param RelationCollectorInterface $relationCollector
     * @param ModelManager $em
     */
    public function __construct(
        CoreService $coreService,
        TableMapping $mapping,
        Connection $connection,
        RelationCollectorInterface $relationCollector,
        ModelManager $em
    ) {
        parent::__construct($connection, $mapping);
        $this->coreService       = $coreService;
        $this->mapping           = $mapping;
        $this->connection        = $connection;
        $this->relationCollector = $relationCollector;
        $this->em                = $em;
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

        if (is_array($data) && !empty($data)) {
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
            $data['neti_tags_formated'] = array();

            return;
        }

        $data['neti_tags'] = sprintf('|%s|', implode('|', $relations));
        $data['neti_tags_formated'] = $this->loadRelationData($relations);
    }

    private function loadRelationData($tagIds)
    {
        $qb   = $this->em->getRepository(Tag::class)->createQueryBuilder('t');
        return $qb->select(['t.id', 't.title', 't.description'])
            ->where($qb->expr()->in('t.id', ':ids'))
            ->setParameter('ids', $tagIds)
            ->getQuery()->getArrayResult();
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
