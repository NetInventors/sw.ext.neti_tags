<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Service\Tag\ProductInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ProductServiceInterface as CoreService;
use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * Class StorefrontProductService
 *
 * @package NetiTags\Service\Decorations
 */
class StorefrontProductService implements CoreService
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
     * StorefrontProductService constructor.
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
     * @return Struct\Product[]
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
     * @return Struct\Product
     */
    public function get($number, Struct\ProductContextInterface $context)
    {
        return $this->productService->get(
            $this->coreService->get($number, $context)
        );
    }
}
