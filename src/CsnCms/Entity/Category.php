<?php
/**
 * Coolcsn Zend Framework 2 CMS Module
 * 
 * @link https://github.com/coolcsn/CsnCms for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnCms/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
*/

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
     * Represents an User, who owns this category. Null if general category.
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @Annotation\Exclude()
     */
    protected $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    protected $id;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Set Name
     *
     * @param  string   $name
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
     * Set user
     *
     * @param  CsnUser\Entity\User $user
     * @return Category
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return CsnUser\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
