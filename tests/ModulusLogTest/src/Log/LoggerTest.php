<?php
namespace ModulusLog\Tests\Log;

use ModulusLog\Log\Logger;

/**
 * Class LoggerTest
 * @package ModulusLog\Tests\Log
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \ModulusLog\Log\Logger
     */
    private $logger;

    public function setUp()
    {
        $writer = new \Zend\Log\Writer\Mock;

        $this->logger = new Logger();
        $this->logger->addWriter($writer);

        $authenticationService = \Mockery::mock('\Zend\Authentication\AuthenticationService')
                    ->shouldReceive('hasIdentity')
                    ->andReturn(false)
                    ->mock();

        $this->logger->setAuthenticationService(
            $authenticationService
        );
    }

    public function testSetGetAuthenticationService()
    {
        $authenticationService = \Mockery::mock('Zend\Authentication\AuthenticationService');

        $this->assertInstanceOf(
            'ModulusLog\Log\Logger',
            $this->logger->setAuthenticationService($authenticationService)
        );

        $this->assertEquals(
            $authenticationService,
            $this->logger->getAuthenticationService()
        );
    }

    public function testSetGetRequest()
    {
        $request = \Mockery::mock('Zend\Http\PhpEnvironment\Request');

        $this->assertInstanceOf(
            'ModulusLog\Log\Logger',
            $this->logger->setRequest($request)
        );

        $this->assertEquals(
            $request,
            $this->logger->getRequest()
        );
    }

    public function testLogAndDefaultExtra()
    {
        $message = 'test message';

        $this->assertInstanceOf(
            'ModulusLog\Log\Logger',
            $this->logger->log(Logger::EMERG, $message)
        );

        $this->assertEquals(0, $this->logger->getWriters()->current()->events[0]['priority']);
        $this->assertEquals('EMERG', $this->logger->getWriters()->current()->events[0]['priorityName']);
        $this->assertEquals($message, $this->logger->getWriters()->current()->events[0]['message']);
        $this->assertEquals(
            array(
                'ModulusLog' => array(
                    'sessionId' => '',
                    'host'      => 'CLI',
                    'ip'        => 'unavailable',
                    'authenticatedUser' => 'unavailable'
                )
            ),
            $this->logger->getWriters()->current()->events[0]['extra']
        );
    }

    public function testExtraAddition()
    {
        $message         = 'test message';
        $extra           = 'extra additional information for the logger';
        $initialiseExtra = 'initialise extra additional information for the logger';

        $this->logger->setCustomExtra(array($initialiseExtra));

        $this->assertInstanceOf(
            'ModulusLog\Log\Logger',
            $this->logger->log(Logger::DEBUG, $message, array($extra))
        );

        $this->assertEquals(7, $this->logger->getWriters()->current()->events[0]['priority']);
        $this->assertEquals('DEBUG', $this->logger->getWriters()->current()->events[0]['priorityName']);
        $this->assertEquals($message, $this->logger->getWriters()->current()->events[0]['message']);
        $this->assertEquals(
            array(
                'ModulusLog' => array(
                    'sessionId' => '',
                    'host'      => 'CLI',
                    'ip'        => 'unavailable',
                    'authenticatedUser' => 'unavailable'
                ),
                $extra,
                $initialiseExtra
            ),
            $this->logger->getWriters()->current()->events[0]['extra']
        );
    }
}
