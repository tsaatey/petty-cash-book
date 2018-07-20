<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './CrudOperation.php';

if (floatval(filter_input(INPUT_POST, 'amountReceived')) >= 0 && !empty(filter_input(INPUT_POST, 'date')) && !empty(filter_input(INPUT_POST, 'folio')) && !empty(filter_input(INPUT_POST, 'itemDescription')) && !empty(filter_input(INPUT_POST, 'totalAmount')) && !empty(filter_input(INPUT_POST, 'voucherNumber')) && !empty(filter_input(INPUT_POST, 'paymentAnalysis'))) {
    $amountReceived = filter_input(INPUT_POST, 'amountReceived');
    $recordedDate = filter_input(INPUT_POST, 'date');
    $folio = filter_input(INPUT_POST, 'folio');
    $itemDescription = filter_input(INPUT_POST, 'itemDescription');
    $totalAmount = filter_input(INPUT_POST, 'totalAmount');
    $voucherNumber = filter_input(INPUT_POST, 'voucherNumber');
    $paymentAnalysisId = filter_input(INPUT_POST, 'paymentAnalysis');

    date_default_timezone_set('Europe/London');
    $dateTime = new DateTime();
    $date_time = $dateTime->format('Y-F-d H:i:s');
    
    $dd = new DateTime($recordedDate);
    $recordedDate = $dd->format('Y-F-d');
    
    $date = explode('-', $recordedDate);
    
    $month = $date[1];
    $year = $date[0];
    
    $payment = new Payment();
    $payment->setUserId($_SESSION['user_id']);
    $payment->setAmountReceived($amountReceived);
    $payment->setRecordedDate($recordedDate);
    $payment->setRecordedDateTime($date_time);
    $payment->setYear($year);
    $payment->setMonth($month);
    $payment->setFolio($folio);
    $payment->setItemDescription($itemDescription);
    $payment->setVoucherNumber($voucherNumber);
    $payment->setTotalAmount($totalAmount);
    $payment->setPaymentAnalysisId($paymentAnalysisId);
    
    $crud = new CrudOperation();
    
    if ($crud->recordPayment($payment)) {
        echo 'payment_recorded';
    } else {
        echo 'payment_recording_failed';
    }
    
} else {
    echo 'empty_fields';
}
