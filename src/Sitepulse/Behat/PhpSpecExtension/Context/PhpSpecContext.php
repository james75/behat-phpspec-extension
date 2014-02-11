<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:05
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context;

use Behat\Behat\Context\BehatContext;
use Sitepulse\Behat\PhpSpecExtension\PhpSpec\PhpSpecAccessor;

class PhpSpecContext extends BehatContext implements PhpSpecAwareInterface
{
    use PhpSpecAccessor;
}
 