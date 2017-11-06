<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Tag;

use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * Interface ProductInterface
 *
 * @package NetiTags\Service\Tag
 */
interface ProductInterface
{
    /**
     * @param Struct\Product $product
     *
     * @return Struct\Product
     */
    public function get($product);

    /**
     * @param Struct\Product[] $products
     *
     * @return Struct\Product[]
     */
    public function getList(array $products);
}
