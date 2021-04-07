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
    
         if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
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
                    if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("Content Type header not set to JSON");
                        $response->send();
                        exit;
                    }

                    $rawPatchData = file_get_contents('php://input');

                    if(!$jsonData = json_decode($rawPatchData)) {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("Request body is not valid JSON");
                        $response->send();
                        exit;
                    }

                    $product_title_updated = false;
                    $description_updated = false;
                    $price_updated = false;
                    $stock_updated = false;
                    $img_url_updated = false;

                    $queryFields = "";

                    if(isset($jsonData->product_title)) {
                        $product_title_updated = true;
                        $queryFields .= "product_title = :product_title, ";
                    }

                    if(isset($jsonData->description)) {
                        $description_updated = true;
                        $queryFields .= "description = :description, ";
                    }

                    if(isset($jsonData->price)) {
                        $price_updated = true;
                        $queryFields .= "price = :price, ";
                    }

                    if(isset($jsonData->stock)) {
                        $stock_updated = true;
                        $queryFields .= "stock = :stock, ";
                    }

                    if(isset($jsonData->img_url)) {
                        $img_url_updated = true;
                        $queryFields .= "img_url = :img_url, ";
                    }

                    //rtrim tar bort sista "," i query stringen
                    $queryFields = rtrim($queryFields, ", ");

                    if($product_title_updated === false && $description_updated === false && $price_updated === false && $stock_updated === false && $img_url_updated === false) {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("No prducts fields are provided");
                        $response->send();
                        exit;
                    }

                    $query = $writeDB->prepare('SELECT id, product_title, description, price, stock, img_url FROM product WHERE id = :productid');
                    $query->bindParam(':productid', $productID, PDO::PARAM_INT);
                    $query->execute();

                    $rowCount = $query->rowCount();

                    if($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(404);
                        $response->setSuccess(false);
                        $response->addMessage("No prduct found to update");
                        $response->send();
                        exit;
                    }

                    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $product = new Product($row['id'], $row['product_title'], $row['description'], $row['price'], $row['stock'], $row['img_url']);
                    }

                    $queryString = "UPDATE product SET " . $queryFields . " WHERE id = :productid";
                    $query = $writeDB->prepare($queryString);

                    if($product_title_updated === true) {
                        $product->setProductTitle($jsonData->product_title);
                        $up_product_title = $product->getProductTitle();
                        $query->bindParam(":product_title", $up_product_title, PDO::PARAM_STR);
                    }

                    if($description_updated === true) {
                        $product->setDescription($jsonData->description);
                        $up_description = $product->getDescription();
                        $query->bindParam(":description", $up_description, PDO::PARAM_STR);
                    }

                    if($price_updated === true) {
                        $product->setPrice($jsonData->price);
                        $up_price = $product->getPrice();
                        $query->bindParam(":price", $up_price, PDO::PARAM_INT);
                    }

                    if($stock_updated === true) {
                        $product->setStock($jsonData->stock);
                        $up_stock = $product->getStock();
                        $query->bindParam(":stock", $up_stock, PDO::PARAM_STR);
                    }

                    if($img_url_updated === true) {
                        $product->setImageUrl($jsonData->img_url);
                        $up_img_url = $product->getImageUrl();
                        $query->bindParam(":img_url", $up_img_url, PDO::PARAM_STR);
                    }

                    $query->bindParam(':productid', $productID, PDO::PARAM_INT);
                    $query->execute();

                    $rowCount = $query->rowCount();

                    if($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("Product not updated");
                        $response->send();
                        exit;
                    }

                    $query = $writeDB->prepare('SELECT id, product_title, description, price, stock, img_url FROM product WHERE id = :productid');
                    $query->bindParam(':productid', $productID, PDO::PARAM_INT);
                    $query->execute();

                    $rowCount = $query->rowCount();

                    if($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(404);
                        $response->setSuccess(false);
                        $response->addMessage("No product found after update");
                        $response->send();
                        exit;
                    }

                    $productArray = array();

                    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $product = new Product($row['id'], $row['product_title'], $row['description'], $row['price'], $row['stock'], $row['img_url']);
                        $productArray[] = $product->returnProductAsArray();
                    }

                    $returnData = array();
                    $returnData['rows_returned'] = $rowCount;
                    $returnData['product'] = $productArray;

                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->addMessage("Product updated");
                    $response->setData($returnData);
                    $response->send();
                    exit;

                } catch (ProductException $error) {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage($error->getMessage());
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