<?php
session_start();
require_once 'controllers/DatabaseConnection.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Petty Cash Book</title>
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="material-icons-0.2.1/iconfont/material-icons.css" rel="stylesheet" type="text/css"/>
        <link href="node_modules/@material/fab/dist/mdc.fab.min.css" rel="stylesheet" type="text/css"/>
        <style>
            .navbar-header a{
                font-size: 20px;
                color: #ffffff;
                font-family: Tahoma;
                font-weight: bold;
            }

            #credentials_error:hover{
                cursor: pointer;
            }

            #floating_button{
                background-color: #5cb85c;
                border-color: #4cae4c;
            }

            .app-fab--absolute {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
            }

            @media(min-width: 1024px) {
                .app-fab--absolute {
                    bottom: 9.5rem;
                    right: 23.5rem;
                }
            }

            #home-content{
                border: 1px solid #000000;
                border-style: outset;
                height: 720px;
                margin-top: 30px;
            }

            #heading{
                text-align: center;
                color: #5cb85c;
                background-color: #778899;
                border-radius: 10px;
                background-size: 60px;
                font-weight: bold;
                height: 80px;
                margin: 0 auto;
                line-height: 75px;
                font-size: 30px;
                border-style: outset;
            }

            tr{
                width: 500px;
            }

            td {
                width: 120px;
            }

            caption{
                font-size: 22px;
                font-family: Monospace;
                border-bottom: 1px solid #000000;
                margin-bottom: 12px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">pCashBook</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li id="home"><a href="#" class="active"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
                    <?php
                    if (!empty($_SESSION['login_success']) && $_SESSION['login_success'] == 1) {
                        ?>
                        <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['fname']; ?></a></li>
                        <li><a href="controllers/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout</a></li>
                        <?php
                    } else {
                        ?>
                        <li id="sign_up"><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        </nav> 
        <?php
        if (!empty($_SESSION['login_success']) && $_SESSION['login_success'] == 1) {
            ?>
            <div class="container">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div id="heading">
                        <p>The Simplest Petty Cash Book on the Web!</p>
                    </div>
                    <div id="petty_cash_book_dislay" style="margin-top: 35px;">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <!-- Display petty cash book here -->
                            <?php
                            $db = new DatabaseConnection('localhost', 'root', 'unityn');
                            $connection = $db->ConnectDB();
                            $query = $connection->prepare("SELECT DISTINCT recorded_year, recorded_month FROM payment WHERE userId = :userId ORDER BY recorded_datetime ASC");
                            $query->execute([
                                'userId' => $_SESSION['user_id']
                            ]);

                            if ($query->rowCount() > 0) {
                                ?>
                                <table class="table table-responsive table-bordered">
                                    <caption style="text-align: center;">All Recorded Expenses</caption>

                                    <?php
                                    $counter = 1;
                                    while ($result = $query->fetch()) {
                                        ?>
                                    <form class="form-horizontal" role = "form" method="POST" action="views/monthly_expenses.php" target="_blank">
                                            <input type="hidden" value="<?php echo $result['recorded_month']; ?>" name="month"/>
                                            <input type="hidden" value="<?php echo $result['recorded_year']; ?>" name="year"/>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td><?php echo $result['recorded_year']; ?></td>
                                                <td><?php echo $result['recorded_month']; ?></td>
                                                <td>
                                                    <input type="submit" class="form-control btn btn-success" value="View" />
                                                </td>
                                            </tr>

                                        </form>
                                        <?php
                                        $counter += 1;
                                    }
                                    ?>
                                </table>
                                <?php
                            } else {
                                echo 'No petty cash expenses recorded yet';
                            }
                            ?>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                   
                    <div id="home-content" style="display: none;">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6" style="padding-top: 20px;">
                            <div id="payment_messages" style="display: none;">
                                <div id="payment_alert">
                                </div>
                            </div>
                            <form class="form-horizontal" role = "form">
                                <div class="form-group">
                                    <label class = "col-md-5">Amount received</label>
                                    <div class = "col-md-12">
                                        <input type = "number" class = "form-control input-lg" name = "amount_received" id="amount_received"/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <label class = "col-md-5">Date</label>
                                    <div class = "col-md-12">
                                        <input type = "date" class = "form-control input-lg" name = "date" id="date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class = "col-md-5">Folio</label>
                                    <div class = "col-md-12">
                                        <input type = "text" class = "form-control input-lg" name = "folio" id="folio"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class = "col-md-5">Item description</label>
                                    <div class = "col-md-12">
                                        <input type = "text" class = "form-control input-lg" name = "item_description" id="item_description"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class = "col-md-5">Voucher number</label>
                                    <div class = "col-md-12">
                                        <input type = "text" class = "form-control input-lg" name = "voucher" id="voucher_number"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class = "col-md-5">Total</label>
                                    <div class = "col-md-12">
                                        <input type = "number" class = "form-control input-lg" name = "total" id="total_amount"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class = "col-md-5">Payment analysis</label>
                                    <div class = "col-md-12">
                                        <select class="form-control input-lg" id="payment_analysis" name="payment_analysis">
                                            <option value="" selected disabled>Choose</option>
                                            <option value="1">Stationary</option>
                                            <option value="2">Fare</option>
                                            <option value="3">Postage</option>
                                            <option value="4">Miscellaneous</option>
                                        </select>
                                    </div>
                                </div>
                                <div class = "form-group form-group-lg">
                                    <div class = "col-md-12">
                                        <input type = "button" class = "form-control btn btn-success" value = "Save" id="record_button" name = "record_button"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <?php
        } else {
            ?>
            <div class="container" style="display: block;" id="login_container">
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <div style="margin-top: 130px;">
                        <h3 style="text-align: center; margin-bottom: 40px;">User Login</h3>
                        <?php
                        if (!empty($_SESSION['wrong_credentials']) && $_SESSION['wrong_credentials'] == 1) {
                            ?>
                            <div id="" style="">
                                <div class="alert alert-danger" id="">
                                    Username or password is not correct!
                                </div>
                                <div style="color: #0275d8; margin-bottom: 20px;" id="credentials_error">
                                    Forgot password? Click here
                                </div>
                            </div>
                            <?php
                        } else if (!empty($_SESSION['empty_fields']) && $_SESSION['empty_fields'] == 1) {
                            ?>
                            <div id="" style="">
                                <div class="alert alert-danger" id="">
                                    Please supply username and password!
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <form class="form-horizontal" role = "form" method="POST" action="controllers/login.php">
                            <div class="form-group">
                                <label class = "col-md-5">Username</label>
                                <div class = "col-md-12">
                                    <input type = "email" class = "form-control input-lg" name = "username"/>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "col-md-5">Password</label>
                                <div class = "col-md-12">
                                    <input type = "password" class = "form-control input-lg" name = "password"/>
                                </div>
                            </div>
                            <div class = "form-group form-group-lg">
                                <div class = "col-md-12">
                                    <button type = "submit" class = "form-control btn btn-success" value = "Login" name = "loginbutton">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="col-lg-4"></div>
            </div>
            <?php
        }
        ?>

        <div class="container" style="display: none;" id="signup_container">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div style="margin: 0 auto;">
                    <h2 style="text-align: center; margin-bottom: 40px;">Create Account</h2>
                    <div id="messages" style="display: none;">
                        <div class="alert alert-danger" id="alert">
                        </div>
                    </div>
                    <form class="form-horizontal" role = "form">
                        <div class="form-group">
                            <label class = "col-md-5" for="firstname">First name</label>
                            <div class = "col-md-12">
                                <input type = "text" class = "form-control input-lg" name = "firstname" id="firstname"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class = "col-md-5">Last name</label>
                            <div class = "col-md-12">
                                <input type = "text" class = "form-control input-lg" name = "lastname" id="lastname"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class = "col-md-5">Gender</label>
                            <div class = "col-md-12">
                                <select class="form-control input-lg" id="gender" name="gender">
                                    <option value="" selected disabled>Choose</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class = "col-md-5">Phone number</label>
                            <div class = "col-md-12">
                                <input type = "text" class = "form-control input-lg" name = "phone" id="phone"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class = "col-md-5">Email</label>
                            <div class = "col-md-12">
                                <input type = "email" class = "form-control input-lg" name = "email" id="email"/>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label class = "col-md-5">Password</label>
                            <div class = "col-md-12">
                                <input type = "password" class = "form-control input-lg" name = "password" id="password"/>
                            </div>
                        </div>
                        <div class = "form-group form-group-lg">
                            <div class = "col-md-12">
                                <input type = "button" class = "form-control btn btn-success" value = "Sign Up" id="signupbutton" name = "signupbutton"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>

        <div class="container" style="display: none;" id="reset_password_container">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div style="margin-top: 130px;">
                    <h2 style="text-align: center; margin-bottom: 40px;">Change Password</h2>
                    <div id="reset_messages" style="display: none;">
                        <div class="alert alert-danger" id="reset_alert">
                        </div>
                    </div>
                    <form class="form-horizontal" role = "form">
                        <div class="form-group">
                            <label class = "col-md-5">Username</label>
                            <div class = "col-md-12">
                                <input type = "email" class = "form-control input-lg" name = "usermail" id="usermail"/>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label class = "col-md-5">New Password</label>
                            <div class = "col-md-12">
                                <input type = "password" class = "form-control input-lg" name = "new_password" id="new_password"/>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label class = "col-md-5">Confirm Password</label>
                            <div class = "col-md-12">
                                <input type = "password" class = "form-control input-lg" name = "new_password_confirmed" id="new_password_confirmed"/>
                            </div>
                        </div>
                        <div class = "form-group form-group-lg">
                            <div class = "col-md-12">
                                <input type = "button" class = "form-control btn btn-success" value = "Change Password" id="change_password_button" name = "change_password_button"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>

        <?php
        if (!empty($_SESSION['login_success']) && $_SESSION['login_success'] == 1) {
            ?>
            <div class="container">
                <div style="float: right;">
                    <button class="mdc-fab material-icons app-fab--absolute" aria-label="Add" id="floating_button">
                        <span class="mdc-fab__icon">
                            add
                        </span>
                    </button>
                </div>
            </div>
            <?php
        }
        ?>

        <script>
            function setMonth(month) {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    data: {month: month},
                    complete: function (response) {
                        console.log(response);
                        if (response.responseText === 'data_received') {
                            // hide current div and display cashbook details
                            document.getElementById('petty_cash_book_dislay').style.display = 'none';
                            document.getElementById('cashbook_details').style.display = 'block';
                        } else {
                            // do nothing
                        }
                    }
                });
            }
        </script>

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/index.js" type="text/javascript"></script>
    </body>
</html>
