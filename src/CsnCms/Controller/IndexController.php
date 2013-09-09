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
        
        return new ViewModel(array('articles' => $articles));
    }
}