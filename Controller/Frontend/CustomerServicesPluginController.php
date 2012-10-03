<?php
namespace Neutron\Plugin\CustomerServicesBundle\Controller\Frontend;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class CustomerServicesPluginController extends ContainerAware
{
    
    public function indexAction(CategoryInterface $category)
    {   
        $manager = $this->container->get('neutron_customer_services.plugin_manager');
        $overview = $manager->findOneBy(array('category' => $category));
        
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
