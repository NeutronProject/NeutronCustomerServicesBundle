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

        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $overview = $categoryManager->findOneByCategorySlug(
            $this->container->getParameter('neutron_customer_service.customer_service_overview_class'),
            $categorySlug,
            $this->container->get('request')->getLocale()
        );

        $customerServiceManager = $this->container->get('neutron_customer_service.customer_service_manager');

        if (null === $overview){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($overview->getCategory(), 'VIEW')){
            throw new AccessDeniedException();
        }
        
        $customerService = $customerServiceManager->findOneBy(array('slug' => $serviceSlug, 'enabled' => true));
        
        if (null === $customerService){
            throw new NotFoundHttpException();
        }
        

        $template = $this->container->get('templating')->render(
            $customerService->getTemplate(), array(
                'overview' => $overview,
                'customerService' => $customerService,     

                'menu_name' => 'neutron.plugin.customer_service' . $overview->getCategory()->getId(),
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
