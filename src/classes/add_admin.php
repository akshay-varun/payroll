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
    public static $EMAIL_EXCEPTION_CODE = 112;
    public static $PASSWORD_EXCEPTION_CODE = 113;

    private $Admin_ID;
    private $Admin_Name;
    private $Email;

    private $db;

    public function __construct1()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function adminADD(string $Admin_Name, string $Email, string $Password, int $Company_ID)
    {

        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
        try {
            $id = $this->mysqlHelper->insert(
                self::ADMINS_TABLE,
                array(self::NAME_FIELD, self::EMAIL_FIELD, self::PASSWORD_FIELD, self::ID_FIELD1),
                'sssi',
                $Admin_Name, $Email, $hashedPassword, $Company_ID);

        } catch (Exception $e) {
            // TODO log the error
            error_log($e->getMessage());
        }
    }
    public function login(string $email, string $password): bool
    {
        $result =
            $this->mysqlHelper->fetchRow(self::ADMINS_TABLE,
                self::EMAIL_FIELD,
                $email);
        if ($result !== null) {

            $hash = $result[self::PASSWORD_FIELD];
            if (password_verify($password, $hash) === TRUE) {

                $this->Admin_ID = $result[self::ID_FIELD];
                $this->name = $result[self::NAME_FIELD];
                return true;

            } else {
                throw new Exception("The email or password is incorrect.", self::$PASSWORD_EXCEPTION_CODE);
            }
        } else {
            throw new Exception("The email or password is incorrect.", self::$PASSWORD_EXCEPTION_CODE);
        }
    }

    public function __construct(string $Email, bool $new = false)
    {
        if (!$this->isEmailValid($Email)) {
            throw new Exception("Email address is not valid.", self::$EMAIL_EXCEPTION_CODE);
        }
        try {

            $this->mysqlHelper =
                new MySQLHelper(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        } catch (Exception $e) {
            logger::getLogger()->critical('Error connecting to database' . $e->getMessage());
            EasyHeaders::server_error();
        }

//        if ($new) {
//            if ($this->userExists($Email)) {
//                throw new Exception("Email is already registered.", self::$EMAIL_EXCEPTION_CODE);
//            }
//        }
        $this->Email = $Email;
        if (isset($_SESSION['login'])) {
            $this->fetchDetails();
        }
    }

    private function isEmailValid(string $input): bool
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function userExists(string $Email): bool
    {
        $count =
            $this->mysqlHelper->getResultCount(self::ADMINS_TABLE, self::EMAIL_FIELD, $Email);
        return ($count > 0);
    }

    private function fetchDetails()
    {
        $result = $this->db->mysqlHelper->fetchRow(self::ADMINS_TABLE, self::EMAIL_FIELD, $this->Email);
        if ($result !== null) {
            $this->Admin_ID = $result[self::ID_FIELD];
            $this->Admin_Name = $result[self::NAME_FIELD];
        }
    }

    public function getUserId(): int
    {
        return $this->Admin_ID;
    }

    public function getName(): string
    {
        return $this->Admin_Name;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }
}
