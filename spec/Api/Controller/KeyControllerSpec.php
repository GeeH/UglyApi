<?php

namespace spec\Api\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeyControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Api\Controller\KeyController');
    }

    function it_has_a_default_post_method()
    {
        $this->postAction()->shouldBeArray();
    }
}
