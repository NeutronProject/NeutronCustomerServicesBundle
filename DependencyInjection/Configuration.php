<?php

namespace Neutron\Plugin\CustomerServiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_customer_service');

        $this->addGeneralConfigurations($rootNode);
        $this->addCustomerServiceOverviewFormConfigurations($rootNode);
        $this->addCustomerServiceOverviewTemplatesConfigurations($rootNode);
        $this->addCustomerServiceFormConfigurations($rootNode);
        $this->addCustomerServiceTemplatesConfigurations($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultFalse()->end()
                ->scalarNode('customer_service_overview_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('customer_service_overview_manager')->defaultValue('neutron_customer_service.doctrine.customer_service_overview_manager.default')->end()
                ->scalarNode('customer_service_controller_backend')->defaultValue('neutron_customer_service.controller.backend.customer_service.default')->end()
                ->scalarNode('customer_service_overview_controller_backend')->defaultValue('neutron_customer_service.controller.backend.customer_service_overview.default')->end()
                ->scalarNode('customer_service_overview_controller_frontend')->defaultValue('neutron_customer_service.controller.frontend.customer_service_overview.default')->end()
                ->scalarNode('customer_service_class')->isRequired(true)->cannotBeEmpty()->end()
                ->scalarNode('customer_service_reference_class')->isRequired(true)->cannotBeEmpty()->end()
                ->scalarNode('customer_service_manager')->defaultValue('neutron_customer_service.doctrine.customer_service_manager.default')->end()
                ->scalarNode('customer_service_controller_backend')->defaultValue('neutron_customer_service.controller.backend.customer_service.default')->end()
                ->scalarNode('customer_service_controller_frontend')->defaultValue('neutron_customer_service.controller.frontend.customer_service.default')->end()
                ->scalarNode('customer_service_datagrid_management')->defaultValue('customer_service_management')->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronCustomerServiceBundle')->end()
            ->end()
        ;
    }
    
    private function addCustomerServiceOverviewFormConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                 ->arrayNode('customer_service_overview_form')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_customer_service_overview')->end()
                            ->scalarNode('handler')->defaultValue('neutron_customer_service.form.handler.customer_service_overview.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_customer_service_overview')->end()
                            ->scalarNode('datagrid')->defaultValue('customer_service_form')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    
    private function addCustomerServiceFormConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                 ->arrayNode('customer_service_form')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_customer_service')->end()
                            ->scalarNode('handler')->defaultValue('neutron_customer_service.form.handler.customer_service.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_customer_service')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    
    private function addCustomerServiceOverviewTemplatesConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('customer_service_overview_templates')->isRequired()
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
    
    private function addCustomerServiceTemplatesConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('customer_service_templates')->isRequired()
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
}
