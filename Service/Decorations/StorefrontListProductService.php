<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Service\Tag\ProductInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface as CoreService;
use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * Class StorefrontListProductService
 *
 * @package NetiTags\Service\Decorations
 */
class StorefrontListProductService implements CoreService
{
    /**
     * @var CoreService
     */
    private $coreService;

    /**
     * @var ProductInterface
     */
    private $productService;

    /**
     * StorefrontListProductService constructor.
     *
     * @param CoreService      $coreService
     * @param ProductInterface $productService
     */
    public function __construct(
        CoreService $coreService,
        ProductInterface $productService
    ) {
        $this->coreService    = $coreService;
        $this->productService = $productService;
    }

    /**
     * @param array                          $numbers
     * @param Struct\ProductContextInterface $context
     *
     * @return Struct\ListProduct[]
     */
    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products = $this->productService->getList(
            $this->coreService->getList(
                $numbers,
                $context
            )
        );

        return $products;
    }

    /**
     * @param string                         $number
     * @param Struct\ProductContextInterface $context
     *
     * @return Struct\ListProduct
     */
    public function get($number, Struct\ProductContextInterface $context)
    {
        $product = $this->productService->get(
            $this->coreService->get($number, $context)
        );

        return $product;
    }
}
