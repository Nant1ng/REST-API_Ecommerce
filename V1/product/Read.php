<?php
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Product.php');

    try {
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

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = $readDB->prepare('SELECT id, product_title, description, price, stock, img_url FROM product WHERE id = :productid');
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

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product($row['id'], $row['product_title'], $row['description'], $row['price'], $row['stock'], $row['img_url']);
                $productArray[] = $product->returnProductAsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['products'] = $productArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->send();
            exit;

        } catch (ProductException $error) {
            $response = new Response();
            $response->setHttpStatusCode(500); 
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;

        } catch (PDOException $error) {
            error_log("Database query error - " . $error, 0); 
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get product");
            $response->send();
            exit();
        }
    }
?>