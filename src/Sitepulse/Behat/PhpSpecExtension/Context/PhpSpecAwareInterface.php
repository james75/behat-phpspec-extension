<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:05
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context;


interface PhpSpecAwareInterface {

    /**
     * @param PhpSpecFactoryInterface $phpSpecFactory
     * @return mixed
     */
    public function setPhpSpecFactory(PhpSpecFactoryInterface $phpSpecFactory);
} 