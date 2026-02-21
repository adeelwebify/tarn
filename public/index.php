<?php

/**
 * Entry file
 */

// Disable deprecation warnings in PHP 8.4+
error_reporting(E_ALL & ~E_DEPRECATED);

define('TARN_ROOT', __DIR__ . '/../');

require_once TARN_ROOT . 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(TARN_ROOT);
$dotenv->safeLoad();

require_once TARN_ROOT . 'app/Core/functions.php';

require_once TARN_ROOT . 'app/Core/Application.php';
(new Tarn\Core\Application())->run();
