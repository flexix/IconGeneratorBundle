<?php

namespace Flexix\IconGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('flexix_icon_generator');
        $rootNode
        ->children()
            ->arrayNode('bundles')->defaultValue(array())
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->useAttributeAsKey('name')
                        ->prototype('array')
                        ->children()
                            ->scalarNode('alias')->end()
                            ->scalarNode('class')->end()
                            ->scalarNode('icon')->end()
                            ->scalarNode('icon_color')->end()
                            ->scalarNode('color')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
