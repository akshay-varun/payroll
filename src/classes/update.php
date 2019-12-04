<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";


use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class update
{


    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }
    public function do(int $Emp_ID, string $Start_Period,bool $Status)
    {
        //var_dump($Start_Period);
        //exit(0);
        try {

            $query="update SALARY_DATA s SET s.Status=? where s.Emp_ID=? AND s.Start_Period=?";
            $stmt=DB::getDB()->mysqlHelper->getConn()->prepare($query);

            $stmt->bind_param('sis',$Status,$Emp_ID,$Start_Period);
            if($stmt->execute()===false) {
                error_log($stmt->error);
            }
//            if (DB::getDB()->mysqlHelper->getConn()->query($query) === TRUE) {
//                echo "Record updated successfully";
//            } else {
//                echo "Error updating record ";
//                error_log(DB::getDB()->mysqlHelper->getConn()->error);
//            }


        } catch (Exception $e) {
            // TODO log the error
            error_log($e);
        }
    }


}