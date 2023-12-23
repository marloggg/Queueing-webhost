
<?php
session_start();
	require_once("./../DBConnection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

    <link rel="stylesheet" href="./../Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="./../css/bootstrap.css">
    <link rel="stylesheet" href="./../select2/css/select2.min.css">

    <script src="./../js/jquery-3.6.0.min.js"></script>
    <script src="./../js/popper.min.js"></script>
   <script src="./../js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" href="./../DataTables/datatables.min.css">
    <script src="./../DataTables/datatables.min.js"></script>
    <script src="./../Font-Awesome-master/js/all.min.js"></script>
    <script src="./../select2/js/select2.min.js"></script>
    <script src="./../js/script.js"></script>





    <style>
        :root{
            --bs-success-rgb:71, 222, 152 !important;
        }
        html,body{
          margin: 0.25in;  
        }
        .thumbnail-img{
            width:50px;
            height:50px;
            margin:2px
        }
        .truncate-1 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        .truncate-3 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .modal-dialog.large {
            width: 80% !important;
            max-width: unset;
        }
        .modal-dialog.mid-large {
            width: 50% !important;
            max-width: unset;
        }
        @media (max-width:720px){
            
            .modal-dialog.large {
                width: 100% !important;
                max-width: unset;
            }
            .modal-dialog.mid-large {
                width: 100% !important;
                max-width: unset;
            }  
        }
        .display-select-image{
            width:60px;
            height:60px;
            margin:2px
        }
        img.display-image {
            width: 100%;
            height: 45vh;
            object-fit: cover;
            background: black;
        }
        /* width */
        ::-webkit-scrollbar {
        width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        background: #f1f1f1; 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #555; 
        }
        .img-del-btn{
            right: 2px;
            top: -3px;
        }
        .img-del-btn>.btn{
            font-size: 10px;
            padding: 0px 2px !important;
        }
	
		.table {
			width: 100%;
			margin-bottom: 20px;
		}	
 
		.table-striped tbody > tr:nth-child(odd) > td,
		.table-striped tbody > tr:nth-child(odd) > th {
			background-color: #f9f9f9;
		}
 
		@media print{
			#print {
				display:none;
			}
		}
		@media print {
			#PrintButton {
				display: none;
			}
		}
 
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
        
	</style>
	</head>
<body>

    <?php

$sql = "SELECT ql.*, c.*, t.* FROM `queue_list` ql JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id JOIN `teller_list` t ON ql.teller_id = t.teller_id ORDER BY ql.date_created";
$qry = $conn->query($sql);

$count = 0; // Initialize counter
$max_rows_per_card = 25; // Set maximum rows per card-body

while($row = $qry->fetchArray()) {
  if ($count % $max_rows_per_card == 0) { // Create new card-body every $max_rows_per_card rows
    
   echo ' <center><img src="./swu-phinma.png" alt="SWU Phinma Logo" width="500" height="100"></center>';
   echo '   <br /> ';
   echo '    <center><h2>QUEUING REPORT</h2></center>';
   echo '    <br />';
   echo '   <b style="color:blue;">Date Prepared:</b>';
   
   $date = date("Y-m-d", strtotime("+6 HOURS"));
   echo $date;

   echo '    <br />';



    echo '<div class="card-body">';
    echo '<table class="table table-hover table-striped table-bordered">';
    echo '<colgroup><col width="25%"><col width="25%"><col width="25%"><col width="50%"></colgroup>';
    echo '<thead><tr><th class="text-center p-0">Cashier Name</th><th class="text-center p-0">Terminal # Used</th><th class="text-center p-0">Queue Number</th><th class="text-center p-0">Date</th></tr></thead>';
    echo '<tbody>';
  }

  echo '<tr>';
  echo '<td class="text-center py-0 px-1">' . $row['lastname'] . '</td>';
  echo '<td class="text-center py-0 px-1">' . $row['teller_name'] . '</td>';
  echo '<td class="text-center py-0 px-1">' . $row['queue'] . '</td>';
  echo '<td class="text-center py-0 px-1">' . date('Y-m-d', strtotime($row['date_created'])) . '</td>';
  echo '</tr>';

  $count++;

  if ($count % $max_rows_per_card == 0) { // Close current card-body and start a new one
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '<br>';
    echo '<br />';
    
  }
}

if ($count % $max_rows_per_card != 0) { // Close the last card-body if there are remaining rows
  echo '</tbody>';
  echo '</table>';
  echo '</div>';
}

?>

    <?php
$sql = "SELECT c.cashier_id, c.lastname AS cashier_name, COUNT(*) AS queue_count
        FROM queue_list ql
        JOIN cashier_list c ON ql.cashier_id = c.cashier_id
        GROUP BY c.cashier_id";

$qry = $conn->query($sql);
?>

<div class="card-body">
    <table class="table table-hover table-striped table-bordered">
        <colgroup>
            <col width="25%">
            <col width="25%">
        </colgroup>
        <thead>
            <tr>
                <th class="text-center p-0">Cashier Name</th>
                <th class="text-center p-0">Total Queue Number Served</th>   
            </tr>
        </thead>
        <tbody>
            <?php while($row = $qry->fetchArray()): ?>
            <tr>
                <td class="text-center py-0 px-1"><?php echo $row['cashier_name'] ?></td>
                <td class="text-center py-0 px-1"><?php echo $row['queue_count'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
  
                
            </tbody>
        </table>
    </div>

<br>
<?php

    echo 'Prepared by: ';
    echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; 
    echo ' (Administrator)';
?>
<br>
	<center><button id="PrintButton" onclick="PrintPage()">Print</button></center>
</body>


<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
</script>
</html>