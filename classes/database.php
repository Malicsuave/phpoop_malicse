<?php

class database{

    function opencon(){
        return new PDO('mysql:host=localhost; dbname=loginmethod', 'root', '');
    }


    function check($username, $password){
        $con = $this->opencon();
        $query = "SELECT * from users WHERE username='".$username."'&&password='".$password."'                ";
        return $con->query($query)->fetch();
    }

function signup($username, $password, $firstname, $lastname, $birthday, $sex){
        $con = $this->opencon();

// Check if the username is already exists

    $query=$con->prepare("SELECT username FROM users WHERE username =?");
    $query->execute([$username]);
    $existingUser= $query->fetch();
    

// If the username already exists, return false
    if($existingUser){
    return false;
}
// Insert the new username and password into the database
    return $con->prepare("INSERT INTO users(username, password,firstname,lastname,birthday,sex)
VALUES (?, ?, ?, ?, ?, ?)")
           ->execute([$username,$password, $firstname, $lastname, $birthday, $sex]);
           
}

function signupUser($username, $password, $firstname, $lastname, $birthday, $sex){
    $con = $this->opencon();


    // Check if the username is already exists

    $query=$con->prepare("SELECT username FROM users WHERE username =?");
    $query->execute([$username]);
    $existingUser= $query->fetch();
    

// If the username already exists, return false
    if($existingUser){
    return false;
}
// Insert the new username and password into the database
 $con->prepare("INSERT INTO users(username,password,firstname,lastname,birthday,sex)
VALUES (?, ?, ?, ?, ?, ?)")
           ->execute([$username,$password, $firstname, $lastname, $birthday, $sex]);
           return $con->lastInsertId();
}

function insertAddress($User_Id, $street, $barangay, $city, $province){
    $con = $this->opencon();
     return $con->prepare("INSERT INTO user_address (User_Id,street, barangay, city,province) VALUES(?, ?, ?, ?, ?)") ->execute([$User_Id, $street, $barangay, $city, $province]);
    
 

}


}