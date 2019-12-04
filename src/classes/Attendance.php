<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";


use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class Attendance
{
    const ATTENDANCE_TABLE='EMPLOYEE_ATTENDANCE';
    const ID_FIELD='Emp_ID';
    const DATE_FIELD='Att_Date';
    const PRESENT_FIELD='Present';
    const HOUR_FIELD='No_Of_Hours';


    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function take_attendance(string $Emp_ID, string $Date,bool $Present, int $No_Of_Hours)
    {
        $date = strtotime($Date);
     $newDate  = date('Y-m-d', $date);
        try {
            $id = $this->db->mysqlHelper->insert(
                self::ATTENDANCE_TABLE,

                array(self::ID_FIELD, self::DATE_FIELD, self::PRESENT_FIELD, self::HOUR_FIELD),
                'issi',
                $Emp_ID, $newDate,$Present,$No_Of_Hours);

        } catch (Exception $e) {
            // TODO log the error
            error_log($e);
        }
    }
}