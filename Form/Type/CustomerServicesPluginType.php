<?php
namespace Neutron\Plugin\CustomerServicesBundle\Form\Type;

use Neutron\Bundle\DataGridBundle\DataGrid\Provider\DataGridProviderInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Neutron\MvcBundle\Form\Type\AbstractPluginInstanceType;

class CustomerServicesPluginType extends AbstractPluginInstanceType
{
    
    protected $gridName;
    
    protected $customerServicesPluginClass;
    
    protected $customerServiceClass;
    
    protected $referenceClass;
    
    public function setGridName($gridName)
    {
        $this->gridName = $gridName;
    }
    
    
    public function setCustomerServicesPluginCLass($customerServicesPluginClass)
    {   
        $this->customerServicesPluginClass = $customerServicesPluginClass;
    }
    
    public function setCustomerServiceClass($customerServiceClass)
    {
        $this->customerServiceClass = $customerServiceClass;
    }
    
    public function setReferenceClass($referenceClass)
    {
        $this->referenceClass = $referenceClass;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('services', 'neutron_multi_select_sortable_form', array(
               'label' => 'form.customerServiceReferences',
               'grid_name' => $this->gridName,
               'data_class' => $this->customerServicesPluginClass,
               'inversed_class' => $this->customerServiceClass,
               'reference_class' => $this->referenceClass,
           ))
       ;
    }
    public function getName()
    {
        return 'neutron_customer_services_plugin';
    }
}