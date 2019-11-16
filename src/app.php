<?php
require_once __DIR__ . '/../vendor/autoload.php';

//require_once __DIR__ . "/logger.php";


use EasyPHPApp\add_admin;
use EasyPHPApp\add_company;
use EasyPHPApp\add_Employee;
use EasyRoute\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use PhpUseful\EasyHeaders;
use PhpUseful\Functions;

$router = new Route();

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);


try {
    $router->addMatch('GET', '/', function () use ($twig) {
        echo $twig->render('default.twig', array('title' => 'EasyPHP-boilerplate'));
    });

    $router->addMatch('GET', '/company', function () use ($twig) {
        echo $twig->render('company.twig');
    });

    $router->addMatch('GET', '/admin_login', function () use ($twig) {
        echo $twig->render('admin_login.twig');
    });

    $router->addMatch('GET', '/loginbtn', function () use ($twig) {
        echo $twig->render('loginbtn.twig');
    });

    $router->addMatch('GET', '/main_add', function () use ($twig) {
        echo $twig->render('main_add.twig');
    });

    $router->addMatch('GET', '/add_company', function () use ($twig) {
        echo $twig->render('add_company.twig');
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
        EasyHeaders::redirect('/main_add');

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

            $admin = new add_admin();
            $admin->adminADD($Admin_Name, $Email, $Password, $Company_ID);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/main_add');
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
            $Salary=Functions::escapeInput($_POST['Salary']);

            $val['Emp_Name'] = $Emp_Name;
            $val['Emp_Email'] = $Emp_Email;
            $val['Emp_Phone'] = $Emp_Phone;
            $val['Admin_ID'] = $Admin_ID;
            $val['Salary']=$Salary;

          $employee = new add_Employee();
         $employee->addEmployee($Emp_Name,$Emp_Email,$Emp_Phone,$Admin_ID,$Salary);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/dashboard');
    });


    $router->addMatch('GET', '/dashboard', function () use ($twig) {
        echo $twig->render('dashboard.twig');
    });

    $router->addMatch('GET', '/add_Employee', function () use ($twig) {
        echo $twig->render('add_Employee.twig');
    });


    $router->execute();

} catch (Exception $e) {
    error_log($e->getMessage());
}
