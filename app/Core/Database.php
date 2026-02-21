<?php

namespace Tarn\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database {
    protected static ?Capsule $capsule = null;

    /**
     * Initialize the ORM connection using .env configuration.
     *
     * @return void
     */
    public static function init(): void {
        self::$capsule = new Capsule;

        $driver = $_ENV['DB_DRIVER'] ?? 'mysql';

        $config = [
            'driver'    => $driver,
            'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port'      => $_ENV['DB_PORT'] ?? '3306',
            'database'  => $_ENV['DB_NAME'] ?? 'forge',
            'username'  => $_ENV['DB_USER'] ?? 'forge',
            'password'  => $_ENV['DB_PASS'] ?? '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ];

        // SQLite requires a specific path for the database file
        if ($driver === 'sqlite') {
            $config['database'] = TARN_ROOT . ($_ENV['DB_DATABASE'] ?? 'storage/database.sqlite');
        }

        self::$capsule->addConnection($config);

        // Make this Capsule instance available globally via static methods (e.g., Capsule::table('users'))
        self::$capsule->setAsGlobal();

        // Setup the Eloquent ORM so Models can be used
        self::$capsule->bootEloquent();
    }
}
