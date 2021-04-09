<?php 
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Cart.php');

    try {
        $writeDB = DB::connectWriteDB();

    } catch (PDOException $error) {
        error_log("Connection error - " . $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database connection error");
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
    
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once('../authorization.php');
            
            try {

                $newCart = new Cart(null, $productID, $returned_userid, null, 1);

                $cartUserID = $newCart->getUserID();
                $cartProductID = $newCart->getProductID();

                $query = $writeDB->prepare('INSERT INTO cart(productid, userid) VALUES (:productid, :userid)');
                $query->bindParam(':productid', $cartProductID, PDO::PARAM_INT);
                $query->bindParam(':userid', $cartUserID, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to add product to cart");
                    $response->send();
                    exit;
                }

                $lastCartID = $writeDB->LastInsertId();

                $query = $writeDB->prepare('SELECT C.id, C.productid, C.userid, P.product_title, P.price FROM cart C INNER JOIN product P ON C.productid = P.id WHERE C.id = :cartid AND C.userid = :userid');
                $query->bindParam(':cartid', $lastCartID, PDO::PARAM_INT);
                $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to get product after added to cart");
                    $response->send();
                    exit;
                }

                $cartArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cart = new Cart($row['id'], $row['productid'], $row['userid'], $row['product_title'], $row['price']);
                    $cartArray[] = $cart->returnAddProductToCartAsArray();
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['cart'] = $cartArray;

                $response = new Response();
                $response->setHttpStatusCode(201);
                $response->setSuccess(true);
                $response->addMessage("Product added to cart");
                $response->setData($returnData);
                $response->send();
                exit;

            } catch (CartException $error) {
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
                $response->addMessage("Failed to insert product into cart database - check submitted data for errors");
                $response->send();
                exit;
            }
        } 
    }
?>