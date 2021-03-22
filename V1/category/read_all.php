<?php 
    //http://localhost/Skola/REST_API/V1/category/read_all.php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../objects/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);
    $result = $category->read_all();
    
    $num = $result->rowCount();

    // Check if there is any categories
    if($num > 0) {
        $category_arr = array();
        $category_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'name' => $name
            );

            array_push($category_arr['data'], $category_item);
        }

        // JSON output
        echo json_encode($category_arr);
    } else {
            echo json_encode(
                array('message' => 'No Categories Found')
            );
    }
?>