<?php
namespace Neutron\Plugin\CustomerServiceBundle\Controller\Frontend;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceInterface;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceOverviewInterface;

use Knp\Menu\ItemInterface;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

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
            ->get(CustomerServicePlugin::IDENTIFIER);
        
        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $mvcManager = $this->container->get('neutron_mvc.mvc_manager');
        $customerServiceOverviewManager = $this->container->get('neutron_customer_service.customer_service_overview_manager');
        $customerServiceManager = $this->container->get('neutron_customer_service.customer_service_manager');
     
        $category = $categoryManager
            ->findCategoryBySlug($categorySlug, true, $this->container->get('request')->getLocale());
        
        if (null === $category){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($category, 'VIEW')){
            throw new AccessDeniedException();
        }
        
        $overview = $customerServiceOverviewManager->getByCategory($category);
        
        if (null === $overview){
            throw new NotFoundHttpException();
        }
        
        $customerService = $customerServiceManager->findOneBy(array('slug' => $serviceSlug, 'enabled' => true));
        
        if (null === $customerService){
            throw new NotFoundHttpException();
        }
        
        $mvcManager->loadPanels($plugin, $customerService->getId(), CustomerServicePlugin::ITEM_IDENTIFIER);
        
        $template = $this->container->get('templating')->render(
            $customerService->getTemplate(), array(
                'category' => $category,
                'customerService' => $customerService,     
                'plugin' => $plugin,
                'menu_name' => $plugin->getName() . $category->getId(),
                'nextService' => $this->getNextService($overview, $customerService),
                'prevService' => $this->getPrevService($overview, $customerService),
            )
        );
    
        return  new Response($template);
    }
    
    protected function getNextService(CustomerServiceOverviewInterface $overview, CustomerServiceInterface $customerService)
    {
        $currentRef = $overview->getReferences()->filter(function ($entity) use ($customerService){
            return ($customerService === $entity->getInversed());
        })->first();

        $key = $overview->getReferences()->indexOf($currentRef) + 1;
 
        $reference = $overview->getReferences()->get($key);
        
        if ($reference){
            return $reference->getInversed();   
        }
    }
    
    protected function getPrevService(CustomerServiceOverviewInterface $overview, CustomerServiceInterface $customerService)
    {
        $currentRef = $overview->getReferences()->filter(function ($entity) use ($customerService){
            return ($customerService === $entity->getInversed());
        })->first();

        $key = $overview->getReferences()->indexOf($currentRef) - 1;
 
        $reference = $overview->getReferences()->get($key);
        
        if ($reference){
            return $reference->getInversed();   
        }
    }
    
}
