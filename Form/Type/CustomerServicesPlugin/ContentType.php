<?php 
namespace Neutron\Plugin\CustomerServicesBundle\Form\Type\CustomerServicesPlugin;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class ContentType extends AbstractType
{    
    protected $pluginClass;
    
    protected $translationDomain;

    protected $templates;
    
    protected $allowedRoles = array('ROLE_SUPER_ADMIN');
    

    public function __construct($pluginClass, array $templates, $translationDomain)
    {
        $this->pluginClass = $pluginClass;
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
            'data_class' => $this->pluginClass,
            'validation_groups' => function(FormInterface $form){
                return 'default';
            },
        ));
    }
    
    public function getName()
    {
        return 'neutron_customer_services_plugin_content';
    }
   
}