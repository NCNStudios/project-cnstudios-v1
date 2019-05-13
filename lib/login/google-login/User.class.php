<?php

class User {
    private $dbHost     = "localhost";
    private $dbUsername = "cnstudios";
    private $dbPassword = "Chinguyen5161#";
    private $dbName     = "cnstudios";
    private $userTbl    = 'users';
    
    function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            mysqli_set_charset($conn, 'UTF8');

            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
    
    function checkUser($userData = array()){
        if(!empty($userData)){
            // Check whether user data already exists in the database
            $checkQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
            $checkResult = $this->db->query($checkQuery);
            if($checkResult->num_rows > 0){
                // Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = NOW() WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
                $update = $this->db->query($query);
            }else{
                // Insert user data in the database
                $rank = "User";
                $query = "INSERT INTO ".$this->userTbl." SET oauth_provider = '".$userData['oauth_provider']."', oauth_uid = '".$userData['oauth_uid']."', first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."',rank = '".$rank."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', created = NOW(), modified = NOW()";
                $insert = $this->db->query($query);
            }
            
            // Get user data from the database
            $result = $this->db->query($checkQuery);
            $userData = $result->fetch_assoc();
        }
        
        // Return user data
        return $userData;
    }
}