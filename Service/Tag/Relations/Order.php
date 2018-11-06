<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Attribute\Order as OrderAttribute;

/**
 * Class Order
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Order implements RelationsInterface
{
    use RelationTrait;

    /**
     * @var string
     */
    const TABLE_NAME = 's_order';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\Order\Order::class;

    /**
     *
     */
    const ATTRIBUTE_ENTITY_NAME = OrderAttribute::class;

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_order_attributes';

    /**
     *
     */
    const ENTITY_FIELDS = [
        't.id',
        't.number'
    ];

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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/order')
            ->get('name', 'Order');
    }
}
