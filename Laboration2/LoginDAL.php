<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:22
 */

class LoginDAL {
    private $dbConnection;


    public function __construct() {
        $this->dbConnection = mysqli_connect("xxxx", "xxxx", "xxxx", "xxxx");
    }

    public function getUserCredentialsFromDB($user, $pwd) {
        $result = mysqli_query($this->dbConnection, "SELECT username
                                                      , password
                                                      FROM users
                                                      WHERE username = '$user' AND password = '$pwd'");

        $this->dbConnection->close();

        if(mysqli_num_rows($result) == 1){
            //$row = mysqli_fetch_array($result);
            //echo "Anvandarnam: " . $row[0] . " " . "Losenord: " . $row[1];
            return true;
        }
        else {
            return false;
        }
    }
}
