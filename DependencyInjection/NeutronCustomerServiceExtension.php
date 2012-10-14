<?php

namespace Neutron\Plugin\CustomerServiceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronCustomerServiceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array('services', 'customer_service_overview', 'customer_service') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
        
        if ($config['enable'] === false){
            $container->getDefinition('neutron_customer_service.plugin')
                ->clearTag('neutron.plugin');
            return;
        }
        
        $this->loadCustomerServiceOverviewConfigurations($config, $container);
        $this->loadCustomerServiceConfigurations($config, $container);
        
        $container->setParameter('neutron_customer_service.translation_domain', $config['translation_domain']);
    }
    
    protected function loadCustomerServiceOverviewConfigurations(array $config, ContainerBuilder $container)
    {
        $container->setAlias('neutron_customer_service.controller.backend.customer_service_overview', $config['customer_service_overview_controller_backend']);
        $container->setAlias('neutron_customer_service.controller.frontend.customer_service_overview', $config['customer_service_overview_controller_frontend']);
        $container->setAlias('neutron_customer_service.customer_service_overview_manager', $config['customer_service_overview_manager']);
        $container->setParameter('neutron_customer_service.customer_service_overview_templates', $config['customer_service_overview_templates']);
        $container->setParameter('neutron_customer_service.customer_service_overview_class', $config['customer_service_overview_class']);
        $container->setParameter('neutron_customer_service.customer_service_reference_class', $config['customer_service_reference_class']);
        $container->setAlias('neutron_customer_service.form.handler.customer_service_overview', $config['customer_service_overview_form']['handler']);
        $container->setParameter('neutron_customer_service.form.type.customer_service_overview', $config['customer_service_overview_form']['type']);
        $container->setParameter('neutron_customer_service.form.name.customer_service_overview', $config['customer_service_overview_form']['name']);
        $container->setParameter('neutron_customer_service.form.datagrid.customer_service_overview', $config['customer_service_overview_form']['datagrid']);
    }
    
    protected function loadCustomerServiceConfigurations(array $config, ContainerBuilder $container)
    {
        $container->setParameter('neutron_customer_service.customer_service_class', $config['customer_service_class']);
        $container->setAlias('neutron_customer_service.customer_service_manager', $config['customer_service_manager']);
        $container->setAlias('neutron_customer_service.controller.backend.customer_service', $config['customer_service_controller_backend']);
        $container->setAlias('neutron_customer_service.controller.frontend.customer_service', $config['customer_service_controller_frontend']);
        $container->setParameter('neutron_customer_service.datagrid.customer_service_management', $config['customer_service_datagrid_management']);
        $container->setParameter('neutron_customer_service.form.type.customer_service', $config['customer_service_form']['type']);
        $container->setParameter('neutron_customer_service.form.name.customer_service', $config['customer_service_form']['name']);
        $container->setAlias('neutron_customer_service.form.handler.customer_service', $config['customer_service_form']['handler']);
        $container->setParameter('neutron_customer_service.customer_service_templates', $config['customer_service_templates']);
    }
}
