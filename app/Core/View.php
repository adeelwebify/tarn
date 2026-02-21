<?php

namespace Tarn\Core;

use Jenssegers\Blade\Blade;

class View {
    protected static ?Blade $blade = null;

    /**
     * Initialize the Blade engine statically.
     *
     * @param string $viewsPath Path to the views directory
     * @param string $cachePath Path to the cache directory
     * @return void
     */
    public static function init(string $viewsPath, string $cachePath): void {
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }
        self::$blade = new Blade($viewsPath, $cachePath);
    }

    /**
     * Render a view file using Blade.
     *
     * @param string $view Name of the view (e.g., 'home.index')
     * @param array $data Data to pass to the view
     * @return string
     * @throws \RuntimeException if init() hasn't been called
     */
    public static function render(string $view, array $data = []): string {
        if (self::$blade === null) {
            throw new \RuntimeException('View engine has not been initialized. Call View::init() first.');
        }

        return self::$blade->render($view, $data);
    }
}
