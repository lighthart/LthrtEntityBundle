<?php

namespace Lthrt\EntityBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Parser;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LthrtEntityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $container->setParameter('class_aliases', $this->getClassAliases($container));
    }

    public function getClassAliases($container)
    {
        $aliases = [];
        $yml     = new Parser();

        foreach ($container->getParameter('kernel.bundles') as $bundleStr) {
            $bundle = new $bundleStr();
            $config = $bundle->getPath() . "/Resources/config/aliases.yml";
            if (file_exists($config)) {
                $bundleAliases = $yml->parse(file_get_contents($config));
                foreach ($bundleAliases as $alias => $class) {
                    if (isset($aliases[$alias])) {
                        throw new \Exception("Duplicate Route Alias passed to Lthrt\EntityBundle from $bundleStr: \n" . $alias . ' => ' . $class . "\n\nPrevious alias:\n " . $alias . " => " . $aliases[$alias]);
                    } else {
                        $aliases[$alias] = $class;
                    }
                }
            }
        }

        return $aliases;
    }
}
