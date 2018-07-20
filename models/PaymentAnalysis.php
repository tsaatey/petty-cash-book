<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaymentAnalysis
 *
 * @author ARTLIB
 */
class PaymentAnalysis {
    
    private $id;
    private $analysis;
    
    public function __construct() {
        
    }
    
    public function getId() {
        return $this->id;
    }

    public function getAnalysis() {
        return $this->analysis;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setAnalysis($analysis) {
        $this->analysis = $analysis;
    }


}
