<?php
    require_once('../config/Database.php');
    require_once('../objects/Response.php');

    try {
        $writeDB = DB::connectWriteDB();

    } catch(PDOException $error) {
        error_log("Connection Error: ". $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database connection error");
        $response->send();
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method need to be POST");
        $response->send();
        exit;
    }

    if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Content Type header must be JSON");
        $response->send();
        exit;
    }

    $PostData = file_get_contents('php://input');

    if(!$jsonData = json_decode($PostData)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Request body must be valid JSON");
        $response->send();
        exit;
    }

    if(!isset($jsonData->fullname) || !isset($jsonData->email) || !isset($jsonData->username) || !isset($jsonData->password)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (!isset($jsonData->fullname) ? $response->addMessage("Full name not supplied") : false);
        (!isset($jsonData->email) ? $response->addMessage("Email not supplied") : false);
        (!isset($jsonData->username) ? $response->addMessage("Username not supplied") : false);
        (!isset($jsonData->password) ? $response->addMessage("Password not supplied") : false);
        $response->send();
        exit;
    }

    if(strlen($jsonData->fullname) < 1 || strlen($jsonData->fullname) > 255 || strlen($jsonData->email) < 1 || strlen($jsonData->email) > 255 || strlen($jsonData->username) < 1 || strlen($jsonData->username) > 255 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (strlen($jsonData->fullname) < 1 ? $response->addMessage("Full name cannot be blank") : false);
        (strlen($jsonData->fullname) > 255 ? $response->addMessage("Full name cannot be greater than 255 characters") : false);
        (strlen($jsonData->email) < 1 ? $response->addMessage("Email cannot be blank") : false);
        (strlen($jsonData->email) > 255 ? $response->addMessage("Email cannot be greater than 255 characters") : false);
        (strlen($jsonData->username) < 1 ? $response->addMessage("Username cannot be blank") : false);
        (strlen($jsonData->username) > 255 ? $response->addMessage("Username cannot be greater than 255 characters") : false);
        (strlen($jsonData->password) < 1 ? $response->addMessage("Password cannot be blank") : false);
        (strlen($jsonData->password) > 255 ? $response->addMessage("Password cannot be greater than 255 characters") : false);
        $response->send();
        exit;
    }

    $fullname = trim($jsonData->fullname);
    $email = trim($jsonData->email);
    $username = trim($jsonData->username);
    $password = $jsonData->password;

    try {
        // Checks if a username or email already exists
        $query = $writeDB->prepare('SELECT id FROM users WHERE username = :username AND email = :email');
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();

        if($rowCount !== 0) {
            $response = new Response();
            $response->setHttpStatusCode(409);
            $response->setSuccess(false);
            $response->addMessage("Username or Email already exists");
            $response->send();
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = $writeDB->prepare('INSERT INTO users (fullname, email, username, password) VALUES (:fullname, :email, :username, :password)');
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();

        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue creating a user account - please try again");
            $response->send();
            exit;
        }

        

    } catch(PDOException $error) {

    }

?>