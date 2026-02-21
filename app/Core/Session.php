<?php

namespace Tarn\Core;

class Session {

    /**
     * Initialize the session.
     *
     * @return void
     */
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cycle flash messages. Move "new flash" to "old flash" 
        // and clear anything that was old from the previous request.
        if (isset($_SESSION['_flash_old'])) {
            foreach ($_SESSION['_flash_old'] as $key) {
                unset($_SESSION[$key]);
            }
        }
        
        $_SESSION['_flash_old'] = $_SESSION['_flash_new'] ?? [];
        $_SESSION['_flash_new'] = [];
    }

    /**
     * Set a session value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function put(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session key exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    /**
     * Set a flash message that will only exist for the NEXT request.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function flash(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
        $_SESSION['_flash_new'][] = $key;
    }

    /**
     * Flash current input data (useful for repopulating forms if validation fails).
     *
     * @param Request $request
     * @return void
     */
    public static function flashInput(Request $request): void {
        self::flash('_old_input', $request->all());
    }

    /**
     * Retrieve "old" input data from the previous request.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public static function old(string $key = null, mixed $default = '') {
        $oldInput = self::get('_old_input', []);
        
        if ($key === null) {
            return $oldInput;
        }

        return $oldInput[$key] ?? $default;
    }
}
