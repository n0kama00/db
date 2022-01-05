<?php

function createDbConnection(){

    try{
        $dbcon = new PDO('mysql:host=localhost;dbname=n0kama00', 'root', '');
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        createTable($dbcon);
    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }

    return $dbcon;
}

function checkUser(PDO $dbcon, $username, $passwd){

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $passwd = filter_var($passwd, FILTER_SANITIZE_STRING);

    try{
        $sql = "SELECT password FROM user WHERE username=?";  
        $prepare = $dbcon->prepare($sql);  
        $prepare->execute(array($username));  

        $rows = $prepare->fetchAll(); 

        //Käydään rivit läpi
        foreach($rows as $row){
            $pw = $row["password"];  
            if( password_verify($passwd, $pw) ){ 
                return true;
            }
        }

        return false;

    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }
}

/**
 * Luo tietokantaan uuden käyttäjän ja hashaa salasanan
 */
function createUser(PDO $dbcon, $fname, $lname, $username, $passwd){

    //Sanitoidaan. Lisätty tuntien jälkeen.
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $passwd = filter_var($passwd, FILTER_SANITIZE_STRING);

    try{
        $hash_pw = password_hash($passwd, PASSWORD_DEFAULT); //salasanan hash
        $sql = "INSERT IGNORE INTO user VALUES (?,?,?,?)"; //komento, arvot parametreina
        $prepare = $dbcon->prepare($sql); //valmistellaan
        $prepare->execute(array($fname, $lname, $username, $hash_pw));  //parametrit tietokantaan
    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }
}

function createTable(PDO $con){
    $sql = "CREATE TABLE IF NOT EXISTS user(
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL,
        username varchar(50) NOT NULL,
        password varchar(150) NOT NULL,
        PRIMARY KEY (username)
        )";

    try{   
        $con->exec($sql);  
    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }

    createUser($con,'Emilia','Kaihua', 'emmu', 'emmu123');
    createUser($con,'Elina','Kaihua', 'ellu', 'ellu123');
    createUser($con,'Oskari','Kaihua', 'osku', 'osku123');

    $sql2 = "CREATE TABLE IF NOT EXISTS user_info(
        username varchar(50) NOT NULL,
        email varchar(50) PRIMARY KEY,
        phone int NOT NULL,
        address varchar (50) NOT NULL,
        zipcode varchar (5) NOT NULL,
        city varchar(20) NOT NULL,
        FOREIGN KEY (username) 
        REFERENCES user(username)
        )";

    createUserInfo($con, 'emmu', 'emmu@email.com', '0401234567', 'Oulu 1', '90100', 'Oulu' );

}

?>