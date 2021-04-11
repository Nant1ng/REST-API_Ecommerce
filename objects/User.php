<?php 
    class UserException extends Exception {}

    class User {
        private $_id;
        private $_fullname;
        private $_email;
        private $_username;
        private $_useractive;
        private $_loginattempts;
        private $_role;

        public function __construct($id, $fullname, $email, $username, $useractive, $loginattempts, $role) {
            $this->setID($id);
            $this->setFullName($fullname);
            $this->setEmail($email);
            $this->setUsername($username);
            $this->setUserActive($useractive);
            $this->setLoginattempts($loginattempts);
            $this->setRole($role);
        }

        public function getID() {
            return $this->_id;
        }

        public function getFullName() {
            return $this->_fullname;
        }

        public function getEmail() {
            return $this->_email;
        }

        public function getUsername() {
            return $this->_username;
        }

        public function getUseractive() {
            return $this->_useractive;
        }

        public function getLoginattempts() {
            return $this->_loginattempts;
        }

        public function getRole() {
            return $this->_role;
        }

        public function setID($id) {
        if (($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
            throw new UserException("User ID Error");
        }
        $this->_id = $id;
        }

        public function setFullName($fullname) {
        if (strlen($fullname) < 0 || strlen($fullname) > 255) {
            throw new UserException("User full name error");
        }
        $this->_fullname = $fullname;
        }

        public function setEmail($email) {
        if (strlen($email) < 0 || strlen($email) > 255) {
            throw new UserException("Email error");
        }
        $this->_email = $email;
        }

        public function setUsername($username) {
        if (strlen($username) < 0 || strlen($username) > 255) {
            throw new UserException("Username error");
        }
        $this->_username = $username;
        }

        public function setUseractive($useractive) {
        if (strtoupper($useractive) !== 'Y' && strtoupper($useractive) !== 'N') {
            throw new UserException("Useractive must have value Y or N");
        }
        $this->_useractive = $useractive;
        }

        public function setLoginattempts($loginattempts) {
        if (intval($loginattempts) >= 0 && intval($loginattempts) >= 3) {
            throw new UserException("loginattempts must have value 0-3");
        }
        $this->_loginattempts = $loginattempts;
        }

        public function setRole($role) {
        if (strlen($role) < 0 || strlen($role) > 255) {
            throw new UserException("role error");
        }
        $this->_role = $role;
        }

        public function returnUserAsArray() {
        $user = array();
        $user['id'] = $this->getID();
        $user['fullname'] = $this->getFullName();
        $user['email'] = $this->getEmail();
        $user['username'] = $this->getUsername();
        $user['useractive'] = $this->getUseractive();
        $user['loginattempts'] = $this->getLoginattempts();
        $user['role'] = $this->getRole();
        
        return $user;
        }
    }
?>