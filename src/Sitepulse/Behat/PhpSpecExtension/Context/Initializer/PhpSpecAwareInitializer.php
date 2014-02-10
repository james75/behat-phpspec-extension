<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:49
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context\Initializer;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use Sitepulse\Behat\PhpSpecExtension\Context\PhpSpecAwareInterface;
use Sitepulse\Behat\PhpSpecExtension\Context\PhpSpecFactoryInterface;

class PhpSpecAwareInitializer implements InitializerInterface
{

    /**
     * @var \Sitepulse\Behat\PhpSpecExtension\Context\PhpSpecFactoryInterface
     */
    private $phpSpecFactory;

    public function __construct(PhpSpecFactoryInterface $phpSpecFactory)
    {
        $this->phpSpecFactory = $phpSpecFactory;
    }

    /**
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof PhpSpecAwareInterface;
    }

    /**
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        if($context instanceof PhpSpecAwareInterface){
            $context->setPhpSpecFactory($this->phpSpecFactory);
        }
    }
}
 