<?php
// Load the database configuration file
require_once("./../DBConnection.php");



// Fetch records from database 
$query = $conn->query("
    SELECT ql.*, c.*, t.*, s.*, g.*
    FROM `queue_list` ql
    JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id
    JOIN `teller_list` t ON ql.teller_id = t.teller_id
    LEFT JOIN `student_list` s ON ql.student_id = s.student_id
    LEFT JOIN `guest_list` g ON ql.guest_id = g.guest_id
    ORDER BY ql.date_created ASC
"); 

if($query->fetchArray()){ 
    $delimiter = ","; 
    $filename = "cashier-logs-data_" . date('Y-m-d') . ".csv"; 

    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 

    // Set column headers 
   // $fields = array('Cashiers Name', 'Terminal Used', 'Queue Served', 'DATE SERVED', 'CLIENT TYPE', 'CLIENT NAME'); 
    $fields = array('Cashiers Name', 'Terminal Used', 'Queue Served', 'DATE SERVED'); 
    fputcsv($f, $fields, $delimiter); 

    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query ->fetchArray(SQLITE3_ASSOC)){ 
        $clientType = '';
        $clientName = '';

        if(!empty($row['student_id'])){
            $clientType = 'STUDENT';
            $clientName = $row['student_id'];
        }
        elseif(!empty($row['guest_id'])){
            $clientType = 'GUEST';
            $clientName = $row['guest_name'];
        }

        $lineData = array(
            $row['lastname'],
            $row['teller_name'],
            $row['queue'],
            $row['date_created'],
            $clientType,
            $clientName
        );
        fputcsv($f, $lineData, $delimiter);
    }

    $totalServed = array();
    
    while($row = $query ->fetchArray(SQLITE3_ASSOC)){ 
        $clientType = '';
        $clientName = '';
        
        if(!empty($row['student_id'])){
            $clientType = 'STUDENT';
            $clientName = $row['student_id'];
        }
        elseif(!empty($row['guest_id'])){
            $clientType = 'GUEST';
            $clientName = $row['guest_name'];
        }
        
        // Increment the total served counter for the current cashier
        $cashierName = $row['lastname'];
        if(!isset($totalServed[$cashierName])) {
            $totalServed[$cashierName] = 0;
        }
        $totalServed[$cashierName]++;

        $lineData = array(
            $cashierName,
            $row['teller_name'],
            $row['queue'],
            $row['date_created'],
            $clientType,
            $clientName
        );
        fputcsv($f, $lineData, $delimiter);
    }
    
    fputcsv($f, array(''), $delimiter);
    fputcsv($f, array(''), $delimiter);

    // Add a row for the total served queues for each cashier
    $fields = array('Cashiers Name', 'Total Served Queue');
    fputcsv($f, $fields, $delimiter);
    
    foreach($totalServed as $cashierName => $count) {
        $lineData = array(
            $cashierName,
            $count
        );
        fputcsv($f, $lineData, $delimiter);
    }

  
    fseek($f, 0); 
    
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 

    fpassthru($f); 
}


//to delete all logs just uncomment
$conn->query("DELETE FROM `queue_list`");

exit; 
?>
