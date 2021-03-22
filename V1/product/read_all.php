<?php 
    //http://localhost/Skola/REST_API/V1/product/read_all.php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../objects/Products.php';

    $database = new Database();
    $db = $database->connect();

    $product = new Products($db);
    $result = $product->read_all();

    $num = $result->rowCount();

    // Check if there is any products
    if($num > 0){
        $products_arr= array();
        $products_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $product_item = array(
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'imgUrl' => $imgUrl,
                'price' => $price,
                'created_at' => $created_at,
                'category_id' => $category_id,
                'category_name' => $category_name
            );

            array_push($products_arr['data'], $product_item);
        }

        // JSON output
        echo json_encode($products_arr);
    } else {
            echo json_encode(
                array('message' => 'No Products Found')
            );
    }
?>