<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags;

use NetiTags\CompilerPass\Relations;
use NetiTags\Components\Setup;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
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
     * Plugin can be installed from github, so the license check should be skipped
     *
     * @return bool
     */
    public function isChargeable()
    {
        return false;
    }

    /**
     * Secret key to allow akipping the license check
     *
     * @return string
     */
    public function getSecret()
    {
        return '__SECRET__';
    }

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
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Shopware\Components\Api\Exception\ValidationException
     */
    public function install(InstallContext $context)
    {
        parent::install($context);
        $setup = new Setup($this->container);
        $setup->registerRelationTables($context->getPlugin());
    }

    /**
     * @param UpdateContext $context
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Shopware\Components\Api\Exception\ValidationException
     */
    public function update(UpdateContext $context)
    {
        parent::update($context);
        $setup = new Setup($this->container);
        $setup->registerRelationTables($context->getPlugin());
    }
}
