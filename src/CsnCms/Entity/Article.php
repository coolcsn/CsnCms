<?php
/**
 * Coolcsn Zend Framework 2 CMS Module
 * 
 * @link https://github.com/coolcsn/CsnCms for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnCms/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
*/

namespace CsnCms\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

// children - are the transaltions
// parent - is the original article

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity
 * @Annotation\Name("Article")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Article
{
    /**
     * @ORM\OneToMany(targetEntity="CsnCms\Entity\Article", mappedBy="parent")
     * @Annotation\Exclude()
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="CsnCms\Entity\Article", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Required(false)
     * @Annotation\Options({
     * "label":"Original Article:",
     * "empty_option": "Please, choose the Original Article",
     * "target_class":"CsnCms\Entity\Article",
     * "property": "title"})
     */
    protected $parent = null;

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
     * @var CsnCms\Entity\Resource
     *
     * @ORM\ManyToOne(targetEntity="CsnCms\Entity\Resource")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Options({
     * "label":"Resource:",
     * "empty_option": "Please, choose the Resource",
     * "target_class":"CsnCms\Entity\Resource",
     * "property": "name"})
     */
    protected $resource;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":100}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_ -]{0,100}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Title:"})
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":100}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,100}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Slug:"})
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="intro_text", type="text", nullable=true)
     * @Annotation\Attributes({"type":"textarea"})
     * @Annotation\Options({"label":"Intro Text:"})
     */
    protected $introtext;

    /**
     * @var string
     *
     * @ORM\Column(name="full_text", type="text", nullable=true)
     * @Annotation\Attributes({"type":"textarea"})
     * @Annotation\Options({"label":"Full Text:"})
     */
    protected $fulltext;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"Zend\Form\Element\DateTime", "id": "created", "min":"2010-01-01T00:00:00Z", "max":"2020-01-01T00:00:00Z", "step":"1"})
     * @Annotation\Options({"label":"Date\Time:", "format":"Y-m-d\TH:iP"})
     */
    protected $created;

    /**
     * @var CsnCms\Entity\Category
     *
     * @ORM\ManyToMany(targetEntity="CsnCms\Entity\Category", inversedBy="articles")
     * @ORM\JoinTable(name="articles_categories",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"multiple":true})
     * @Annotation\Options({
     * "label":"Categories:",
     * "empty_option": "Please, choose the categories",
     * "target_class":"CsnCms\Entity\Category",
     * "property": "name"})
     */
    protected $categories;

    /**
     * @var Comment[]
     *
     * @ORM\OneToMany(targetEntity="CsnCms\Entity\Comment", mappedBy="article")
     * @Annotation\Exclude()
     */
    protected $comments;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_comments", type="boolean", nullable=true)
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({
     * "label":"Allow comments:"})
     */
    protected $allowComments = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="view_count", type="integer", nullable=false)
     * @Annotation\Exclude()
     */
    protected $viewCount = 0;
	
    /**
     * @var Vote
     *
     * @ORM\OneToOne(targetEntity="Vote")
     * @ORM\JoinColumn(name="vote_id", referencedColumnName="id")
     * @Annotation\Exclude()
     */
    protected $vote = 0;

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
        $this->children = new ArrayCollection;
        $this->categories = new ArrayCollection;
        $this->comments = new ArrayCollection;
        $this->created = new \DateTime();
    }

    /**
     * Set language
     *
     * @param  CsnUser\Entity\Language $language
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
     * @param  CsnUser\Entity\User   $author
     * @return CsnCms\Entity\Article
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
     * Set resource
     *
     * @param  CsnCms\Entity\Resource $resource
     * @return CsnCms\Entity\Article
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return CsnCms\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param  string  $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set Slug
     *
     * @param  string  $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get Introtext
     *
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * Set Introtext
     *
     * @param  string  $introtext
     * @return Article
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;

        return $this;
    }

    /**
     * Get Fulltext
     *
     * @return string
     */
    public function getFulltext()
    {
        return $this->fulltext;
    }

    /**
     * Set Fulltext
     *
     * @param  string  $fulltext
     * @return Article
     */
    public function setFulltext($fulltext)
    {
        $this->fulltext = $fulltext;

        return $this;
    }

    /**
     * Get Created
     *
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set Created
     *
     * @param  DateTime $created
     * @return Article
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get categories
     *
     * @return Array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param  array   $categories
     * @return Article
     */
    public function setCategories($categories)
    {
        $this->categories = $categories; // NOT neccessary

        return $this;
    }

    /**
     * Add Catgories
     *
     * @param  Collection $categories
     * @return Article
     */
    public function addCategories(Collection $categories)
    {
        foreach ($categories as $category) {
                $this->addCategory($category);
        }

        return $this;
    }

    /**
     * Add Catgory
     *
     * @param  CsnCms\Entity\Category $category
     * @return Article
     */
    public function addCategory(\CsnCms\Entity\Category $category)
    {
        $category->addArticle($this); // synchronously updating inverse side
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove Categories
     *
     * @param  Collection $categories
     * @return Article
     */
    public function removeCategories(Collection $categories)
    {
        foreach ($categories as $category) {
                $this->removeCategory($category);
        }

        return $this;
    }

    /**
     * Remove Category
     *
     * @param  CsnCms\Entity\Category $category
     * @return Article
     */
    public function removeCategory(\CsnCms\Entity\Category $category)
    {
        $this->categories->removeElement($category);
        $category->removeArticle($this); // update the other site

        return $this;
    }

    /**
     * Get children
     *
     * @return Array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param  array   $children
     * @return Article
     */
    public function setChildren($children)
    {
        $this->children = $children; // NOT neccessary

        return $this;
    }

    /**
     * Add Child - translation
     *
     * @param  Collection $children
     * @return Article
     */
    public function addChildren(Collection $children)
    {
        foreach ($children as $child) {
                $this->addChild($child);
        }

        return $this;
    }

    /**
     * Add Child
     *
     * @param  CsnCms\Entity\Article $child
     * @return Article
     */
    public function addChild(\CsnCms\Entity\Article $child)
    {
        $child->setParent($this); // synchronously updating inverse side
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove Children
     *
     * @param  Collection $children
     * @return Article
     */
    public function removeChildren(Collection $children)
    {
        foreach ($children as $child) {
                $this->removeChild($child);
        }

        return $this;
    }

    /**
     * Remove Child
     *
     * @param  \CsnCms\Entity\Article $child
     * @return Article
     */
    public function removeChild(\CsnCms\Entity\Article $child)
    {
        $this->children->removeElement($child);
        $child->removeParent($this); // update the other site

        return $this;
    }

    /**
     * Get parent
     *
     * @return \CsnCms\Entity\Article
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param  \CsnCms\Entity\Article $parent
     * @return Article
     */
    public function setParent($parent) //can be null in this case
    {
        //public function setParent(\CsnCms\Entity\Article $parent) doesn't work with null parent
        $this->parent = $parent;
        //if ($parent) $parent->addChild($this); // Max nested functions update the inverse site
        return $this;
    }

    /**
     * Remove parent
     *
     * @return Article
     */
    public function removeParent(\CsnCms\Entity\Article $parent)
    {
        $this->parent = null;
        // $this->parent->removeElement($parent);
        // $parent->removeChild($this); // update othe site
        return $this;
    }

    /**
     * Get comments
     *
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get allowComments
     *
     * @return boolean
     */
    public function getAllowComments()
    {
        return $this->allowComments;
    }

    /**
     * Set allowComments
     *
     * @param  boolean $allowComments
     * @return Article
     */
    public function setAllowComments($allowComments)
    {
        $this->allowComments = $allowComments;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set viewCount
     *
     * @param  boolean $viewCount
     * @return Article
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }
	
    /**
     * Get vote
     *
     * @return Vote
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set vote
     *
     * @param  Vote $vote
     * @return Article
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
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
