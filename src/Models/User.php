<?php
namespace App\Models;

/** Class User **/
class User {

    private $id;
    private $username;
    private $email;
    private $password;

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    
    public function setId() {
        $this->id = uniqid();
    }

    public function setUsername(String $username) {
        $this->username = $username;
    }

    public function setEmail(String $email) {
        $this->email = $email;
    }

    public function setPassword(String $password) {
        $this->password = $password;
    }
}