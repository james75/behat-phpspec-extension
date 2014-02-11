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
use Sitepulse\Behat\PhpSpecExtension\PhpSpec\Subject;
use Sitepulse\Behat\PhpSpecExtension\PhpSpec\Wrapper;
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
    protected $presenter;
    protected $eventDispatcher;
    protected $exampleNode;
    protected $matchers;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = array())
    {
        $this->config = array('presenter' => 'StringPresenter');
        $this->setup();
    }

    private function setup()
    {
        $this->presenter = $this->getPresenter();
        $this->matchers = $this->getMatcherManager($this->presenter);
        $reflectionMethod = new \ReflectionMethod(__CLASS__, 'getSubject');
        $this->exampleNode = new ExampleNode('expect', $reflectionMethod);
    }


    public function getSubject($subject)
    {
        $eventDispatcher = new EventDispatcher();
        $exceptionFactory = new ExceptionFactory($this->presenter);
        $wrapper = new Wrapper($this->matchers, $this->presenter, $eventDispatcher, $this->exampleNode);
        $wrappedObject = new WrappedObject($subject, $this->presenter);
        $caller = new Caller($wrappedObject, $this->exampleNode, $eventDispatcher, $exceptionFactory, $wrapper);
        $arrayAccess = new SubjectWithArrayAccess($caller, $this->presenter, $eventDispatcher);
        $expectationFactory = new ExpectationFactory($this->exampleNode, $eventDispatcher, $this->matchers);

        return new Subject($subject, $wrapper, $wrappedObject, $caller, $arrayAccess, $expectationFactory);
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

        if(!$presenter instanceof PresenterInterface && class_exists($presenter)){
            $presenter = new $presenter($differ);
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
            'PhpSpec\Matcher\ComparisonMatcher',
            'PhpSpec\Matcher\IdentityMatcher',
            'PhpSpec\Matcher\ObjectStateMatcher',
            'PhpSpec\Matcher\ScalarMatcher',
            'PhpSpec\Matcher\StringEndMatcher',
            'PhpSpec\Matcher\StringRegexMatcher',
            'PhpSpec\Matcher\StringStartMatcher',
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
 