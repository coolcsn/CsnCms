<?php

namespace CsnCms;

return array(
    'controllers' => array(
        'invokables' => array(
            'CsnCms\Controller\Index' => 'CsnCms\Controller\IndexController',
            'CsnCms\Controller\Article' => 'CsnCms\Controller\ArticleController',
            'CsnCms\Controller\Translation' => 'CsnCms\Controller\TranslationController',
            'CsnCms\Controller\Comment' => 'CsnCms\Controller\CommentController',
            'CsnCms\Controller\Category' => 'CsnCms\Controller\CategoryController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'csn-cms' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/csn-cms',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CsnCms\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // 'route'    => '/[:controller[/:action[/:id]]]',
                            'route'    => '/[:controller[/:action[/:id[/:id2]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
					'paginator-doctrine' => array(
						'type'    => 'Segment',
						'options' => array(
		//					'route'    => '/[:controller[/:action[/:id[/:id2[/:page]]]]]',
							'route'    => '/list/[:controller[/page:page]]',
							'constraints' => array(
								'page' => '[0-9]*',
							),
							'defaults' => array(
								'__NAMESPACE__' => 'CsnCms\Controller',
								'controller'    => 'article',
								'action'        => 'index',
							),
						),
					),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'csn-cms' => __DIR__ . '/../view'
        ),

        'display_exceptions' => true,
    ),
    'view_helpers' => array(
        'factories' => array(
            'vote' => function($sm) {
              $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
              $em = $sm->get('doctrine.entitymanager.orm_default');

              $helper = new \CsnCms\View\Helper\Vote($em);
              return $helper;
            }
        ),
    ),
    
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
);
