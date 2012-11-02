<?php
namespace Neutron\Plugin\CustomerServiceBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

use Neutron\AdminBundle\Acl\AclManagerInterface;

class CustomerServiceOverviewType extends AbstractType
{

    protected $aclManager;
    
    public function setAclManager(AclManagerInterface $aclManager)
    {
        $this->aclManager = $aclManager;
        return $this;
    }
  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('general', 'neutron_category');
        $builder->add('content', 'neutron_customer_service_overview_content');
        $builder->add('seo', 'neutron_seo');
 
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