<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment
 *
 * @author ARTLIB
 */
class Payment {

    private $id;
    private $userId;
    private $amountReceived;
    private $recordedDate;
    private $recordedDateTime;
    private $year;
    private $month;
    private $folio;
    private $itemDescription;
    private $voucherNumber;
    private $totalAmount;
    private $paymentAnalysisId;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getAmountReceived() {
        return $this->amountReceived;
    }

    public function getRecordedDate() {
        return $this->recordedDate;
    }

    public function getRecordedDateTime() {
        return $this->recordedDateTime;
    }

    public function getFolio() {
        return $this->folio;
    }

    public function getItemDescription() {
        return $this->itemDescription;
    }

    public function getVoucherNumber() {
        return $this->voucherNumber;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }

    public function getPaymentAnalysisId() {
        return $this->paymentAnalysisId;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setAmountReceived($amountReceived) {
        $this->amountReceived = $amountReceived;
    }

    public function setRecordedDate($recordedDate) {
        $this->recordedDate = $recordedDate;
    }

    public function setRecordedDateTime($recordedDateTime) {
        $this->recordedDateTime = $recordedDateTime;
    }

    public function setFolio($folio) {
        $this->folio = $folio;
    }

    public function setItemDescription($itemDescription) {
        $this->itemDescription = $itemDescription;
    }

    public function setVoucherNumber($voucherNumber) {
        $this->voucherNumber = $voucherNumber;
    }

    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

    public function setPaymentAnalysisId($paymentAnalysisId) {
        $this->paymentAnalysisId = $paymentAnalysisId;
    }

    public function getMonth() {
        return $this->month;
    }

    public function setMonth($month) {
        $this->month = $month;
    }
    
    public function getYear() {
        return $this->year;
    }

    public function setYear($year) {
        $this->year = $year;
    }


}
