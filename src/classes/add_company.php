<?php

namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";

use Exception;
use PhpUseful\EasyHeaders;
use PhpUseful\MySQLHelper;

class add_company
{
    const COMPANY_TABLE = 'COMPANY';
    const ID_FIELD = 'Company_ID';
    const NAME_FIELD = ' Company_Name';

//    private $Company_ID;
//    private $Company_Name;

    private $db;

    public function __construct()
    {
        $this->db = \EasyPHPApp\DB::getDB();
    }

    public function cmp_Name(string $Company_Name)
    {

        try {
            $id = $this->db->mysqlHelper->insert(
                self::COMPANY_TABLE,
                array(self::NAME_FIELD),
                's',
                $Company_Name);
        } catch (Exception $e) {
            // TODO log the error
            error_log($e->getMessage());
        }
    }
}