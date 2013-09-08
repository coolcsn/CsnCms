<?php

namespace CsnCms\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;
// children - are the transaltions
// parent - is the original article

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="CsnCms\Entity\Repository\CommentRepository")
 * @Annotation\Name("Comment")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Comment
{
    /**
     * @var CsnUser\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Options({
     * "label":"Language:",
     * "empty_option": "Please, choose your language",
     * "target_class":"CsnUser\Entity\Language",
     * "property": "name"})
     */
    protected $language;

    /**
     * @var CsnUser\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Options({
     * "label":"Author:",
     * "empty_option": "Please, choose the Author",
     * "target_class":"CsnUser\Entity\User",
     * "property": "username"})
     */
    protected $author;
	
    /**
     * @var CsnCms\Entity\Article
     *
	 * @ORM\ManyToOne(targetEntity="CsnCms\Entity\Article", inversedBy="comments")
	 * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
	 * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	 * @Annotation\Options({
	 * "label":"Article:",
	 * "empty_option": "Please, choose the Article",
	 * "target_class":"CsnCms\Entity\Article",
	 * "property": "article"})
     */
	 
    private $article;
	
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":100}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,100}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Title:"})	 
     */
    private $title;
	
	/**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     * @Annotation\Attributes({"type":"textarea"})
     * @Annotation\Options({"label":"Text:"})	 
     */
    private $text;
	
    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"Zend\Form\Element\DateTime", "id": "created", "min":"2010-01-01T00:00:00Z", "max":"2020-01-01T00:00:00Z", "step":"1"})
     * @Annotation\Options({"label":"Date\Time:", "format":"Y-m-d\TH:iP"})	 
     */ 
	 
    protected $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
     */
    private $id;

    public function __construct() {
	
    }
	public function __toString()
	{
        return $this->author . ' -> '.$this->text ;
    }
	
    /**
     * Set language
     *
     * @param CsnUser\Entity\Language $language
     * @return Article
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return CsnUser\Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set author
     *
     * @param CsnUser\Entity\User $author
     * @return CsnCms\Entity\Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return ORM\ManyToMany\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }	
	
	/**
     * Set article
     *
     * @param CsnCms\Entity\Article $article
     * @return CsnCms\Entity\Comment
     */
    public function setArticle($article)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return CsnCms\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
    	

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param DateTime $created
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	
}

/*
@var CsnCms\Entity\Article

 @ORM\ManyToOne(targetEntity="CsnCms\Entity\Article") - Unidirectional
*/