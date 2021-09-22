<?php 
namespace Categoria;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'     => '/categoria',
                    'defaults'  => array(
                        'controller' => 'Categoria\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'      => '/categoria',
                    'defaults'   => array(
                        '__NAMESPACE__' => 'Categoria\Controler',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'chil_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Categoria\Controller\Index' => 'Categoria\Controller\IndexController'
        ),
    ),
    'view_menager' => array(
        'display_not_founs_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'erros/404',
        'exception_template'       => 'erro/index',
        'template_map' => array(
            'layout/layout'             => __DIR__ . '/../../Base/view/layout/layout.phtml',
            'erro/404'                  => __DIR__ . '/../../Base/view/erro/404.phtml',
            'erro/index'                => __DIR__ . '/../../Base/view/erro/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
?>