<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CrudOperation
 *
 * @author ARTLIB
 */
session_start();

require_once '../models/Account.php';
require_once 'DatabaseConnection.php';
require_once '../models/Person.php';
require_once '../models/Payment.php';

class CrudOperation {

    private $hostname;
    private $username;
    private $password;
    private $connection;

    public function __construct() {
        $this->hostname = "localhost";
        $this->username = 'root';
        $this->password = 'unityn';

        $db = new DatabaseConnection($this->hostname, $this->username, $this->password);
        $this->connection = $db->ConnectDB();
    }

    public function login(Account $account) {
        try {
            $query = $this->connection->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
            $query->execute([
                'username' => $account->getUsername(),
                'password' => $account->getPassword()
            ]);

            if ($query->rowCount() > 0) {
                $qr = $this->connection->prepare("SELECT id, firstname FROM person WHERE email = :email");
                $qr->execute([
                    'email' => $account->getUsername()
                ]);

                while ($result = $qr->fetch()) {
                    $_SESSION['fname'] = $result['firstname'];
                    $_SESSION['user_id'] = $result['id'];
                }

                return true;
            }
            return false;
        } catch (Exception $ex) {
            //die($ex->getMessage());
        }
    }

    public function createAccount(Account $account) {
        try {
            $query = $this->connection->prepare("INSERT INTO users(userId, username, password) VALUES(:userId, :username, :password)");
            $query->execute([
                'userId' => $account->getUserId(),
                'username' => $account->getUsername(),
                'password' => $account->getPassword()
            ]);

            return true;
        } catch (Exception $ex) {
            //die($ex->getMessage());
        }
    }

    public function savePersonDetails(Person $person) {
        try {
            $query = $this->connection->prepare("INSERT INTO person(id, firstname, lastname, gender, phone, email) VALUES(:id, :firstname, :lastname, :gender, :phone, :email)");
            $query->execute([
                'id' => $person->getId(),
                'firstname' => $person->getFirstname(),
                'lastname' => $person->getLastname(),
                'gender' => $person->getGender(),
                'phone' => $person->getPhone(),
                'email' => $person->getEmail()
            ]);

            return true;
        } catch (Exception $ex) {
            
        }
    }

    public function isUserExists($username) {
        try {
            $query = $this->connection->prepare("SELECT * FROM person WHERE email = :email");
            $query->execute([
                'email' => $username
            ]);

            if ($query->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            
        }
    }

    public function changePassword(Account $account) {
        try {
            $query = $this->connection->prepare("UPDATE users SET password = :password WHERE username = :username");
            $query->execute([
                'password' => $account->getPassword(),
                'username' => $account->getUsername()
            ]);
            return true;
        } catch (Exception $ex) {
            
        }
    }

    public function getPersonId() {
        $prefix = 'PCB';
        $id = '';
        try {
            $query = $this->connection->prepare("SELECT id FROM person");
            $query->execute();
            $rows = $query->rowCount() + 1;

            if ($rows < 10) {
                $id = $prefix . '00' . $rows;
            }

            if ($rows > 9 && $rows < 100) {
                $id = $prefix . '0' . $rows;
            }

            if ($rows > 99) {
                $id = $prefix . $rows;
            }

            return $id;
        } catch (Exception $ex) {
            
        }
    }

    public function recordPayment(Payment $payment) {
        try {
            $query = $this->connection->prepare("INSERT INTO payment(userId, amount_received, recorded_date, recorded_datetime, recorded_year, recorded_month, folio, item_description, "
                    . "voucher_number, total_amount, payment_analysis_id) "
                    . "VALUES(:userId, :amount_received, :recorded_date, :recorded_datetime, :recorded_year, :recorded_month, :folio, :item_description, :voucher_number, "
                    . ":total_amount, :payment_analysis_id)");
            $query->execute([
                'userId' => $payment->getUserId(),
                'amount_received' => $payment->getAmountReceived(),
                'recorded_date' => $payment->getRecordedDate(),
                'recorded_datetime' => $payment->getRecordedDateTime(),
                'recorded_year' => $payment->getYear(),
                'recorded_month' => $payment->getMonth(),
                'folio' => $payment->getFolio(),
                'item_description' => $payment->getItemDescription(),
                'voucher_number' => $payment->getVoucherNumber(),
                'total_amount' => $payment->getTotalAmount(),
                'payment_analysis_id' => $payment->getPaymentAnalysisId()
            ]);
            return true;
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }
    
    public function getSpecificMonthExpenses ($year, $month) {
        $cashbook = array();
        try{
            $query = $this->connection->prepare("SELECT payment.recorded_year AS 'year', payment.recorded_date AS 'date', payment.amount_received AS 'received', payment.folio AS 'folio', "
                    . "payment.item_description AS 'details', payment.voucher_number AS 'voucher', payment.total_amount AS 'amount', "
                    . "payment_analysis.analysis AS 'analysis' FROM payment, payment_analysis, person WHERE "
                    . "payment.payment_analysis_id = payment_analysis.id "
                    . "AND payment.userId = person.id AND person.id = :userId AND payment.recorded_year = :year AND payment.recorded_month = :month ORDER BY recorded_datetime ASC");
            $query->execute([
                'userId' => $_SESSION['user_id'],
                'year' => $year,
                'month' => $month
            ]);
            
            if ($query->rowCount() > 0) {
                while ($result = $query->fetch()) {
                    $date = explode('-', $result['date']);
                    $mon = $date[1];
                    $day = $date[2];
                    $day_month = $mon . ' ' . $day;
                    $cashbook[] = array(
                        'year' => $result['year'],
                        'day_month' => $day_month,
                        'received' => $result['received'],
                        'folio' => $result['folio'],
                        'details' => $result['details'],
                        'voucher' => $result['voucher'],
                        'amount' => $result['amount'],
                        'analysis' => $result['analysis']
                    );
                }
            }
            return $cashbook;
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
        
        
    }
    
    public function getDuplicateAssociativeArray($year, $month) {
        $duplicateYears = array();
        $allYears = $this->getAllYears($year, $month);
        foreach ($allYears as $year) {
            $count = $this->getYearCount($year, $month);
            $duplicateYears[] = array('year' => $year, 'count' => $count);
        }
        
        return $duplicateYears;
    }
    
    public function getAllYears($year, $month) {
        $years = array();
        try{
            $query = $this->connection->prepare("SELECT DISTINCT recorded_year AS 'year' FROM payment WHERE userId = :id AND recorded_year = :year AND recorded_month = :month");
            $query->execute([
                'id' => $_SESSION['user_id'],
                'year' => $year,
                'month' => $month
            ]);
            if ($query->rowCount() > 0) {
                $counter = 0;
                while ($result = $query->fetch()) {
                    $years[] += $result['year'];
                }
            }
            return $years;
        } catch (Exception $ex) {

        }
    }
    
    public function getYearCount($year, $month) {
        $yearCount = 0;
        try{
            $query = $this->connection->prepare("SELECT COUNT(recorded_year) AS 'year_count' FROM payment WHERE recorded_year = :year AND recorded_month = :month AND userId = :id");
            $query->execute([
                'year' => $year,
                'month' => $month,
                'id' => $_SESSION['user_id']
            ]);
            
            while ($result = $query->fetch()) {
                $yearCount = $result['year_count'];
            }
            return $yearCount;
        } catch (Exception $ex) {

        }
    }

}
