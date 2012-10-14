<?php
namespace Neutron\Plugin\CustomerServiceBundle\DataGrid;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceManagerInterface;

class CustomerServiceForm
{

    const IDENTIFIER = 'customer_service_form';
    
    protected $factory;
    
    protected $translator;
    
    protected $manager;
    
    protected $translationDomain;
   

    public function __construct (FactoryInterface $factory, Translator $translator, 
        CustomerServiceManagerInterface $manager, $translationDomain)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->manager = $manager;
        $this->translationDomain = $translationDomain;
    }

    public function build ()
    {
        
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption(
                $this->translator->trans('grid.customer_service_from.title',  array(), $this->translationDomain)
            )
            ->setAutoWidth(true)
            ->setColNames(array(
                $this->translator->trans('grid.customer_service_form.title',  array(), $this->translationDomain),
                $this->translator->trans('grid.customer_service_form.slug',  array(), $this->translationDomain),
            ))
            ->setColModel(array(
                array(
                    'name' => 's.title', 'index' => 's.title', 'width' => 400, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 's.slug', 'index' => 's.slug', 'width' => 400, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 

            ))
            ->setQueryBuilder($this->manager->getQueryBuilderForCustomerServiceFormDataGrid())
            ->setSortName('s.title')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableMultiSelectSortable(true)
            ->setMultiSelectSortableColumn('s.title')
       ;

        return $dataGrid;
    }



}