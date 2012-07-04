<?php

class User {

  private $username;

  public function __construct($name) {
    $this->username = $name;
  }

  public function getUsername() {
    return $this->username;
  }
}

?>