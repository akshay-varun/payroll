<?php
require_once __DIR__ . '/../vendor/autoload.php';
//require_once __DIR__ . "/logger.php";


use EasyPHPApp\add_company;
use EasyRoute\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use PhpUseful\EasyHeaders;
use PhpUseful\Functions;

$router = new Route();

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);


try {
    $router->addMatch('GET', '/', function () use($twig) {
        echo $twig->render('default.twig', array('title' => 'EasyPHP-boilerplate'));
    });

    $router->addMatch('GET', '/company', function () use($twig) {
        echo $twig->render('company.twig');
    });

    $router->addMatch('GET', '/admin_login',function () use($twig){
        echo $twig->render('admin_login.twig');
    });

    $router->addMatch('GET','/loginbtn',function () use($twig){
        echo $twig->render('loginbtn.twig');
    });

    $router->addMatch('GET','/main_add',function () use($twig){
        echo $twig->render('main_add.twig');
    });

    $router->addMatch('GET','/add_company',function () use($twig){
        echo $twig->render('add_company.twig');
    });

    $router->addMatch('POST', '/add_company', function () {
        global $twig;
        $val = array();
        $error = array();

        if (isset($_POST['name'])) {

            $name = Functions::escapeInput($_POST['name']);

            $val['name'] = $name;

                $company= new add_company();
                $company->cmp_Name($name);


        } else {
            $error['general'] = "Fill all the fields.";
        }
        EasyHeaders::redirect('/main_add');

    });


    $router->addMatch('GET','/add_admin',function () use($twig){
        echo $twig->render('add_admin.twig');
    });
    $router->execute();

} catch (Exception $e) {
    error_log($e->getMessage());
}
