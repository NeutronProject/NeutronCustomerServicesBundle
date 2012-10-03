<?php
/*
 * This file is part of NeutronCustomerServicesBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\CustomerServicesBundle\Entity;

use Neutron\Plugin\CustomerServicesBundle\CustomerServicesPlugin;

use Neutron\MvcBundle\Model\SluggableInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class AbstractCustomerService implements CustomerServiceInterface, SluggableInterface
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
     * @ORM\Column(type="string", name="title", length=255, nullable=false, unique=false)
     */
    protected $title;
    
    /**
     * @var string 
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", name="slug", length=255, nullable=false, unique=true)
     */
    protected $slug;
    
    /**
     * @var string 
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", name="description", length=255, nullable=true, unique=false)
     */
    protected $description;
    
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
     * @var boolean 
     *
     * @ORM\Column(type="boolean", name="enabled")
     */
    protected $enabled = false;
    
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * @ORM\OneToOne(targetEntity="Neutron\SeoBundle\Entity\Seo", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $seo;
    
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
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }

	public function getDescription ()
    {
        return $this->description;
    }

	public function setDescription ($description)
    {
        $this->description = $description;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
	public function getEnabled ()
    {
        return $this->enabled;
    }

	public function setEnabled ($enabled)
    {
        $this->enabled = $enabled;
    }
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
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
        return CustomerServicesPlugin::ITEM_IDENTIFIER;
    }

}