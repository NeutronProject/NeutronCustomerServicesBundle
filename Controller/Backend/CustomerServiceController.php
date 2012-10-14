<?php
namespace Neutron\Plugin\CustomerServiceBundle\Controller\Backend;

use Neutron\SeoBundle\Model\SeoAwareInterface;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class CustomerServiceController extends ContainerAware
{
    public function indexAction()
    {
        $datagrid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_customer_service.datagrid.customer_service_management'));
    
        $template = $this->container->get('templating')->render(
            'NeutronCustomerServiceBundle:Backend\CustomerService:index.html.twig', array(
                'datagrid' => $datagrid,
                'translationDomain' => 
                    $this->container->getParameter('neutron_customer_service.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $form = $this->container->get('neutron_customer_service.form.customer_service');
        $handler = $this->container->get('neutron_customer_service.form.handler.customer_service');
        $form->setData($this->getData($id));

        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }

        $template = $this->container->get('templating')->render(
            'NeutronCustomerServiceBundle:Backend\CustomerService:update.html.twig', array(
                'form' => $form->createView(),
                'plugin' => $plugin,
                'translationDomain' => 
                    $this->container->getParameter('neutron_customer_service.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        
        $entity = $this->getEntity($id);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->container->get('neutron_customer_service.customer_service_manager')
                ->delete($entity, true);
            $redirectUrl = $this->container->get('router')
                ->generate('neutron_customer_service.backend.customer_service');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')
            ->render('NeutronCustomerServiceBundle:Backend\CustomerService:delete.html.twig', array(
                'entity' => $entity,
                'plugin' => $plugin,
                'translationDomain' =>
                    $this->container->getParameter('neutron_customer_service.translation_domain')
            )
        );
    
        return  new Response($template); 
    }
    
    public function getData($id)
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $mvcManager = $this->container->get('neutron_mvc.mvc_manager');
        $entity = $this->getEntity($id);
        $panels = $mvcManager->getPanelsForUpdate($plugin, $id, CustomerServicePlugin::ITEM_IDENTIFIER);
        $seo = $this->getSeo($entity);
        
        return array('content' => $entity, 'panels' => $panels, 'seo' => $seo);
    }
    
    protected function getEntity($id)
    {

        $manager = $this->container->get('neutron_customer_service.customer_service_manager');
        
        if ($id){
            $entity = $manager->findOneBy(array('id' => $id));
        } else {
            $entity = $manager->create();
        }
        
        if (!$entity){
            throw new NotFoundHttpException();
        }
        
        return $entity;
    }
    
    protected function getSeo(SeoAwareInterface $entity)
    {
    
        if(!$entity->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $manager->createSeo();
            $entity->setSeo($seo);
        }
    
        return $entity->getSeo();
    }
}
