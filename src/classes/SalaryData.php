<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";


use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class SalaryData
{   const ID_FIELD='Emp_ID';
    const PAID_TABLE='Last_Paid';
    const SALARY_TABLE='SALARY_DATA';
    const DATE1_FIELD='Start_Period';
     const DATE2_FIELD='End_period';
       const STATUS_FIELD='Status';
    const AMOUNT_FIELD='Amount';

    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function salaryStore(int $Emp_ID, string $Start_Period,string $End_Period)
    {
        $compute = 10;
        $Status=0;
        $qset = 'SET @compute = ?';
        $stmt = DB::getDB()->mysqlHelper->getConn()->prepare($qset);
        $stmt->bind_param('s', $compute);
        if($stmt->execute() === false) {
            error_log($stmt->error . ' Line no: '. __LINE__);
        }

        $call = 'CALL compute3_salary(?, ?, ?, @compute)';
        $stmt = DB::getDB()->mysqlHelper->getConn()->prepare($call);
        $stmt->bind_param('iss',
            $Emp_ID, $Start_Period, $End_Period);
        $stmt->execute();

        if($stmt->execute() === false) {
            error_log($stmt->error . ' Line no: '. __LINE__);
        }


        $get =  'SELECT @compute';
        $stmt =  DB::getDB()->mysqlHelper->getConn()->prepare($get);
        if($stmt->execute() === false) {
            error_log($stmt->error . ' Line no: '. __LINE__);
        }
        //$stmt->store_result();
        $stmt->bind_result($compute);
        $stmt->fetch();

        DB::getDB()->resetConnection();

        $perform1=new perform();
        $perform1->todo($Emp_ID,$Start_Period,$End_Period,$Status,$compute);

//
//        try {
////            $id = $this->db->mysqlHelper->insert(
////                self::SALARY_TABLE,
////
////                array(self::ID_FIELD, self::DATE1_FIELD, self::DATE2_FIELD, self::STATUS_FIELD,self::AMOUNT_FIELD),
////                'issid',
////            $Emp_ID,$Start_Period,$End_Period,$Status,$compute);
//
//            $query="insert into SALARY_DATA(Emp_ID,Start_Period,End_Period,Status,Amount) values (?,?,?,?,?)";
//            $stmt=DB::getDB()->mysqlHelper->getConn()->prepare($query);
//            $stmt->bind_param(issid,$Emp_ID,$Start_Period,$End_Period,$Status,$compute);
//            if($stmt->execute()===false) {
//                error_log($stmt->error);
//            }
//
//        } catch (Exception $e) {
//            // TODO log the error
//            error_log($e->getMessage());
//        }
    }

    public function add(int $Emp_ID)
    {
        try{
            $id = $this->db->mysqlHelper->insert(
                self::PAID_TABLE,

                array(self::ID_FIELD),
                'i',
                $Emp_ID);
        }
        catch (Exception $e) {
            // TODO log the error
            error_log($e);
        }

    }


}