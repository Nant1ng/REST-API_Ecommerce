<?php
    // http://localhost/Skola/REST_API/V1/category/update.php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../objects/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $category->id = $data->id;

    $category->name = $data->name;

    // Update category
    if($category->update()) {
        echo json_encode(
            array('message' => 'Category Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Category Not Updated')
        );
    }
?>