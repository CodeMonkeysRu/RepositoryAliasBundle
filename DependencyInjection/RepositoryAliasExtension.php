<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RepositoryAliasExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!empty($config['repository'])) {

            //Main Repo
            $container->setDefinition(
                $config['repository_key'],
                new \Symfony\Component\DependencyInjection\Definition(
                    $config['repository_factory'],
                    array(
                        'em' => new \Symfony\Component\DependencyInjection\Reference('em'),
                        'map' => $config['repository'],
                        'container' => new \Symfony\Component\DependencyInjection\Reference('service_container'),
                    )
                )
            );

            foreach ($config['repository'] as $key => $options) {
                $definition = new \Symfony\Component\DependencyInjection\Definition(
                    $config['repository_factory'],
                    array(
                        $key
                    )
                );
                $definition->setFactoryService($config['repository_key']);
                $definition->setFactoryMethod('get');
                $container->setDefinition(
                    $config['repository_key'].'.'.$key, $definition
                );
            }
        }
    }

}
