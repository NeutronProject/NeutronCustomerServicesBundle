<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Handler;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class CustomerServiceHandler extends AbstractFormHandler
{

    protected function onSuccess()
    {
        $manager = $this->container->get('neutron_customer_service.customer_service_manager');
        $entity = $this->form->get('content')->getData();
        
        $manager->update($entity, true);
    }
    
    public function getRedirectUrl()
    {
        return $this->container->get('router')->generate('neutron_customer_service.backend.customer_service');
    }
}
