<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './CrudOperation.php';

$crud = new CrudOperation();

$data = $crud->getDuplicateAssociativeArray('June');

print_r($data);

//echo date('Y-F-d');