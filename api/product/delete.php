<?php
    //http://localhost/Skola/REST_API/api/product/delete.php
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  
    include_once '../../config/Database.php';
    include_once '../../objects/Products.php';
  
    $database = new Database();
    $db = $database->connect();
  
    $product = new Products($db);
  
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
  
    $product->id = $data->id;
  
    // Delete post
    if($product->delete()) {
      echo json_encode(
        array('message' => 'Product Deleted')
      );
    } else {
      echo json_encode(
        array('message' => 'Product Not Deleted')
      );
    }
?>