<?php
return array(
    'modulus_log' => array(

        // will add the $logger object before the current PHP error handler
        'registerErrorHandler'     => true, // errors logged to your writers
        'registerExceptionHandler' => true, // exceptions logged to your writers
        'authenticationService' => 'modulususer_auth_service',

        // multiple zend writer output & zend priority filters
        'writers' => array(
            'standard-error' => array(
                'adapter' => '\Zend\Log\Writer\Stream',
                'options' => array(
                    'output' => 'php://stderr'
                ),
                // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                'filter'  => \Zend\Log\Logger::DEBUG,
                'enabled' => true
            )
        )
    )
);
