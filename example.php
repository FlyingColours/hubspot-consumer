<?php

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\LoggerListener;
use Hubspot\Consumer;
use Hubspot\Listener\ContactSerializationListener;
use Hubspot\Listener\ContactSerializationSubscriber;
use Hubspot\Listener\ErrorListener;
use Hubspot\Listener\HapiListener;
use Hubspot\Service\Normalization\ContactDenormalizer;
use Hubspot\Service\Normalization\ContactNormalizer;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

require __DIR__ . '/vendor/autoload.php';

$hapiKey = '';

$browser = new Browser(new Curl());

$logger = new Logger('hubspot', [ new ErrorLogHandler() ]);

$browser->addListener(new LoggerListener(function($message) use ($logger) { $logger->info($message); }));
$browser->addListener(new HapiListener($hapiKey));
$browser->addListener(new ErrorListener());

$serializer = new Serializer([ new ArrayDenormalizer(), new ContactDenormalizer(), new ContactNormalizer() ], [ new JsonEncoder() ]);

$browser->addListener(new ContactSerializationListener($serializer));

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new ContactSerializationSubscriber($serializer));

$consumer = new Consumer($dispatcher, $browser, 'https://api.hubapi.com');

try {

    $contact = $consumer->getContactById('numeric id');

//    $contact = $consumer->getContactByEmail('email@address');

//    $contact = $consumer->createContact([
//        'email' => 'amy.pond@example.com',
//        'firstName' => 'Amy',
//        'lastName' => 'Pond'
//    ]);
//
    printf("%s: %s\n", 'ID', $contact->getId());

    foreach ($contact as $key => $value)
    {
        printf("%s: %s\n", $key, $value);
    }

//    $contacts = $consumer->getContacts(['email', 'sms', 'post', 'e_mail', 'telephone']);
//    print_r($contacts);

//      $iterable = $consumer->getAllContacts(['email', 'sms', 'post', 'e_mail', 'telephone']);
//      foreach ($iterable as $k => $contact) {
//          echo $k . ' => ' . $contact->getEmail() . PHP_EOL;
//      }

} catch (HttpException $e) {
    printf("error: %s %s\n", $e->getStatusCode(), $e->getMessage());
}
