<?php
    // http://localhost/Skola/REST_API/V1/category/read.php?id=

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../objects/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();
    $category->read();

    $category_arr = array(
        'id' => $category->id,
        'name' => $category->name
    );

    // JSON output
    print_r(json_encode($category_arr));
?>