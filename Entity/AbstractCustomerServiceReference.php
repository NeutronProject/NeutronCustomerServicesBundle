<?php
namespace Neutron\Plugin\CustomerServicesBundle\Entity;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceReferenceInterface;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\MappedSuperclass
 */
class AbstractCustomerServiceReference implements CustomerServiceReferenceInterface 
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="position", length=10, nullable=false, unique=false)
     */
    protected $position = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="Neutron\Plugin\CustomerServicesBundle\Model\CustomerServicesPluginInterface")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $customerServicesPlugin;
    
    /**
     * @ORM\ManyToOne(targetEntity="Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceInterface")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $reference;
    
    public function getName()
    {
        return $this->reference->getTitle();
    }
    
    public function setPosition($position)
    {
        $this->position = $position;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
	public function getCustomerServicesPlugin ()
    {
        return $this->customerServicesPlugin;
    }

	public function setCustomerServicesPlugin (PluginInstanceInterface $customerServicesPlugin)
    {
        $this->customerServicesPlugin = $customerServicesPlugin;
    }

	public function getReference ()
    {
        return $this->reference;
    }

	public function setReference ($reference)
    {
        if (!$reference instanceof CustomerServiceInterface){
            throw new \InvalidArgumentException('Reference must be instance of CustomerServiceInterface');
        }
        
        $this->reference = $reference;
    }
   
}