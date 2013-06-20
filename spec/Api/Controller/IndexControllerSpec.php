<?php

namespace spec\Api\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Api\Controller\IndexController');
    }

    function it_will_return_an_array_from_default_method()
    {
        $this->getAction()->shouldBeArray();
    }
}
