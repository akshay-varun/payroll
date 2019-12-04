<?php
namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";
use EasyPHPApp\add_admin;

class logout
{
    public function logout()
    {
        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        session_destroy();
    }
}