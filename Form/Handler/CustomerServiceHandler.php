<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Handler;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class CustomerServiceHandler extends AbstractFormHandler
{

    protected function onSuccess()
    {
        $manager = $this->container->get('neutron_customer_service.customer_service_manager');
        $entity = $this->form->get('content')->getData();
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        
        $manager->update($entity, true);
        
        if (count($plugin->getPanels()) > 0){
            $panels = $this->form->get('panels')->getData();
            $this->container->get('neutron_mvc.mvc_manager')
                ->updatePanels($entity->getId(), $panels, true);
        } 
    }
    
    public function getRedirectUrl()
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        
        return $this->container->get('router')->generate($plugin->getAdministrationRoute());
    }
}
