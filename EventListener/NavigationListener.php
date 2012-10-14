<?php
namespace Neutron\Plugin\CustomerServiceBundle\EventListener;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceOverviewManagerInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Knp\Menu\ItemInterface;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Neutron\MvcBundle\Model\Category\CategoryManagerInterface;

use Neutron\MvcBundle\Menu\Navigation;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

class NavigationListener
{
    protected $customerServiceOverviewManager;
    
    protected $categoryManager;

    public function __construct(CustomerServiceOverviewManagerInterface $customerServiceOverviewManager, 
        CategoryManagerInterface $categoryManager)
    {
        $this->customerServiceOverviewManager = $customerServiceOverviewManager;
        $this->categoryManager = $categoryManager;
    }
    
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        if ($event->getIdentifier() !== Navigation::IDENTIFIER){
            return;
        }
    
        $root = $event->getMenu()->getRoot();
        $factory = $event->getFactory();
        
        foreach ($this->findCustomerServiceCategories() as $category){
            $menuItem = $root->getChild(CustomerServicePlugin::IDENTIFIER . $category->getId());
            $overview = $this->customerServiceOverviewManager->getByCategory($category);
            if ($overview){
                $this->addServicesToNavigation($category, $menuItem, $overview->getReferences());
            }   
        }
    
    }
    
    protected function findCustomerServiceCategories()
    {
        return  $this->categoryManager->findBy(array(
            'type' => CustomerServicePlugin::IDENTIFIER, 
            'lvl' => 1,
            'enabled' => true,
            'displayed' => true
       ));
    }

    
    protected function addServicesToNavigation(CategoryInterface $category, ItemInterface $menuItem, $references)
    {

        foreach ($references as $reference){
            $menuItem->addChild(CustomerServicePlugin::ITEM_IDENTIFIER . $category->getId() . $reference->getInversed()->getId(), array(
                'label' => $reference->getLabel(),
                'route' => 'neutron_customer_service.frontend.customer_service',
                'routeParameters' => array(
                    'categorySlug' => $category->getSlug(), 
                    'serviceSlug' => $reference->getInversed()->getSlug(),
                ),        
            ));
        }
    }
}