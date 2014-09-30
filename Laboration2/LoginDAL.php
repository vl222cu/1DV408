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
        $this->dbConnection = mysqli_connect("xxx", "xxx", "xxx", "xxx");

        if(!$this->dbConnection) {
            die('Connectionfailure: ' . mysql_error());
        }
    }

    public function getUserCredentialsFromDB($user, $pwd) {
        $result = mysqli_query($this->dbConnection, "SELECT username
                                                      , password
                                                      FROM login
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

    public function setUserCredentialsInDB($user, $pwd) {
        $sqlQuery = mysqli_query($this->dbConnection, "SELECT username
                                                        FROM login
                                                        WHERE username = '$user'");

        if (mysqli_num_rows($sqlQuery) > 0) {
            return false;
        }
        else {
        $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO login
                                                            (username, password)
                                                            VALUES ('$user', '$pwd')");

            $this->dbConnection->close();

            if($sqlInsert){
                return true;
            }
            else {
                return false;
            }
        }
    }
}
