<?php
namespace EvaTest\Mvc\Router\Http;

use PHPUnit_Framework_TestCase as TestCase,
    Zend\Http\Request as Request,
    Zend\Stdlib\Request as BaseRequest,
    Eva\Mvc\Router\Http\ModuleRoute,
    EvaTest\Mvc\Router\FactoryTester;

class ModuleRouteTest extends TestCase
{
    public static function routeProvider()
    {

        return array(
            'core' => array(
                new ModuleRoute('/', array("Core", "Blog", "Album"), array('Admin')),
                '/',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'core',
                    'controller' => 'Core\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),
            'core-noslash' => array(
                new ModuleRoute('', array("Core", "Blog", "Album"), array('Admin')),
                '',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'core',
                    'controller' => 'Core\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),
            'core-nomodule-loaded' => array(
                new ModuleRoute('/', array(), array('Admin')),
                '/',
                null,
                array(
                ),
                false
            ),

            'level1' => array(
                new ModuleRoute('/blog', array("Core", "Blog", "Album"), array('Admin')),
                '/blog',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'level1-nomodule' => array(
                new ModuleRoute('/blog', array("Core", "Album"), array('Admin')),
                '/blog',
                null,
                array(
                ),
                false
            ),

            'level1-number' => array(
                new ModuleRoute('/123', array("Core", "Blog", "Album"), array('Admin')),
                '/123',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'core',
                    'controller' => 'Core\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level1-id' => array(
                new ModuleRoute('/blog/123', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/123',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),


            'level1-namespace' => array(
                new ModuleRoute('/blog-user', array("Core", "Blog", "Album"), array('Admin')),
                '/blog-user',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'user',
                    'controller' => 'Blog\User\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'level1-namespace-id' => array(
                new ModuleRoute('/blog-user/123', array("Core", "Blog", "Album"), array('Admin')),
                '/blog-user/123',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'user',
                    'controller' => 'Blog\User\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level1-module-number' => array(
                new ModuleRoute('/123/post', array("Core", "Blog", "Album"), array('Admin')),
                '/123/post',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'core',
                    'controller' => 'Core\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level2' => array(
                new ModuleRoute('/blog/post', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'level2-namespace' => array(
                new ModuleRoute('/blog-user/post', array("Core", "Blog", "Album"), array('Admin')),
                '/blog-user/post',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'user',
                    'controller' => 'Blog\User\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),


            'level3-id' => array(
                new ModuleRoute('/blog/post/123', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post/123',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level3-number-controller' => array(
                new ModuleRoute('/blog/123/abc', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/123/abc',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level3-action' => array(
                new ModuleRoute('/blog/post/abc', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post/abc',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'abc',
                    'id' => 'abc'
                ),
                true
            ),

            'level4' => array(
                new ModuleRoute('/blog/post/abc/def', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post/abc/def',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'abc',
                    'id' => 'def'
                ),
                true
            ),

            'level4-number-id' => array(
                new ModuleRoute('/blog/post/123/abc', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post/123/abc',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'level5' => array(
                new ModuleRoute('/blog/post/abc/def/ghi', array("Core", "Blog", "Album"), array('Admin')),
                '/blog/post/abc/def/ghi',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'blog',
                    'controller' => 'Blog\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'abc',
                    'id' => 'def'
                ),
                true
            ),

            'protected' => array(
                new ModuleRoute('/admin', array("Core", "Blog"), array('Admin')),
                '/admin',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'admin',
                    'controller' => 'Core\Admin\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'protected-number' => array(
                new ModuleRoute('/admin/123', array("Core", "Blog"), array('Admin')),
                '/admin/123',
                null,
                array(
                    'module' => 'core',
                    'moduleNamespace' => 'admin',
                    'controller' => 'Core\Admin\Controller\CoreController',    
                    'controllerName' => 'core',    
                    'action' => 'get',
                    'id' => '123'
                ),
                true
            ),

            'protected-nomodule' => array(
                new ModuleRoute('/admin', array(), array('Admin')),
                '/admin',
                null,
                array(
                ),
                false
            ),

            'protected-level1' => array(
                new ModuleRoute('/admin/blog', array("Core", "Blog"), array('Admin')),
                '/admin/blog',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'admin',
                    'controller' => 'Blog\Admin\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'protected-level1-namespace' => array(
                new ModuleRoute('/admin/blog-user', array("Core", "Blog"), array('Admin')),
                '/admin/blog-user',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'admin',
                    'controller' => 'Blog\Admin\Controller\BlogController',    
                    'controllerName' => 'blog',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),

            'protected-level2-namespace' => array(
                new ModuleRoute('/admin/blog/post', array("Core", "Blog"), array('Admin')),
                '/admin/blog/post',
                null,
                array(
                    'module' => 'blog',
                    'moduleNamespace' => 'admin',
                    'controller' => 'Blog\Admin\Controller\PostController',    
                    'controllerName' => 'post',    
                    'action' => 'index',
                    'id' => ''
                ),
                true
            ),


        );
    }

    /**
     * @dataProvider routeProvider
     * @param        Literal $route
     * @param        string  $path
     * @param        integer $offset
     * @param        boolean $shouldMatch
     */
    public function testMatching(ModuleRoute $route, $path, $offset, $routeParams, $shouldMatch)
    {
        $request = new Request();
        $request->setUri('http://example.com' . $path);
        $match = $route->match($request, $offset);
        
        if (!$shouldMatch) {
            $this->assertNull($match);
        } else {
            $this->assertInstanceOf('\Zend\Mvc\Router\RouteMatch', $match);

            foreach($routeParams as $key => $param){
                $this->assertEquals($match->getParam($key), $param);
            }
        }
    }
    
    /**
     * @dataProvider routeProvider
     * @param        Literal $route
     * @param        string  $path
     * @param        integer $offset
     * @param        boolean $shouldMatch
     */
    public function testAssembling(ModuleRoute $route, $path, $offset, $shouldMatch)
    {
        if (!$shouldMatch) {
            // Data which will not match are not tested for assembling.
            return;
        }
                
        $result = $route->assemble();
        
        if ($offset !== null) {
            $this->assertEquals($offset, strpos($path, $result, $offset));
        } else {
            $this->assertEquals($path, $result);
        }
    }
    
    public function testNoMatchWithoutUriMethod()
    {
        $route   = new ModuleRoute('/foo');
        $request = new BaseRequest();
        
        $this->assertNull($route->match($request));
    }
    
    public function testGetAssembledParams()
    {
        $route = new ModuleRoute('/foo');
        $route->assemble(array('foo' => 'bar'));
        
        $this->assertEquals(array(), $route->getAssembledParams());
    }
    
    public function testFactory()
    {
        /*
        $tester = new FactoryTester($this);
        $tester->testFactory(
            'Eva\Mvc\Router\Http\ModuleRoute',
            array(
            ),
            array(
                'route' => '/foo'
            )
        );
         */
    }
}

