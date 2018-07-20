<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './CrudOperation.php';

if (!empty(filter_input(INPUT_POST, 'firstname')) && !empty(filter_input(INPUT_POST, 'lastname')) && !empty(filter_input(INPUT_POST, 'gender')) && !empty(filter_input(INPUT_POST, 'phone')) && !empty(filter_input(INPUT_POST, 'email')) && !empty(filter_input(INPUT_POST, 'password'))) {
    $firstname = filter_input(INPUT_POST, 'firstname');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $gender = filter_input(INPUT_POST, 'gender');
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    
    $person = new Person();
    $crud = new CrudOperation();
    
    $id = $crud->getPersonId();
    
    $person->setId($id);
    $person->setFirstname($firstname);
    $person->setLastname($lastname);
    $person->setGender($gender);
    $person->setPhone($phone);
    $person->setEmail($email);
    
    $account = new Account();
    $account->setUserId($id);
    $account->setUsername($email);
    $account->setPassword(sha1($password));
      
    if ($crud->savePersonDetails($person)) {
        if ($crud->createAccount($account)) {
            $_SESSION['login_success'] = 1;
            $_SESSION['fname'] = $firstname;
            $_SESSION['user_id'] = $id;
            echo 'account_created';
        } else {
            echo 'account_error';
        } 
    } else {
        echo 'person_error';
    }
} else {
    echo 'empty_fields';
}