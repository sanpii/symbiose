<?php
declare(strict_types = 1);

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = __DIR__ . '/../.env';
if (is_file($dotenv) && class_exists(Dotenv::class)) {
    (new Dotenv)
        ->load($dotenv);
}
