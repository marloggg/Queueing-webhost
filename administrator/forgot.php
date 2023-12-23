<?php
require_once("./../DBConnection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | SWU Queuing System</title>
    <link rel="stylesheet" href="./../css/bootstrap.min.css">
    <script src="./../js/jquery-3.6.0.min.js"></script>
    <script src="./../js/popper.min.js"></script>
    <script src="./../js/bootstrap.min.js"></script>
    <script src="./../js/script.js"></script>
    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>

<body class="bg-dark bg-gradient">
    <div class="h-100 d-flex jsutify-content-center align-items-center">
        <div class='w-100'>
            <div class="card my-3 col-md-4 offset-md-4">
                <div class="card-body">
                    <form action="" id="check-form">
                        <center>
                            <h2>Reset Password</h2>
                        </center>
                        <center>
                            <p>It's quick and easy.</p>
                        </center>
                        <hr>
                        <div class="form-group">
                            <input type="text" id="email" name="email" placeholder="Email"
                                class="form-control form-control-sm rounded-0" required>
                        </div>
                        <!-- Add a div to display the message -->
                        <div id="message-container" class="pop_msg"></div>
                        <div class="form-group d-flex w-100 justify-content-between align-items-center">
                            <center><button class="btn btn-sm btn-primary rounded-1 my-3">Reset</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(function () {
        $('#check-form').submit(function (e) {
            e.preventDefault();
            // Update the message element
            $('#message-container').removeClass('alert alert-success alert-danger').empty();
            var _this = $(this)
            var _el = $('<div>')
            _el.addClass('pop_msg')
            _this.find('button').attr('disabled', true)
            _this.find('button[type="submit"]').text('Checking...')
            $.ajax({
                url: './../Actions.php?a=checkAdmin',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                error: err => {
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    $('#message-container').append(_el);
                    _el.show('slow')
                    _this.find('button').attr('disabled', false)
                    _this.find('button[type="submit"]').text('Save')
                },
                success: function (resp) {
                    // Update the message element
                    var _messageEl = $('<div>')
                    _messageEl.addClass('pop_msg');
                    if (resp.status == 'success') {
                        _messageEl.addClass('alert alert-success');
                        setTimeout(() => {
                            location.replace('./login.php');
                        }, 2000);
                    } else {
                        _messageEl.addClass('alert alert-danger');
                    }
                    _messageEl.text(resp.msg);

                    _messageEl.hide();
                    $('#message-container').append(_messageEl);
                    _messageEl.show('slow');
                    _this.find('button').attr('disabled', false);
                    _this.find('button[type="submit"]').text('Save');
                }
            })
        })
    })
</script>

</html>