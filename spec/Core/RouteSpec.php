<?php

namespace spec\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Core\Route');
    }

    function it_is_setting_and_getting_the_controller()
    {
        $this->setController('controller')->shouldReturn(null);
        $this->getController()->shouldEqual('controller');
    }

    function it_is_setting_and_getting_the_action()
    {
        $this->setAction('action')->shouldReturn(null);
        $this->getAction()->shouldEqual('action');
    }

    function it_is_setting_and_getting_the_params()
    {
        $this->setParams(array('foo' => 'bar'))->shouldReturn(null);
        $this->getParams()->shouldReturnAnInstanceOf('Core\Parameters');
    }

}
