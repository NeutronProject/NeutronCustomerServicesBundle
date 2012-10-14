<?php
namespace Neutron\Plugin\CustomerServiceBundle;

use Neutron\MvcBundle\MvcEvents;

use Neutron\MvcBundle\Event\ConfigurePluginEvent;

use Neutron\MvcBundle\Model\Plugin\PluginManagerInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\MvcBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;

class CustomerServicePlugin
{
    const IDENTIFIER = 'neutron.plugin.customer_service';
    
    const ITEM_IDENTIFIER = 'neutron.plugin.customer_service.item';
    
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $translationDomain;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, RouterInterface $router, 
            TranslatorInterface $translator, $translationDomain)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        
    }
    
    public function build()
    {
        $plugin = $this->factory->createPlugin(self::IDENTIFIER);
        $plugin
            ->setLabel($this->translator->trans('plugin.customer_service.label', array(), $this->translationDomain))
            ->setDescription($this->translator->trans('plugin.customer_service.description', array(),$this->translationDomain))
            ->setFrontController('neutron_customer_service.controller.frontend.customer_service_overview:indexAction')
            ->setAdministrationRoute('neutron_customer_service.backend.customer_service')
            ->setUpdateRoute('neutron_customer_service.backend.customer_service_overview.update')
            ->setDeleteRoute('neutron_customer_service.backend.customer_service_overview.delete')
            ->setManagerServiceId('neutron_customer_service.customer_service_overview_manager')
            ->setTreeOptions(array(
                'children_strategy' => 'none',
            ))
        ;
        
        $this->dispatcher->dispatch(
            MvcEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}