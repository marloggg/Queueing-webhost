<?php
session_start();
require_once('./../DBConnection.php');

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

    function check()
    {
        extract($_POST);
        $sql = "SELECT * FROM administrator_list where email_address = '{$email}'";
        $qry = $this->query($sql)->fetchArray();

        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid Email Address";
        } else {
            // generate new password
            $new_password = bin2hex(random_bytes(8)); // generate an 8-character random string
            $hashed_password = md5($new_password); // hash the password for security
            // update user account with new password
            $this->exec("UPDATE administrator_list SET password='$hashed_password' WHERE email_address='$email'");
            // send email with new password
            $email_body = "Your new password is: $new_password";
            mail($email, "New password for your account", $email_body);

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
        } else { {
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
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
                                $resp['status'] = "success";
                                if (empty($id))
                                    $resp['msg'] = "Cashier successfully saved.";
                                else
                                    $resp['msg'] = "Cashier successfully updated.";

                                // Add the code to send email here
                                $email_subject = 'New Cashier Account Created';
                                $email_body = '
                                Congratulations! Your new cashier account has been created.
                                
                                Username: ' . $username . '
                                Password: ' . $password . '
                            ';
                                $recipient = $email_address;
                                sendEmail($recipient, $email_subject, $email_body);

                            } else {
                                $resp['status'] = "failed";
                                if (empty($id))
                                    $resp['msg'] = "Saving New Cashier Failed.";
                                else
                                    $resp['msg'] = "Updating Cashier Failed.";
                                $resp['error'] = $this->lastErrorMsg();
                            }
                        }
                    }
                }
            }
        }
        return json_encode($resp);
    }

    function save_teller()
    {
        extract($_POST);
        $columns = array();
        $values = array();
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $columns[] = "`{$k}`";
                $values[] = "'{$v}'";
            }
        }
        if (!empty($columns) && !empty($values)) {
            $cols_join = implode(",", $columns);
            $vals_join = implode(",", $values);
            $sql = "INSERT INTO `teller_list` ({$cols_join}) VALUES ({$vals_join})";
        }
        if (empty($teller_name)) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Please fill all fields.";
        } else {
            @$check = $this->query("SELECT COUNT(teller_id) as count from `teller_list` where `teller_name` = '{$teller_name}'")->fetchArray()['count'];
            if (@$check > 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Terminal already exists.';
            } else {
                @$save = $this->query($sql);

                if ($save) {
                    $resp['status'] = "success";
                    $resp['msg'] = "Terminal successfully saved.";
                } else {
                    $resp['status'] = "failed";
                    $resp['msg'] = "Saving New Terminal Failed. ";
                    $resp['error'] = $this->lastErrorMsg();
                }
            }
        }
        return json_encode($resp);
    }


    function save_guest()
    {
        extract($_POST);
        $columns = array();
        $values = array();
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $columns[] = "`{$k}`";
                $values[] = "'{$v}'";
            }
        }
        if (!empty($columns) && !empty($values)) {
            $cols_join = implode(",", $columns);
            $vals_join = implode(",", $values);
            $sql = "INSERT INTO `guest_list` ({$cols_join}) VALUES ({$vals_join})";
        }
        @$check = $this->query("SELECT COUNT(guest_id) as count from `guest_list` where `guest_id` = '{$guest_id}'")->fetchArray()['count'];
        if (@$check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Guest already exists.';
        } else {
            @$save = $this->query($sql);

            if ($save) {
                $resp['status'] = "success";
                $resp['msg'] = "Guest successfully saved.";
            } else {
                $resp['status'] = "failed";
                $resp['msg'] = "Saving New Guest Failed. ";
                $resp['error'] = $this->lastErrorMsg();
            }
        }

        return json_encode($resp);
    }

    function save_student()
    {
        extract($_POST);
        $columns = array();
        $values = array();
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $columns[] = "`{$k}`";
                $values[] = "'{$v}'";
            }
        }
        if (!empty($columns) && !empty($values)) {
            $cols_join = implode(",", $columns);
            $vals_join = implode(",", $values);
            $sql = "INSERT INTO `student_list` ({$cols_join}) VALUES ({$vals_join})";
        }
        if (empty($student_id) || empty($student_LN) || empty($student_FN) || empty($student_MI) || empty($student_email) || empty($student_course)) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Please fill all fields.";
        } else {
            @$check = $this->query("SELECT COUNT(student_id) as count from `student_list` where `student_id` = '{$student_id}'")->fetchArray()['count'];
            if (@$check > 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Student already exists.';
            } else {
                @$save = $this->query($sql);
                if ($save) {
                    $resp['status'] = "success";
                    $resp['msg'] = "Student successfully saved.";
                } else {
                    $resp['status'] = "failed";
                    $resp['msg'] = "Saving New Student Failed. " . $sql;
                    $resp['msg'] = "Saving New Student Failed. " . $sql . " - Error message: " . $this->lastErrorMsg();
                    $resp['error'] = $this->lastErrorMsg();
                }
            }
        }

        return json_encode($resp);
    }


    function update_teller()
    {
        extract($_POST);

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !empty($v)) {
                if (!empty($data)) {
                    $data .= ",";
                }
                $data .= " `{$k}` = '{$v}' ";
            }
        }

        $sql = "UPDATE `teller_list` SET {$data} WHERE teller_id = '{$_POST['id']}'";
        $save = $this->query($sql);

        if ($save) {
            $resp['status'] = 'success';
            $resp['msg'] = 'Credential successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
            $resp['sql'] = $sql;
        }

        return json_encode($resp);
    }

    function update_guest()
    {
        extract($_POST);

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !empty($v)) {
                if (!empty($data)) {
                    $data .= ",";
                }
                $data .= " `{$k}` = '{$v}' ";
            }
        }

        $sql = "UPDATE `guest_list` SET {$data} WHERE guest_id = '{$_POST['id']}'";
        $save = $this->query($sql);

        if ($save) {
            $resp['status'] = 'success';
            $resp['msg'] = 'Credential successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
            $resp['sql'] = $sql;
        }

        return json_encode($resp);
    }

    function update_student()
    {
        extract($_POST);

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !empty($v)) {
                if (!empty($data)) {
                    $data .= ",";
                }
                $data .= " `{$k}` = '{$v}' ";
            }
        }

        $sql = "UPDATE `student_list` SET {$data} WHERE student_id = '{$_POST['id']}'";
        $save = $this->query($sql);

        if ($save) {
            $resp['status'] = 'success';
            $resp['msg'] = 'Credential successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->lastErrorMsg();
            $resp['sql'] = $sql;
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

    function delete_teller()
    {
        extract($_POST);
        $get = $this->query("SELECT * FROM `teller_list` where teller_id = '{$id}'");
        @$res = $get->fetchArray();
        $is_logged = false;
        if ($res) {
            $is_logged = $res['log_status'] == 1 ? true : false;
            if ($is_logged) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Teller is in use.';
            } else {
                @$delete = $this->query("DELETE FROM `teller_list` where teller_id = '{$id}'");
                if ($delete) {
                    $resp['status'] = 'success';
                    $_SESSION['flashdata']['type'] = 'success';
                    $_SESSION['flashdata']['msg'] = 'Teller successfully deleted.';
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
    function delete_guest()
    {
        extract($_POST);
        $get = $this->query("SELECT * FROM `guest_list` where guest_id = '{$id}'");
        @$res = $get->fetchArray();
        if ($res) {
            @$delete = $this->query("DELETE FROM `guest_list` where guest_id = '{$id}'");
            if ($delete) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Guest successfully deleted.';
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = $this->lastErrorMsg();
            }
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->lastErrorMsg();
        }

        return json_encode($resp);
    }
    function delete_student()
    {
        extract($_POST);
        $get = $this->query("SELECT * FROM `student_list` where student_id = '{$id}'");
        @$res = $get->fetchArray();
        if ($res) {
            @$delete = $this->query("DELETE FROM `student_list` where student_id = '{$id}'");
            if ($delete) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Student successfully deleted.';
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = $this->lastErrorMsg();
            }
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->lastErrorMsg();
        }

        return json_encode($resp);
    }
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
}
$a = isset($_GET['a']) ? $_GET['a'] : '';
$action = new Actions();
switch ($a) {
    case 'login':
        echo $action->login();
        break;
    case 'check':
        echo $action->check();
        break;
    case 'c_login':
        echo $action->c_login();
        break;
    case 'logout':
        echo $action->logout();
        break;
    case 'c_logout':
        echo $action->c_logout();
        break;

    case 'update_Acredentials':
        echo $action->update_Acredentials();
        break;
    case 'update_Ccredentials':
        echo $action->update_Ccredentials();
        break;

    case 'update_credentials':
        echo $action->update_credentials();
        break;
    case 'save_cashier':
        echo $action->save_cashier();
        break;
    case 'save_teller':
        echo $action->save_teller();
        break;
    case 'save_guest':
        echo $action->save_guest();
        break;
    case 'save_student':
        echo $action->save_student();
        break;
    case 'update_teller':
        echo $action->update_teller();
        break;
    case 'update_guest':
        echo $action->update_guest();
        break;
    case 'update_student':
        echo $action->update_student();
        break;
    case 'delete_cashier':
        echo $action->delete_cashier();
        break;
    case 'delete_teller':
        echo $action->delete_teller();
        break;
    case 'delete_guest':
        echo $action->delete_guest();
        break;
    case 'delete_student':
        echo $action->delete_student();
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
    case 'update_video':
        echo $action->update_video();
        break;
    default:
        // default action here
        break;
}