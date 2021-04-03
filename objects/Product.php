<?php
    class ProductException extends Exception {}

    class Product {
        private $_id;
        private $_product_title;
        private $_description;
        private $_price;
        private $_stock;

        public function __construct($id, $product_title, $description, $price, $stock) {
            $this->setID($id);
            $this->setProductTitle($product_title);
            $this->setDescription($description);
            $this->setPrice($price);
            $this->setStock($stock);
        }

        public function getID() {
            return $this->_id;
        }

        public function getProductTitle() {
            return $this->_product_title;
        }

        public function getDescription() {
            return $this->_description;
        }

        public function getPrice() {
            return $this->_price;
        }

        public function getStock() {
            return $this->_stock;
        }
    }
?>