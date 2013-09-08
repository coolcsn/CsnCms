<?php

namespace CsnCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Form\Element;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;

use CsnCms\Entity\Category;

class CategoryController extends AbstractActionController
{
    // R - retrieve
    public function indexAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $dql = "SELECT c FROM CsnCms\Entity\Category c ";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(30);
        $categories = $query->getResult();

        return new ViewModel(array('categories' => $categories));
    }

    // C - create
    public function addAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $category = new Category;
        $form = $this->getForm($category, $entityManager, 'Add');

        $form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
                $post = $request->getPost();
                $form->setData($post);
                if ($form->isValid()) {
                    $entityManager->persist($category);
                    $entityManager->flush();
                    return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));				
                }
        }
        return new ViewModel(array('form' => $form));
    }

    // U - update
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        try {
            $category = $entityManager->find('CsnCms\Entity\Category', $id);
        }
        catch (\Exception $ex) {
            echo $ex->getMessage(); // this will never be seen if you don't comment the redirect
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));
        }

        $form = $this->getForm($category, $entityManager, 'Update');
        $form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                //$this->prepareData($category);
                $entityManager->persist($category);
                $entityManager->flush();
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));				
            }
        }
        return new ViewModel(array('form' => $form, 'id' => $id));
    }		
	
    // D - delete
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        try {
            $category = $entityManager->find('CsnCms\Entity\Category', $id);
            $entityManager->remove($category);
            $entityManager->flush();			
        }
        catch (\Exception $ex) {
            echo $ex->getMessage(); // this will never be seen if you don't comment the redirect
            return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));
        }	
        
        return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'category', 'action' => 'index'));
    }
	
    public function getForm($category, $entityManager, $action)
    {
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm( $category );

        //!!!!!! Start !!!!! Added to make the association tables work with select
        foreach ($form->getElements() as $element){
            if(method_exists($element, 'getProxy')){                
                $proxy = $element->getProxy();
                if(method_exists($proxy, 'setObjectManager')){
                    $proxy->setObjectManager($entityManager);
                }
            }           
        }

        $form->remove('created');
        $form->remove('parent');
        $form->remove('author');
        $form->setHydrator(new DoctrineHydrator($entityManager,'CsnCms\Entity\Category'));
        $send = new Element('send');
        $send->setValue($action); // submit
        $send->setAttributes(array(
                'type'  => 'submit'
        ));
        $form->add($send);

        return $form;		
    }
}