<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Site\Group;
use Shopware\Models\Attribute\Site as SiteAttribute;
use Shopware\Models\Site\Site;

/**
 * Class Cms
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Cms extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_cms_static';

    /**
     * @var string
     */
    const ENTITY_NAME = Site::class;

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_cms_static_attributes';

    /**
     * @var string
     */
    const ATTRIBUTE_ENTITY_NAME = SiteAttribute::class;

    /**
     *
     */
    const ENTITY_FIELDS = array(
        't.id',
        't.description',
        't.grouping',
    );

    /**
     * @var \Enlight_Components_Snippet_Manager
     */
    protected $snippets;

    /**
     * Customer constructor.
     *
     * @param ModelManager                        $modelManager
     * @param \Enlight_Components_Snippet_Manager $snippets
     * @param TableRegistryInterface              $tableRegistry
     */
    public function __construct(
        ModelManager $modelManager,
        \Enlight_Components_Snippet_Manager $snippets,
        TableRegistryInterface $tableRegistry
    ) {
        $this->modelManager  = $modelManager;
        $this->snippets      = $snippets;
        $this->tableRegistry = $tableRegistry;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/cms')
            ->get('name', 'Cms');
    }

    /**
     * @param array $value
     *
     * @return array
     */
    protected function transformData(array $value)
    {
        $value = parent::transformData($value);

        $value['description'] = sprintf('%s (%s)', $value['description'], $this->fetchGrouping($value['grouping']));

        return $value;
    }

    /**
     * @param string $grouping
     *
     * @return array
     */
    private function fetchGrouping($grouping)
    {
        $repository = $this->modelManager->getRepository(Group::class);
        $qbr        = $repository->createQueryBuilder('t');
        $qbr->select(array('t.name'));
        $qbr->andWhere(
            $qbr->expr()->in('t.key', explode('|', $grouping))
        );

        $results = $qbr->getQuery()->getResult();

        return implode(', ', array_column($results, 'name'));
    }
}
