<?php
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Product.php');

    try {
        $writeDB = DB::connectWriteDB();
        $readDB = DB::connectReadDB();

    } catch(PDOException $error) {
        error_log("Connection error - ". $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database Connection Error");
        $response->send();
        exit();
    }
?>