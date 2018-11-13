<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Attribute\Blog as BlogAttribute;

/**
 * Class Blog
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Blog extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_blog';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\Blog\Blog::class;

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_blog_attributes';

    /**
     * @var string
     */
    const ATTRIBUTE_ENTITY_NAME = BlogAttribute::class;

    /**
     *
     */
    const ENTITY_FIELDS = array(
        't.id',
        't.title',
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/blog')
            ->get('name', 'Blog');
    }
}
