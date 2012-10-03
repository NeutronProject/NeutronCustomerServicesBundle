<?php
namespace Neutron\Plugin\CustomerServicesBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Neutron\MvcBundle\Form\Type\AbstractPluginInstanceType;

class CustomerServicesPluginType extends AbstractPluginInstanceType
{
    public function getName()
    {
        return 'neutron_customer_services_plugin';
    }
}