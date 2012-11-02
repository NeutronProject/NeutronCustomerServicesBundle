<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Handler;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

class CustomerServiceOverviewHandler extends AbstractFormHandler
{
    protected function onSuccess()
    {   
        $content = $this->form->get('content')->getData();
        $category = $content->getCategory();

        $this->container->get('neutron_customer_service.customer_service_overview_manager')->update($content);
        
        
        $acl = $this->form->get('acl')->getData();
        
        $this->container->get('neutron_admin.acl.manager')
            ->setObjectPermissions(ObjectIdentity::fromDomainObject($category), $acl);
        
        $this->container->get('object_manager')->flush();
    }
    
    protected function getRedirectUrl()
    {
        return $this->container->get('router')->generate('neutron_mvc.category.management');
    }
}
