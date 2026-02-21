<?php

/**
 * Application Router
 *
 * This file is part of the application's core routing mechanism.
 * It integrates with the FastRoute library to dispatch HTTP requests
 * to their appropriate controllers or closures.
 */

namespace Tarn\Core;

class Router {

  /**
   * Router constructor.
   */
  public function __construct() {
  }

  /**
   * Dispatches the current HTTP request to the registered route handler.
   *
   * @param Request $request
   * @return void
   */
  public function dispatch(Request $request): void {
    // Ensure the cache directory exists before FastRoute attempts to write to it
    $cacheFile = TARN_ROOT . 'storage/cache/routes.php';
    $cacheDir = dirname($cacheFile);
    if (!is_dir($cacheDir)) {
      mkdir($cacheDir, 0755, true);
    }

    // We use cachedDispatcher instead of simpleDispatcher to take advantage of the caching options.
    $dispatcher = \FastRoute\cachedDispatcher(function (\FastRoute\RouteCollector $router) {
      $routeFiles = glob(TARN_ROOT . 'routes/*.php');
      if ($routeFiles) {
        foreach ($routeFiles as $file) {
          $routes = require $file;
          if (is_callable($routes)) {
            $routes($router);
          }
        }
      }
    }, [
      'cacheFile'     => $cacheFile,
      'cacheDisabled' => $_ENV['APP_ENV'] === 'development',
    ]);

    // Dispatch the request
    $routeInfo = $dispatcher->dispatch($request->method(), $request->uri());

    switch ($routeInfo[0]) {

      // Handle 404 Not Found error
      case \FastRoute\Dispatcher::NOT_FOUND:
        $response = new Response();
        try {
            $response->view('errors.404', [], 404)->send();
        } catch (\Exception $e) {
            $response->html('<h1>404 - Not Found</h1>', 404)->send();
        }
        break;

      // Handle 405 Method Not Allowed error
      case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response = new Response();
        try {
            $response->view('errors.405', [], 405)->send();
        } catch (\Exception $e) {
            $response->html('<h1>405 - Method Not Allowed</h1>', 405)->send();
        }
        break;

      // Extract handler and matched route variables
      case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        // Ensure proper handler execution based on data type (e.g., array of [Class, Method] or Closure)
        if (is_array($handler)) {
          [$class, $method] = $handler;
          $result = (new $class())->$method($request, $vars);
        } elseif (is_callable($handler)) {
          $result = $handler($request, $vars);
        }else {
          dd($handler);
        }

        // Handle the return value appropriately
        if ($result instanceof Response) {
          $result->send();
        } elseif (is_array($result) || is_object($result)) {
          // Auto-convert arrays/objects to JSON
          (new Response())->json($result)->send();
        } else {
          // Assume HTML/string for anything else
          (new Response())->html((string) $result)->send();
        }
        break;
    }
  }
}
