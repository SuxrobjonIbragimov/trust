<?php

namespace common\library\paycom\Paycom;

class Database
{
    public $config;

    protected static $db;

    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    public function new_connection()
    {
        $db = null;

        return $db;
    }

    /**
     * Connects to the database
     * @return null|\PDO connection
     */
    public static function db()
    {
        if (!self::$db) {
            $config   = require_once CONFIG_FILE;
            $instance = new self($config);
            self::$db = $instance->new_connection();
        }

        return self::$db;
    }
}