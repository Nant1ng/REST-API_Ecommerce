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

        if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            require_once('../authorization.php');

            if($returned_role !== 'admin') {
                $response = new Response();
                $response->setHttpStatusCode(405);
                $response->setSuccess(false);
                $response->addMessage("Request method not allowed");
                $response->send();
                exit;

            } else {
                try {
                    $query = $writeDB->prepare('DELETE FROM product WHERE id = :productid');
                    $query->bindParam(':productid', $productID, PDO::PARAM_INT);
                    $query->execute();

                    $rowCount = $query->rowCount();

                    if($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(404);
                        $response->setSuccess(false);
                        $response->addMessage("Product not found");
                        $response->send();
                        exit;
                    }

                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->addMessage("Product deleted");
                    $response->send();
                    exit;

                } catch (PDOException $error) {
                    error_log("Database query error -" . $error, 0);
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to update product, check your data for errors");
                    $response->send();
                    exit;
                }
            }

        }

    } else {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Endpoint not found");
        $response->send();
        exit;
    }
?>