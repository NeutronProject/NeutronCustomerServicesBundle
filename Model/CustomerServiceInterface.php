<?php
namespace Neutron\Plugin\CustomerServiceBundle\Model;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

interface CustomerServiceInterface extends PluginInstanceInterface
{
    public function getTitle ();
    
    public function setTitle ($title);
    
    public function getDescription ();
    
    public function setDescription ($description);
    
    public function setContent($content);
    
    public function getContent();
    
    public function getEnabled ();
    
    public function setEnabled ($enabled);
    
}