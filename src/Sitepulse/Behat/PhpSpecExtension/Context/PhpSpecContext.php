<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:05
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context;


use Behat\Behat\Context\BehatContext;

class PhpSpecContext extends BehatContext implements PhpSpecAwareInterface
{

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
 