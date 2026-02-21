<?php

/**
 * Routes
 */

use Tarn\Core\Request;
use Tarn\Core\Response;

return function ($router) {
    // Default home route
    $router->get('/', function(Request $request) {
        return (new Response())->view('home');
    });
};
