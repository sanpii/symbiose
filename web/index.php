<?php
declare(strict_types = 1);

use \Symfony\Component\Dotenv;
use \Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$env = __DIR__ . '/../.env';
if (is_file($env) && getenv('APP_ENV') !== 'prod') {
    (new Dotenv\Dotenv)
        ->load($env);
}

$kernel = new AppKernel();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
