<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:23
 */

require_once("LoginDAL.php");

class LoginModel {
    private $userName;
    private $password;
    private $isUserAuthenticated;
    private $saveLogin;
    private $loginMessage;
    private $DALObject;

    public function __construct() {
        $this->userName = null;
        $this->password = null;
        $this->loginMessage = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->DALObject = new LoginDAL();
    }

    public function authenticateUser($userName, $password) {

        if(empty($userName)) {
            $this->loginMessage = 0;
            return false;
        } elseif(empty($password)) {
            $this->loginMessage = 1;
            return false;
        } elseif(($userName && $password) !== null) {
            $ret = $this->DALObject->getUserCredentialsFromDB($userName, $password);
            if($ret == true) {
                $this->setUserCredentials($userName, $password);
                $this->loginMessage = 4;
                return true;
            } else {
                $this->isUserAuthenticated = false;
                $this->loginMessage = 2;
                return false;
            }
        }
        else {
            $this->loginMessage = 2;
            return false;
        }
    }

    public function logOutUser() {
        $this->loginMessage = 3;
        $this->userName = "";
        $this->password = "";
    }

    public function getLoginMessage(){
        return $this->loginMessage;
    }

    public function getIsUserAuthenticated() {
        return $this->isUserAuthenticated;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setUserCredentials($userName, $password) {
        $this->isUserAuthenticated = true;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function setSaveLogin($bool) {
        $this->saveLogin = $bool;
        if($this->saveLogin === true) {
            $this->loginMessage = 5;
            //echo " LOGINMSG = 5 ";
        } elseif($this->saveLogin === false) {
            $this->loginMessage = 4;
            //echo " LOGINMSG = 4 ";
        }
    }

    public function getSaveLogin() {
        return $this->saveLogin;
    }

    public function setLoginMessage($code){
        $this->loginMessage = $code;
    }
}