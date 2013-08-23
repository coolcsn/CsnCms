<?php
namespace CsnCms\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 * @Annotation\Name("Category")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Category
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Category Name:"})	 
     */
    protected $name;

    /**
     * Bidirectional - Not neccessary !!! many category to many Article (INVERSE SIDE)
     *
     * @ORM\ManyToMany(targetEntity="CsnCms\Entity\Article", mappedBy="categories")
     * @Annotation\Exclude()
     */
    protected $articles;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
     */
    protected $id;

    public function __construct() {
        $this->articles = new ArrayCollection();
    }
	
    /**
     * Set Name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get Name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get articles
     *
     * @return array 
     */	
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add article
     *
     * @return Collection 
     */	
    public function addArticle(\CsnCms\Entity\Article $article) 
    {
        return $this->articles[] = $article;
    }

    public function removeArticle(\CsnCms\Entity\Article $article) 
    {
        $this->articles->removeElement($article);
    }
	
    /**
     * Get Id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}