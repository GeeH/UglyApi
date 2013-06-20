<?php

namespace spec\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Core\Router');
    }

    function it_will_throw_exception_with_bum_uri()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringRoute('', 'GET');
    }

    function it_will_parse_uris_correctly()
    {
        $this->parseUri('/foo/bar')->shouldBeArray();
        $this->parseUri('/foo/bar')->shouldHaveKey('controller');
        $this->parseUri('/foo/bar')['controller']->shouldBe('foo');
        $this->parseUri('/foo/bar')->shouldHaveKey('action');
        $this->parseUri('/foo/bar')['action']->shouldBe('bar');
        $this->parseUri('/foo/bar/key/val')['params'][0]->shouldBe('key');
    }

    function it_will_merge_params_correctly()
    {
        $this->getMergedParams(array('hello' => 'world'), array('cock' => 'balls'), array('farm' => 'animals'))
            ->shouldBeArray();
        $this->getMergedParams(array('hello' => 'world'), array('hello' => 'balls'), array('hello' => 'animals'))
            ->shouldHaveKey('hello');
        $this->getMergedParams(
            array('hello' => 'world'),
            array('hello' => 'balls'),
            array('hello' => 'animals')
        )['hello']
            ->shouldBe('balls');
    }

    function it_will_route_valid_uri_correctly()
    {
        $this->route('/foo/bar', 'GET')->shouldBeAnInstanceOf('Core\Route');
        $this->route('/foo/bar', 'GET')->getController()->shouldBe('FooController');
        $this->route('/foo/bar', 'GET')->getAction()->shouldBe('barGetAction');
    }

    function it_will_route_the_parameters_correctly()
    {
        $this->route('/foo/bar/plum/pudding', 'GET')->shouldBeAnInstanceOf('Core\Route');
        $this->route('/foo/bar/plum/pudding', 'GET')->getParams()->shouldBeAnInstanceOf('Core\Parameters');
        $this->route('/foo/bar/plum/pudding', 'GET')->getParams()->getParam('plum', 'Alpha')->shouldBe('pudding');
    }

    function it_will_route_home_uri_correctly()
    {
        $this->route('/', 'GET')->shouldBeAnInstanceOf('Core\Route');
        $this->route('/', 'GET')->getController()->shouldBe('IndexController');
        $this->route('/', 'GET')->getAction()->shouldBe('getAction');
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
