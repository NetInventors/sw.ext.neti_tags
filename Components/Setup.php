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
use NetiTags\Service\Tag\Relations\Order;
use NetiTags\Service\Tag\Relations\ProductStream;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Plugin\Plugin;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Setup
 *
 * @package NetiTags\Components
 */
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

        $relations = array(
            new Article($modelManager, $snippets, $tableRegistry),
            new Customer($modelManager, $snippets, $tableRegistry),
            new Blog($modelManager, $snippets, $tableRegistry),
            new Cms($modelManager, $snippets, $tableRegistry),
            new Category($modelManager, $snippets, $tableRegistry),
            new ProductStream($modelManager, $snippets, $tableRegistry),
            new CustomerStream($modelManager, $snippets, $tableRegistry),
            new Order($modelManager, $snippets, $tableRegistry),
        );

        foreach ($relations as $relation) {
            $tableRegistry->register(
                $relation->getName(),
                $relation->getTableName(),
                $relation->getEntityName(),
                $plugin
            );
        }
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
