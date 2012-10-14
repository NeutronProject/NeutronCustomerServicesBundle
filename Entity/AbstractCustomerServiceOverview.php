<?php
namespace Neutron\Plugin\CustomerServiceBundle\Entity;

use Neutron\SeoBundle\Model\SeoAwareInterface;

use Neutron\Plugin\CustomerServiceBundle\CustomerServicePlugin;

use Neutron\MvcBundle\Model\CategoryAwareInterface;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceOverviewInterface;

use Neutron\Bundle\FormBundle\Model\MultiSelectSortableReferenceInterface;

use Neutron\Bundle\FormBundle\Model\MultiSelectSortableInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceReferenceInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;


use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractCustomerServiceOverview 
    implements CustomerServiceOverviewInterface, CategoryAwareInterface, 
                MultiSelectSortableInterface, SeoAwareInterface
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
     * @ORM\OneToMany(targetEntity="Neutron\Plugin\CustomerService\Model\CustomerServiceReferenceInterface", mappedBy="customerServiceOverview", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */  
    protected $references; 
    
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
     * @ORM\OneToOne(targetEntity="Neutron\SeoBundle\Entity\Seo", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $seo;
    
    public function __construct()
    {
        $this->references = new ArrayCollection();
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
        return CustomerServicePlugin::IDENTIFIER;
    }
    
    public function getReferences()
    {
        return $this->references;
    }
    
    public function addReference(MultiSelectSortableReferenceInterface $reference)
    {
        if (!$this->references->contains($reference)){
            $this->references->add($reference);
            $reference->setCustomerServiceOverview($this);
        }
    
        return $this;
    }
    
    public function removeReference(MultiSelectSortableReferenceInterface $reference)
    {
        if ($this->references->contains($reference)){
            $this->references->removeElement($reference);
        }
    
        return $this;
    }

}