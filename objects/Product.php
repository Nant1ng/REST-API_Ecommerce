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

        public function setID($id) {                           // 9223372036854775807 = Största talet som får finnas i en SQL Databasen
            if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
                throw new ProductException("Product ID Error");
            }
            $this->_id = $id;
        }

        public function setProductTitle($product_title) {
            if(strlen($product_title) < 0 || strlen($$product_title) > 255) {
                throw new ProductException("Product Title Error");
            }
            $this->_product_title = $product_title;
        }

        public function setDescription($description) {
            if(strlen($description) < 0 || strlen($description) > 16777215) {
                throw new ProductException("Product Description Error");
            }
            $this->_description = $description;
        }

        public function setPrice($price) {
            if(!is_numeric($price) || $price <= 0) {
                throw new ProductException("Product Price Error");
            }
            $this->_price = $price;
        }

        public function setStock($stock) {
            if(strtoupper($stock) !== 'Y' && strtoupper($stock) !== 'N') {
                throw new ProductException("Product Stock Error");
            }
            $this->_stock = $stock;
        }

        public function returnProductAsArray() {
            $product = array();
            $product['id'] = $this->getID();
            $product['product_title'] = $this->getProductTitle();
            $product['description'] = $this->getDescription();
            $product['price'] = $this->getPrice();
            $product['stock'] = $this->getStock();
            return $product;
        }
    }
?>