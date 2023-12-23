<?php
require_once('./../DBConnection.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `queuing_start_end` where start_end_id = '{$_GET['id']}'");
        foreach($qry->fetchArray() as $k => $v){
            $$k = $v;
        }
    }
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Other</h3>
    </div>


    <?php
        // Handle form submission
        if (isset($_POST['save_button'])) {
            // Get the selected start and end times from the form
            $start_time = $_POST['Start_time'];
            $end_time = $_POST['End_time'];
            $manual = isset($_POST['Manual']) ? $_POST['Manual'] : 0;
        
            $count_query = "SELECT COUNT(*) AS count FROM queuing_start_end";
            $count_result = $conn->query($count_query);
        
            if ($count_result) {
                $count_row = $count_result->fetchArray(SQLITE3_ASSOC);
                $count = $count_row['count'];
                
                if ($count == 0) {
                    // If there is no record, insert a new one
                    $insert_query = "INSERT INTO queuing_start_end (default_start_time, default_cutoff_time, manual) VALUES ('$start_time', '$end_time', '$manual')";
                    $result = $conn->query($insert_query);
                    
                    if ($result) {
                        echo "Record inserted successfully";
                    } else {
                        echo "Error inserting record: " . $conn->lastErrorMsg();
                    }
                } else {
                    // If there is a record, update it
                    $update_query = "UPDATE queuing_start_end SET default_start_time = '$start_time', default_cutoff_time = '$end_time', manual_cutoff_time = '$manual'";
                    $result = $conn->query($update_query);
                    
                    if ($result) {
                    } else {
                        echo "Error updating record: " . $conn->lastErrorMsg();
                    }
                }
            } else {
                echo "Error checking count of records: " . $conn->lastErrorMsg();
            }
        }
        
        ?>
    <div class="card-body">
    <form method="POST">
            <input type="hidden" name="id" value="<?php echo isset($start_end_id) ? $start_end_id : '' ?>">
            <div class="col-12">
            <center><h3 class="card-title">Set Cut Off Time:</h3> </center>
            <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <center>
                            <label for="time1"><b>DEFAULT</b></label><br>
                            <br>
                            <label for="start_time">Start:</label>
                            <select name="Start_time">
                            <?php 
                            $start_time = strtotime('5:00am');
                            $end_time = strtotime('6:00pm');
                            $interval = 30 * 60; // 30 minutes in seconds

                            $query = "SELECT default_start_time, default_cutoff_time, manual_cutoff_time FROM queuing_start_end";
                            $result = $conn->query($query);
                            $row = $result->fetchArray(SQLITE3_ASSOC);
 
        
        // check if selected value is stored in session variable
            $selected_start_time = $row['default_start_time'];
       
        
        // generate time options
        for ($i = $start_time; $i <= $end_time; $i += $interval) {
            $time = date('g:i A', $i);
            echo "<option value='$time'";
            if ($selected_start_time == $time) {
                echo ' selected';
            }
            echo ">$time</option>";
        }
    ?>
                            </select>

                            <label for="end_time">CutOff:</label>
                            <select name="End_time">
                            <?php 
                                $start_time = strtotime('5:00am');
                                $end_time = strtotime('6:00pm');
                                $interval = 30 * 60; // 30 minutes in seconds

                                $query = "SELECT default_start_time, default_cutoff_time, manual_cutoff_time FROM queuing_start_end";
                                $result = $conn->query($query);
                                $row = $result->fetchArray(SQLITE3_ASSOC);
                        
                            
                                $selected_end_time = $row['default_cutoff_time'];
                                

                                for ($i = $start_time; $i <= $end_time; $i += $interval) {
                                $time = date('g:i A', $i);
                                echo "<option value='$time'";
                                if ($selected_end_time == $time) {
                                    echo ' selected';
                                }
                                echo ">$time</option>";
                                }
                            ?>
                            </select>
                            </center>
                        </div>

                        <div class="col-md-6">
                            
                             <label for="manual_toggle" ><b>MANUAL</b></label><br>
                             <br>
                            <input type="hidden" name="Manual" value="0">
                            <label class="switch">
                        <?php
                        // Set the default value of the manual toggle
                        
                        
                        $query = "SELECT default_start_time, default_cutoff_time, manual_cutoff_time FROM queuing_start_end";
                        $result = $conn->query($query);
                        $row = $result->fetchArray(SQLITE3_ASSOC);
                        
                        $manual_toggle = $row['manual_cutoff_time'];
                    
                        ?>
                        <input type="checkbox" name="Manual" value="1" <?php if ($manual_toggle == 1) echo "checked"; ?>>
                        <span class="slider round"></span>
                    </label>
                    <label><i>(Switching to manual, overrides the deafult settings)</i></label>
                        
                        </div>
                        </div>
                        <br><br><br><br>
                        <div class="row justify-content-center my-2">
                            
                        <center>
                            <button class="btn btn-primary" type="submit" name="save_button">Save</button>
                        </center>
                    </div>
            </div>
        </form>
    </div>
    </div>
</div>
<div>

<label></label>
</div>