<?php
namespace Neutron\Plugin\CustomerServiceBundle\DataGrid;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class CustomerServiceManagement
{

    const IDENTIFIER = 'customer_service_management';
    
    protected $factory;
    
    protected $translator;
    
    protected $router;
    
    protected $manager;
    
    protected $translationDomain;
   

    public function __construct (FactoryInterface $factory, Translator $translator, Router $router, 
             $manager, $translationDomain)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->router = $router;
        $this->manager = $manager;
        $this->translationDomain = $translationDomain;
    }

    public function build ()
    {
        
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption(
                $this->translator->trans('grid.customer_services_management.title',  array(), $this->translationDomain)
            )
            ->setAutoWidth(true)
            ->setColNames(array(
                $this->translator->trans('grid.customer_services_management.title',  array(), $this->translationDomain),
                $this->translator->trans('grid.customer_services_management.slug',  array(), $this->translationDomain),
                $this->translator->trans('grid.customer_services_management.enabled',  array(), $this->translationDomain),
            ))
            ->setColModel(array(
                array(
                    'name' => 's.title', 'index' => 's.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 's.slug', 'index' => 's.slug', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 

                array(
                    'name' => 's.enabled', 'index' => 's.enabled', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'formatter' => 'checkbox',  'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(
                        1 => $this->translator->trans('grid.enabled', array(), $this->translationDomain), 
                        0 => $this->translator->trans('grid.disabled', array(), $this->translationDomain), 
                    ))
                ), 

            ))
            ->setQueryBuilder($this->manager->getQueryBuilderForCustomerServiceManagementDataGrid())
            ->setSortName('s.title')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableAddButton(true)
            ->setAddBtnUri($this->router->generate('neutron_customer_service.backend.customer_service.update', array(), true))
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_customer_service.backend.customer_service.update', array('id' => '{id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_customer_service.backend.customer_service.delete', array('id' => '{id}'), true))
        ;

        return $dataGrid;
    }



}