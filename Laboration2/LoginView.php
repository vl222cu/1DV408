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
    public $name;

    public function __construct(LoginModel $loginModel) {
        $this->loginModel = $loginModel;
        $this->name = isset($_POST['username']) ? $_POST['username'] : '';
    }

    public function loginHTML() {
        if($this->loginModel->getIsUserRegistered()) {
            $this->name = $this->getRegisteredUser();
        }

        $returnHTML = "
                <head>
                    <title>Laboration. Inte inloggad.</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                </head>
                <body>
                    <h1>Laboration 4 - vl222cu</h1>
                    <p><a href='?registerpage'>Registrera ny användare</a></p>
                    <h2>Ej Inloggad</h2>
                    <form enctype='multipart/form-data' method='post' action='?login'>
                        <fieldset>";
        if($this->loginModel->getLoginMessage() !== null) $returnHTML .= $this->loginMessage($this->loginModel->getLoginMessage());
        $returnHTML .= "<legend>Login - Skriv användarnamn och lösenord</legend>
                            <label>Användarnamn: </label><input type='text' name='username' value='$this->name' />
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
                    <h1>Laboration 4 - vl222cu</h1>
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

    public function registerHTML() {
        $this->name = $this->loginModel->sanitizeString($this->name);

        $returnHTML = "
                <head>
                    <title>Laboration. Registrera användare.</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                </head>
                <body>
                    <h1>Laboration 4 - vl222cu</h1>
                    <p><a href='?return'>Tillbaka</a></p>
                    <h2>Ej Inloggad, Registrerar användare</h2>
                    <form enctype='multipart/form-data' method='post' action='?register'>
                        <fieldset>";
        if($this->loginModel->getLoginMessage() !== null) $returnHTML .= $this->loginMessage($this->loginModel->getLoginMessage());
        $returnHTML .= "<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
                            <p><label>Användarnamn: </label><input type='text' name='username' value='$this->name'/></p>
                            <p><label>Lösenord: </label><input type='password' name='password'/></p>
                            <p><label>Repetera Lösenord: </label><input type='password' name='confirmPassword'/></p>
                            <p><label>Skicka: </label><input type='submit' value='Registrera'/>
                        </fieldset>
                    </form>
                    <p>";
        $returnHTML .= $this->today();
        $returnHTML .= "</p>
                 </body>
                ";

        return $returnHTML;
    }

    public function loginMessage($type) {
        $loginMsg = array();

        $loginMsg[0] = "<p>Användarnamn saknas</p>";
        $loginMsg[1] = "<p>Lösenord saknas</p>";
        $loginMsg[2] = "<p>Felaktigt användarnamn och/eller lösenord</p>";
        $loginMsg[3] = "<p>Du har nu loggat ut</p>";
        $loginMsg[4] = "<p>Inloggning lyckades</p>";
        $loginMsg[5] = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
        $loginMsg[6] = "<p>Inloggning lyckades via cookies</p>";
        $loginMsg[7] = "<p>Felaktig information i cookie</p>";
        $loginMsg[8] = "<p>Användarnamnet har för få tecken. Minst 3 tecken</p>";
        $loginMsg[9] = "<p>Lösenordet har för få tecken. Minst 6 tecken</p>";
        $loginMsg[10] = "<p>Registrering av ny användare lyckades</p>";
        $loginMsg[11] = "<p>Lösenorden matchar inte</p>";
        $loginMsg[12] = "<p>Användarnamnet innehåller ogiltiga tecken</p>";
        $loginMsg[13] = "<p>Användarnamnet är redan upptaget</p>";
        $loginMsg[14] = "<p>Användarnamnet har för få tecken. Minst 3 tecken</p>
                         <p>Lösenordet har för få tecken. Minst 6 tecken</p>";

        return $loginMsg[$type];
    }

    public function getAction() {
        if(key($_GET) == "login")
            $action = "login";
        elseif(key($_GET) == "logout") {
            $action = "logout";
        } elseif(key($_GET) == "registerpage") {
            $action = "registerpage";
        } elseif(key($_GET) == "register") {
            $action = "register";
        } elseif(key($_GET) == "return") {
            $action = "return";
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
    //Lagt till
    public function getRegisteredUser() {
        return $_POST["username"];
    }
    //Lagt till
    public function getRegisteredPassword() {
        return $_POST["password"];
    }
    //Lagt till
    public function getConfirmedPassword() {
        return $_POST["confirmPassword"];
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
        $passwordIsEncrypted = crypt($this->loginModel->getPassword());

        setcookie("LoginView::UserName", $this->loginModel->getUserName(), time() + 3600);
        setcookie("LoginView::Password",  $passwordIsEncrypted, time() + 3600);
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