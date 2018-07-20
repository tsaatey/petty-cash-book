<?php
require_once '../controllers/CrudOperation.php';
$month = filter_input(INPUT_POST, 'month');
$yr = filter_input(INPUT_POST, 'year');
?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Expenses for <?php echo $month . ' ' . $yr; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="../css/bootstrap.css" rel="stylesheet" type="text/css"/>
    </head>

    <style>
        .container{
            margin-top: 50px;
        }

        th{
            text-align: center;
        }

        table{
            border: 2px solid #000;
        }

        #heading{
            font-size: 35px;
            font-family: Monospace;
            border-bottom: 1px solid #000000;
            margin-bottom: 18px;
            text-align: center;
        }

        #currency {
            font-size: 18px;
        }

    </style>
    <body>
        <div class="container" id="expenses_div">
            <?php
            $crud = new CrudOperation();

            $data = $crud->getSpecificMonthExpenses($yr, $month);
            $asso_year = $crud->getDuplicateAssociativeArray($yr, $month);
            ?>
            <div id="heading">
                <p>Petty Cash Book for <?php echo $month . ' ' . $yr; ?></p>
                <p id="currency">(All Amounts Are Quoted in GHS)</p>
            </div>
            <div>
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th>Year</th>
                        <th>Received</th>
                        <th>Month</th>
                        <th>Folio</th>
                        <th>Details</th>
                        <th>Voucher Number</th>
                        <th>Total Payment</th>
                        <th colspan="4">Payment Analysis</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; font-size: 14px;">Postage</td>
                        <td style="font-weight: bold;">Stationary</td>
                        <td style="font-weight: bold;">Fare</td>
                        <td style="font-weight: bold;">Miscellaneous</td>
                    </tr>
                    <?php
                    $current_year = '';
                    $receivedTotal = 0;
                    $postageTotal = 0;
                    $fareTotal = 0;
                    $stationaryTotal = 0;
                    $miscellaneousTotal = 0;
                    $paymentTotal = 0;

                    foreach ($data as $expense) {
                        $received = $expense['received'];
                        $postage = '';
                        $stationary = '';
                        $fare = '';
                        $miscellaneous = '';

                        if ($expense['analysis'] == 'Postage') {
                            $postage = $expense['amount'];
                            $postageTotal += floatval($postage);
                        }

                        if ($expense['analysis'] == 'Stationary') {
                            $stationary = $expense['amount'];
                            $stationaryTotal += floatval($stationary);
                        }

                        if ($expense['analysis'] == 'Fare') {
                            $fare = $expense['amount'];
                            $fareTotal += floatval($fare);
                        }

                        if ($expense['analysis'] == 'Miscellaneous') {
                            $miscellaneous = $expense['amount'];
                            $miscellaneousTotal += floatval($miscellaneous);
                        }

                        if ($received == 0) {
                            $received = '';
                        }

                        $receivedTotal += floatVal($expense['received']);

                        foreach ($asso_year as $year) {
                            ?>
                            <tr>
                                <?php
                                if ($expense['year'] != $current_year) {
                                    ?>
                                    <td rowspan="<?php echo $year['count'] + 4; ?>"><?php echo $expense['year']; ?></td>
                                    <?php
                                    $current_year = $year['year'];
                                }
                                ?>
                                <td style="text-align: right;"><?php echo $received ?></td>
                                <td><?php echo $expense['day_month'] ?></td>
                                <td style="text-align: center;"><?php echo $expense['folio'] ?></td>
                                <td><?php echo $expense['details'] ?></td>
                                <td style="text-align: center;"><?php echo $expense['voucher'] ?></td>
                                <td style="text-align: center;"><?php echo $expense['amount'] ?></td>
                                <td style="text-align: center;"><?php echo $postage ?></td>
                                <td style="text-align: center;"><?php echo $stationary ?></td>
                                <td style="text-align: center;"><?php echo $fare ?></td>
                                <td style="text-align: center;"><?php echo $miscellaneous ?></td>

                            </tr>
                            <?php
                        }
                    }
                    $paymentTotal = $fareTotal + $miscellaneousTotal + $postageTotal + $stationaryTotal;
                    if ($fareTotal == 0) {
                        $fareTotal = '';
                    }

                    if ($miscellaneous == 0) {
                        $miscellaneousTotal = '';
                    }

                    if ($postageTotal == 0) {
                        $postageTotal = '';
                    }

                    if ($stationaryTotal == 0) {
                        $stationaryTotal = '';
                    }

                    $amountLeft = $receivedTotal - $paymentTotal;
                    ?>
                    <tr>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">TOTAL</td>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $paymentTotal; ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $postageTotal; ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $stationaryTotal; ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $fareTotal; ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $miscellaneousTotal; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">Balance c/d</td>
                        <td></td>
                        <td style="font-weight: bold; text-align: center;"><?php echo $amountLeft; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: right"><?php echo $receivedTotal; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; text-align: center;"><?php echo $receivedTotal; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: right;"><?php echo $amountLeft; ?></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">Balance b/d</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>

                </table>
            </div>
        </div>
        <div class="container">
            <input type="button" value="PRINT" class="btn btn-info" onclick="printCashBook('expenses_div');"/>
        </div>


        <script>
            function printCashBook(div) {
                var printContents = document.getElementById(div).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

            }
        </script>


        <script src="../js/jquery.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>



