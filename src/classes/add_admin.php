<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";


use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class add_admin
{
    const ADMINS_TABLE='ADMINS';
    const ID_FIELD='Admin_ID';
    const NAME_FIELD='Admin_Name';
    const EMAIL_FIELD='Email';
    const PASSWORD_FIELD='Password';
    const ID_FIELD1='Company_ID';

    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function adminADD(string $Admin_Name, string $Email, string $Password, int $Company_ID)
    {

        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
        try {
            $id = $this->db->mysqlHelper->insert(
                self::ADMINS_TABLE,
                array(self::NAME_FIELD, self::EMAIL_FIELD, self::PASSWORD_FIELD, self::ID_FIELD1),
                'sssi',
                $Admin_Name, $Email, $hashedPassword, $Company_ID);

        } catch (Exception $e) {
            // TODO log the error
            error_log($e->getMessage());
        }
    }
}


