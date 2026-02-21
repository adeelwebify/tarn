<?php

namespace Tarn\Core;

class ExceptionHandler {
    /**
     * Handle the given exception.
     *
     * @param \Throwable $e
     * @param Request $request
     * @return void
     */
    public static function handle(\Throwable $e, Request $request): void {
        // 1. Log the error immediately
        Log::error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => $request->uri(),
            'method' => $request->method(),
            'trace' => $e->getTraceAsString(),
        ]);

        $response = new Response();
        $response->setStatusCode(500);

        // 2. Decide what to show the user based on the environment
        $env = $_ENV['APP_ENV'] ?? 'production';

        if ($env === 'development') {
            // Show detailed error (we can render a simple HTML template for this)
            $response->html(self::renderDevException($e));
        } else {
            // Show friendly 500 page using Blade (if it exists)
            try {
                $response->view('errors.500');
            } catch (\Exception $viewException) {
                // Fallback if the view doesn't exist yet
                $response->html('<h1>500 - Server Error</h1><p>Something went wrong on our end.</p>');
            }
        }

        $response->send();
    }

    /**
     * Generates a simple HTML debug view for the exception.
     *
     * @param \Throwable $e
     * @return string
     */
    protected static function renderDevException(\Throwable $e): string {
        $html = '<div style="font-family: sans-serif; padding: 20px; background: #ffebee; border-left: 5px solid #d32f2f; margin: 20px; word-wrap: break-word;">';
        $html .= '<h2 style="color: #d32f2f; margin-top: 0;">Exception: ' . htmlspecialchars($e->getMessage()) . '</h2>';
        $html .= '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' on line <strong>' . $e->getLine() . '</strong></p>';
        $html .= '<h3>Stack Trace:</h3>';
        $html .= '<pre style="background: #fff; padding: 15px; border: 1px solid #ffcdd2; overflow-x: auto;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        $html .= '</div>';
        
        return $html;
    }
}
