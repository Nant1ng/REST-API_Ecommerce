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

    if(array_key_exists("page", $_GET)) {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $page = $_GET['page'];

            if($page == "" || !is_numeric($page)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Page number cannot be blank or must be numeric");
                $response->send();
                exit;
            }

            // 5 bara för vissa att det funkar.
            $limitPerPage = 5;

            try{
                $query = $readDB->prepare('SELECT COUNT(id) AS totalProducts FROM product');
                $query->execute();

                $row = $query->fetch(PDO::FETCH_ASSOC);

                $productCount = intval($row['totalProducts']);

                $numOfPages = ceil($productCount/$limitPerPage);

                if($numOfPages == 0) {
                    $numOfPages = 1;
                }

                if($page > $numOfPages || $page == 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("Page not found");
                    $response->send();
                    exit;
                }

                $offset = ($page == 1 ? 0 : ($limitPerPage*($page-1)));

                $query = $readDB->prepare('SELECT id, product_title, description, price, stock, img_url FROM product LIMIT :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                $productArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $product = new Product($row['id'], $row['product_title'], $row['description'], $row['price'], $row['stock'], $row['img_url']);
                    $productArray[] = $product->returnProductAsArray();
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['total_rows'] = $productCount;
                $returnData['total_pages'] = $numOfPages;
                ($page < $numOfPages ? $returnData['has_next_page'] = true : $returnData['has_next_page'] = false);
                ($page > 1 ? $returnData['has_previous_page'] = true : $returnData['has_previous_page'] = false);
                $returnData['product'] = $productArray;

                $response= new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->setData($returnData);
                $response->send();
                exit;

            } catch (ProductException $error) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($error-getMessage());
                $response->send();
                exit; 

            }catch (PDOException $error) {
                error_log("Database query error - " . $error, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to get products");
                $response->send();
                exit; 
            }
        }
    }
?>