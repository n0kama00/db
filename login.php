<?php

use Firebase\JWT\JWT;

require('headers.php');
require('functions.php');

//Tarkistetaan tuleeko palvelimelle basic login tiedot (authorization: Basic asfkjsafdjsajflkasj)
if( isset($_SERVER['PHP_AUTH_USER']) ){
    //Tarkistetaan käyttäjä tietokannasta
    if(checkUser(createDbConnection(), $_SERVER['PHP_AUTH_USER'],$_SERVER["PHP_AUTH_PW"] )){
       

        //Käyttäjä tunnistettu, joten luodaan vastaukseen JWT token payload
        $payload = array(
            "iat"=>time(),
            "sub"=>$_SERVER['PHP_AUTH_USER']
        );

        //Luodaan signeerattu JWT
        $jwt = JWT::encode( $payload, base64_encode('mysecret'), 'HS256' );

        //Lähetetään JSON muodossa infoteksti ja JWT token clientille
        //{"info":"Kirjauduit sisään", "token":"xxxxxxxxxxx"}
        echo  json_encode( array("info"=>"Kirjauduit sisään", "token"=>$jwt)  );
        header('Content-Type: application/json');
        exit;
    }
}

//Failed login
echo '{"info":"Kirjautuminen epäonnistui"}';
header('Content-Type: application/json');
header('HTTP/1.1 401');
exit;

?>