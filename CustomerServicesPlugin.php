<?php
namespace Neutron\Plugin\CustomerServicesBundle;

use Neutron\MvcBundle\MvcEvents;

use Neutron\MvcBundle\Event\ConfigurePluginEvent;

use Neutron\MvcBundle\Model\Plugin\PluginManagerInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\MvcBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;

class CustomerServicesPlugin
{
    const IDENTIFIER = 'neutron.plugin.customer_services';
    
    const ITEM_IDENTIFIER = 'neutron.plugin.customer_services.item';
    
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $manager;
    
    protected $translationDomain;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, RouterInterface $router, 
            TranslatorInterface $translator, PluginManagerInterface $manager, $translationDomain)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->manager = $manager;
        $this->translationDomain = $translationDomain;
        
    }
    
    public function build()
    {
        $plugin = $this->factory->createPlugin(self::IDENTIFIER);
        $plugin
            ->setLabel($this->translator->trans('plugin.customer_services.label', array(), $this->translationDomain))
            ->setDescription($this->translator->trans('plugin.customer_services.description', array(),$this->translationDomain))
            ->setFrontController('neutron_customer_services.controller.frontend.plugin:indexAction')
            ->setAdministrationRoute('neutron_customer_services.backend.administration')
            ->setUpdateRoute('neutron_customer_services.backend.plugin.update')
            ->setDeleteRoute('neutron_customer_services.backend.plugin.delete')
            ->setManager($this->manager)
            ->setTreeOptions(array(
                'children_strategy' => 'none',
            ))
            ->setExtraData(array('itemIdentifier' => 'neutron.plugin.customer_services.item'))
        ;
        
        $this->dispatcher->dispatch(
            MvcEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}