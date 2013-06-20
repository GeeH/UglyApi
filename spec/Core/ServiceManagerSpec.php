<?php

namespace spec\Core;

use Core\ServiceManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceManagerSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(
            array(
                'factories' => array(
                    'factoryService' => function (ServiceManager $sm) {
                        return 'factoryHydratedService';
                    },
                )
            ),
            array()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Core\ServiceManager');
    }

    function it_should_return_false_if_a_key_doesnt_exist()
    {
        $this->exists('imaginary')->shouldBe(false);
    }

    function it_should_return_true_if_a_key_exists()
    {
        $this->set('testKey', 'testService')->shouldReturn(null);
        $this->exists('testKey')->shouldBe(true);
    }

    function it_should_throw_exception_if_get_called_on_fake_key()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringGet('cock');
    }

    function it_should_return_a_pre_hydrated_service()
    {
        $this->set('testKey', 'testService')->shouldReturn(null);
        $this->get('testKey')->shouldBe('testService');
    }

    function it_should_hydrate_and_return_a_factory_service()
    {
        $this->get('factoryService')->shouldBe('factoryHydratedService');
    }
}
