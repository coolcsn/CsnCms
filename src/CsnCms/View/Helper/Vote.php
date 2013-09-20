<?php

/**
 * Coolcsn Zend Framework 2 CMS Module
 * 
 * @link https://github.com/coolcsn/CsnCms for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnCms/blob/master/LICENSE BSDLicense
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 */

namespace CsnCms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Vote extends AbstractHelper {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Generates html with options to vote and display current viewCount
     * 
     * @param \CsnCms\Entity\Vote $vote
     * @param string $likeUrl
     * @param string $dislikeUrl
     * @return string
     */
    public function __invoke(\CsnCms\Entity\Vote $vote, $likeUrl, $dislikeUrl) {
        $hasUserVoted = $this->hasUserVoted($vote);

        $result = '<p>';

        switch ($hasUserVoted) {
            case -1:
                $result .= $this->getView()->translate('Only registered users can vote.');
                break;
            case 0:
                $result .= '<a href="' . $likeUrl . '">' . $this->getView()->translate('Like') . '</a>' .
                        '<a href="' . $dislikeUrl . '">' . $this->getView()->translate('Dislike') . '</a>';
                break;
            case 1:
                $result .= $this->getView()->translate('Already voted!');
        }

        $result .= '</p>';

        $result .=
                '<p>' .
                $this->getView()->translate('Likes') . ': ' . '<span>' . $vote->getLikesCount() . '</span>' . ' ' .
                $this->getView()->translate('Disikes') . ': ' . '<span>' . $vote->getDislikesCount() . '</span>' .
                '</p>';

        return $result;
    }

    /**
     * Checks if the current user has already voted for the entity
     * 
     * @param \CsnCms\Entity\Vote $vote
     * @return integer 1 if voted, 0 if not, -1 if is not allowed to vote.
     */
    protected function hasUserVoted($vote) {
        $dql = "SELECT count(v.id) FROM CsnCms\Entity\Vote v LEFT JOIN v.usersVoted u WHERE v.id = ?0 AND u.id =?1";
        $query = $this->entityManager->createQuery($dql);

        $voteId = $vote->getId();

        $user = $this->getView()->identity();
        $hasUserVoted = -1;

        if ($voteId != null && $user != null) {
            $userId = $user->getId();
            $query->setParameter(0, $voteId);
            $query->setParameter(1, $userId);
            $hasUserVoted = $query->getSingleScalarResult();
        }

        return $hasUserVoted;
    }

}