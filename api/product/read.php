<?php
    //http://localhost/Skola/REST_API/api/product/read.php?id=

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../objects/Products.php';

    $database = new Database();
    $db = $database->connect();

    $product = new Products($db);

    $product->id = isset($_GET['id']) ? $_GET['id'] : die();
    $product->read();

    $product_arr = array(
        'id' => $product->id,
        'title' => $product->title,
        'description' => $product->description,
        'imgUrl' => $product->imgUrl,
        'price' => $product->price,
        'created_at' => $product->created_at,
        'category_id' => $product->category_id,
        'category_name' => $product->category_name
    );

    // JSON output
    print_r(json_encode($product_arr));
?>