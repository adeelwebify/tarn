<?php

namespace Tarn\Core;

class Response {
    protected string $content = '';
    protected int $statusCode = 200;
    protected array $headers = [];

    /**
     * Set the HTTP status code.
     *
     * @param int $code
     * @return self
     */
    public function setStatusCode(int $code): self {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Add a header.
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setHeader(string $key, string $value): self {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Return a JSON response.
     *
     * @param mixed $data
     * @param int $status
     * @return self
     */
    public function json(mixed $data, int $status = 200): self {
        $this->content = json_encode($data, JSON_THROW_ON_ERROR);
        $this->statusCode = $status;
        $this->setHeader('Content-Type', 'application/json');
        return $this;
    }

    /**
     * Return an HTML response (usually from a view).
     *
     * @param string $html
     * @param int $status
     * @return self
     */
    public function html(string $html, int $status = 200): self {
        $this->content = $html;
        $this->statusCode = $status;
        $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        return $this;
    }

    /**
     * Return a rendered view.
     *
     * @param string $view
     * @param array $data
     * @param int $status
     * @return self
     */
    public function view(string $view, array $data = [], int $status = 200): self {
        $html = View::render($view, $data);
        return $this->html($html, $status);
    }

    /**
     * Redirect to another URL.
     *
     * @param string $url
     * @param int $status
     * @return self
     */
    public function redirect(string $url, int $status = 302): self {
        $this->statusCode = $status;
        $this->setHeader('Location', $url);
        return $this;
    }

    /**
     * Send the final response to the browser.
     *
     * @return void
     */
    public function send(): void {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }
        }
        
        echo $this->content;
        exit;
    }
}
