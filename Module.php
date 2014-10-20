<?php
namespace ModulusLog;

use ModulusLog\Listener\Request as RequestListener;
use ModulusLog\Listener\Response as ResponseListener;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(
            new RequestListener($e->getApplication()->getServiceManager()->get('ModulusLog\Logger'))
        );

        $eventManager->attach(
            new ResponseListener($e->getApplication()->getServiceManager()->get('ModulusLog\Logger'))
        );

        return;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ModulusLog\Logger' => 'ModulusLog\Factory\LoggerFactory'
            )
        );
    }

}
