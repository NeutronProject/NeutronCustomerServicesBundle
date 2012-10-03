<?php

namespace Neutron\Plugin\CustomerServicesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronCustomerServicesExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        if ($config['enable'] === false){
            $container->getDefinition('neutron_customer_services.plugin')->clearTag('neutron.plugin');
            return;
        }
        
        //var_dump($config); die;
        $container->setAlias('neutron_customer_services.controller.backend.administration', $config['administration_controller']);
        $container->setParameter('neutron_customer_services.translation_domain', $config['translation_domain']);
        
        $this->loadPluginConfigurations($config, $container);
        $this->loadItemConfigurations($config['item'], $container);
    }
    
    protected function loadPluginConfigurations(array $config, ContainerBuilder $container)
    {
        $container->setAlias('neutron_customer_services.controller.backend.plugin', $config['plugin_controller_backend']);
        $container->setAlias('neutron_customer_services.controller.frontend.plugin', $config['plugin_controller_front']);
        $container->setAlias('neutron_customer_services.plugin_manager', $config['plugin_manager']);
        $container->setParameter('neutron_customer_services.plugin_templates', $config['plugin_templates']);
        $container->setParameter('neutron_customer_services.plugin_class', $config['plugin_class']);
        
        $container->setAlias('neutron_customer_services.form.handler.customer_services_plugin', $config['plugin_form']['handler']);
        $container->setParameter('neutron_customer_services.form.plugin.type', $config['plugin_form']['type']);
        $container->setParameter('neutron_customer_services.form.plugin_instance.type', $config['plugin_form']['instance_type']);
        $container->setParameter('neutron_customer_services.form.plugin.name', $config['plugin_form']['name']);
    }
    
    protected function loadItemConfigurations(array $config, ContainerBuilder $container)
    {
        $container->setParameter('neutron_customer_services.item.class', $config['class']);
        $container->setParameter('neutron_customer_services.item.reference_class', $config['reference_class']);
        $container->setAlias('neutron_customer_services.customer_service_manager', $config['manager']);
        $container->setAlias('neutron_customer_services.controller.backend.administration', $config['controller_backend']);
        $container->setAlias('neutron_customer_services.controller.frontend.customer_service', $config['controller_frontend']);
        $container->setParameter('neutron_customer_services.grid', $config['grid']);
        $container->setParameter('neutron_customer_services.form.customer_service.type', $config['form']['type']);
        $container->setParameter('neutron_customer_services.form.customer_service.name', $config['form']['name']);
        $container->setParameter('neutron_customer_services.form.customer_service.grid', $config['form']['grid']);
        $container->setAlias('neutron_customer_services.form.handler.customer_service', $config['form']['handler']);
        $container->setParameter('neutron_customer_services.item.templates', $config['templates']);
    }
}
