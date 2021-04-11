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

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    $response->addMessage("Content type header is not set to JSON");
                    $response->send();
                    exit;
                }

                $rawPostData = file_get_contents('php://input');

                if(!$jsonData = json_decode($rawPostData)) {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Request body is not valid JSON");
                    $response->send();
                    exit;
                }

                if (!isset($jsonData->product_title) || !isset($jsonData->description) || !isset($jsonData->price) || !isset($jsonData->stock) || !isset($jsonData->img_url)) {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    (!isset($jsonData->product_title) ? $response->addMessage("Product Title filed is a mandatory and must be provided") : false);
                    (!isset($jsonData->description) ? $response->addMessage("Description filed is a mandatory and must be provided") : false);
                    (!isset($jsonData->price) ? $response->addMessage("Price filed is a mandatory and must be provided") : false);
                    (!isset($jsonData->stock) ? $response->addMessage("Stock filed is a mandatory and must be provided") : false);
                    (!isset($jsonData->img_url) ? $response->addMessage("Image Url filed is a mandatory and must be provided") : false);
                    $response->send();
                    exit;
                }

                $NewProduct = new Product(null, $jsonData->product_title, $jsonData->description, $jsonData->price, $jsonData->stock, $jsonData->img_url);

                $product_title = $NewProduct->getProductTitle();
                $description = $NewProduct->getDescription();
                $price = $NewProduct->getPrice();
                $stock = $NewProduct->getStock();
                $img_url = $NewProduct->getImageUrl();

                $query = $writeDB->prepare('INSERT INTO product (product_title, description, price, stock, img_url) VALUES (:product_title, :description, :price, :stock, :img_url)');
                $query->bindParam(':product_title', $product_title, PDO::PARAM_STR);
                $query->bindParam(':description', $description, PDO::PARAM_STR);
                $query->bindParam(':price', $price, PDO::PARAM_INT);
                $query->bindParam(':stock', $stock, PDO::PARAM_STR);
                $query->bindParam(':img_url', $img_url, PDO::PARAM_STR);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to create product");
                    $response->send();
                    exit;
                }

                $lastProductID = $writeDB->LastInsertId();

                $query = $writeDB->prepare('SELECT id, product_title, description, price, stock, img_url FROM product WHERE id = :productid');
                $query->bindParam(':productid', $lastProductID, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to retrive product after creation");
                    $response->send();
                    exit;
                }

                $ProductArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $product = new Product($row['id'], $row['product_title'], $row['description'], $row['price'], $row['stock'], $row['img_url']);
                    $ProductArray[] = $product->returnProductAsArray();
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['product'] = $ProductArray;

                $response = new Response();
                $response->setHttpStatusCode(201); 
                $response->setSuccess(true);
                $response->addMessage("Product Created");
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
                error_log("Database query error" . $error, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to insert product into database - check submitted data for errors");
                $response->send();
                exit;
            }
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit;
    }
?>: