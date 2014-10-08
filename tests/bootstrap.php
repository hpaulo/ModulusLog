<?php
namespace ModulusLog\Tests;

$vendorDir = __DIR__ . '/../vendor';

if (file_exists($file = $vendorDir . '/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = './vendor/autoload.php')) {
    require_once $file;
} else {
    throw new \RuntimeException("Not found composer autoload");
}
