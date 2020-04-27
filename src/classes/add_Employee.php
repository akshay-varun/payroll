<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";

use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class add_Employee
{
    const EMP_TABLE='EMPLOYEE';
    const NAME_FIELD='Emp_Name';
    const EMAIL_FIELD='Emp_Email';
    const NUMBER_FIELD='Emp_Phone';
    const ID_FIELD='Admin_ID';
    const SALARY_FIELD='Salary';



    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function addEmployee(string $Emp_Name, string $Emp_Email, string $Emp_Phone, string $Admin_ID, string $Salary)
    {

        try {
            $id = $this->db->mysqlHelper->insert(
                self::EMP_TABLE,
                array(self::NAME_FIELD, self::EMAIL_FIELD, self::NUMBER_FIELD, self::ID_FIELD, self::SALARY_FIELD),
                'sssid',
                $Emp_Name, $Emp_Email,$Emp_Phone,$Admin_ID,$Salary);

            if($this->db->mysqlHelper->getConn()->error) {
                echo $this->db->mysqlHelper->getConn()->error;
            }
        } catch (Exception $e) {
            // TODO log the error
            echo $e->getMessage();
        }
    }
}