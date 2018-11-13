<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;

/**
 * Class ProductStream
 *
 * @package NetiTags\Service\Tag\Relations
 */
class ProductStream extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_product_streams';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\ProductStream\ProductStream::class;

    /**
     * @var  string
     */
    const ATTRIBUTE_TABLE_NAME = 's_product_streams_attributes';

    /**
     *
     */
    const ATTRIBUTE_ENTITY_NAME = \Shopware\Models\Attribute\ProductStream::class;

    /**
     *
     */
    const ENTITY_FIELDS = [
        't.id',
        't.name',
    ];

    /**
     * @var \Enlight_Components_Snippet_Manager
     */
    protected $snippets;

    /**
     * Article constructor.
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/product_stream')
            ->get('name', 'Product stream');
    }
}
