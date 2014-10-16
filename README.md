# Modulus CMF - ModulusLog

This module was based on [eddiejaoude/zf2-logger](https://github.com/eddiejaoude/zf2-logger).

#### Zend Framework 2 Event Logger.
* Log incoming Requests data with host name
* Log events for debbuging
* Manually log your application information with priorities (i.e. emerg..debug)
* Change your logging output via config without changing code
* Multiple logging outputs (`Zend\Log\Writer` classes)
* Filter errors to log per environment (i.e production > error, development > debug)
* Default log information includes (Session Id, Host, IP, authorizedUser)

---

## Installation via Composer

### Steps

#### 1. Add to composer.

    ```json
    "require": {
        "modulus/modulus-log": "0.0.*"
    }
    ```

Update your dependencies `php composer.phar update`

#### 2. To override or add additional configuration copy the file ```/config/module.config.php.dist``` to ```config/autoload``` with name (modulus.global.php)

#### 3. Add module to application config (/config/application.config.php)

```PHP
   //...
   'modules' => array(
        'ModulusLog',
   ),
   //...
```

---

## Example usage of manual logging & prority

As the ```Zend\Log\Logger``` is returned from the Service call, one can use the methods:
* emerg  // Emergency: system is unusable
* alert  // Alert: action must be taken immediately
* crit   // Critical: critical conditions
* err    // Error: error conditions
* warn   // Warning: warning conditions
* notice // Notice: normal but significant condition
* info   // Informational: informational messages
* debug  // Debug: debug messages

```PHP
    //...
    $serviceLocator->get('ModulusLog\Logger')->emerg('Emergency message');
    //...
```

### Use an alias for decoupling

Instead of using `ModulusLog\Logger` in your code, put an `Alias` in your service manager, therefore allowing you to swap out different logger libraries later on without modifying your code & usage.

i.e.
```php
    //...
    'aliases'    => array(
        // alias used, so can be swapped out later without changing any code
        'Logger' => 'ModulusLog\Logger'
    ),
    //...
```

Then your usage in your code becomes...

```PHP
    //...
    $serviceLocator->get('Logger')->emerg('Emergency message');
    //...
```

### Add to default logging parameters

Additional default logging information includes:

* IP
* Host
* Session Id
* User Authenticated

To log more additional default information, use `$logger->addCustomExtra($extraArray)`. Full example below.

1. Change the `alias` to your new service *(point 2 below)*
i.e.

```PHP
    'aliases' => array(
        // ...
        'Logger' => 'MyLogger',
        // ...
    ),
```

2. Create your new service

```PHP
    // ...
    'MyLogger' => function($sm) {
        $logger = $sm->get('ModulusLog\Logger');
        $logger->addCustomExtra(
            array(
                'host' => !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI',
            )
        );

        return $logger;
    },
    // ...
```

---

## Example - built in logging

Each output includes & is prepended with the host - this is especially useful when working with multi layer/tier architecture, i.e. F/E (UI) -> B/E (API). As these can all write to the same output in the stack execution order or alternatively to different outputs.

### Request (priority DEBUG)

```
    2014-10-09T16:14:13+00:00 DEBUG (7): Array
    (
        [zf2.local] => Array
            (
                [Request] => Zend\Uri\Http Object
                    (
                        [validHostTypes:protected] => 15
                        [user:protected] =>
                        [password:protected] =>
                        [scheme:protected] => http
                        [userInfo:protected] =>
                        [host:protected] => zendSkeleton.local
                        [port:protected] =>
                        [path:protected] => /test
                        [query:protected] =>
                        [fragment:protected] =>
                    )

            )

    )
```

---

## Configuration (config)

```PHP
    return array(
        'modulus_log' => array(

            // will add the $logger object before the current PHP error handler
            'registerErrorHandler'     => 'true', // errors logged to your writers
            'registerExceptionHandler' => 'true', // exceptions logged to your writers
            'authenticationService' => 'modulususer_auth_service', // the \Zend\Authentication\AuthenticationService to get the user authenticator

            // multiple zend writer output & zend priority filters
            'writers' => array(
                'standard-file' => array(
                    'adapter'  => '\Zend\Log\Writer\Stream',
                    'options'  => array(
                        'output' => 'data/application.log', // path to file
                    ),
                    // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                    'filter' => \Zend\Log\Logger::DEBUG,
                    'enabled' => true
                ),
                'tmp-file' => array(
                    'adapter'  => '\Zend\Log\Writer\Stream',
                    'options'  => array(
                        'output' => '/tmp/application-' . $_SERVER['SERVER_NAME'] . '.log', // path to file
                    ),
                    // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                    'filter' => \Zend\Log\Logger::DEBUG,
                    'enabled' => false
                ),
                'standard-output' => array(
                    'adapter'  => '\Zend\Log\Writer\Stream',
                    'options'  => array(
                        'output' => 'php://output'
                    ),
                    // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                    'filter' => \Zend\Log\Logger::NOTICE,
                    'enabled' => $_SERVER['APPLICATION_ENV'] == 'development' ? true : false
                ),
                'standard-error' => array(
                    'adapter'  => '\Zend\Log\Writer\Stream',
                    'options'  => array(
                        'output' => 'php://stderr'
                    ),
                    // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                    'filter' => \Zend\Log\Logger::ERR,
                    'enabled' => true
                )
            )
        )
    );

```

---

## Unit tests

To run unit tests (from root diectory)

1. Download Composer

```
curl -sS https://getcomposer.org/installer | php
```


2. Install dependencies

```
php composer.phar install
```

3. Run tests

```
vendor/bin/phpunit -c tests/phpunit.xml
```

---

## Example output of Log file

```

```

---

## What Next...

* More events

Ideas & requirements welcome.

