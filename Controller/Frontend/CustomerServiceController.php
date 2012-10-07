<?php
namespace Neutron\Plugin\CustomerServicesBundle\Controller\Frontend;

use Knp\Menu\ItemInterface;

use Neutron\Plugin\CustomerServicesBundle\CustomerServicesPlugin;

use Neutron\MvcBundle\Provider\PluginProvider;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class CustomerServiceController extends ContainerAware
{
    
    public function indexAction($categorySlug, $serviceSlug)
    {   
        
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicesPlugin::IDENTIFIER);
        
        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $manager = $this->container->get('neutron_customer_services.customer_service_manager');
     
        $category = $categoryManager
            ->findCategoryBySlug($categorySlug, true, $this->container->get('request')->getLocale());
        
        if (null === $category){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($category, 'VIEW')){
            throw new AccessDeniedException();
        }
        
        $customerService = $manager->findOneBy(array('slug' => $serviceSlug, 'enabled' => true));
        
        if (null === $customerService){
            throw new NotFoundHttpException();
        }
        
        $plugin->getManager()->loadPanels($customerService->getId(), CustomerServicesPlugin::ITEM_IDENTIFIER);
        
        $template = $this->container->get('templating')->render(
            $customerService->getTemplate(), array(
                'category' => $category,
                'customerService' => $customerService,     
                'plugin' => $plugin,
                'menu_name' => $plugin->getName() . $category->getId()
            )
        );
    
        return  new Response($template);
    }
    
}
