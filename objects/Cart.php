<?php
    class CartException extends Exception {}

    class Cart 
    {
        private $_id;
        private $_productid;
        private $_userid;
        private $_product_title;
        private $_price;

        public function __construct($id, $productid, $userid, $product_title, $price) {
            $this->setID($id);
            $this->setProductID($productid);
            $this->setUserID($userid);
            $this->setProductTitle($product_title);
            $this->setPrice($price);
        }

        public function getID() {
            return $this->_id;
        }

        public function getProductID() {
            return $this->_productid;
        }

        public function getUserID() {
            return $this->_userid;
        }

        public function getProductTitle() {
            return $this->_product_title;
        }

        public function getPrice() {
            return $this->_price;
        }

        public function setID($id) {
            if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
                throw new CartException("ID Error");
            }
            $this->_id = $id;
        }

        public function setProductID($productid) {
            if (($productid !== null) && (!is_numeric($productid) || $productid <= 0 || $productid > 9223372036854775807 || $this->_productid !== null)) {
                throw new CartException("Product ID Error");
            }
            $this->_productid = $productid;
        }

        public function setUserID($userid) {
            if (($userid !== null) && (!is_numeric($userid) || $userid <= 0 || $userid > 9223372036854775807 || $this->_userid!== null)) {
                throw new CartException("User ID Error");
            }
            $this->_userid = $userid;
        }

        public function setProductTitle($product_title) {
            if(strlen($product_title) < 0 || strlen($product_title) > 255) {
                throw new CartException("Product Title Error");
            }
            $this->_product_title = $product_title;
        }

        public function setPrice($price) {
            if(!is_numeric($price) || $price <= 0) {
                throw new CartException("Product Price Error");
            }

            $this->_price = $price;
        }

        public function returnCartAsArray() {
            $cart = array();
            $cart['id'] = $this->getID();
            $cart['productid'] = $this->getProductID();
            $cart['userid'] = $this->getUserID();
            $cart['product_title'] = $this->getProductTitle();
            $cart['price'] = $this->getPrice();
            return $cart;
        }
    }
?>