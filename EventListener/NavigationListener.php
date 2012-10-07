<?php
namespace Neutron\Plugin\CustomerServicesBundle\EventListener;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Knp\Menu\ItemInterface;

use Neutron\Plugin\CustomerServicesBundle\CustomerServicesPlugin;

use Neutron\MvcBundle\Model\Category\CategoryManagerInterface;

use Neutron\MvcBundle\Menu\Navigation;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

class NavigationListener
{
    protected $categoryManager;
    
    protected $plugin;
    
    public function __construct(CategoryManagerInterface $categoryManager, PluginInterface $plugin)
    {
        $this->categoryManager = $categoryManager;
        $this->plugin = $plugin;
    }
    
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
    
        if ($event->getIdentifier() !== Navigation::IDENTIFIER){
            return;
        }
    
        $root = $event->getMenu()->getRoot();
        $factory = $event->getFactory();
        
        foreach ($this->findCustomerServicesCategories() as $category){
            $menuItem = $root->getChild(CustomerServicesPlugin::IDENTIFIER . $category->getId());
            $overview = $this->plugin->getManager()->getOverviewByCategory($category);
            $this->addServicesToNavigation($category, $menuItem, $overview->getReferences());
        }
    
    }
    
    protected function findCustomerServicesCategories()
    {
        return  $this->categoryManager->findBy(array(
            'type' => CustomerServicesPlugin::IDENTIFIER, 
            'lvl' => 1,
            'enabled' => true,
            'displayed' => true
       ));
    }

    
    protected function addServicesToNavigation(CategoryInterface $category, ItemInterface $menuItem, $references)
    {

        foreach ($references as $reference){
            $menuItem->addChild(CustomerServicesPlugin::ITEM_IDENTIFIER . $category->getId() . $reference->getInversed()->getId(), array(
                'label' => $reference->getInversed()->getTitle(),
                'route' => 'neutron_customer_services.frontend.item',
                'routeParameters' => array(
                    'categorySlug' => $category->getSlug(), 
                    'serviceSlug' => $reference->getInversed()->getSlug(),
                ),        
            ));
        }
    }
}