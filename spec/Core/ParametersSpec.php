<?php

namespace spec\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParametersSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(
            array(
                'foo' => 'bar'
            )
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Core\Parameters');
    }

    function it_should_except_if_key_doesnt_exist()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringKeyExists('cock');
    }

    function it_should_except_if_the_filter_is_wrong()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringGetFilter('foo', 'String');
    }

    function it_is_initialized_correctly()
    {
        $this->getParam('foo', 'Alpha')->shouldReturn('bar');
    }

    function it_will_return_empty_string_if_return_empty_is_set()
    {
        $this->setReturnEmpty(true)->shouldReturn(null);
        $this->getParam('empty', 'Alpha')->shouldEqual('');
    }
}
