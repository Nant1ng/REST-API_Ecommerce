<?php
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Product.php');

    try {
        $writeDB = DB::connectWriteDB();

    } catch(PDOException $error) {
        error_log("Connection error - ". $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database Connection Error");
        $response->send();
        exit();
    }

    if(array_key_exists("productid", $_GET)) {
        $productID = $_GET['productid'];

        if($productID == "" || !is_numeric($productID)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Product ID cannot be blank or must be numeric");
            $response->send();
            exit;
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        require_once('../authorization.php');

        try {
            $query = $writeDB->prepare('DELETE FROM cart WHERE productid = :productid AND userid = :userid');
            $query->bindParam(':productid', $productID, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Product not found in cart");
                $response->send();
                exit;
            }

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Product deleted from cart");
            $response->send();
            exit;

        } catch (PDOException $error) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to delete product");
            $response->send();
            exit;

        }
    }