<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Handler;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

class CustomerServiceOverviewHandler extends AbstractFormHandler
{
    protected function onSuccess()
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $content = $this->form->get('content')->getData();
        $category = $content->getCategory();

        $this->container->get($plugin->getManagerServiceId())->update($content);
        
        if (count($plugin->getPanels()) > 0){
            $panels = $this->form->get('panels')->getData();
            $this->container->get('neutron_mvc.mvc_manager')->updatePanels($content->getId(), $panels);
        }
        
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
