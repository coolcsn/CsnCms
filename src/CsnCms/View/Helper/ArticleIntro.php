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

namespace CsnCms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ArticleIntro extends AbstractHelper {
    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Displays an article's introtext if the user has access to it,
     * otherwise returns an empty string.
     * 
     * @param int $id
     * @param int $indent Number of white spaces to prepend. Defaults to 0.
     * @throws Exception If an article with this id is not found.
     */
    public function __invoke($id, $indentSpaces = 0) {
        $article = $this->entityManager->find('CsnCms\Entity\Article', $id);
        if(!$article) {
            throw new \Exception('Article with id=' . $id . ' not found.');
        }
        if($this->getView()->isAllowed($article->getResource()->getName(), 'view')) {
            $indent = \str_repeat(' ', $indentSpaces);
            
            $html  =           '<article>' . PHP_EOL;
            $html .= $indent . '    <h3>' . PHP_EOL;
            $html .= $indent . '        <a href="' . $this->getView()->url('csn-cms/default',
                            array('controller' => 'article', 'action'=>'view', 'id' => $article->getId())) . '">'
                            . $article->getTitle() . '</a>' . PHP_EOL;
            $html .= $indent . '    </h3>' . PHP_EOL;
            $html .= $indent . '    <p class="article-introtext">' . $article->getIntrotext() . '</p>' . PHP_EOL;
            $html .= $indent . '</article>' . PHP_EOL;
            
            return $html;
        } else {
            return "";
        }
    }
}