<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:23
 */

//require_once("../model/LoginModel.php");

class LoginView {
    private $loginModel;

    public function __construct(LoginModel $loginModel) {
        $this->loginModel = $loginModel;
    }

    public function loginHTML() {
        $returnHTML = "
                <head>
                    <title>Laboration. Inte inloggad.</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                </head>
                <body>
                    <h1>Laboration 2 - uf222ba</h1>
                    <h2>Ej Inloggad</h2>
                    <form enctype='multipart/form-data' method='post' action='?login'>
                        <fieldset>";
        if($this->loginModel->getLoginMessage() !== null) $returnHTML .= $this->loginMessage($this->loginModel->getLoginMessage());
        $returnHTML .= "<legend>Login - Skriv användarnamn och lösenord</legend>
                            <label>Användarnamn: </label><input type='text' name='username' />
                            <label>Lösenord: </label><input type='password' name='password' />
                            <label>Håll mig inloggad: </label><input type='checkbox' name='LoginView::Checked' id='AutologinID' />
                            <input type='submit' value='Logga in' />
                        </fieldset>
                    </form>
                    <p>";
        $returnHTML .= $this->today();
        $returnHTML .= "</p>
                 </body>
                ";

        return $returnHTML;
    }

    public function logOutHTML(){
        if(($this->loginModel->getIsUserAuthenticated() == true) && ($this->loginModel->getSaveLogin() == true)) {
            $this->setCookies();
        }

        $returnHTML = "
                <head>
                    <title>Laboration. Inloggad.</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                </head>
                <body>
                    <h1>Laborationskod xx222aa</h1>
                    <h2>";
        $returnHTML .= $this->loginModel->getUserName();
        $returnHTML .= " är inloggad</h2>";
        if($this->loginModel->getLoginMessage() !== null) $returnHTML .= $this->loginMessage($this->loginModel->getLoginMessage());
        $returnHTML .= "<a href='?logout'>Logga ut</a>
                <p><p>";
        $returnHTML .= $this->today();
        $returnHTML .= "<p>
                </body>
                ";

        return $returnHTML;
    }

    public function loginMessage($type) {
        $loginMsg = array();

        $loginMsg[0] = "<p>Användarnamn saknas</p>";
        $loginMsg[1] = "<p>Lösenord saknas</p>";
        $loginMsg[2] = "<p>Felaktigt användarnamn och/eller lösenord</p>";
        $loginMsg[3] = "<p>Du har loggat ut</p>";
        $loginMsg[4] = "<p>Inloggning lyckades</p>";
        $loginMsg[5] = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
        $loginMsg[6] = "";

        return $loginMsg[$type];
    }

    public function getAction() {
        if(key($_GET) == "login")
            $action = "login";
        elseif(key($_GET) == "logout") {
            $action = "logout";
        } else {
            $action = "";
        }
        return $action;
    }
    public function getPostedUser() {
        return $_POST["username"];
    }

    public function getPostedPassword() {
        return $_POST["password"];
    }

    public function CheckboxSaveLogin() {
        if(isset($_POST["LoginView::Checked"])) {
            //echo "Ikryssad";
            return true;
        } else {
            //echo "EJ ikryssad";
            return false;
        }
    }

    public function today() {
        setlocale(LC_ALL, "sv_SE", "sv_SE.utf-8", "sv", "swedish"); //http://www.webforum.nu/showthread.php?t=182908
        $todayString = ucwords(utf8_encode(strftime("%A"))) . " den " . date("j") . " ".  strftime("%B") . " &aring;r " . strftime("%Y") . ". Klockan &auml;r [" . date("H:i:s") . "].";
        return $todayString;
    }

    public function setCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUserName(), time() + 3600);
        setcookie("LoginView::Password",  $this->loginModel->getPassword(), time() + 3600);
    }

    public function getCookies() {
        if(isset($_COOKIE["LoginView::UserName"]) && isset($_COOKIE["LoginView::Password"])) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUserName(), time() - 3600);
        setcookie("LoginView::Password",  $this->loginModel->getPassword(), time() - 3600);
    }

    public function getUserFromCookie(){
        if(isset($_COOKIE["LoginView::UserName"]))
            return $_COOKIE["LoginView::UserName"];
    }

    public function getPasswordFromCookie(){
        if(isset($_COOKIE["LoginView::Password"]))
            return $_COOKIE["LoginView::Password"];
    }

    public function setSessionVariables() {
        if(isset($_POST["username"]) == true)
            $_SESSION["username"] = $_POST["username"];

        if(isset($_POST["password"]) == true)
            $_SESSION["password"] = $_POST["password"];
    }

    public function getSessionUser() {
        if(isset($_SESSION["username"]) == true)
            return $_SESSION["username"];
    }

    public function getSessionPassword() {
        if(isset($_SESSION["password"]) == true)
            return $_SESSION["password"];
    }

    public function deleteSessionVariables() {
        if(isset($_SESSION["username"]))
            unset($_SESSION["username"]);

        if(isset($_SESSION["password"]))
            unset($_SESSION["password"]);
    }
}