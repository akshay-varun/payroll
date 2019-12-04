<?php


namespace EasyPHPApp;

require_once __DIR__."/../../vendor/autoload.php";

use PhpUseful\MySQLHelper;


class DB
{

    private static $dbObj = null;

    public $mysqlHelper;

    private function __construct()
    {
        try {
            $this->mysqlHelper = new MySQLHelper(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getDB()
    {
        if (self::$dbObj === null) {
            self::$dbObj = new DB();
        }
        return self::$dbObj;
    }

    public function resetConnection()
    {
        $this->mysqlHelper = null;
        $this->mysqlHelper = new MySQLHelper(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
    }

}