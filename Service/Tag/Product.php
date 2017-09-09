<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

use NetiTags\Service\RelationInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware\Bundle\StoreFrontBundle\Struct\Country;
use Shopware\Bundle\StoreFrontBundle\Struct\Customer\Group;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

/**
 * Class Product
 *
 * @package NetiTags\Service\Tag
 */
class Product implements ProductInterface
{
    /**
     * @var RelationInterface
     */
    private $relationService;

    /**
     * Product constructor.
     *
     * @param RelationInterface $relationService
     */
    public function __construct(
        RelationInterface $relationService
    ) {
        $this->relationService = $relationService;
    }

    /**
     * @param Struct\Product $product
     *
     * @return Struct\Product
     */
    public function get($product)
    {
        if (! $product->hasAttribute('core')) {
            return $product;
        }

        $coreAttriutes = $product->getAttribute('core');
        $tags          = $this->relationService->getTags('articles', $product->getVariantId());
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
        foreach ($products as &$product) {
            $product = $this->get($product);
        }

        return $products;
    }
}
