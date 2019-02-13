<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     sbrueggenolte
 */

namespace NetiTags\Components;


use Doctrine\ORM\Tools\SchemaTool;
use NetiTags\Service\TableRegistry;
use NetiTags\Service\Tag\Relations\Article;
use NetiTags\Service\Tag\Relations\Blog;
use NetiTags\Service\Tag\Relations\Category;
use NetiTags\Service\Tag\Relations\Cms;
use NetiTags\Service\Tag\Relations\Customer;
use NetiTags\Service\Tag\Relations\CustomerStream;
use NetiTags\Service\Tag\Relations\ProductStream;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Plugin\Plugin;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Setup
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Setup constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Plugin $plugin
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Shopware\Components\Api\Exception\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function registerRelationTables(Plugin $plugin)
    {
        /**
         * @var ModelManager                        $modelManager
         * @var \Enlight_Components_Snippet_Manager $snippets
         */
        $modelManager  = $this->container->get('models');
        $snippets      = $this->container->get('snippets');
        $tableRegistry = new TableRegistry(
            $modelManager
        );

        $articleRelationService = new Article($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $articleRelationService->getName(),
            $articleRelationService->getTableName(),
            $articleRelationService->getEntityName(),
            $plugin
        );

        $customerRelationService = new Customer($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $customerRelationService->getName(),
            $customerRelationService->getTableName(),
            $customerRelationService->getEntityName(),
            $plugin
        );

        $customerRelationService = new Blog($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $customerRelationService->getName(),
            $customerRelationService->getTableName(),
            $customerRelationService->getEntityName(),
            $plugin
        );

        $customerRelationService = new Cms($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $customerRelationService->getName(),
            $customerRelationService->getTableName(),
            $customerRelationService->getEntityName(),
            $plugin
        );

        $customerRelationService = new Category($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $customerRelationService->getName(),
            $customerRelationService->getTableName(),
            $customerRelationService->getEntityName(),
            $plugin
        );

        $productStreamRelationService = new ProductStream($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $productStreamRelationService->getName(),
            $productStreamRelationService->getTableName(),
            $productStreamRelationService->getEntityName(),
            $plugin
        );

        $customerStreamRelationService = new CustomerStream($modelManager, $snippets, $tableRegistry);
        $tableRegistry->register(
            $customerStreamRelationService->getName(),
            $customerStreamRelationService->getTableName(),
            $customerStreamRelationService->getEntityName(),
            $plugin
        );
    }

    /**
     * Create the table registry table, if it does not exist
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     * @throws \Zend_Db_Adapter_Exception
     * @throws \Zend_Db_Statement_Exception
     */
    public function createRelationTable()
    {
        $modelManager = $this->container->get('models');
        $db           = $this->container->get('db');
        $schemaTool   = new SchemaTool($modelManager);
        $metaData     = $modelManager->getClassMetadata(\NetiTags\Models\TableRegistry::class);

        $query = $db->query('show tables like "' . $metaData->getTableName() . '%"');
        if (0 === $query->rowCount()) {
            $schemaTool->createSchema([$metaData]);
        }
    }
}
