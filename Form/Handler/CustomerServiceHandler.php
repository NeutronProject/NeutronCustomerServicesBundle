<?php
namespace Neutron\Plugin\CustomerServicesBundle\Form\Handler;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceManagerInterface;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class CustomerServiceHandler extends AbstractFormHandler
{
    
    protected $manager;
    
    protected $plugin;
        
    public function setManager(CustomerServiceManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
    
    protected function onSuccess()
    {
        $entity = $this->form->get('content')->getData();
        $panels = $this->form->get('panels')->getData();
        
        $this->manager->update($entity, true);
        $this->plugin->getManager()->updatePanels($entity->getId(), $panels, true);
    }
    
    public function getRedirectUrl()
    {
        return $this->router->generate('neutron_customer_services.backend.administration');
    }
}
