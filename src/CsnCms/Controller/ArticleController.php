<?php

namespace CsnCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use CsnCms\Entity\Article;
// Pagination
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class ArticleController extends AbstractActionController {

    public function __construct() {
        //new Paginator();
    }

    // R - retrieve
    public function indexAction() {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $dql = "SELECT a, u, l, c FROM CsnCms\Entity\Article a LEFT JOIN a.author u LEFT JOIN a.language l LEFT JOIN a.categories c WHERE a.parent IS NULL";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(30);
        $articles = $query->getResult();

        $repository = $entityManager->getRepository('CsnCms\Entity\Article');
        $adapter = new DoctrineAdapter(new ORMPaginator($repository->createQueryBuilder('article')));

        // Create the paginator itself
        $paginator = new Paginator($adapter);
        $page = 1;
        if ($this->params()->fromRoute('page'))
            $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(5);


        return new ViewModel(array('articles' => $articles, 'paginator' => $paginator));
    }

    // C - create
    public function addAction() {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $article = new Article;
        $form = $this->getForm($article, $entityManager, 'Add');

        $form->bind($article);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $this->prepareData($article);
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    // U - update
    public function editAction() {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        try {
            $article = $entityManager->find('CsnCms\Entity\Article', $id);
        } catch (\Exception $ex) {
            echo $ex->getMessage(); // this will never be seen if you don't comment the redirect

            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
        }

        $form = $this->getForm($article, $entityManager, 'Update');
        $form->bind($article);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                //$this->prepareData($article);
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    // D - delete
    public function deleteAction() {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $repository = $entityManager->getRepository('CsnCms\Entity\Article');
        $article = $repository->find($id);

        if ($article) {
            //Delete all comments first
            $repository = $entityManager->getRepository('CsnCms\Entity\Comment');
            $comments = $repository->find($id);

            if ($comments) {
                foreach ($comments as $comment) {
                    $entityManager()->remove($comment);
                }
            }

            //Delete votes
            $voteId = $article->getVote();
            $entityManager->remove($voteId);
            //Delete the article
            $entityManager->remove($article);
            $entityManager->flush();
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
        }

        return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
    }

    public function viewAction() {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'index', 'action' => 'index'));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        try {
            $article = $entityManager->find('CsnCms\Entity\Article', $id);
            if (!is_object($article)) {
                return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'index', 'action' => 'index'));
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage(); // this will never be seen if you don't comment the redirect

            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'index', 'action' => 'index'));
        }

        //--- Decide whether the user has access to this article ---------------
        $resource = $article->getResource()->getName();
        $privilege = 'view';
        if (!$this->isAllowed($resource, $privilege)) {
            return $this->redirect()->toRoute('home');
        }
        //END --- Decide whether the user has access to this article -----------

        //--- Increase the View Count ------------------------------------------
        $counterViews = $article->getViewCount();
        $counterViews +=1;
        $article->setViewCount($counterViews);
        $entityManager->persist($article);
        $entityManager->flush();
        //END --- Increase the View Count --------------------------------------
        
        //--- Get all comments -------------------------------------------------
        $dql = "SELECT c, a FROM CsnCms\Entity\Comment c LEFT JOIN c.article a WHERE a.id = ?1";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(30);
        $query->setParameter(1, $id);
        $comments = $query->getResult();
        //END --- Get all comments ---------------------------------------------

        if ($article->getLayout()) {$this->layout($article->getLayout());}

        return new ViewModel(array('article' => $article, 'comments' => $comments));
    }

    public function voteAction() {
        $id2 = $this->params()->fromRoute('id2');
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'index', 'action' => 'index'));
        }
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $article = $entityManager->find('CsnCms\Entity\Article', $id);

        $currentVoteCount = 0;

        if ($id2 > 0) {
            $currentVoteCount = $article->getVote()->getLikesCount();
            $currentVoteCount++;
            $article->getVote()->setLikesCount($currentVoteCount);
        } else {
            $currentVoteCount = $article->getVote()->getDislikesCount();
            $currentVoteCount++;
            $article->getVote()->setDislikesCount($currentVoteCount);
        }

        $usersVoted = $article->getVote()->getUsersVoted();
        $usersVoted[] = $this->identity();

        try {

            $entityManager->persist($article);
            $entityManager->flush();
        } catch (\Exception $ex) {

        }



        return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'view', 'id' => $id));
    }

    public function getForm($article, $entityManager, $action) {
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm($article);

        //!!!!!! Start !!!!! Added to make the association tables work with select
        foreach ($form->getElements() as $element) {
            if (method_exists($element, 'getProxy')) {
                $proxy = $element->getProxy();
                if (method_exists($proxy, 'setObjectManager')) {
                    $proxy->setObjectManager($entityManager);
                }
            }
        }

        $form->remove('created');
        $form->remove('parent');
        $form->remove('author');
        $form->setHydrator(new DoctrineHydrator($entityManager, 'CsnCms\Entity\Article'));
        $send = new Element('send');
        $send->setValue($action); // submit
        $send->setAttributes(array(
            'type' => 'submit'
        ));
        $form->add($send);

        return $form;
    }

    public function prepareData($article) {
        $article->setCreated(new \DateTime());
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
        }
        $article->setAuthor($user);

        $vote = new \CsnCms\Entity\Vote();
        $article->setVote($vote);
    }
    
}
