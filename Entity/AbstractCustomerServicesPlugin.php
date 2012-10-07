<?php
namespace Neutron\Plugin\CustomerServicesBundle\Entity;

use Neutron\Bundle\FormBundle\Model\MultiSelectSortableReferenceInterface;

use Neutron\Bundle\FormBundle\Model\MultiSelectSortableInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceReferenceInterface;

use Neutron\MvcBundle\Model\CategoriableInterface;

use Neutron\Plugin\CustomerServicesBundle\CustomerServicesPlugin;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServicesPluginInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractCustomerServicesPlugin 
    implements CustomerServicesPluginInterface, CategoriableInterface, MultiSelectSortableInterface
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
     * @var string 
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", name="title", length=255, nullable=true, unique=false)
     */
    protected $title;
    
    /**
     * @var string 
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text", name="content", nullable=true)
     */
    protected $content;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="template", length=255, nullable=true, unique=false)
     */
    protected $template;
    
    /**
     * @ORM\OneToMany(targetEntity="Neutron\Plugin\CustomerServices\Model\CustomerServiceReferenceInterface", mappedBy="customerServicesPlugin", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */  
    protected $customerServiceReferences; 
    
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * @ORM\OneToOne(targetEntity="Neutron\MvcBundle\Model\Category\CategoryInterface", cascade={"all"}, orphanRemoval=true) 
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $category;
    
    /**
     * @ORM\OneToOne(targetEntity="Neutron\SeoBundle\Entity\Seo", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $seo;
    
    public function __construct()
    {
        $this->customerServiceReferences = new ArrayCollection();
    }
    
    public function getId ()
    {
        return $this->id;
    }

	public function getTitle ()
    {
        return $this->title;
    }

	public function setTitle ($title)
    {
        $this->title = $title;
    }

	public function getContent ()
    {
        return $this->content;
    }

	public function setContent ($content)
    {
        $this->content = $content;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }

	public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
    
    public function setCategory(CategoryInterface $category)
    {
        $this->category = $category;
        return $this;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setSeo(SeoInterface $seo)
    {
        $this->seo = $seo;
        return $this;
    }
    
    public function getSeo()
    {
        return $this->seo;
    }
    
    public function getIdentifier()
    {
        return CustomerServicesPlugin::IDENTIFIER;
    }
    
    public function getReferences()
    {
        return $this->customerServiceReferences;
    }
    
    public function addReference(MultiSelectSortableReferenceInterface $reference)
    {
        if (!$this->customerServiceReferences->contains($reference)){
            $this->customerServiceReferences->add($reference);
            $reference->setCustomerServicesPlugin($this);
        }
    
        return $this;
    }
    
    public function removeReference(MultiSelectSortableReferenceInterface $reference)
    {
        if ($this->customerServiceReferences->contains($reference)){
            $this->customerServiceReferences->removeElement($reference);
        }
    
        return $this;
    }

}