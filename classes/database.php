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
    function view(){
        $con = $this->opencon();
        return $con->query("SELECT
        users.User_Id,
        users.firstname,
        users.lastname,
        users.birthday,
        users.sex,
        users.username,
        users.password,
        CONCAT(
            user_address.street,' ',user_address.barangay,' ',user_address.city,' ',user_address.province
        ) AS address
    FROM
        users
    JOIN user_address ON users.User_Id = user_address.User_Id")->fetchAll();

    }

    
    function delete($id){
        try{
            $con = $this->opencon();
            $con->beginTransaction();

            // Delete user address

            $query = $con->prepare("DELETE FROM user_address
            WHERE User_Id =?");
            $query->execute([$id]);
        
            // Delete user

          
            $query2 = $con->prepare("DELETE FROM users
            WHERE User_Id =?");
            $query2->execute([$id]);

            $con->commit();
            return true; //Deletion successful
} catch (PDOException $e) {
    $con->rollBack();
    return false;
} 

}
}