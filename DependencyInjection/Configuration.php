<?php

namespace Neutron\Plugin\CustomerServicesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
        $rootNode = $treeBuilder->root('neutron_customer_services');

        $this->addGeneralConfigurations($rootNode);
        $this->addPluginFormConfigurations($rootNode);
        $this->addPluginTemplatesConfiguration($rootNode);
        $this->addItemConfigurations($rootNode);


        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultFalse()->end()
                ->scalarNode('plugin_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('plugin_manager')->defaultValue('neutron_customer_services.doctrine.plugin_manager.default')->end()
                ->scalarNode('administration_controller')->defaultValue('neutron_customer_services.controller.backend.administration.default')->end()
                ->scalarNode('plugin_controller_backend')->defaultValue('neutron_customer_services.controller.backend.plugin.default')->end()
                ->scalarNode('plugin_controller_front')->defaultValue('neutron_customer_services.controller.frontend.plugin.default')->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronCustomerServicesBundle')->end()
            ->end()
        ;
    }
    
    private function addPluginFormConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                 ->arrayNode('plugin_form')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_customer_services_plugin')->end()
                            ->scalarNode('instance_type')->defaultValue('neutron_customer_services_plugin_content')->end()
                            ->scalarNode('handler')->defaultValue('neutron_customer_services.form.handler.customer_services_plugin.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_customer_services_plugin')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    
    private function addPluginTemplatesConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('plugin_templates')->isRequired()
                ->validate()
                    ->ifTrue(function($v){return empty($v);})
                    ->thenInvalid('You should provide at least one template.')
                ->end()
                ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end() 
                ->cannotBeOverwritten()
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
        ;
    }
    
    private function addItemConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('item')
                    ->isRequired(true)
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->isRequired(true)->cannotBeEmpty()->end()
                            ->scalarNode('reference_class')->isRequired(true)->cannotBeEmpty()->end()
                            ->scalarNode('manager')->defaultValue('neutron_customer_services.doctrine.customer_service_manager.default')->end()
                            ->scalarNode('controller_backend')->defaultValue('neutron_customer_services.controller.backend.administration.default')->end()
                            ->scalarNode('controller_frontend')->defaultValue('neutron_customer_services.controller.frontend.customer_service.default')->end()
                            ->scalarNode('grid')->defaultValue('customer_service_management')->end()
                            ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('type')->defaultValue('neutron_customer_service')->end()
                                    ->scalarNode('handler')->defaultValue('neutron_customer_services.form.handler.customer_service.default')->end()
                                    ->scalarNode('name')->defaultValue('neutron_customer_service')->end()
                                    ->scalarNode('grid')->defaultValue('customer_service_list')->end()
                                ->end()
                            ->end()
                            ->arrayNode('templates')->isRequired()
                            ->validate()
                                ->ifTrue(function($v){return empty($v);})
                                ->thenInvalid('You should provide at least one template.')
                            ->end()
                            ->useAttributeAsKey('name')
                                ->prototype('scalar')
                            ->end() 
                            ->cannotBeOverwritten()
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    

}
