<?php

use App\Classes\Response;

include_once(__DIR__ . '/../vendor/autoload.php');

// Environment file load
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Custom app routes
include_once(__DIR__ . '/../config/routes.php');

// 404 not found
response('Route not found', Response::HTTP_NOT_FOUND);