<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Attribute\Customer as CustomerAttribute;

/**
 * Class Customer
 * @package NetiTags\Service\Tag\Relations
 */
class Customer extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_user';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\Customer\Customer::class;

    /**
     * @var string
     */
    const ATTRIBUTE_TABLE_NAME = 's_user_attributes';

    /**
     * @var string
     */
    const ATTRIBUTE_ENTITY_NAME = CustomerAttribute::class;

    /**
     *
     */
    const ENTITY_FIELDS = array(
        't.id',
        't.number',
        't.groupKey',
        't.email',
        't.firstname',
        't.lastname',
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/customer')
            ->get('name', 'Customer');
    }

    /**
     * @param array $value
     *
     * @return array
     */
    protected function transformData(array $value)
    {
        $value = parent::transformData($value);

        $value['name'] = sprintf('%s %s (%s)', $value['firstname'], $value['lastname'], $value['number']);

        return $value;
    }
}
