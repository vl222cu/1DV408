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
    private $filteredName;
    private $newUserName;
    private $isUserRegistered;

    public function __construct() {
        $this->userName = null;
        $this->password = null;
        $this->loginMessage = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->DALObject = new LoginDAL();
        $this->filteredName = null;
        $this->newUserName = null;
        $this->isUserRegistered = false;
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

    public function authenticateUserRegistration($userName, $password, $confirmedPassword) {

        if(empty($userName) && empty($password) && empty($confirmedPassword)) {
            $this->loginMessage = 14;
            return false;        
        } elseif(strlen($userName) < 3) {
            $this->loginMessage = 8;
            return false;
        } elseif(strlen($password) < 6) {
            $this->loginMessage = 9;
            return false;
        } elseif(preg_match('/[^A-Za-z0-9._\-$]/', $userName)) {    
            $this->loginMessage = 12;
            return false;
        } elseif($password != $confirmedPassword) {
            $this->loginMessage = 11;
            return false;
        } elseif (($userName && $password && $confirmedPassword) !== null) {
            $ret = $this->DALObject->setUserCredentialsInDB($userName, $password);
            if($ret == true) {
                $this->isUserRegistered = true;
                $this->loginMessage = 10;
                return true;
            } else {
                $this->loginMessage = 13;
                return false;
            }
        }
    }

    public function sanitizeString($userName) {
        $name = filter_var($userName, FILTER_SANITIZE_STRING);
        $this->filteredName = preg_replace("/[^A-Za-z0-9._\-$]/", "", $name);
        return $this->filteredName;
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

    public function getRegisteredUserName() {
        return $this->newUserName;
    }

    public function getIsUserRegistered() {
        return $this->isUserRegistered;
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