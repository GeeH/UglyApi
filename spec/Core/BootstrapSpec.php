<?php

namespace spec\Core;

use Core\Assets\IndexController;
use Core\Bootstrap;
use Core\Router;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BootstrapSpec extends ObjectBehavior
{
    /**
     * @param \Core\Router $router
     */
    function let($router)
    {
        $this->beConstructedWith($router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Core\Bootstrap');
    }

    function it_will_return_merged_configs()
    {
        $this->getConfig()->shouldBeArray();
    }

    function it_will_have_service_manager_key()
    {
        $this->getConfig()->shouldHaveKey('service_manager');
    }

    function it_will_have_general_key()
    {
        $this->getConfig()->shouldHaveKey('general');
    }

    function it_will_have_valid_database_credentials_for_production()
    {
        $this->getConfig()['general']->shouldHaveKey('production');
        $this->getConfig()['general']['production']->shouldHaveKey('db');
    }

    /**
     * @param \Core\Router $router
     */
    function it_will_proxy_routing_to_the_router_class($router)
    {
        $router->route('/foo/bar', 'GET')->shouldBeCalled();
        // when
        $this->route('/foo/bar', 'GET');
    }

    function it_will_use_default_header_if_none_set()
    {
        $this->getYamgoAppOs()->shouldBe(Bootstrap::DEFAULT_APP_OS);
    }

    function it_will_use_custom_header_if_one_is_passed()
    {
        $this->getYamgoAppOs(
            array(
                'Yamgo-App-Os' => 'mobi1'
            )
        )->shouldBe('mobi1');
    }

    function it_will_return_a_valid_controller_class_if_one_exists()
    {
        $router = new Router();
        $this->setRouter($router);
        $route = $router->route('/', 'GET');
        $this->getServiceManager()->set('IndexController', new \Core\Assets\IndexController());
        $this->getControllerClass($route)->shouldBeAnInstanceOf('Core\Assets\IndexController');
    }

    function it_will_return_false_if_a_controller_is_not_found()
    {
        $router = new Router();
        $this->setRouter($router);
        $route = $router->route('/foo', 'GET');
        $this->getControllerClass($route)->shouldBe(false);
    }

    function it_will_except_if_init_cant_find_controller()
    {
        $router = new Router();
        $this->setRouter($router);
        $this->shouldThrow('\InvalidArgumentException')->duringInit('/cock', 'GET');
    }

    function it_will_except_if_init_cant_find_action()
    {
        $router = new Router();
        $this->setRouter($router);
        $this->getServiceManager()->set('IndexController', 'Core\Assets\IndexController');

        $this->shouldThrow('\InvalidArgumentException')->duringInit('/', 'POST');
    }

    function it_will_except_if_dispatch_doesnt_return_an_array()
    {
        $controller = new IndexController();
        $this->shouldThrow('\InvalidArgumentException')->duringDispatch($controller, 'bumGetAction');
    }

    function it_will_route_and_call_controller_and_action()
    {
        $router = new Router();
        $this->getServiceManager()->set('IndexController', 'Core\Assets\IndexController');
        $this->setRouter($router);
        $this->init('/', 'GET')->shouldBeArray();
    }

    public function getMatchers()
    {
        return array(
            'haveKey' => function ($subject, $key) {
                return array_key_exists($key, $subject);
            },
            'haveValue' => function ($subject, $value) {
                return in_array($value, $subject);
            },
        );
    }
}
