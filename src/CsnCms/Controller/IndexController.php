<?php
namespace CsnCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $dql = "SELECT a FROM CsnCms\Entity\Article a WHERE a.parent IS NULL ORDER BY a.created DESC";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(30);
        $articles = $query->getResult();

        //Top 5 commented articles;

        $dql = "SELECT count(a.id) as total, a.id FROM CsnCms\Entity\Article a, CsnCms\Entity\Comment c where a.id = c.article GROUP BY a.id ORDER BY total DESC";
        $query = $entityManager->createQuery($dql);
        $result = $query->getResult();

        //second way;
        //http://stackoverflow.com/questions/11137395/doctrine-2-does-not-recognize-select-on-the-from-clause

        //$qb = $entityManager->createQueryBuilder();
        //$qb->select(array('count(a.id) as total, a.id'))
        //->from('CsnCms\Entity\Article a, CsnCms\Entity\Comment c')
        //->where('a.id = c.article')
        //->groupBy('a.id')
        //->orderBy('total','DESC'); //missing the object.. follow bottom steps;

        //$result = $qb->getQuery()->getResult();

        $dql1 = '';
        $mostC = '';
		$mostCommentedArticles = Array();
		$countOfComments = Array();
        foreach ($result as $resul) {
            $dql1 = "SELECT a FROM CsnCms\Entity\Article a where a.id = ". $resul['id'];
            //echo $dql1;
            $query = $entityManager->createQuery($dql1);
            $query->setMaxResults(5);
            $result1 = $query->getResult();
            $mostCommentedArticles[] = $result1[0]; //List.Add($result[0]);
            $countOfComments[] = $resul['total'];
        }

        $dql = "SELECT a FROM CsnCms\Entity\Article a WHERE a.parent IS NULL ORDER BY a.viewCount DESC";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(5);
        $mostPreviewedArticles = $query->getResult();

        return new ViewModel(array('articles' => $articles, 'mostCommentedArticles' => $mostCommentedArticles, 'countOfComments' => $countOfComments,
        'mostPreviewedArticles' => $mostPreviewedArticles));
    }

}
