<?php
namespace Neutron\Plugin\CustomerServiceBundle\Controller\Frontend;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class CustomerServiceOverviewController extends ContainerAware
{
    
    public function indexAction(CategoryInterface $category)
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(CustomerServicePlugin::IDENTIFIER);
        $mvcManager = $this->container->get('neutron_mvc.mvc_manager');
        $customerServiceOverviewManager = $this->container->get($plugin->getManagerServiceId());
        $overview = $customerServiceOverviewManager->getByCategory($category);
        
        if (null === $overview){
            throw new NotFoundHttpException();
        }

        $mvcManager->loadPanels($plugin, $overview->getId(), $plugin->getName());
       
        $template = $this->container->get('templating')->render(
            $overview->getTemplate(), array(
                'overview'   => $overview,     
                'plugin' => $plugin,
                'menu_name' => $plugin->getName() . $category->getId()
            )
        );
    
        return  new Response($template);
    }
  
}
