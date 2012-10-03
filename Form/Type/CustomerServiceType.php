<?php
/*
 * This file is part of NeutronCustomerServicesBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\CustomerServicesBundle\Form\Type;

use Symfony\Component\HttpFoundation\Request;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\Form\FormView;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

/**
 * Short description
 *
 * @author Zender <azazen09@gmail.com>
 * @since 1.0
 */
class CustomerServiceType extends AbstractType
{
    protected $plugin;
    
    public function __construct(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'neutron_customer_service_content');
        $builder->add('seo', 'neutron_seo');
        
        if (count($this->plugin->getPanels()) > 0){ 
            $builder->add('panels', 'neutron_panels', array(
                'plugin' => $this->plugin->getName(),
                'pluginIdentifier' => $this->plugin->getExtraData('itemIdentifier'),
            ));
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_customer_service';
    }
}