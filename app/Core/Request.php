<?php

namespace Tarn\Core;

class Request {

    /**
     * @var array
     */
    public readonly array $get;

    /**
     * @var array
     */
    public readonly array $post;

    /**
     * @var array
     */
    public readonly array $server;

    /**
     * @var array
     */
    public readonly array $files;

    /**
     * @var array
     */
    public readonly array $cookies;

    /**
     * Request constructor.
     */
    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    /**
     * Get the HTTP method.
     *
     * @return string
     */
    public function method(): string {
        return $_POST['_METHOD_'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Get the requested URI.
     *
     * @return string
     */
    public function uri(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        return rawurldecode((string) $uri);
    }

    /**
     * Get a value from the GET request (query string) or all values if no key is provided.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(?string $key = null, mixed $default = null): mixed {
        if($key === null) {
          return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    /**
     * Get a value from the POST request or all values if no key is provided.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post(?string $key = null, mixed $default = null): mixed {
        if($key === null) {
          return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    /**
     * Get all request data (GET and POST).
     *
     * @return array
     */
    public function all(): array {
        return array_merge($this->get, $this->post);
    }

    /**
     * Check if a key exists in the request data.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool {
        return array_key_exists($key, $this->all());
    }

    /**
     * Get a value from the headers.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function header(string $key, mixed $default = null): mixed {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$serverKey] ?? $default;
    }
}
