<?php
namespace ModulusLog\Tests\Listener;

use ModulusLog\Listener\Response;

/**
 * Class ResponseTest
 *
 * @package ModulusLog\Tests\Listener
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \ModulusLog\Listener\Response
     */
    private $instance;

    /**
     * @var \Zend\Log\Logger
     */
    private $logger;

    /**
     * @var \Zend\Log\Writer\Mock
     */
    private $writer;

    public function setUp()
    {
        $this->writer = new \Zend\Log\Writer\Mock;

        $this->logger = new \Zend\Log\Logger;
        $this->logger->addWriter($this->writer);

        $this->instance = new Response($this->logger);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('ModulusLog\Listener\Response', $this->instance);
    }

    public function testConstruct()
    {
        $request = new Response();

        $this->assertNull($request->getLog());
    }

    public function testLogSetterGetter()
    {
        $request = new Response();

        $request->setLog($this->logger);

        $this->assertNotNull($request->getLog());
        $this->assertInstanceOf('Zend\Log\Logger', $request->getLog());
    }

    public function testListenerAddGetterRemove()
    {
        $this->assertEquals(array(), $this->instance->getListeners());

        $this->assertInstanceOf(
            'ModulusLog\Listener\Response',
            $this->instance->addListener(
                new \Zend\Stdlib\CallbackHandler(function () {
                })
            )
        );

        $this->assertEquals(1, count($this->instance->getListeners()));

        $listeners = $this->instance->getListeners();
        $this->assertInstanceOf(
            'Zend\Stdlib\CallbackHandler',
            $listeners[0]
        );

        $this->assertTrue($this->instance->removeListener(0));
        $this->assertEquals(0, count($this->instance->getListeners()));
    }

    public function testAttachDettach()
    {
        $eventManager = \Mockery::mock('Zend\EventManager\EventManager')->shouldDeferMissing();
        $this->instance->attach($eventManager);

        $this->assertEquals(1, count($this->instance->getListeners()));

        $this->instance->detach($eventManager);

        $this->assertEquals(0, count($this->instance->getListeners()));
    }
}
