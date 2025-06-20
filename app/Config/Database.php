<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [];

    /**
     * This database will handle the local file caching
     * 
     * @var array<string, mixed>
     */
    public array $cache = [];

    /**
     * This database will handle the local file caching for the api logs
     * 
     * @var array<string, mixed>
     */
    public array $apiCache = [];

    /**
     * Constructor
     */
    public function __construct()
    {

        try {
            
            // set the cache database
            foreach([
                'tests' => 'whispernet_db', 
                'views' => 'whispernet_db_views',
                'cache' => 'whispernet_db_cache', 
                'votes' => 'whispernet_db_votes',
                'apiCache' => 'whispernet_db_api',
                'chats' => 'whispernet_db_chats',
                'notification' => 'whispernet_db_notification',
            ] as $group => $db) {
                $this->{$group} = [
                    'hostname'    => '127.0.0.1',
                    'database'    => $db,
                    'DBDriver'    => 'SQLite3',
                    'pConnect'    => false,
                    'DBDebug'     => true,
                    'charset'     => 'utf8',
                    'encrypt'     => false,
                    'compress'    => false,
                    'strictOn'    => false,
                    'failover'    => [],
                    'port'        => 3306,
                    'foreignKeys' => true,
                    'busyTimeout' => 1000,
                    'dateFormat'  => [
                        'date'     => 'Y-m-d',
                        'datetime' => 'Y-m-d H:i:s',
                        'time'     => 'H:i:s',
                    ],
                ];
            }
            
            // set the default group
            $this->defaultGroup = getenv('DB_GROUP') ?? 'default';

        } catch(\Exception $e) { }

        // run the parent constructor
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }

    }
    
}
