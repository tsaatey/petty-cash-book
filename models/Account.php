<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 *
 * @author ARTLIB
 */
class Account {
    
    private $userId;
    private $username;
    private $password;
    
    public function __construct() {
        
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }


}
