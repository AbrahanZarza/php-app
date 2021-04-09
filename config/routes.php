<?php

use App\Classes\Router;
use App\Classes\Request;

/**
 * Main application routes file
 * ============================
 */

// Basic example
Router::route(Request::METHOD_GET, '/', function (Request $request) {
	response('Hello app!');
});
