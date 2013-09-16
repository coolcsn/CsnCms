<?php
/**
 * Coolcsn Zend Framework 2 CMS Module
 * 
 * @link https://github.com/coolcsn/CsnCms for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnCms/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Stoyan Revov <st.revov@gmail.com>
*/

namespace CsnCms\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Vote can be used to vote for other entities.
 * 
 * A vote object contains information about the current vote (it can be likes or rating, etc) and the users who participated in it. Users who have already voted should not be allowed to vote again for tha same entity.
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="likes_count", type="integer", nullable=false)
     */
    protected $likesCount = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="dislikes_count", type="integer", nullable=false)
     */
    protected $dislikesCount = 0;

    /**
     * Holds a Collection of the users who voted for the entity, this object is attached to.
     *
     * @ORM\ManyToMany(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinTable(name="votes_users",
     *      joinColumns={@ORM\JoinColumn(name="vote_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    protected $usersVoted;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function __construct()
    {
        $this->usersVoted = new ArrayCollection();
    }

    /**
     * Set likesCount
     *
     * @param int $likesCount
     * @return Vote
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;

        return $this;
    }

    /**
     * Get likesCount
     *
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }
    
    /**
     * Set dislikesCount
     *
     * @param int $dislikesCount
     * @return Vote
     */
    public function setDislikesCount($dislikesCount)
    {
        $this->dislikesCount = $dislikesCount;

        return $this;
    }

    /**
     * Get dislikesCount
     *
     * @return int
     */
    public function getDislikesCount()
    {
        return $this->dislikesCount;
    }
    
    /**
     * Get users who already voted
     *
     * @return array
     */
    public function getUsersVoted()
    {
        return $this->usersVoted;
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