<?php
// Load the database configuration file
require_once("./../DBConnection.php");

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $student_id   = $line[0];
                $student_FN  = $line[1];
                $student_LN  = $line[2];
                $student_MI = $line[3];
                $student_email = $line[4];
                $student_course = $line[5];

                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT student_id FROM student_list WHERE student_id = '".$line[1]."'";
                $prevResult = $conn->query($prevQuery);
                
                if($prevResult->fetchArray()){
                    // Update member data in the database
                    $conn->query("UPDATE student_list SET 
                    student_FN = '".$student_FN."', 
                    student_LN = '".$student_LN."', 
                    student_MI = '".$student_MI."', 
                    student_email = '".$student_email."', 
                    student_course = '".$student_course."'
                    WHERE student_id = '".$student_id."'");                
                }else{
                    // Insert member data in the database
                    $conn->query("INSERT INTO student_list (student_id, student_FN, student_LN, student_MI, student_email, student_course) 
                    VALUES ('".$student_id."', '".$student_FN."', '".$student_LN."', '".$student_MI."', '".$student_email."', '".$student_course."')");
   
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '&status=succ';
        }else{
            $qstring = '&status=err';
        }
    }else{
        $qstring = '&status=invalid_file';
    }
}

header("Location: ./?page=students" .$qstring);



