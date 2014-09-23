<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:22
 */

require_once("LoginView.php");
require_once("LoginModel.php");

class LoginController {
    private $loginView;
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
        $this->loginView = new LoginView($this->loginModel);
    }

    public function doLogin() {
        $action = $this->loginView->getAction();
        //$this->loginView->updateSessionInfo();
        //var_dump($_SESSION);

        if(($this->loginView->getSessionUser() !== null) && ($action !== "logout") ) {
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getSessionUser(), $this->loginView->getSessionPassword());
            if($isUserValid == true) {
                $this->loginModel->setLoginMessage(6);
                return $this->loginView->logOutHTML();
            } else {
            return $this->loginView->loginHTML();
            }
        }

        if(($this->loginView->getCookies() == true) && ($action !== "logout")) {
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getUserFromCookie(), $this->loginView->getPasswordFromCookie());
            if($isUserValid == true) {
                $this->loginModel->setLoginMessage(6);
                return $this->loginView->logOutHTML();
            } else {
            return $this->loginView->loginHTML();
            }
        }

        if(empty($action)) {
            return $this->loginView->loginHTML();
        } elseif($action === "login") {
           // anropa model med anvnamn o lösenord
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getPostedUser(), $this->loginView->getPostedPassword());
            // var_dump($_POST);

            //echo("Logga in");
            if($isUserValid == true) {
                $this->loginModel->setSaveLogin($this->loginView->CheckboxSaveLogin());
                //var_dump($this->loginView->CheckboxSaveLogin());
                $this->loginView->setSessionVariables();
                return $this->loginView->logOutHTML();
            } else {
                return $this->loginView->loginHTML();
            }

        } elseif($action === "logout") {

            // töm eventuella kakor i model osv
            // anropa tom inloggning från vyn med texten: Du har nu loggat ut
            $this->loginModel->logOutUser();
            //echo("Logga ut");
            //ta bort ev kakor
            $this->loginView->deleteSessionVariables();
            $this->loginView->deleteCookies();
            //var_dump($_SESSION);
            return $this->loginView->loginHTML();
        } else {
            return $this->loginView->loginHTML();
        }
    }
}
