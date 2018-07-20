<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './CrudOperation.php';

if (!empty(filter_input(INPUT_POST, 'username')) && !empty(filter_input(INPUT_POST, 'password'))){
    $username = filter_input(INPUT_POST, 'username');
    $password = sha1(filter_input(INPUT_POST, 'password'));
    
    $account = new Account();
    $account->setUsername($username);
    $account->setPassword($password);
    
    $crud = new CrudOperation();
    if ($crud->login($account)) {
        $_SESSION['login_success'] = 1;
        $_SESSION['empty_fields'] = 0;
        $_SESSION['wrong_credentials'] = 0;
        header("Location: ../index.php");
    } else {
        $_SESSION['wrong_credentials'] = 1;
        header("Location: ../index.php");
    }
} else {
    $_SESSION['empty_fields'] = 1;
    header("Location: ../index.php");
}