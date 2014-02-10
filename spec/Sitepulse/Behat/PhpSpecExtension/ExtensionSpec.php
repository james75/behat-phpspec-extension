<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 13:45
 */

namespace spec\Sitepulse\Behat\PhpSpecExtension;

use PhpSpec\ObjectBehavior;

class ExtensionSpec extends ObjectBehavior
{

    public function it_should_be_a_behat_extension()
    {
        $this->shouldHaveType('Behat\Behat\Extension\ExtensionInterface');
    }

}
 