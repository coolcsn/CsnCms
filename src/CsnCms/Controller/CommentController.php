<?php

namespace CsnCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Form\Annotation\AnnotationBuilder;

use Zend\Form\Element;

// hydration tests
use Zend\Stdlib\Hydrator;

// for Doctrine annotation
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;

//- use Doctrine\Common\Persistence\ObjectManager;

use CsnCms\Entity\Comment;

class CommentController extends AbstractActionController
{
    public function indexAction()
	{
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'index', 'action' => 'index'));
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');	
		$dql = "SELECT c, u, l, a  FROM CsnCms\Entity\Comment c LEFT JOIN c.author u LEFT JOIN c.language l LEFT JOIN c.article a WHERE a.id = ?1";
		$query = $entityManager->createQuery($dql);
		$query->setMaxResults(30);
		$query->setParameter(1, $id);
		// I will get a collection of Articles
		$comments = $query->getResult();	
		return new ViewModel(array(
			'id' => $id,
			'comments' => $comments
		));		        		
	}

    public function addAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
		
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$comment = new Comment;
		try {
			$repository = $entityManager->getRepository('CsnCms\Entity\Article');
			$article = $repository->findOneBy(array('id' => $id));			
			$comment->setArticle($article);
        }
		catch (\Exception $ex) {
           return $this->redirect()->toRoute('csn-cms/default', array(
               'controller' => 'index',
				'action' => 'index'
            ));
        }
		$builder = new DoctrineAnnotationBuilder($entityManager);
		$form = $builder->createForm( $comment );

		$form->remove('created');
		$form->remove('author');
		$form->remove('article');
		$form->remove('language');
                //$idd = $form->setUseHiddenElement('id');
                
                echo '<pre>';
                //var_dump($form);
                echo '</pre>';
                
		
		$repository = $entityManager->getRepository('CsnUser\Entity\Language');
		$language = $repository->findOneBy(array('abbreviation' => 'en'));	
		$comment->setLanguage($language);
		
		
		foreach ($form->getElements() as $element){
			if(method_exists($element, 'getProxy')){                
				$proxy = $element->getProxy();
				if(method_exists($proxy, 'setObjectManager')){  
					$proxy->setObjectManager($entityManager);
				}
			}           
		}

		$form->setHydrator(new DoctrineHydrator($entityManager,'CsnCms\Entity\Comment'));

		$send = new Element('send');
		$send->setValue('Add'); // submit
		$send->setAttributes(array(
			'type'  => 'submit'
		));
		$form->add($send);
		$form->bind($comment);

        $request = $this->getRequest();
        if ($request->isPost()) {
			 $form->setData($request->getPost());
			  if ($form->isValid()) {
				$this->prepareData($comment);
				$entityManager->persist($comment);
				$entityManager->flush();
                return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'comment', 'action' => 'index', 'id' => $id), true);	  
			  }
		}

        return new ViewModel(array(
			'id' => $id,
			'form' => $form
		));
		/*
		
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$comment = new Comment;
        //$article = new Article;
        $form = $this->getForm($comment, $entityManager, 'Add');

        $form->bind($comment);

        $request = $this->getRequest();
        if ($request->isPost()) {
                $post = $request->getPost();
                $form->setData($post);
                if ($form->isValid()) {
                    $this->prepareData($comment);
                    $entityManager->persist($comment);
                    $entityManager->flush();
                    return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'comment', 'action' => 'index'));				
                }
        }
		
		//return new ViewModel(array('form' => $form,'id'=>$id));
		/*		
		/*/
	}

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id2', 0);		
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array(
                'controller' => 'comment',
				'action' => 'add'
            ), true);
        }
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        try {
			$repository = $entityManager->getRepository('CsnCms\Entity\Comment');
			$comment = $repository->getCommentForEdit($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('csn-cms/default', array(
                'controller' => 'comment',
				'action' => 'index'
            ), true);
        }			

		$builder = new DoctrineAnnotationBuilder($entityManager);
		$form = $builder->createForm( $comment );
		$form->remove('created');
		$form->remove('author');
		$form->remove('article');

		foreach ($form->getElements() as $element){
			if(method_exists($element, 'getProxy')){                
				$proxy = $element->getProxy();
				if(method_exists($proxy, 'setObjectManager')){  
					$proxy->setObjectManager($entityManager);
				}
			}           
		}

		$form->setHydrator(new DoctrineHydrator($entityManager,'CsnCms\Entity\Comment'));
		$send = new Element('send');
		$send->setValue('Edit');
		$send->setAttributes(array(
			'type'  => 'submit'
		));
		$form->add($send);

		$form->bind($comment);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
				$entityManager->persist($comment);
				$entityManager->flush();				

                 return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'comment', 'action' => 'index'), true); 
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );		
	}

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id2', 0);
        if (!$id) {
			return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'comment', 'action' => 'index'), true); 
        }

		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        try {
			$repository = $entityManager->getRepository('CsnCms\Entity\Comment');		
			$comment = $repository->find($id);
			$entityManager->remove($comment);
			$entityManager->flush();
		}
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('csn-cms/default', array(
				'controller' => 'comment',
                'action' => 'index'
            ), true);
        }		
		return $this->redirect()->toRoute('csn-cms/default', array(
				'controller' => 'comment',
                'action' => 'index'
        ), true);
	}

	public function prepareData($comment)
	{
		$comment->setCreated(new \DateTime());
		$auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
		if ($auth->hasIdentity()) {
			$user = $auth->getIdentity();
		}
		$comment->setAuthor($user);	
	}
	
	public function getForm($comment, $entityManager, $action)
    {
	
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm( $comment );

        //!!!!!! Start !!!!! Added to make the association tables work with select
        foreach ($form->getElements() as $element){
            if(method_exists($element, 'getProxy')){                
                $proxy = $element->getProxy();
                if(method_exists($proxy, 'setObjectManager')){
                    $proxy->setObjectManager($entityManager);
                }
            }
        }
		
		$form->setHydrator(new DoctrineHydrator($entityManager,'CsnCms\Entity\Comment'));
        
        $send = new Element('send');
        $send->setValue($action); // submit
        $send->setAttributes(array(
                'type'  => 'submit'
        ));
        $form->add($send);

        return $form;		
    }
}