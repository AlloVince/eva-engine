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

            'level1-' => array(
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

