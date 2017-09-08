<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class Relations
 *
 * @package NetiTags\CompilerPass
 */
class Relations implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (! $container->has('neti_tags.service.tag.relation_collector')) {
            return;
        }

        $definition     = $container->findDefinition('neti_tags.service.tag.relation_collector');
        $taggedServices = $container->findTaggedServiceIds('neti_tags.relation');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('add', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }

}
