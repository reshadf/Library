<?php

class Admin extends User  {

    private $adminId;
    
    public function __construct($username, $id) {
        $this->username = $username;
        $this->adminId = $id;
    }

    public function getUsername() {
    return $this->username;
    }
    
    public function getUserStatus() {
        return 'admin';
    }

}

?>