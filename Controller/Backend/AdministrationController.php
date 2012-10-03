<?php
namespace Neutron\Plugin\CustomerServicesBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class AdministrationController extends ContainerAware
{
    public function indexAction()
    {
        $grid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_customer_services.grid'));
    
        $template = $this->container->get('templating')
            ->render('NeutronCustomerServicesBundle:Backend\Administration:index.html.twig', array(
                'grid' => $grid,
                'translationDomain' => 
                    $this->container->getParameter('neutron_customer_services.translation_domain')
            ));
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {   
        $form = $this->container->get('neutron_customer_services.form.customer_service');
        $handler = $this->container->get('neutron_customer_services.form.handler.customer_service');
        $form->setData($this->getData($id));
        $handler->setForm($form);

        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }

        $template = $this->container->get('templating')
            ->render('NeutronCustomerServicesBundle:Backend\Administration:update.html.twig', array(
                'form' => $form->createView(),
                'plugin' => $this->container->get('neutron_mvc.plugin_provider')->get('neutron.plugin.customer_services'),
                'translationDomain' => 
                    $this->container->getParameter('neutron_customer_services.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $entity = $this->getEntity($id);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->container->get('neutron_customer_services.customer_service_manager')->delete($entity, true);
            $redirectUrl = $this->container->get('router')->generate('neutron_customer_services.backend.administration');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')
            ->render('NeutronCustomerServicesBundle:Backend\Administration:delete.html.twig', array(
                'entity' => $entity,
                'plugin' => $this->container->get('neutron_mvc.plugin_provider')->get('neutron.plugin.customer_services'),
                'translationDomain' =>
                    $this->container->getParameter('neutron_customer_services.translation_domain')
            )
        );
    
        return  new Response($template); 
    }
    
    public function getData($id)
    {
        $pluginManager = $this->container->get('neutron_customer_services.plugin_manager');
        $entity = $this->getEntity($id);
        $panels = $pluginManager->getPanelsForUpdate($id, $pluginManager->getPlugin()->getExtraData('itemIdentifier'));
        $seo = $this->getSeo($entity);
        
        return array('content' => $entity, 'panels' => $panels, 'seo' => $seo);
    }
    
    protected function getEntity($id)
    {
        $manager = $this->container->get('neutron_customer_services.customer_service_manager');
        
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
    
    protected function getSeo(CustomerServiceInterface $entity)
    {
    
        if(!$entity->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $manager->createSeo();
            $entity->setSeo($seo);
        }
    
        return $entity->getSeo();
    }
}
