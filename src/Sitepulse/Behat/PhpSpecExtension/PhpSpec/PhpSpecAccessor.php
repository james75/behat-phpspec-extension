<?php
/**
 * User: james
 * Date: 11/02/14
 * Time: 11:58
 */

namespace Sitepulse\Behat\PhpSpecExtension\PhpSpec;

use Sitepulse\Behat\PhpSpecExtension\Context\PhpSpecAwareInterface;
use Sitepulse\Behat\PhpSpecExtension\Context\PhpSpecFactoryInterface;

trait PhpSpecAccessor {

    /**
     * @var PhpSpecFactoryInterface $phpSpecFactory
     */
    private $phpSpecFactory;


    public function setPhpSpecFactory(PhpSpecFactoryInterface $phpSpecFactory)
    {
        $this->phpSpecFactory = $phpSpecFactory;
    }

    /**
     * @param $subject
     *
     * @return \PhpSpec\Wrapper\Subject
     */
    public function expect($subject)
    {
        return $this->phpSpecFactory->getSubject($subject);
    }

} 