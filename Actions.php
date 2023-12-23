<?php
session_start();
require_once('DBConnection.php');
require_once "email-noreply.php";
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';
// require 'vendor/autoload.php';


// function sendemail($username,$password,$email_address,$lastname)
// {
//     $mail = new PHPMailer(true);
//     //Server settings
//     $mail->isSMTP();                              //Send using SMTP
//     $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
//     $mail->SMTPAuth   = true;             //Enable SMTP authentication
//     $mail->Username   = 'swuqueueing@gmail.com';   //SMTP write your email
//     $mail->Password   = 'pxcfvqwtdtudetxx';      //SMTP password
//     $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
//     $mail->Port       = 465;                                    

//     $mail->setFrom('swuqueueing@gmail.com');
//     $mail->addAddress('$email_address');  

//     $mail->isHTML(true);                                  //Set email format to HTML
//     $mail->Subject = 'Account Creation';
//     $mail->Body    = 'Hi! $lastname,

//     Congratulations! Your account has been successfully created. Welcome to SWU queueing system. Please login you account using this credentials:

//     Username: $username
//     Password: $password

//     Sincerely,
//     SWUQUEUEING TEAM';//Add a recipient

//     $mail->send();
//     echo 'Message has been sent';
// }

class Actions extends DBConnection
{
    function __construct()
    {
        parent::__construct();
    }
    function __destruct()
    {
        parent::__destruct();
    }
    function login()
    {
        extract($_POST);
        $sql = "SELECT * FROM administrator_list where username = '{$username}' and `password` = '" . md5($password) . "' ";
        @$qry = $this->query($sql)->fetchArray();
        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        } else {
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach ($qry as $k => $v) {
                if (!is_numeric($k))
                    $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout()
    {
        session_destroy();
        header("location:./login.php");
    }
    function c_login()
    {
        extract($_POST);
        $sql = "SELECT * FROM cashier_list where cashier_id = '{$cashier_id}' and `password` = '" . md5($password) . "' ";
        @$qry = $this->query($sql)->fetchArray();
        if ($qry) {
            if ($qry['log_status'] == 2) {
                $resp['status'] = "success";
                $resp['msg'] = "Login successfully.";
                $this->query("UPDATE `cashier_list` set log_status = 1 where cashier_id  = {$cashier_id}");
                foreach ($qry as $k => $v) {
                    if (!is_numeric($k))
                        $_SESSION[$k] = $v;
                }
            } else {
                $resp['failed'] = "failed";
                $resp['msg'] = "Cashier is In-Use.";
            }
        } else {
            $resp['status'] = "success";
            $resp['msg'] = "An Error occured. Error: " . $this->lastErrorMsg();
        }

        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }
        return json_encode($resp);
    }

    function t_login()
    {
        extract($_POST);
        $sql = "SELECT * FROM teller_list where teller_id = '{$teller_id}' ";
        @$qry = $this->query($sql)->fetchArray();
        if ($qry) {
            if ($qry['log_status'] == 2) {
                $resp['status'] = "success";
                $resp['msg'] = "Login successfully.";
                $this->query("UPDATE `teller_list` set log_status = 1 where teller_id  = {$teller_id}");
                foreach ($qry as $k => $v) {
                    if (!is_numeric($k))
                        $_SESSION[$k] = $v;
                }
            } else {
                $resp['failed'] = "failed";
                $resp['msg'] = "Teller is In-Use.";
            }
        } else {
            $resp['status'] = "success";
            $resp['msg'] = "An Error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }

    function c_logout()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./shs");
    }
    function c_logout_liv()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./foreign");
    }
    function c_logout_sa()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./cashier_sa");
    }
    function c_logout_enrollment()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./enrollment");
    }
    function c_logout_medicine()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./medicine");
    }
    function c_logout_transaction()
    {
        session_destroy();
        $this->query("UPDATE `cashier_list` set log_status = 2 where cashier_id  = {$_SESSION['cashier_id']}");
        $this->query("UPDATE `teller_list` set log_status = 2 where teller_id  = {$_SESSION['teller_id']}");
        header("location:./transaction");
    }
        /*function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `administrator_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    } */

    function update_Acredentials()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'old_password')) && !empty($v)) {
                if (!empty($data))
                    $data .= ",";
                if ($k == 'password')
                    $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if (!empty($password) && md5($old_password) != $_SESSION['password']) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        } else {
            $sql = "UPDATE `administrator_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if ($save) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach ($_POST as $k => $v) {
                    if (!in_array($k, array('id', 'old_password')) && !empty($v)) {
                        if (!empty($data))
                            $data .= ",";
                        if ($k == 'password')
                            $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }

    function update_Ccredentials()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'old_password')) && !empty($v)) {
                if (!empty($data))
                    $data .= ",";
                if ($k == 'password')
                    $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if (empty($password)) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Please enter new password.";
        } else {
            $sql = "UPDATE `cashier_list` set {$data} where cashier_id = '{$_POST['id']}'";
            @$save = $this->query($sql);
            if ($save) {
                $resp['status'] = 'success';
                $resp['type'] = 'success';
                $resp['msg'] = 'Credential successfully updated.';
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }
    function update_credentials()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'old_password')) && !empty($v)) {
                if (!empty($data))
                    $data .= ",";
                if ($k == 'password')
                    $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if (!empty($password) && md5($old_password) != $_SESSION['password']) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        } else {
            $sql = "UPDATE `administrator_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if ($save) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach ($_POST as $k => $v) {
                    if (!in_array($k, array('id', 'old_password')) && !empty($v)) {
                        if (!empty($data))
                            $data .= ",";
                        if ($k == 'password')
                            $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }
    function save_cashier()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'confirm_password')) && !empty($v)) {
                $v = addslashes(trim($v));
                if (empty($id)) {
                    if ($k == 'password')
                        $v = md5($v);
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    if ($k == 'password')
                        $v = md5($v);
                    if (!empty($data))
                        $data .= ", ";
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
        if (isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols);
            $vals_join = implode(",", $vals);
        }

        if (empty($username) || empty($lastname) || empty($firstname) || empty($MI) || empty($email_address) || empty($password) || empty($confirm_password)) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Please fill all fields.";
        } else {
            @$check = $this->query("SELECT COUNT(cashier_id) as count from `cashier_list` where `username` = '{$username}' " . ($id > 0 ? " and cashier_id != '{$id}'" : ""))->fetchArray()['count'];
            if (@$check > 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Cashier already exists.';
            } else {
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
                } else {
                    if (md5($password) != md5($confirm_password)) {
                        $resp['status'] = 'failed';
                        $resp['msg'] = "Password do not Match!";
                    } else {
                        if (empty($id)) {
                            $sql = "INSERT INTO `cashier_list` ({$cols_join}) VALUES ($vals_join)";
                            @$save = $this->query($sql);
                            if ($save) {
                                $email_subject = 'New Cashier Account Created';
                                $email_body = '
    Congratulations! Your new cashier account has been created. Below are your credentials to log-on to your account:
    
    Username: ' . $username . '
    Password: ' . $password . '

    Please proceed to this link "http://localhost/swu-fpqsv1/cashdash/home.php" to log-in.

    
    Regards,
    SWU QUEUEING TEAM

    
    ***This is a system-generated email, no signature required. Please do not reply***
                            ';
                                $recipient = $email_address;
                                sendEmail($recipient, $email_subject, $email_body);
                                $resp['status'] = 'success';
                                $resp['type'] = 'success';
                                $resp['msg'] = 'Credential successfully updated.';

                            } else {
                                $resp['status'] = 'failed';
                                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
                                $resp['sql'] = $sql;
                            }
                        }
                    }
                }
            }
        }
        return json_encode($resp);
    }


    //EMAIL FUNCTIONS
    function check()
    {
        extract($_POST);
        $resp = [];

        if (empty($email)) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Please enter your email.';
        } else {
            // Check if the email exists in the database
            $check = $this->query("SELECT COUNT(cashier_id) as count FROM `cashier_list` WHERE `email_address` = '{$email}'")->fetchArray()['count'];

            if ($check == 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Email not found in our records.';
            } else {
                // Generate an 8-digit random password
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $newPassword = substr(str_shuffle($characters), 0, 8);

                // Store the new password in the database
                $updatePasswordQuery = "UPDATE `cashier_list` SET `password` = md5('{$newPassword}') WHERE `email_address` = '{$email}'";
                $updatePassword = $this->query($updatePasswordQuery);
                $resetLink = "http://localhost/swu-fpqsv1/cashdash/home.php";

                if ($updatePassword) {
                    // Send the new password via email
                    $subject = 'Password Reset Request';
                    $message = "
    Dear Cashier,

    Your new password is: {$newPassword}

    Login your credentials to this link: {$resetLink}
    
    Regards,
    SWU QUEUEING TEAM    
        
    
    ***This is a system-generated email, no signature required. Please do not reply***                            
                    
                    ";

                    try {
                        sendEmail($email, $subject, $message);

                        $resp['status'] = 'success';
                        $resp['msg'] = 'New password sent to your email.';
                    } catch (Exception $e) {
                        $resp['status'] = 'failed';
                        $resp['msg'] = 'Failed to send new password. Please try again.';
                    }
                } else {
                    $resp['status'] = 'failed';
                    $resp['msg'] = 'Error updating password: ' . $this->lastErrorMsg();
                }
            }
        }

        return json_encode($resp);
    }
    function checkAdmin()
    {
        extract($_POST);
        $resp = [];

        if (empty($email)) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Please enter your email.';
        } else {
            // Check if the email exists in the administrator_list table
            $check = $this->query("SELECT COUNT(admin_id) as count FROM `administrator_list` WHERE `email_address` = '{$email}'")->fetchArray()['count'];

            if ($check == 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Email not found in our records.';
            } else {
                // Generate an 8-character random password with letters and numbers
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $newPassword = substr(str_shuffle($characters), 0, 8);

                // Store the new password in the administrator_list table
                $updatePasswordQuery = "UPDATE `administrator_list` SET `password` = md5('{$newPassword}') WHERE `email_address` = '{$email}'";
                $updatePassword = $this->query($updatePasswordQuery);
                $resetLink = "http://localhost/swu-fpqsv1/administrator/";

                if ($updatePassword) {
                    // Send the new password via email
                    $subject = 'Password Reset Request';
                    $message = "
                    
    Dear Admin,
                    
    Your new password is: {$newPassword}

    Login your credentials to this link: {$resetLink}
    
    Regards,
    SWU QUEUEING TEAM    
                        
                    
    ***This is a system-generated email, no signature required. Please do not reply***
                    
                    ";

                    try {
                        sendEmail($email, $subject, $message);

                        $resp['status'] = 'success';
                        $resp['msg'] = 'New password sent to your email.';
                    } catch (Exception $e) {
                        $resp['status'] = 'failed';
                        $resp['msg'] = 'Failed to send new password. Please try again.';
                    }
                } else {
                    $resp['status'] = 'failed';
                    $resp['msg'] = 'Error updating password: ' . $this->lastErrorMsg();
                }
            }
        }

        return json_encode($resp);
    }














    function delete_cashier()
    {
        extract($_POST);
        $get = $this->query("SELECT * FROM `cashier_list` where cashier_id = '{$id}'");
        @$res = $get->fetchArray();
        $is_logged = false;
        if ($res) {
            $is_logged = $res['log_status'] == 1 ? true : false;
            if ($is_logged) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Cashier is in use.';
            } else {
                @$delete = $this->query("DELETE FROM `cashier_list` where cashier_id = '{$id}'");
                if ($delete) {
                    $resp['status'] = 'success';
                    $_SESSION['flashdata']['type'] = 'success';
                    $_SESSION['flashdata']['msg'] = 'Cashier successfully deleted.';
                } else {
                    $resp['status'] = 'failed';
                    $resp['error'] = $this->lastErrorMsg();
                }
            }
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->lastErrorMsg();
        }

        return json_encode($resp);
    }
    function save_queue()
    {
        $code = sprintf("%'.05d", 1);
        while (true) {
            $chk = $this->query("SELECT count(queue_id) `count` FROM `queue_list` where queue = '" . $code . "' and date(date_created) = '" . date('Y-m-d') . "' ")->fetchArray()['count'];
            if ($chk > 0) {
                $code = sprintf("%'.05d", abs($code) + 1);

            } else {
                break;
            }
        }
        $_POST['queue'] = $code;
        extract($_POST);
        $sql = "INSERT INTO `queue_list` (`queue`, `customer_name`, `date_created`) VALUES ('{$queue}', '{$customer_name}', datetime('now', '+8 hours'))";
        $save = $this->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $resp['id'] = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }

    // enrollment
    function save_enrollment()
    {
        $code = sprintf("%'.05d", 1);
        while (true) {
            $chk = $this->query("SELECT count(queue_id) `count` FROM `enrollment` where queue = '" . $code . "' and date(date_created) = '" . date('Y-m-d') . "' ")->fetchArray()['count'];
            if ($chk > 0) {
                $code = sprintf("%'.05d", abs($code) + 1);

            } else {
                break;
            }
        }
        $_POST['queue'] = $code;
        extract($_POST);
        $sql = "INSERT INTO `enrollment` (`queue`, `customer_name`, `date_created`) VALUES ('{$queue}', '{$customer_name}', datetime('now', '+8 hours'))";
        $save = $this->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $resp['id'] = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    // end enrollment

    // medicine
    function save_medicine()
    {
        $code = sprintf("%'.05d", 1);
        while (true) {
            $chk = $this->query("SELECT count(queue_id) `count` FROM `medicine` where queue = '" . $code . "' and date(date_created) = '" . date('Y-m-d') . "' ")->fetchArray()['count'];
            if ($chk > 0) {
                $code = sprintf("%'.05d", abs($code) + 1);

            } else {
                break;
            }
        }
        $_POST['queue'] = $code;
        extract($_POST);
        $sql = "INSERT INTO `medicine` (`queue`, `customer_name`, `date_created`) VALUES ('{$queue}', '{$customer_name}', datetime('now', '+8 hours'))";
        $save = $this->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $resp['id'] = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    // end medicine


    function get_queue()
    {
        extract($_POST);
        $qry = $this->query("SELECT * FROM `queue_list` where queue_id = '{$qid}' ");
        @$res = $qry->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $resp['queue'] = $res['queue'];
            $resp['name'] = $res['customer_name'];
        } else {
            $resp['queue'] = "";
            $resp['name'] = "";
        }
        return json_encode($resp);
    }
    function next_queue()
    {
        extract($_POST);
        $get = $this->query("SELECT queue_id,`queue`,customer_name FROM `queue_list` where status = 2 and date(date_created) = '" . date("Y-m-d") . "' order by queue_id asc  limit 1");
        @$res = $get->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $this->query("UPDATE `queue_list` SET status = 1, cashier_id = '{$_SESSION['cashier_id']}', teller_id = '{$_SESSION['teller_id']}' WHERE queue_id = '{$res['queue_id']}'");
            $resp['data'] = $res;
        } else {
            $resp['data'] = $res;
        }

        return json_encode($resp);
    }

    function save_queue_liv()
    {
        $code = sprintf("%'.05d", 1);
        while (true) {
            $chk = $this->query("SELECT count(queue_id) `count` FROM `queue_list_liv` where queue = '" . $code . "' and date(date_created) = '" . date('Y-m-d') . "' ")->fetchArray()['count'];
            if ($chk > 0) {
                $code = sprintf("%'.05d", abs($code) + 1);

            } else {
                break;
            }
        }
        $_POST['queue'] = $code;
        extract($_POST);
        $sql = "INSERT INTO `queue_list_liv` (`queue`, `customer_name`, `date_created`) VALUES ('{$queue}', '{$customer_name}', datetime('now', '+8 hours'))";
        $save = $this->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $resp['id'] = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_queue_sa()
    {
        $code = sprintf("%'.05d", 1);
        while (true) {
            $chk = $this->query("SELECT count(queue_id) `count` FROM `queue_list_sa` where queue = '" . $code . "' and date(date_created) = '" . date('Y-m-d') . "' ")->fetchArray()['count'];
            if ($chk > 0) {
                $code = sprintf("%'.05d", abs($code) + 1);

            } else {
                break;
            }
        }
        $_POST['queue'] = $code;
        extract($_POST);
        $sql = "INSERT INTO `queue_list_sa` (`queue`, `customer_name`, `date_created`) VALUES ('{$queue}', '{$customer_name}', datetime('now', '+8 hours'))";
        $save = $this->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $resp['id'] = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occured. Error: " . $this->lastErrorMsg();
        }
        return json_encode($resp);
    }

    function get_queue_liv()
    {
        extract($_POST);
        $qry = $this->query("SELECT * FROM `queue_list_liv` where queue_id = '{$qid}' ");
        @$res = $qry->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $resp['queue'] = $res['queue'];
            $resp['name'] = $res['customer_name'];
        } else {
            $resp['queue'] = "";
            $resp['name'] = "";
        }
        return json_encode($resp);
    }
    function get_queue_sa()
    {
        extract($_POST);
        $qry = $this->query("SELECT * FROM `queue_list_sa` where queue_id = '{$qid}' ");
        @$res = $qry->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $resp['queue'] = $res['queue'];
            $resp['name'] = $res['customer_name'];
        } else {
            $resp['queue'] = "";
            $resp['name'] = "";
        }
        return json_encode($resp);
    }
    // enrollment
    function get_enrollment()
    {
        extract($_POST);
        $qry = $this->query("SELECT * FROM `enrollment` where queue_id = '{$qid}' ");
        @$res = $qry->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $resp['queue'] = $res['queue'];
            $resp['name'] = $res['customer_name'];
        } else {
            $resp['queue'] = "";
            $resp['name'] = "";
        }
        return json_encode($resp);
    }
    // end enrollment

    // enrollment
    function get_medicine()
    {
        extract($_POST);
        $qry = $this->query("SELECT * FROM `medicine` where queue_id = '{$qid}' ");
        @$res = $qry->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $resp['queue'] = $res['queue'];
            $resp['name'] = $res['customer_name'];
        } else {
            $resp['queue'] = "";
            $resp['name'] = "";
        }
        return json_encode($resp);
    }
    // end enrollment



    function next_queue_liv()
    {
        extract($_POST);
        $get = $this->query("SELECT queue_id,`queue`,customer_name FROM `queue_list_liv` where status = 2 and date(date_created) = '" . date("Y-m-d") . "' order by queue_id asc  limit 1");
        @$res = $get->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $this->query("UPDATE `queue_list_liv` SET status = 1, cashier_id = '{$_SESSION['cashier_id']}', teller_id = '{$_SESSION['teller_id']}' WHERE queue_id = '{$res['queue_id']}'");
            $resp['data'] = $res;
        } else {
            $resp['data'] = $res;
        }

        return json_encode($resp);
    }

    function next_queue_sa()
    {
        extract($_POST);
        $get = $this->query("SELECT queue_id,`queue`,customer_name FROM `queue_list_sa` where status = 2 and date(date_created) = '" . date("Y-m-d") . "' order by queue_id asc  limit 1");
        @$res = $get->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $this->query("UPDATE `queue_list_sa` SET status = 1, cashier_id = '{$_SESSION['cashier_id']}', teller_id = '{$_SESSION['teller_id']}' WHERE queue_id = '{$res['queue_id']}'");
            $resp['data'] = $res;
        } else {
            $resp['data'] = $res;
        }

        return json_encode($resp);
    }

    // enrollment
    function next_enrollment()
    {
        extract($_POST);
        $get = $this->query("SELECT queue_id,`queue`,customer_name FROM `enrollment` where status = 2 and date(date_created) = '" . date("Y-m-d") . "' order by queue_id asc  limit 1");
        @$res = $get->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $this->query("UPDATE `enrollment` SET status = 1, cashier_id = '{$_SESSION['cashier_id']}', teller_id = '{$_SESSION['teller_id']}' WHERE queue_id = '{$res['queue_id']}'");
            $resp['data'] = $res;
        } else {
            $resp['data'] = $res;
        }

        return json_encode($resp);
    }
    // end enrollment

    // medicine
    function next_medicine()
    {
        extract($_POST);
        $get = $this->query("SELECT queue_id,`queue`,customer_name FROM `medicine` where status = 2 and date(date_created) = '" . date("Y-m-d") . "' order by queue_id asc  limit 1");
        @$res = $get->fetchArray();
        $resp['status'] = 'success';
        if ($res) {
            $this->query("UPDATE `medicine` SET status = 1, cashier_id = '{$_SESSION['cashier_id']}', teller_id = '{$_SESSION['teller_id']}' WHERE queue_id = '{$res['queue_id']}'");
            $resp['data'] = $res;
        } else {
            $resp['data'] = $res;
        }

        return json_encode($resp);
    }
    // end medicine



    function update_video()
    {
        extract($_FILES);
        $mime = mime_content_type($vid['tmp_name']);
        if (strstr($mime, 'video/') > -1) {
            $move = move_uploaded_file($vid['tmp_name'], "./video/" . (time()) . "_{$vid['name']}");
            if ($move) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Video was successfully updated.';
                if (is_file('./video/' . $_POST['video']))
                    unlink('./video/' . $_POST['video']);
            } else {
                $resp['status'] = 'false';
                $resp['msg'] = 'Unable to upload the video.';
            }
        } else {
            $resp['status'] = 'false';
            $resp['msg'] = 'Invalid video type.';
        }
        return json_encode($resp);
    }
    function update_image()
    {
        extract($_FILES);
        $mime = mime_content_type($img['tmp_name']);
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif']; // Add more allowed image types as needed

        if (in_array($mime, $allowedImageTypes)) {
            $move = move_uploaded_file($img['tmp_name'], "./images/" . (time()) . "_{$img['name']}");
            if ($move) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Image was successfully updated.';
                if (is_file('./images/' . $_POST['image'])) {
                    unlink('./images/' . $_POST['image']);
                }
            } else {
                $resp['status'] = 'false';
                $resp['msg'] = 'Unable to upload the image.';
            }
        } else {
            $resp['status'] = 'false';
            $resp['msg'] = 'Invalid image type.';
        }

        return json_encode($resp);
    }
    function update_text()
    {
        $text_input = isset($_POST['text_input']) ? $_POST['text_input'] : "";

        // You can add validation or processing logic for the text input here
        $text_input = strtoupper($text_input);

        if (!empty($text_input)) {
            // Specify the file path where you want to store the text
            $file_path = './text/text_content.txt';

            // Write the user text input to the file
            if (file_put_contents($file_path, $text_input)) {
                // Text was successfully updated in the file.
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Text was successfully updated.';
            } else {
                $resp['status'] = 'false';
                $resp['msg'] = 'Unable to update the text.';
            }
        } else {
            $resp['status'] = 'false';
            $resp['msg'] = 'Text input was empty.';
        }

        return json_encode($resp);
    }
    function update_navcolor()
    {
        $text_input = isset($_POST['text_input']) ? $_POST['text_input'] : "";

        // You can add validation or processing logic for the text input here
        $text_input = strtoupper($text_input);

        if (!empty($text_input)) {
            // Specify the file path where you want to store the text
            $file_path = './text/text_navcolor.txt';

            // Write the user text input to the file
            if (file_put_contents($file_path, $text_input)) {
                // Text was successfully updated in the file.
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Text was successfully updated.';
            } else {
                $resp['status'] = 'false';
                $resp['msg'] = 'Unable to update.';
            }
        } else {
            $resp['status'] = 'false';
            $resp['msg'] = 'Input was empty.';
        }

        return json_encode($resp);
    }
    function update_fontcolor()
    {
        $text_input = isset($_POST['text_input']) ? $_POST['text_input'] : "";

        // You can add validation or processing logic for the text input here
        $text_input = strtoupper($text_input);

        if (!empty($text_input)) {
            // Specify the file path where you want to store the text
            $file_path = './text/text_fontcolor.txt';

            // Write the user text input to the file
            if (file_put_contents($file_path, $text_input)) {
                // Text was successfully updated in the file.
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Text was successfully updated.';
            } else {
                $resp['status'] = 'false';
                $resp['msg'] = 'Unable to update.';
            }
        } else {
            $resp['status'] = 'false';
            $resp['msg'] = 'Input was empty.';
        }

        return json_encode($resp);
    }


}
$a = isset($_GET['a']) ? $_GET['a'] : '';
$action = new Actions();
switch ($a) {
    case 'login':
        echo $action->login();
        break;
    case 'c_login':
        echo $action->c_login();
        break;
    case 't_login':
        echo $action->t_login();
        break;
    case 'logout':
        echo $action->logout();
        break;
    case 'c_logout':
        echo $action->c_logout();
        break;
    case 'c_logout_liv':
        echo $action->c_logout_liv();
        break;
    case 'c_logout_sa':
        echo $action->c_logout_sa();
        break;
    case 'c_logout_enrollment':
        echo $action->c_logout_enrollment();
        break;
    case 'c_logout_medicine':
        echo $action->c_logout_medicine();
        break;
    case 'c_logout_transaction':
        echo $action->c_logout_transaction();
        break;
    case 'update_Acredentials':
        echo $action->update_Acredentials();
        break;
    case 'update_Ccredentials':
        echo $action->update_Ccredentials();
        break;
    case 'check':
        echo $action->check();
        break;
    case 'checkAdmin':
        echo $action->checkAdmin();
        break;
    case 'update_credentials':
        echo $action->update_credentials();
        break;
    case 'save_cashier':
        echo $action->save_cashier();
        break;
    case 'delete_cashier':
        echo $action->delete_cashier();
        break;
    case 'save_queue':
        echo $action->save_queue();
        break;
    case 'get_queue':
        echo $action->get_queue();
        break;
    case 'next_queue':
        echo $action->next_queue();
        break;
    case 'save_queue_liv':
        echo $action->save_queue_liv();
        break;
    case 'save_queue_sa':
        echo $action->save_queue_sa();
        break;
    case 'get_queue_liv':
        echo $action->get_queue_liv();
        break;
    case 'save_enrollment':
        echo $action->save_enrollment();
        break;
    case 'get_enrollment':
        echo $action->get_enrollment();
        break;
    case 'save_medicine':
        echo $action->save_medicine();
        break;
    case 'get_medicine':
        echo $action->get_medicine();
        break;
    case 'get_queue_sa':
        echo $action->get_queue_sa();
        break;
    case 'next_queue_liv':
        echo $action->next_queue_liv();
        break;
    case 'next_queue_sa':
        echo $action->next_queue_sa();
        break;
    case 'next_enrollment':
        echo $action->next_enrollment();
        break;
    case 'next_medicine':
        echo $action->next_medicine();
        break;
    case 'update_video':
        echo $action->update_video();
        break;
    case 'update_image':
        echo $action->update_image();
        break;
    case 'update_text':
        echo $action->update_text();
        break;
    case 'update_navcolor':
        echo $action->update_navcolor();
        break;
    case 'update_fontcolor':
        echo $action->update_fontcolor();
        break;
    default:
        // default action here
        break;
}