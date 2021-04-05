<?php
    if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION'] < 1)) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
        (strlen($_SERVER['HTTP_AUTHORIZATION']) < 1 ? $response->addMessage("Access token cannot be blank") : false);
        $response->send();
        exit;
    }

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

    try {
        $query = $writeDB->prepare('SELECT userid, accesstokenexpiry, useractive, loginattempts FROM sessions, users WHERE sessions.userid = users.id AND accesstoken = :accesstoken');
        $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
        $query->execute();
    
        $rowCount = $query->rowCount();
    
        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("Invalid accesstoken");
            $response->send();
            exit;
        }
    
        $row = $query->fetch(PDO::FETCH_ASSOC);
    
        $returned_userid = $row['userid'];
        $returned_accesstokenexpiry = $row['accesstokenexpiry'];
        $returned_useractive = $row['useractive'];
        $returned_loginattempts = $row['loginattempts'];
    
        if($returned_useractive !== 'Y') {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("User account not active");
            $response->send();
            exit;
        }
    
        if($returned_loginattempts >= 3) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("User account is currently locked out");
            $response->send();
            exit;
        }

        //strtotime för rätt format, om den är äldre än nutid så blir det error.
        if(strtotime($returned_accesstokenexpiry) < time()) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("access token expired");
            $response->send();
            exit;

        }

    } catch (PDOException $error) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("There was an issue authenticating - please try again");
        $response->send();
        exit;
    }
?>