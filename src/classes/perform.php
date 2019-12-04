<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";


use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class perform
{
    const SALARY_TABLE = 'SALARY_DATA';
    const ID_FIELD = 'Emp_ID';
    const DATE1_FIELD = 'Start_Period';
    const DATE2_FIELD = 'End_period';
    const STATUS_FIELD = 'Status';
    const AMOUNT_FIELD = 'Amount';

    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function todo(string $Emp_ID, string $Start_Period, string $End_Period, string $Status, string $Compute)
    {

        try {
            $query="insert into SALARY_DATA(Emp_ID,Start_Period,End_Period,Status,Amount) values (?,?,?,?,?)";
            $stmt=DB::getDB()->mysqlHelper->getConn()->prepare($query);
            $stmt->bind_param('issid',$Emp_ID,$Start_Period,$End_Period,$Status,$Compute);
            if($stmt->execute()===false) {
                error_log($stmt->error . ' Line no: '. __LINE__);
            }

        } catch (Exception $e) {
            // TODO log the error
            error_log($e);
        }

    }
    public function update_salary(int $Emp_ID, int $salary)
    {
        try {
            $query="update EMPLOYEE set Salary=? where  Emp_ID=?";
            $stmt=DB::getDB()->mysqlHelper->getConn()->prepare($query);
            $stmt->bind_param('ii',$Emp_ID, $salary);
            if($stmt->execute()===false) {
                error_log($stmt->error);
            }

        } catch (Exception $e) {
            error_log($e);
        }
    }
}