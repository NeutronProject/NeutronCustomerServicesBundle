<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

use Neutron\AdminBundle\Acl\AclManagerInterface;

class CustomerServiceOverviewType extends AbstractType
{
    protected $plugin;
    
    protected $aclManager;
    
    protected $dataGridName;
    
    protected $customerServiceOverviewClass;
    
    protected $customerServiceClass;
    
    protected $CustomerServiceReferenceClass;
    

    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function setAclManager(AclManagerInterface $aclManager)
    {
        $this->aclManager = $aclManager;
        return $this;
    }
    
    public function setDataGridName($dataGridName)
    {
        $this->dataGridName = $dataGridName;
    }
    
    
    public function setCustomerServiceOverviewCLass($customerServiceOverviewClass)
    {   
        $this->customerServiceOverviewClass = $customerServiceOverviewClass;
    }
    
    public function setCustomerServiceClass($customerServiceClass)
    {
        $this->customerServiceClass = $customerServiceClass;
    }
    
    public function setCustomerServiceReferenceClass($customerServiceReferenceClass)
    {
        $this->CustomerServiceReferenceClass = $customerServiceReferenceClass;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('general', 'neutron_category');
        $builder->add('content', 'neutron_customer_service_overview_content');
        $builder->add('services', 'neutron_multi_select_sortable_form', array(
            'grid_name' => $this->dataGridName,
            'data_class' => $this->customerServiceOverviewClass,
            'inversed_class' => $this->customerServiceClass,
            'reference_class' => $this->CustomerServiceReferenceClass,
        ));
        
        $builder->add('seo', 'neutron_seo');
        
        if (count($this->plugin->getPanels()) > 0){
            $builder->add('panels', 'neutron_panels', array(
                'plugin' => $this->plugin->getName(),
                'pluginIdentifier' => $this->plugin->getName(),
            ));
        }
        
        if ($this->aclManager->isAclEnabled()){
            $builder->add('acl', 'neutron_admin_form_acl_collection', array(
                'masks' => array(
                    'VIEW' => 'View',
                ),
            ));
        }

    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }
    
    public function getName()
    {
        return 'neutron_customer_service_overview';
    }
}