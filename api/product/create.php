<?php
    //http://localhost/Skola/REST_API/api/product/create.php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../objects/Products.php';

    $database = new Database();
    $db = $database->connect();

    $product = new Products($db);
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $product->title = $data->title;
    $product->description = $data->description;
    $product->imgUrl = $data->imgUrl;
    $product->price = $data->price;
    $product->category_id = $data->category_id;

    // Create product
    if($product->create()) {
        echo json_encode(
            array('message' => 'Product Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Product Not Created')
        );
    }
?>