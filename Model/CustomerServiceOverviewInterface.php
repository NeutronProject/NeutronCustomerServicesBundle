<?php
namespace Neutron\Plugin\CustomerServiceBundle\Model;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

interface CustomerServiceOverviewInterface extends PluginInstanceInterface
{
    public function setTemplate($template);
    
    public function getTemplate();
}