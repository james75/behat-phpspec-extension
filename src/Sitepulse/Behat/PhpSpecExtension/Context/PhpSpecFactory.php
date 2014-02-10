<?php
/**
 * User: james
 * Date: 10/02/14
 * Time: 14:07
 */

namespace Sitepulse\Behat\PhpSpecExtension\Context;


use PhpSpec\Exception\ExceptionFactory;
use PhpSpec\Formatter\Presenter\Differ\Differ;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject\Caller;
use PhpSpec\Wrapper\Subject\ExpectationFactory;
use PhpSpec\Wrapper\Subject\SubjectWithArrayAccess;
use PhpSpec\Wrapper\Subject\WrappedObject;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Wrapper\Wrapper;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PhpSpecFactory implements PhpSpecFactoryInterface
{
    protected $exceptionFactory;
    protected $wrapper;
    protected $arrayAccess;
    protected $expectationFactory;
    protected $caller;
    protected $wrappedObject;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = array())
    {
        $this->config = array('presenter' => 'StringPresenter');
    }

    private function setup($sus)
    {
        $presenter = $this->getPresenter();
        $eventDispatcher = new EventDispatcher();
        $matchers = $this->getMatcherManager($presenter);
        $exampleNode = new ExampleNode('expect', new \ReflectionFunction(__FUNCTION__));
        $this->exceptionFactory = new ExceptionFactory($presenter);
        $this->wrapper = new Wrapper($matchers, $presenter, $eventDispatcher, $exampleNode);
        $this->wrappedObject = new WrappedObject($sus, $presenter);
        $this->caller = new Caller($this->wrappedObject, $exampleNode, $eventDispatcher, $this->exceptionFactory, $this->wrapper);
        $this->arrayAccess = new SubjectWithArrayAccess($this->caller, $presenter, $eventDispatcher);
        $this->expectationFactory = new ExpectationFactory($exampleNode, $eventDispatcher, $matchers);
    }


    public function getSubject($subject)
    {
        return new Subject($subject, $this->wrapper, $this->wrappedObject, $this->caller, $this->arrayAccess, $this->expectationFactory);
    }


    /**
     * @return PresenterInterface
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    private function getPresenter()
    {
        $namespace = 'PhpSpec\\Formatter\\Presenter\\';
        $presenter = $this->config['presenter'];

        $differ = new Differ();
        $presenterClass = $namespace . $presenter;
        if(class_exists($presenterClass)){
            $presenter = new $presenterClass($differ);
        }

        $presenterClass = $presenter;
        if(class_exists($presenterClass)){
            $presenter = new $presenterClass($differ);
        }

        if(!$presenter instanceof PresenterInterface){
            throw new InvalidConfigurationException('Unable to find Presenter "'.$presenter.'"');
        }

        return $presenter;
    }

    /**
     * @param PresenterInterface $presenter
     *
     * @return MatcherManager
     */
    private function getMatcherManager(PresenterInterface $presenter)
    {
        $matcherClassList = array(
            'PhpSpec\Matcher\ArrayContainMatcher',
            'PhpSpec\Matcher\ArrayCountMatcher',
            'PhpSpec\Matcher\ArrayKeyMatcher',
            'PhpSpec\Matcher\CallbackMatcher',
            'PhpSpec\Matcher\ComparisonMatcher',
            'PhpSpec\Matcher\IdentityMatcher',
            'PhpSpec\Matcher\MatcherInterface',
            'PhpSpec\Matcher\ObjectStateMatcher',
            'PhpSpec\Matcher\ScalarMatcher',
            'PhpSpec\Matcher\StringEndMatcher',
            'PhpSpec\Matcher\StringRegexMatcher',
            'PhpSpec\Matcher\StringStartMatcher',
            'PhpSpec\Matcher\ThrowMatcher',
            'PhpSpec\Matcher\TypeMatcher',
        );

        $matcherManger = new MatcherManager($presenter);

        foreach($matcherClassList as $matcherClass){
            $matcher = new $matcherClass($presenter);
            $matcherManger->add($matcher);
        }

        return $matcherManger;
    }

}
 