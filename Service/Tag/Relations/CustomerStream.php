<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelEntity;
use Shopware\Components\Model\ModelManager;

/**
 * Class CustomerStream
 *
 * @package NetiTags\Service\Tag\Relations
 */
class CustomerStream extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_customer_streams';

    /**
     * @var string
     */
    const ENTITY_NAME = \Shopware\Models\CustomerStream\CustomerStream::class;

    /**
     * @var  string
     */
    const ATTRIBUTE_TABLE_NAME = 's_customer_streams_attributes';

    /**
     * @var string
     */
    const ATTRIBUTE_ENTITY_NAME = \Shopware\Models\Attribute\CustomerStream::class;

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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/customer_stream')
            ->get('name', 'Customer stream');
    }

    /**
     * @param ModelEntity $model
     */
    protected function createAttributeModel(ModelEntity $model)
    {
        /**
         * @var \Shopware\Models\CustomerStream\CustomerStream $model
         */
        $attributeRepository = $this->modelManager->getRepository(\Shopware\Models\Attribute\CustomerStream::class);
        $attribute           = $attributeRepository->findOneBy(array(
            'customerStreamId' => $model->getId()
        ));

        if (null !== $attribute) {
            return;
        }

        $attribute = new \Shopware\Models\Attribute\CustomerStream();
        $attribute->setCustomerStream($model);
        $this->modelManager->persist($attribute);
    }
}
