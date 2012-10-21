<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('repository_alias');

        $rootNode
            ->children()
                ->variableNode('repository')->defaultValue(array())->end()
                ->variableNode('repository_key')->defaultValue('repository')->end()
                ->variableNode('repository_factory')->defaultValue('CodeMonkeysRu\RepositoryAliasBundle\Service\Repository')->end()
            ->end()
        ;
        return $treeBuilder;
    }

}
