<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag\Relations;

use NetiTags\Service\TableRegistryInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Detail;
use Shopware\Models\Attribute\Article as DetailAttribute;

/**
 * Class Article
 *
 * @package NetiTags\Service\Tag\Relations
 */
class Article extends AbstractRelation
{
    /**
     * @var string
     */
    const TABLE_NAME = 's_articles_details';

    /**
     * @var string
     */
    const ENTITY_NAME = Detail::class;

    /**
     * @var  string
     */
    const ATTRIBUTE_TABLE_NAME = 's_articles_attributes';

    /**
     *
     */
    const ATTRIBUTE_ENTITY_NAME = DetailAttribute::class;

    /**
     *
     */
    const ENTITY_FIELDS = [
        't.id',
        't.number',
        'article.name'
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
        return $this->snippets->getNamespace('plugins/neti_tags/backend/detail/relations/article')
            ->get('name', 'Article');
    }

    /**
     * @return \Shopware\Components\Model\QueryBuilder
     */
    protected function buildQuery()
    {
        $qbr = parent::buildQuery();

        $qbr->innerJoin('t.article', 'article');

        return $qbr;
    }

    /**
     * @param array $value
     *
     * @return array
     */
    protected function transformData(array $value)
    {
        $value = parent::transformData($value);

        $value['name'] = sprintf('%s (%s)', $value['name'], $value['number']);

        return $value;
    }
}
