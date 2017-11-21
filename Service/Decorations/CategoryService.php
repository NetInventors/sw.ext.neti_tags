<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service\Decorations;

use NetiTags\Service\RelationInterface;
use Shopware\Bundle\StoreFrontBundle\Service\CategoryServiceInterface as CoreService;
use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * Class CategoryService
 *
 * @package NetiTags\Service\Decorations
 */
class CategoryService implements CoreService
{
    /**
     * @var RelationInterface
     */
    private $relationService;

    /**
     * @var CoreService
     */
    private $coreService;

    /**
     * CategoryService constructor.
     *
     * @param CoreService       $coreService
     * @param RelationInterface $relationService
     */
    public function __construct(
        CoreService $coreService,
        RelationInterface $relationService
    ) {
        $this->coreService     = $coreService;
        $this->relationService = $relationService;
    }

    /**
     * To get detailed information about the selection conditions, structure and content of the returned object,
     * please refer to the linked classes.
     *
     * @see \Shopware\Bundle\StoreFrontBundle\Gateway\CategoryGatewayInterface::getList()
     *
     * @param                             $ids
     * @param Struct\ShopContextInterface $context
     *
     * @return Struct\Category[] Indexed by the category id.
     */
    public function getList($ids, Struct\ShopContextInterface $context)
    {
        $results = $this->coreService->getList($ids, $context);

        foreach ($results as &$result) {
            if (empty($result)) {
                continue;
            }

            $this->addTags($result);
        }

        return $results;
    }

    /**
     * To get detailed information about the selection conditions, structure and content of the returned object,
     * please refer to the linked classes.
     *
     * @see \Shopware\Bundle\StoreFrontBundle\Gateway\CategoryGatewayInterface::get()
     *
     * @param                             $id
     * @param Struct\ShopContextInterface $context
     *
     * @return Struct\Category
     */
    public function get($id, Struct\ShopContextInterface $context)
    {
        $result = $this->coreService->get($id, $context);

        if (! empty($result)) {
            $this->addTags($result);
        }

        return $result;
    }

    /**
     * @param Struct\BaseProduct[]        $products
     * @param Struct\ShopContextInterface $context
     *
     * @return array Indexed by product number, contains all categories of a product
     */
    public function getProductsCategories(array $products, Struct\ShopContextInterface $context)
    {
        $categories = $this->coreService->getProductsCategories($products, $context);

        foreach ($categories as $key => $productCategories) {
            foreach ($productCategories as &$result) {
                if (empty($result)) {
                    continue;
                }
                $this->addTags($result);
            }
        }

        return $categories;
    }

    /**
     * @param Struct\Category $result
     */
    private function addTags(Struct\Category $result)
    {
        $tags = $this->relationService->getTags('categories', $result->getId());
        if (empty($tags)) {
            return;
        }

        /**
         * @var \Shopware\Bundle\StoreFrontBundle\Struct\Attribute $coreAttribute
         */
        $coreAttribute = $result->getAttribute('core');
        if (empty($coreAttribute)) {
            return;
        }

        $coreAttribute->set('neti_tags', $tags);
    }
}
