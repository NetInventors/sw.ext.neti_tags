<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Attribute\Category as CategoryAttribute;

/**
 * Class Category
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Category extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_categories';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\Category\Category::class;

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_categories_attributes';

    /**
     * @var string
     */
    const ATTRIBUTE_ENTITY_NAME = CategoryAttribute::class;

    /**
     *
     */
    const ENTITY_FIELDS = array(
        't.id',
        't.name',
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/category')
            ->get('name', 'Category');
    }

    /**
     * @param array $value
     *
     * @return array
     */
    protected function transformData(array $value)
    {
        $value         = parent::transformData($value);
        $repository    = $this->modelManager->getRepository(\Shopware\Models\Category\Category::class);
        $value['name'] = $repository->getPathById($value['id'], 'name', ' > ');

        return $value;
    }
}
