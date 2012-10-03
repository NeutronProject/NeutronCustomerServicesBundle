<?php
namespace Neutron\Plugin\CustomerServicesBundle\Model;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

use Neutron\SeoBundle\Model\SeoInterface;

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
    
    public function setSeo(SeoInterface $seo);
    
    public function getSeo();
}