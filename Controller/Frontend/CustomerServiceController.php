<?php
namespace Neutron\Plugin\CustomerServicesBundle\Controller\Frontend;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class CustomerServiceController extends ContainerAware
{
    
    public function indexAction($categorySlug, $slug)
    {   
        $manager = $this->container->get('neutron_customer_services.doctrine.customer_service_manager');
        
        
        if (null === $overview){
            throw new NotFoundHttpException();
        }

        $manager->loadPanels($category->getId(), $manager->getPlugin()->getName());
       
        $template = $this->container->get('templating')
            ->render($overview->getTemplate(), array(
                'overview'   => $overview,     
                'plugin' => $manager->getPlugin()    
            ));
    
        return  new Response($template);
    }
  
}
