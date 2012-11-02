<?php 
namespace Neutron\Plugin\CustomerServiceBundle\Form\Type\CustomerServiceOverview;

use Neutron\Bundle\DataGridBundle\DataGrid\DataGridInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class ContentType extends AbstractType
{    
    protected $dataGrid;
    
    protected $customerServiceOverviewClass;
    
    protected $customerServiceClass;
    
    protected $customerServiceReferenceClass;
    
    protected $translationDomain;

    protected $templates;
    
    protected $allowedRoles = array('ROLE_SUPER_ADMIN');
    

    public function __construct(DataGridInterface $dataGrid, $customerServiceOverviewClass, $customerServiceClass, $customerServiceReferenceClass, array $templates, $translationDomain)
    {
        $this->dataGrid = $dataGrid;
        $this->customerServiceOverviewClass = $customerServiceOverviewClass;
        $this->customerServiceClass = $customerServiceClass;
        $this->customerServiceReferenceClass = $customerServiceReferenceClass;
        $this->translationDomain = $translationDomain;
        $this->templates = $templates;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
           ->add('title', 'text', array(
               'label' => 'form.title',
               'translation_domain' => $this->translationDomain
           ))
           ->add('content', 'neutron_tinymce', array(
               'label' => 'Content',
               'translation_domain' => $this->translationDomain,
               'security' => $this->allowedRoles,
               'configs' => array(
                   'theme' => 'advanced', //simple
                   'skin'  => 'o2k7',
                   'skin_variant' => 'black',
                   'height' => 300,
                   'dialog_type' => 'modal',
                   'readOnly' => false,
               ),
           ))
           ->add('references', 'neutron_multi_select_sortable_collection', array(
               'label' => 'form.services',
               'grid' => $this->dataGrid,
               'translation_domain' => $this->translationDomain,
               'options' => array(
                   'data_class' => $this->customerServiceReferenceClass,
                   'inversed_class' => $this->customerServiceClass,
                   'inversed_name' => 'inversed',
               )
           ))
           ->add('template', 'choice', array(
               'choices' => $this->templates,
               'multiple' => false,
               'expanded' => false,
               'attr' => array('class' => 'uniform'),
               'label' => 'form.template',
               'empty_value' => 'form.empty_value',
               'translation_domain' => $this->translationDomain
           ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->customerServiceOverviewClass,
            'validation_groups' => function(FormInterface $form){
                return 'default';
            },
        ));
    }
    
    public function getName()
    {
        return 'neutron_customer_service_overview_content';
    }
   
}