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
        public $category_name;

        // Constructor 
        public function __construct($db){
            $this->conn = $db;
        }

        // Get all products
        public function read_all(){
            $query = 'SELECT c.name AS category_name, p.id, p.title, p.description, p.imgUrl, p.price, p.created_at, p.category_id FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC';

            $stm = $this->conn->prepare($query);
            $stm->execute();
            return $stm;
        }

        // Get a product
        public function read(){
            $query = 'SELECT c.name AS category_name, p.id, p.title, p.description, p.imgUrl, p.price, p.created_at, p.category_id FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? LIMIT 0,1';

           $stm = $this->conn->prepare($query);
           $stm->bindParam(1, $this->id);
           $stm->execute();

           $row = $stm->fetch(PDO::FETCH_ASSOC);
           $this->title = $row['title'];
           $this->description = $row['description'];
           $this->imgUrl = $row['imgUrl'];
           $this->price = $row['price'];
           $this->created_at = $row['created_at'];
           $this->category_id = $row['category_id'];
           $this->category_name = $row['category_name'];
        }

        // Create Product
        public function create(){
            $query = 'INSERT INTO ' . $this->table . 'SET title = :title, description = :description, imgUrl = :imgUrl, price = :price, category_id = :category_id';
            
            $stm = $this->conn->prepare($query);

            // Cleaning data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->imgUrl = htmlspecialchars(strip_tags($this->imgUrl));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            $stm->bindParam(':title', $this->title);
            $stm->bindParam(':description', $this->description);
            $stm->bindParam(':imgUrl', $this->imgUrl);
            $stm->bindParam(':price', $this->price);
            $stm->bindParam(':category_id', $this->category_id);

            if($stm->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stm->error);
            return false;
        }

        // Update Product
        public function update() {
            $query = 'UPDATE ' . $this->table . ' SET title = :title, description = :description, imgUrl = :imgUrl, price = :price, category_id = :category_id WHERE id = :id';

            $stm = $this->conn->prepare($query);

            // Cleaning data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->imgUrl = htmlspecialchars(strip_tags($this->imgUrl));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stm->bindParam(':title', $this->title);
            $stm->bindParam(':description', $this->description);
            $stm->bindParam(':imgUrl', $this->imgUrl);
            $stm->bindParam(':price', $this->price);
            $stm->bindParam(':category_id', $this->category_id);
            $stm->bindParam(':id', $this->id);

            if($stm->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stm->error);
            return false;
        }

        // Delete Product
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stm = $this->conn->prepare($query);
  
            // Cleaning data
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stm->bindParam(':id', $this->id);

            if($stm->execute()) {
              return true;
            }

            printf("Error: %s.\n", $stm->error);
            return false;
      }

    }
?>