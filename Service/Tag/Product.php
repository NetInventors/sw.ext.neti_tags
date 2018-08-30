<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

use NetiTags\Service\TagsCache;
use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * Class Product
 *
 * @package NetiTags\Service\Tag
 */
class Product implements ProductInterface
{
    /**
     * @var TagsCache
     */
    private $tagsCache;

    /**
     * Product constructor.
     *
     * @param TagsCache $tagsCache
     */
    public function __construct(
        TagsCache $tagsCache
    ) {
        $this->tagsCache = $tagsCache;
    }

    /**
     * @param Struct\Product $product
     *
     * @return Struct\Product
     */
    public function get($product)
    {
        if (null === $product || ! $product->hasAttribute('core')) {
            return $product;
        }

        $coreAttriutes = $product->getAttribute('core');
        // $tags          = $this->relationService->getTags('articles', $product->getVariantId());
        $tags = $this->tagsCache->searchTagsCache([$product->getVariantId()], 'articles');
        $coreAttriutes->set('neti_tags', $tags);

        return $product;
    }

    /**
     * @param Struct\Product[] $products
     *
     * @return Struct\Product[]
     */
    public function getList(array $products)
    {
        $this->tagsCache->warmupTagsCache(\array_keys($products), 'articles');
        foreach ($products as $product) {
            $this->get($product);
        }

        return $products;
    }
}
