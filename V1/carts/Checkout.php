<?php 
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/Cart.php');

    try {
        $readDB = DB::connectReadDB();
        $writeDB = DB::connectReadDB();

    } catch (PDOException $error) {
        error_log("Connection error - " . $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database connection error");
        $response->send();
        exit();
    }

    

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once('../authorization.php');

            try {
                $query = $readDB->prepare('SELECT C.id, C.productid, C.userid, P.product_title, P.price, SUM(P.price) AS totalPrice FROM cart C INNER JOIN product P ON C.productid = P.id WHERE C.userid = :userid GROUP BY C.id ASC');
                $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("Cart not found");
                    $response->send();
                    exit;
                }

                $cartArray = array();
                $totalPrice = 0;

                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cart = new Cart($row['id'], $row['productid'], $row['userid'], $row['product_title'], $row['price']);
                    $cartArray[] = $cart->returnCartAsArray();
                    $totalPrice += $row['totalPrice'];
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['total_price'] = $totalPrice . "kr";
                $returnData['cart'] = $cartArray;

                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->addMessage("Cart checked out");
                $response->setData($returnData);
                $response->send();
                
                if($returnData = true) {
                    try {
                        $query = $writeDB->prepare('DELETE FROM cart WHERE userid = :userid');
                        $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
                        $query->execute();

                    } catch (PDOException $error) {
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage("Failed to checkout cart");
                        $response->send();
                        exit;
                    }
                }
                
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
                $response->addMessage("Failed to checkout cart");
                $response->send();
                exit;
            }
        }
     