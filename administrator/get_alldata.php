<?php
sleep(1);

require_once("./../DBConnection.php");

?>

<table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="25%">
                <col width="25%">
                <col width="25%">
                <col width="50%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">Cashier Name</th>
                    <th class="text-center p-0">Terminal # Used</th>
                    <th class="text-center p-0">Queue Number</th>
                    <th class="text-center p-0">Date</th>   
                </tr>
            </thead>
            <tbody>


            <?php

$cashier = $_POST['cashier'];
$start_date = date('Y-m-d', strtotime($_POST['startDate']));
$end_date = date('Y-m-d', strtotime($_POST['endDate']));

if ($cashier == 0) {
  //  $sql = "SELECT ql.*, c.*, t.*
   //     FROM `queue_list` ql 
     //   JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id 
      //  JOIN `teller_list` t ON ql.teller_id = t.teller_id 
      //  WHERE ql.date_created BETWEEN '$start_date' AND '$end_date'
      //  ORDER BY ql.date_created ASC";


        $sql1 = "SELECT COUNT(*) FROM `queue_list` ql 
        JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id 
        JOIN `teller_list` t ON ql.teller_id = t.teller_id 
        WHERE ql.date_created BETWEEN '$start_date' AND '$end_date'";
            $total_results = $conn->querySingle($sql1);


            $limit = 20; 
            $total_pages = ceil($total_results / $limit);

            $page = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
            $start = ($page - 1) * $limit; 

            $sql = "SELECT ql.*, c.*, t.* FROM `queue_list` ql JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id JOIN `teller_list` t ON ql.teller_id = t.teller_id WHERE ql.date_created BETWEEN '$start_date' AND '$end_date' ORDER BY ql.date_created ASC LIMIT $start, $limit";
            $qry = $conn->query($sql);
            $i = $start + 1;

            $prev_page = $page - 1; 
            $next_page = $page + 1; 



}else{

$sql = "SELECT ql.*, c.*, t.*
        FROM `queue_list` ql 
        JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id 
        JOIN `teller_list` t ON ql.teller_id = t.teller_id 
        WHERE c.lastname = '$cashier'
        AND ql.date_created BETWEEN '$start_date' AND '$end_date'
        ORDER BY ql.date_created ASC";


        $sql1 = "SELECT COUNT(*) FROM `queue_list` ql 
        JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id 
        JOIN `teller_list` t ON ql.teller_id = t.teller_id 
        WHERE c.lastname = '$cashier'
        AND ql.date_created BETWEEN '$start_date' AND '$end_date'";
    $total_results = $conn->querySingle($sql1);


    $limit = 20; 
    $total_pages = ceil($total_results / $limit);

    $page = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
    $start = ($page - 1) * $limit; 

    $sql = "SELECT ql.*, c.*, t.* FROM `queue_list` ql JOIN `cashier_list` c ON ql.cashier_id = c.cashier_id JOIN `teller_list` t ON ql.teller_id = t.teller_id WHERE c.lastname = '$cashier'
    AND ql.date_created BETWEEN '$start_date' AND '$end_date' ORDER BY ql.date_created ASC LIMIT $start, $limit";
    $qry = $conn->query($sql);
    $i = $start + 1;

    $prev_page1 = $page - 1; 
    $next_page1 = $page + 1; 
}

                $qry = $conn->query($sql);

                $i = 1;
                while($row = $qry->fetchArray()):
                ?>
         
                <tr>
                    <td class="text-center py-0 px-1"><?php echo $row['lastname'] ?></td>
                    <td class="text-center py-0 px-1"><?php echo $row['teller_name'] ?></td>
                    <td class="text-center py-0 px-1"><?php echo $row['queue'] ?></td>
                    <td class="text-center py-0 px-1"><?php echo date('Y-m-d', strtotime($row['date_created'])); ?></td>

                  
                </tr>
                <?php
                 endwhile; 
                  ?>
                  <?php if(!$qry->fetchArray()): ?>
                    <tr>
                        <th class="text-center p-0" colspan="7">No data display.</th>
                    </tr>
                <?php endif; ?>
  
                
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=cashiers_logs&file=get_alldata.php&page_num=<?php echo $prev_page1 ?>">Previous</a></li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if($page == $i) echo 'active'; ?>"><a class="page-link" href="?page=cashiers_logs&file=get_alldata.php&page_num=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>
                <?php if($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=cashiers_logs&file=get_alldata.php&page_num=<?php echo $next_page1 ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        