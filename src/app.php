<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/includes/logger.php';


use EasyPHPApp\add_admin;
use EasyPHPApp\add_company;
use EasyPHPApp\add_Employee;
use EasyPHPApp\Attendance;
use EasyPHPApp\DB;
use EasyPHPApp\SalaryData;
use EasyPHPApp\salaryStatus;
use EasyRoute\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use PhpUseful\EasyHeaders;
use PhpUseful\Functions;
use EasyPHPApp\LoginHelper;

$router = new Route();

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);
$makeClassFilter = new \Twig\TwigFilter('class', function ($string) {
    return strtolower(str_replace(' ', '-', $string));
});
$twig->addFilter($makeClassFilter);

try {
    $router->addMatch('GET', '/', function () use ($twig) {
        echo $twig->render('default.twig', array('title' => 'EasyPHP-boilerplate'));
    });


    $router->addMatch('GET', '/admin_login', function () use ($twig) {
        echo $twig->render('admin_login.twig');
    });

    $router->addMatch('GET', '/loginbtn', function () use ($twig) {
        echo $twig->render('loginbtn.twig');
    });

    $router->addMatch('GET', '/owner_dashboard', function () use ($twig) {
        echo $twig->render('owner_dashboard.twig');
    });

    $router->addMatch('GET', '/add_company', function () use ($twig) {
        echo $twig->render('add_company.twig');
    });
    $router->addMatch('GET', '/update', function () use ($twig) {
        echo $twig->render('update.twig');
    });
    $router->addMatch('GET', '/upSalary', function () use ($twig) {
        echo $twig->render('upSalary.twig');
    });
    $router->addMatch('GET', '/delete', function () use ($twig) {
        echo $twig->render('emp_delete.twig');
    });

    $router->addMatch('GET', '/last', function () use ($twig) {
        echo $twig->render('lastPaid.twig');
    });


    $router->addMatch('POST', '/add_company', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['name'])) {

            $name = Functions::escapeInput($_POST['name']);

            $val['name'] = $name;

            $company = new add_company();
            $company->cmp_Name($name);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/add_company');

    });


    $router->addMatch('GET', '/add_admin', function () use ($twig) {
        echo $twig->render('add_admin.twig');
    });

    $router->addMatch('POST', '/add_admin', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['Admin_Name']) && isset($_POST['Email']) && isset($_POST['Company_ID']) && isset($_POST['Password'])) {
            $Admin_Name = Functions::escapeInput($_POST['Admin_Name']);
            $Email = Functions::escapeInput($_POST['Email']);
            $Password = Functions::escapeInput($_POST['Password']);
            $Company_ID = Functions::escapeInput($_POST['Company_ID']);

            $val['Admin_Name'] = $Admin_Name;
            $val['Email'] = $Email;
            $val['Password'] = $Password;
            $val['Company_ID'] = $Company_ID;

            $admin = new add_admin($Email, true);
            $admin->adminADD($Admin_Name, $Email, $Password, $Company_ID);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/add_admin');
    });

    $router->addMatch('GET', '/company_Disp', function () use ($twig) {

        $db = DB::getDB();
        $result = $db->mysqlHelper->fetchAll(add_company::COMPANY_TABLE);
        $list2 = array();
        while ($row2 = $result->fetch_assoc()) {
            array_push($list2, $row2);
        }
        $twigData2 = array();
        $twigData2['company'] = $list2;
        echo $twig->render('company_Disp.twig', $twigData2);
    });

    $router->addMatch('GET', '/admin_Disp', function () use ($twig) {

        $db = DB::getDB();
        $result = $db->mysqlHelper->fetchAll(add_admin::ADMINS_TABLE);
        $list3 = array();
        while ($row3 = $result->fetch_assoc()) {
            array_push($list3, $row3);
        }
        $twigData3 = array();
        $twigData3['admins'] = $list3;
        echo $twig->render('admin_Disp.twig', $twigData3);
    });

    $router->addMatch('POST', '/add_Employee', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['Emp_Name']) && isset($_POST['Emp_Email']) && isset($_POST['Emp_Phone']) && isset($_POST['Admin_ID']) && isset($_POST['Salary'])) {
            $Emp_Name = Functions::escapeInput($_POST['Emp_Name']);
            $Emp_Email = Functions::escapeInput($_POST['Emp_Email']);
            $Emp_Phone = Functions::escapeInput($_POST['Emp_Phone']);
            $Admin_ID = Functions::escapeInput($_POST['Admin_ID']);
            $Salary = Functions::escapeInput($_POST['Salary']);

            $val['Emp_Name'] = $Emp_Name;
            $val['Emp_Email'] = $Emp_Email;
            $val['Emp_Phone'] = $Emp_Phone;
            $val['Admin_ID'] = $Admin_ID;
            $val['Salary'] = $Salary;

            $employee = new add_Employee();
            $employee->addEmployee($Emp_Name, $Emp_Email, $Emp_Phone, $Admin_ID, $Salary);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/dashboard');
    });


//    $router->addMatch('POST', '/Emp_Disp', function () {
//        global $twig;
//        $val = array();
//        $error = array();
//
//        if (isset($_POST['date']) && isset($_POST['select']) && isset($_POST['hour'])) {
//            $date= Functions::escapeInput($_POST['date']);
//            $select = Functions::escapeInput($_POST['select']);
//            $hour = Functions::escapeInput($_POST['hour']);
//
//            $val['date'] = $date;
//            $val['select'] = $select;
//            $val['hour'] = $hour;
//
////            $admin = new add_admin();
////            $admin->adminADD($Admin_Name, $Email, $Password, $Company_ID);
//
//
//        } else {
//            $error['general'] = "Fill all the fields.";
//        }
//        EasyHeaders::redirect('/main_add');
//    });


    //Admin Login

    $router->addMatch('POST', '/admin_login', function () {
        //var_dump($_POST);
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['email']) && isset($_POST['password'])) {

            $email = Functions::escapeInput($_POST['email']);
            $password = Functions::escapeInput($_POST['password']);

            $val['email'] = $email;

            try {
                $admin = new add_admin($email, true);
                if ($admin->login($email, $password)) {
                    LoginHelper::newLogin($admin->getUserId(), $admin->getEmail());
                    EasyHeaders::redirect('/dashboard');
                }
            } catch (Exception $e) {
                if ($e->getCode() === add_admin::$PASSWORD_EXCEPTION_CODE) {
                    $error['password'] = $e->getMessage();
                }
                $error['email'] = $e->getMessage();
            }
        } else {
            $error['general'] = "Fill all the fields";
        }

        $val['error'] = $error;
        echo $twig->render('admin_login.twig', $val);
    });

    $router->addMatch('GET', '/dashboard', function () use ($twig) {
        echo $twig->render('dashboard.twig');
    });

    $router->addMatch('GET', '/add_Employee', function () use ($twig) {
        echo $twig->render('add_Employee.twig');
    });

    //To take the data from database
    $router->addMatch('GET', '/Emp_Disp', function () use ($twig) {
        $db = DB::getDB();
        $result = $db->mysqlHelper->fetchAll(add_Employee::EMP_TABLE);
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
        $twigData = array();
        $twigData['employees'] = $list;
        echo $twig->render('Emp_Disp.twig', $twigData);
    });

    $router->addMatch('GET', '/salary_data', function () use ($twig) {

        $db = DB::getDB();
        $result = $db->mysqlHelper->fetchAll(add_Employee::EMP_TABLE);
        $list1 = array();
        while ($row1 = $result->fetch_assoc()) {
            array_push($list1, $row1);
        }
        $twigData1 = array();
        $twigData1['employees1'] = $list1;
        echo $twig->render('salary_data.twig', $twigData1);
    });

    $router->addMatch('POST', '/Emp_Disp', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['emp-id']) && isset($_POST['date']) && isset($_POST['emp-status']) && isset($_POST['Hour'])) {
            $Emp_ID = Functions::escapeInput($_POST['emp-id']);
            $Date = Functions::escapeInput($_POST['date']);
            $Status = Functions::escapeInput($_POST['emp-status']);
            $Hour = Functions::escapeInput($_POST['Hour']);

            $val['Emp_ID'] = $Emp_ID;
            $val['Date'] = $Date;
            $val['Status'] = $Status;
            $val['Hour'] = $Hour;

            $attend = new Attendance();
            $attend->take_attendance($Emp_ID, $Date, $Status, $Hour);

        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/Emp_Disp');
        echo $Emp_ID;
    });

    $router->addMatch('GET', '/salary_data', function () use ($twig) {

        $db = DB::getDB();
        $result = $db->mysqlHelper->fetchAll(add_Employee::EMP_TABLE);
        $list1 = array();
        while ($row1 = $result->fetch_assoc()) {
            array_push($list1, $row1);
        }
        $twigData1 = array();
        $twigData1['employees1'] = $list1;
        echo $twig->render('salary_data.twig', $twigData1);
    });


    $router->addMatch('POST', '/salary_data', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['emp-id1']) && isset($_POST['str-date1']) && isset($_POST['end-date1'])) {
            $Emp_ID = Functions::escapeInput($_POST['emp-id1']);
            $Start_Period = Functions::escapeInput($_POST['str-date1']);
            $End_Period = Functions::escapeInput($_POST['end-date1']);
            $val['Emp_ID'] = $Emp_ID;
            $val['Start_Period'] = $Start_Period;
            $val['End_Period'] = $End_Period;
            $compute = new SalaryData();
            $compute->salaryStore($Emp_ID, $Start_Period, $End_Period);

            $compute->add($Emp_ID);

        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/salary_data');
    });


    $router->addMatch('GET', '/salary_status', function () use ($twig) {

        $db = DB::getDB();
        $query = "select Emp_Name,m.Emp_ID,m.Start_Period,m.End_Period,m.Amount,m.Txn_ID from EMPLOYEE e, SALARY_DATA m where e.Emp_ID=m.Emp_ID AND m.Status=0";
        $result1 = DB::getDB()->mysqlHelper->getConn()->query($query);
        $list4 = array();
        if ($result1->num_rows > 0) {
            while ($row4 = $result1->fetch_assoc()) {
                array_push($list4, $row4);
            }
        }
        $twigData4 = array();
        $twigData4['details'] = $list4;

        echo $twig->render('salary_status.twig', $twigData4);
    });


    $router->addMatch('POST', '/salary_status', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['emp-id2']) && isset($_POST['str-date1']) && isset($_POST['status']) && isset($_POST['txn'])) {
            $Emp_ID = Functions::escapeInput($_POST['emp-id2']);
            $Start_Period = Functions::escapeInput($_POST['str-date1']);
            $Status = Functions::escapeInput($_POST['status']);
            $txn = Functions::escapeInput($_POST['txn']);

            $val['Emp_ID'] = $Emp_ID;
            $val['Start_Period'] = $Start_Period;
            $val['End_Period'] = $Status;
            $val['txn'] = $txn;

            $save1 = new salaryStatus();
            $save1->status($Emp_ID, $Start_Period, $Status);
            $save1->lastTxn($txn, $Emp_ID);

        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/salary_status');
    });

    //INFO

    $router->addMatch('GET', '/info', function () use ($twig) {

        $db = DB::getDB();
        $query = "select DISTINCT e.Emp_ID,e.Emp_Name,s.Start_Period,s.End_Period,s.Status,e.Salary from EMPLOYEE e, SALARY_DATA s where e.Emp_ID=s.Emp_ID;";
        $result = DB::getDB()->mysqlHelper->getConn()->query($query);
        $list = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($list, $row);
            }
        }
        $twigData = array();
        $twigData['details'] = $list;
//var_dump($twigData);
        echo $twig->render('info.twig', $twigData);
    });

//
//    $router->addMatch('POST', '/update', function () {
//        global $twig;
//        $val = array();
//        $error = array();
//
//        if (isset($_POST['data'])) {
//
//            $data= Functions::escapeInput($_POST['data']);
//
//            $val['data'] = $data;
//
//            $do = new update();
//            $do->updated($data);
//
//
//        } else {
//            $error['general'] = "Fill all the fields.";
//        }
//        EasyHeaders::redirect('/add_company');
//
//    });

    $router->addMatch('POST', '/upSalary', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['id']) && isset($_POST['salary'])) {

            $id = Functions::escapeInput($_POST['id']);
            $salary = Functions::escapeInput($_POST['salary']);

            $val['id'] = $id;
            $val['salary'] = $salary;

            $update = new \EasyPHPApp\perform();
            $update->update_salary($id, $salary);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/upSalary');

    });

    $router->addMatch('GET', '/last', function () use ($twig) {
        $db = DB::getDB();
        $query = "select e.Emp_Name,e.Emp_ID,l.Last_Txn_ID,s.Amount from EMPLOYEE e, Last_Paid l, SALARY_DATA s where e.Emp_ID=s.Emp_ID AND s.Txn_ID=l.Last_Txn_ID";
        $result = DB::getDB()->mysqlHelper->getConn()->query($query);
        $list = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($list, $row);
            }
        }
        $twigData = array();
        $twigData['lastPaid'] = $list;
        echo $twig->render('lastPaid.twig', $twigData);
    });

    $router->execute();

//    $router->addMatch('GET', '/test', function ()
//    {
//        $Emp_ID = 1001;
//        $Start_Period = '2019-11-01';
//        $End_Period='2019-11-25';
//        $Status=0;
//        $compute=400;
//
//        $query="insert into SALARY_DATA(Emp_ID,Start_Period,End_Period,Status,Amount) values (?,?,?,?,?)";
//        $stmt=DB::getDB()->mysqlHelper->getConn()->prepare($query);
//        $stmt->bind_param('issid',$Emp_ID,$Start_Period,$End_Period,$Status,$compute);
//        if($stmt->execute()===false) {
//            error_log($stmt->error);
//        }
//    });


} catch (Exception $e) {
    error_log($e->getMessage());
}
