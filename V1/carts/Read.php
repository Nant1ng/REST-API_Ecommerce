<?php 
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Cart.php');

    try {
        $readDB = DB::connectReadDB();
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

    if(empty($_GET)) {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once('../authorization.php');

            try {
                $query = $readDB->prepare('SELECT C.id, C.productid, C.userid, P.product_title, P.price FROM cart C INNER JOIN product P ON C.productid = P.id WHERE C.userid = :userid');
                $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Cart is empty");
                    $response->send();
                    exit;
                }

                $cartArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cart = new Cart($row['id'], $row['productid'], $row['userid'], $row['product_title'], $row['price']);
                    $cartArray[] = $cart->returnCartAsArray();
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['cart'] = $cartArray;

                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->addMessage("Your cart");
                $response->setData($returnData);
                $response->send();
                exit;

            } catch (CartException $error) {
                $response = new Response();
                $response->setHttpStatusCode(500); 
                $response->setSuccess(false);
                $response->addMessage($error->getMessage());
                $response->send();
                exit;

            } catch (PDOException $error) {
                error_log("Database query error - " . $error, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to get cart");
                $response->send();
                exit();
            } 
        }
    }
?>