<?php

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\LoggerListener;
use Hubspot\Consumer;
use Hubspot\Listener\ErrorListener;
use Hubspot\Listener\HapiListener;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

require __DIR__ . '/vendor/autoload.php';

$hapi = '';

$browser = new Browser(new Curl());

$logger = new Logger('hubspot', [ new ErrorLogHandler() ]);

$browser->addListener(new LoggerListener(function($message) use ($logger) { $logger->info($message); }));
$browser->addListener(new HapiListener($hapi));
$browser->addListener(new ErrorListener());

$classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(__DIR__ . "/resources/serialization.yml"));

$normalizer = new ObjectNormalizer($classMetadataFactory, null, null, new ReflectionExtractor());
$serializer = new Serializer([ $normalizer ], [ new JsonEncoder() ]);

$dispatcher = new EventDispatcher();

$consumer = new Consumer($dispatcher, $browser, 'https://api.hubapi.com');

try {
    print_r($consumer->getContactById('108701'));
} catch (HttpException $e) {
    printf("error: %s %s\n", $e->getStatusCode(), $e->getMessage());
}
