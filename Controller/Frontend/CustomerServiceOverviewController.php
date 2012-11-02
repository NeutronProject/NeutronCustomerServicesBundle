<?php
namespace Neutron\Plugin\CustomerServiceBundle\Controller\Frontend;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class CustomerServiceOverviewController extends ContainerAware
{
    
    public function indexAction($slug)
    {   

        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $entity = $categoryManager->findOneByCategorySlug(
            $this->container->getParameter('neutron_customer_service.customer_service_overview_class'), 
            $slug,
            $this->container->get('request')->getLocale()
        );
        
        if (null === $entity){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($entity->getCategory(), 'VIEW')){
            throw new AccessDeniedException();
        }

        $template = $this->container->get('templating')->render(
            $entity->getTemplate(), array(
                'overview'   => $entity,     
                'menu_name' => 'neutron.plugin.customer_service' . $entity->getCategory()->getId()
            )
        );
    
        return  new Response($template);
    }
  
}
