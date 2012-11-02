<?php
namespace Neutron\Plugin\CustomerServiceBundle\Controller\Backend;

use Neutron\SeoBundle\Model\SeoAwareInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceOverviewInterface;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Symfony\Component\DependencyInjection\ContainerAware;

class CustomerServiceOverviewController extends ContainerAware
{
    public function updateAction($id)
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $form = $this->container->get('neutron_customer_service.form.customer_service_overview');
        $handler = $this->container->get('neutron_customer_service.form.handler.customer_service_overview');
        $form->setData($this->getData($id));
   
        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronCustomerServiceBundle:Backend\CustomerServiceOverview:update.html.twig', array(
                'form' => $form->createView(),
                'plugin' => $plugin,
                'translationDomain' => $this->container
                    ->getParameter('neutron_customer_service.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($entity);
            $redirectUrl = $this->container->get('router')->generate('neutron_mvc.category.management');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronCustomerServiceBundle:Backend\CustomerServiceOverview:delete.html.twig', array(
                'entity' => $entity,
                'plugin' => $plugin,
                'translationDomain' => $this->container
                    ->getParameter('neutron_customer_service.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    protected function doDelete(CustomerServiceOverviewInterface $entity)
    {
        $this->container->get('neutron_admin.acl.manager')
            ->deleteObjectPermissions(ObjectIdentity::fromDomainObject($entity->getCategory()));
    
        $this->container->get('neutron_customer_service.customer_service_overview_manager')
            ->delete($entity, true);
    }
    
    protected function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_mvc.category.category_class'));
    
        $category = $treeManager->findNodeBy(array('id' => $id));
    
        if (!$category){
            throw new NotFoundHttpException();
        }
    
        return $category;
    }
    
    protected function getEntity(CategoryInterface $category)
    {
        $manager = $this->container
            ->get('neutron_customer_service.customer_service_overview_manager');
        
        $entity = $manager->findOneBy(array('category' => $category));
    
        if (!$entity){
            throw new NotFoundHttpException();
        }
    
        return $entity;
    }
    
    
    protected function getSeo(SeoAwareInterface $entity)
    {
    
        if(!$entity->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $this->container->get('neutron_seo.manager')->createSeo();
            $entity->setSeo($seo);
        }
    
        return $entity->getSeo();
    }
    
    protected function getData($id)
    {
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
        $seo = $this->getSeo($entity);
    
        return array(
            'general' => $category,
            'content' => $entity,
            'seo'     => $seo,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))
        );
    }
}
