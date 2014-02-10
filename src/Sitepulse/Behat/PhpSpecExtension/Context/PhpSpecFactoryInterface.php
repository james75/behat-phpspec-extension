<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:46
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context;

use PhpSpec\Wrapper\Subject;

interface PhpSpecFactoryInterface {

    /**
     * @param mixed $subject
     * @return Subject
     */
    public function getSubject($subject);
} 