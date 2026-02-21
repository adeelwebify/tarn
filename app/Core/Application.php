<?php

/**
 * Bootstrap Application
 */

namespace Tarn\Core;

class Application {
  protected Router $router;
  protected Request $request;

  public function __construct() {
    $this->router = new Router();
    $this->request = new Request();
    
    // Initialize standard framework components
    $this->initSession();
    $this->initViewEngine();
    $this->initLogger();
    $this->initDatabase();
  }

  protected function initSession(): void {
    \Tarn\Core\Session::init();
  }

  protected function initDatabase(): void {
    \Tarn\Core\Database::init();
  }

  protected function initLogger(): void {
    $logPath = TARN_ROOT . 'storage/logs/app.log';
    \Tarn\Core\Log::init($logPath);
  }

  protected function initViewEngine(): void {
    $viewsPath = TARN_ROOT . 'resources/views';
    $cachePath = TARN_ROOT . 'storage/cache/views';

    \Tarn\Core\View::init($viewsPath, $cachePath);
  }

  public function run(): void {
    try {
      $this->router->dispatch($this->request);
    } catch (\Throwable $e) {
      $this->handleException($e);
    }
  }

  protected function handleException(\Throwable $e): void {
    \Tarn\Core\ExceptionHandler::handle($e, clone $this->request);
  }
}
