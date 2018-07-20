/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function () {

    $('#sign_up').click(function () {
        $('#login_container').css('display', 'none');
        $('#signup_container').css('display', 'block');
    });

    $('#home').click(function () {
        window.location.reload(true);
    });


    let getFieldValue = (id) => {
        return document.getElementById(id).value;
    };

    function getPersonObject() {
        return {
            firstname: getFieldValue('firstname'),
            lastname: getFieldValue('lastname'),
            gender: getFieldValue('gender'),
            phone: getFieldValue('phone'),
            email: getFieldValue('email'),
            password: getFieldValue('password')
        };
    }

    var signUpButton = document.getElementById('signupbutton');
    if (signUpButton) {
        signUpButton.addEventListener('click', function () {
            var person = getPersonObject();
            if (person.firstname !== '' && person.lastname !== '' && person.gender !== '' && person && person.phone !== '' && person.email !== '' && person.password !== '') {
                if (validatePassword() === true) {
                    $.ajax({
                        url: 'controllers/signup.php',
                        method: 'POST',
                        data: {
                            firstname: person.firstname,
                            lastname: person.lastname,
                            gender: person.gender,
                            phone: person.phone,
                            email: person.email,
                            password: person.password
                        },
                        complete: function (response) {
                            if (response.responseText === 'account_created') {
                                setTimeout(function () {
                                    window.top.location.reload(true);
                                }, 500);
                            }

                            if (response.responseText === 'account_error') {
                                displayAlert('messages', 'alert', 'Failed to create account', 'alert alert-danger');
                            }

                            if (response.responseText === 'person_error') {
                                displayAlert('messages', 'alert', 'Failed to save your details', 'alert alert-danger');
                            }

                            if (response.responseText === 'empty_fields') {
                                displayAlert('messages', 'alert', 'Please all fields are required!', 'alert alert-danger');
                            }
                        }
                    });
                } else {
                    displayAlert('messages', 'alert', 'Password must contain at least 6 characters, including UPPER/lower case and numbers!', 'alert alert-danger');
                }

            } else {
                displayAlert('messages', 'alert', 'Please all fields are required!', 'alert alert-danger');
            }
        });
    }

    function displayAlert(parent, child, message, alertClass) {
        var parentDiv = document.getElementById(parent);
        var childDiv = document.getElementById(child);

        childDiv.className = alertClass;
        childDiv.innerHTML = message;

        parentDiv.style.display = 'block';
    }

    let checkPassword = (password) => {
        var regularExpression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        return regularExpression.test(password);
    };

    let validatePassword = () => {
        var pass = getPersonObject();
        if (!checkPassword(pass.password)) {
            return false;
        }
        return true;
    };

    var credentialError = document.getElementById('credentials_error');
    if (credentialError) {
        credentialError.addEventListener('click', function () {
            var loginContainer = document.getElementById('login_container');
            var forgotPassword = document.getElementById('reset_password_container');
            loginContainer.style.display = 'none';
            forgotPassword.style.display = 'block';
        });
    }

    function getPasswordResetDetails() {
        return {
            username: getFieldValue('usermail'),
            newPassword: getFieldValue('new_password'),
            confirmPassword: getFieldValue('new_password_confirmed')
        };
    }

    let validateNewPassword = () => {
        var password = getPasswordResetDetails();
        if (!checkPassword(password.newPassword)) {
            return false;
        }
        return true;
    };

    var changePasswordButton = document.getElementById('change_password_button');
    if (changePasswordButton) {
        changePasswordButton.addEventListener('click', function () {
            var passOb = getPasswordResetDetails();
            if (passOb.username !== '' && passOb.newPassword !== '' && passOb.confirmPassword !== '') {
                if (validateNewPassword() === true) {
                    // password is good
                    if (passOb.newPassword === passOb.confirmPassword) {
                        // passwords match. Go ahead and change password
                        $.ajax({
                            url: 'controllers/reset_password.php',
                            method: "POST",
                            data: {username: passOb.username, password: passOb.newPassword},
                            complete: function (response) {
                                if (response.responseText === 'password_reset_success') {
                                    setTimeout(function () {
                                        window.top.location.reload(true);
                                    }, 500);
                                }

                                if (response.responseText === 'password_reset_error') {
                                    displayAlert('reset_messages', 'reset_alert', 'Failed to reset password! Try again', 'alert alert-danger');
                                }

                                if (response.responseText === 'user_does_not_exist') {
                                    displayAlert('reset_messages', 'reset_alert', 'The username entered does not exist!', 'alert alert-danger');
                                }

                                if (response.responseText === 'empty_fields') {
                                    displayAlert('reset_messages', 'reset_alert', 'Please all fields are required!', 'alert alert-danger');
                                }
                            }
                        });
                    } else {
                        // passwords do not match. display an error message
                        displayAlert('reset_messages', 'reset_alert', 'Passwords do not match!', 'alert alert-danger');
                    }
                } else {
                    // password not good
                    // display an error message
                    displayAlert('reset_messages', 'reset_alert', 'Password must contain at least 6 characters, including UPPER/lower case and numbers!', 'alert alert-danger');
                }
            } else {
                displayAlert('reset_messages', 'reset_alert', 'Please all fields are required!', 'alert alert-danger');
            }
        });
    }


    var floatingButton = document.getElementById('floating_button');
    var clickCount = 0;
    if (floatingButton) {
        floatingButton.addEventListener('click', function () {
            if (++clickCount === 1) {
                var page = document.getElementById('home-content');
                var p = document.getElementById('petty_cash_book_dislay');
                p.style.display = 'none';
                page.style.display = 'block';
                floatingButton.style.display = 'none';
            }
        });
    }

    /*
     * 
     */
    function getPaymentObject() {
        return {
            amountReceived: getFieldValue('amount_received'),
            date: getFieldValue('date'),
            folio: getFieldValue('folio'),
            itemDescription: getFieldValue('item_description'),
            voucherNumber: getFieldValue('voucher_number'),
            totalAmount: getFieldValue('total_amount'),
            paymentAnalysis: getFieldValue('payment_analysis')
        };
    }

    var recordButton = document.getElementById('record_button');
    if (recordButton) {
        recordButton.addEventListener('click', function () {
            var paymentData = getPaymentObject();
            var formIds = ['amount_received', 'date', 'folio', 'item_description', 'voucher_number', 'total_amount', 'payment_analysis'];
            if (paymentData.date !== ''
                    && paymentData.folio !== ''
                    && paymentData.itemDescription !== ''
                    && paymentData.totalAmount !== ''
                    && paymentData.voucherNumber !== ''
                    && paymentData.paymentAnalysis !== '') {

                // all is well, make ajax call
                $.ajax({
                    url: 'controllers/save_payment.php',
                    method: 'POST',
                    data: {
                        amountReceived: paymentData.amountReceived,
                        date: paymentData.date,
                        folio: paymentData.folio,
                        itemDescription: paymentData.itemDescription,
                        totalAmount: paymentData.totalAmount,
                        voucherNumber: paymentData.voucherNumber,
                        paymentAnalysis: paymentData.paymentAnalysis
                    },
                    complete: function (response) {
                        console.log(response);
                        if (response.responseText === 'payment_recorded') {
                            resizeContainer('home-content', '800px');
                            displayAlert('payment_messages', 'payment_alert', 'Payment details successfully recorded', 'alert alert-success');
                            clearPaymentFields(formIds);
                        }

                        if (response.responseText === 'payment_recording_failed') {
                            resizeContainer('home-content', '800px');
                            displayAlert('payment_messages', 'payment_alert', 'Failed to record payment details. Try again', 'alert alert-danger');
                        }

                        if (response.responseText === 'empty_fields') {
                            resizeContainer('home-content', '800px');
                            displayAlert('payment_messages', 'payment_alert', 'Please all fields are required! Put 0 in amount received field if not applicable', 'alert alert-danger');
                        }
                    }
                });

            } else {
                // Some field(s) is/are empty, display error message
                resizeContainer('home-content', '800px');
                displayAlert('payment_messages', 'payment_alert', 'Please all fields are required! Put 0 in amount received field if not applicable', 'alert alert-danger');
            }
        });
    }

    let resizeContainer = (id, height) => {
        document.getElementById(id).setAttribute("style", "height:" + height);
    };
    
    let clearPaymentFields = (ids) => {
        for (var i = 0; i < ids.length; i++) {
            document.getElementById(ids[i]).value = '';
        }
    };
    

})();