<?php
namespace EasyPHPApp;

require_once __DIR__ . "/../../vendor/autoload.php";
use EasyPHPApp\add_admin;


class LoginHelper{
    public static function startSecureSession()
    {
        ini_set('session.use_only_cookies', 1);
        ini_set('session.hash_function', 'sha512');
        ini_set('session.hash_bits_per_character', 5);

        $cookieParams = session_get_cookie_params();
        session_set_cookie_params(
            $cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            false, // set to true for HTTPS connections;
            true);

        session_name('Payroll_');
        session_start();
        session_regenerate_id(true);
    }

    public static function newLogin(int $uid, string $email)
    {
        $_SESSION['login'] = 1;
        $_SESSION['key'] = self::getSessionHash($uid, $email);
        $_SESSION['Admin_ID'] = $uid;
        $_SESSION['Email'] = $email;
    }


    private static function getSessionHash(int $id, string $email): string
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return hash('sha256', $id . $email . $user_agent);
    }

    public static function getLoggedInUser(): add_admin
    {
        try {
            if (self::isLoggedIn()) {
                return new add_admin($_SESSION['Email']);
            } else {
                throw new Exception("No User is logged in.", 198);
            }
        } catch (Exception $e) {
            if ($e->getCode() === 198) {
                throw new Exception($e);
            } else {
                error_log($e->getMessage());
            }

        }

    }
    public static function isLoggedIn(): bool
    {
        if (isset($_SESSION['login']) && isset($_SESSION['key']) && $_SESSION['login'] === 1) {
            $user_id = $_SESSION['Admin_ID'];
            $email = $_SESSION['Email'];
            $key = $_SESSION['key'];
            $sHash = self::getSessionHash($user_id, $email);
            if ($key === $sHash) {
                return true;
            }
        }
        return false;
    }

    public function logout()
    {
        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        session_destroy();
    }
}