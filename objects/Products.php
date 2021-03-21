<?php
    class Products{
        // Database
        private $conn;
        private $table = 'products';

        // Properties
        public $id;
        public $title;
        public $description;
        public $imgUrl;
        public $price;
        public $created_at;
        public $category_id;

        // Constructor 
        public function __construct($db){
            $this->conn = $db;
        }

        // Get all products
        public function read_all(){
            $query = 'SELECT
                     c.name AS category_name,
                     p.id, 
                     p.title,
                     p.description,
                     p.imgUrl,
                     p.price,
                     p.created_at, 
                     p.category_id
                    FROM
                    ' . $this->table . ' p
                    LEFT JOIN
                     categories c ON p.category_id = c.id
                    ORDER BY
                     p.created_at DESC';

            $stm = $this->conn->prepare($query);
            $stm->execute();
            return $stm;
        }
    }
?>