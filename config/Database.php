<?php
/* 
    $dsn = "mysql:host=localhost;dbname=rest-api";
    $user = "root";
    $password = "";

    $pdo = new PDO($dsn, $user, $password);
*/
class Database {
    // Database params
    private $host = 'localhost';
    private $db_name = 'rest-api';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Connect database
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $error) {
        echo 'Connection Error: ' . $error->getMessage();
      }

      return $this->conn;
    }
  }
?>