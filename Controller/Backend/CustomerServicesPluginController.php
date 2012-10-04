<?php
namespace Neutron\Plugin\CustomerServicesBundle\Controller\Backend;

use Neutron\MvcBundle\Controller\Backend\AbstractPluginInstanceController;

use Symfony\Component\DependencyInjection\ContainerAware;

class CustomerServicesPluginController extends AbstractPluginInstanceController
{
    protected function getData($id)
    {
        $data = parent::getData($id);
        $data['services'] = $data['instance'];
        return $data;
    }
}
