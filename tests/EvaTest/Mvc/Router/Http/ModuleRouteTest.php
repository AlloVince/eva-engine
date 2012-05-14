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
            'simple-match' => array(
                new ModuleRoute('/foo'),
                '/foo',
                null,
                true
            ),
            'no-match-without-leading-slash' => array(
                new ModuleRoute('foo'),
                '/foo',
                null,
                false
            ),
            'no-match-with-trailing-slash' => array(
                new ModuleRoute('/foo'),
                '/foo/',
                null,
                false
            ),
            'offset-skips-beginning' => array(
                new ModuleRoute('foo'),
                '/foo',
                1,
                true
            ),
            'offset-enables-partial-matching' => array(
                new ModuleRoute('/foo'),
                '/foo/bar',
                0,
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
    public function testMatching(ModuleRoute $route, $path, $offset, $shouldMatch)
    {
        $request = new Request();
        $request->setUri('http://example.com' . $path);
        $match = $route->match($request, $offset);
        
        if (!$shouldMatch) {
            $this->assertNull($match);
        } else {
            $this->assertInstanceOf('Zend\Mvc\Router\Http\RouteMatch', $match);
            
            if ($offset === null) {
                $this->assertEquals(strlen($path), $match->getLength());            
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
    public function testAssembling(Literal $route, $path, $offset, $shouldMatch)
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
        $tester = new FactoryTester($this);
        $tester->testFactory(
            'Eva\Mvc\Router\Http\ModuleRoute',
            array(
                'route' => 'Missing "route" in options array'
            ),
            array(
                'route' => '/foo'
            )
        );
    }
}

