<?php
namespace Neutron\Plugin\CustomerServicesBundle\Model;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

interface CustomerServicesPluginInterface extends PluginInstanceInterface
{
    public function setTemplate($template);
    
    public function getTemplate();
}