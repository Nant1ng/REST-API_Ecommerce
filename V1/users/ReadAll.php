<?php
    require_once('../../config/Database.php');
    require_once('../../objects/Response.php');
    require_once('../../objects/User.php');
    
    try {
        $readDB = DB::connectreadDB();
        $writeDB = DB::connectWriteDB();

    } catch (PDOException $error) {
        error_log("Connection Error:" . $error, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database connection error");
        $response->send();
        exit;
    }

    if(empty($_GET)) {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
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
                    $query = $readDB->prepare('SELECT id, fullname, email, username, useractive, loginattempts, role FROM users');
                    $query->execute();

                    $rowCount = $query->rowCount();

                    $userArray = array();

                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $user = new User($row['id'], $row['fullname'], $row['email'], $row['username'], $row['useractive'], $row['loginattempts'], $row['role'],);
                        $userArray[] = $user->returnUserAsArray();
                    }

                    $returnData = array();
                    $returnData['rows_returned'] = $rowCount;
                    $returnData['users'] = $userArray;
    
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->setData($returnData);
                    $response->send();
                    exit;

                } catch(UserException $error) {
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
                    $response->addMessage("Failed to get users");
                    $response->send();
                    exit;
                }
            }
        }
    }
?>