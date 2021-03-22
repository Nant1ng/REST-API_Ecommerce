<?php
    class Category {
        // Database
        private $conn;
        private $table = 'categories';

        // Properties
        public $id;
        public $name;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get all categories
        public function read_all(){
            $query = 'SELECT id, name FROM ' . $this->table . '';

            $stm = $this->conn->prepare($query);
            $stm->execute();
            return $stm;
        }

        // Get a category
        public function read(){
            $query = 'SELECT id, name FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';

            $stm = $this->conn->prepare($query);
            $stm->bindParam(1, $this->id);
            $stm->execute();

            $row = $stm->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
        }

        // Create Category
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET name = :name';

            $stm = $this->conn->prepare($query);

            // Cleaning data
            $this->name = htmlspecialchars(strip_tags($this->name));

            $stm-> bindParam(':name', $this->name);

            if($stm->execute()) {
                return true;
            }
            printf("Error: $s.\n", $stm->error);
            return false;
        }

        // Update category
        public function update() {
            $query = 'UPDATE ' . $this->table . ' SET name = :name WHERE id = :id';

            $stm = $this->conn->prepare($query);

            // Cleaning data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stm-> bindParam(':name', $this->name);
            $stm-> bindParam(':id', $this->id);

            if($stm->execute()) {
                return true;
            }

            printf("Error: $s.\n", $stm->error);
            return false;
        }

        // Delete category
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stm = $this->conn->prepare($query);

        // Cleaning data
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stm-> bindParam(':id', $this->id);

            if($stm->execute()) {
                return true;
            }

            printf("Error: $s.\n", $stm->error);
            return false;
        }
    }
?>