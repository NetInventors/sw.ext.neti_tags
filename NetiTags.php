<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags;

use NetiTags\CompilerPass\Relations;
use NetiTags\Service\TableRegistry;
use NetiTags\Service\Tag\Relations\Article;
use NetiTags\Service\Tag\Relations\Blog;
use NetiTags\Service\Tag\Relations\Category;
use NetiTags\Service\Tag\Relations\Cms;
use NetiTags\Service\Tag\Relations\Customer;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class NetiTags
 *
 * @package NetiTags
 */
class NetiTags extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Relations());
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        parent::install($context);
        $this->registerRelationTables($context->getPlugin());
    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        parent::update($context);
        $this->registerRelationTables($context->getPlugin());
    }

    /**
     * @param \Shopware\Models\Plugin\Plugin $plugin
     */
    private function registerRelationTables(\Shopware\Models\Plugin\Plugin $plugin)
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

        try {
            $articleRelationService = new Article($modelManager, $snippets, $tableRegistry);
            $tableRegistry->register(
                $articleRelationService->getName(),
                $articleRelationService->getTableName(),
                $plugin
            );
        } catch (\Exception $e) {
        }

        try {
            $customerRelationService = new Customer($modelManager, $snippets, $tableRegistry);
            $tableRegistry->register(
                $customerRelationService->getName(),
                $customerRelationService->getTableName(),
                $plugin
            );
        } catch (\Exception $e) {

        }
        try {
            $customerRelationService = new Blog($modelManager, $snippets, $tableRegistry);
            $tableRegistry->register(
                $customerRelationService->getName(),
                $customerRelationService->getTableName(),
                $plugin
            );
        } catch (\Exception $e) {

        }

        try {
            $customerRelationService = new Cms($modelManager, $snippets, $tableRegistry);
            $tableRegistry->register(
                $customerRelationService->getName(),
                $customerRelationService->getTableName(),
                $plugin
            );
        } catch (\Exception $e) {

        }

        try {
            $customerRelationService = new Category($modelManager, $snippets, $tableRegistry);
            $tableRegistry->register(
                $customerRelationService->getName(),
                $customerRelationService->getTableName(),
                $plugin
            );
        } catch (\Exception $e) {

        }
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        parent::uninstall($context); // TODO: Change the autogenerated stub
    }

}
