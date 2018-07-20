<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './CrudOperation.php';

if (!empty(filter_input(INPUT_POST, 'username')) && !empty(filter_input(INPUT_POST, 'password'))) {
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');

    $account = new Account();
    $crud = new CrudOperation();

    $account->setUsername($username);
    $account->setPassword(sha1($password));

    if ($crud->isUserExists($username)) {
        if ($crud->changePassword($account)) {
            $_SESSION['login_success'] = 1;
            $_SESSION['empty_fields'] = 0;
            $_SESSION['wrong_credentials'] = 0;
            echo 'password_reset_success';
        } else {
            echo 'password_reset_error';
        }
    } else {
        echo 'user_does_not_exist';
    }
} else {
    echo 'empty_fields';
}