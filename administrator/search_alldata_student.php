<?php
sleep(1);

require_once("./../DBConnection.php");

?>

<table class="table table-hover table-striped table-bordered">
<colgroup>
                <col width="5%">
                <col width="25%">
                <col width="25%">
                <col width="25%">   
                <col width="25%">
                <col width="25%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Student #</th>
                    <th class="text-center p-0">Full Name</th>
                    <th class="text-center p-0">Email Address</th>
                    <th class="text-center p-0">Course</th>
                    <th class="text-center p-0">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 

                $search = $_POST['search'];

                $sql = "SELECT * FROM `student_list` WHERE 
                   `student_LN` LIKE '%$search%' OR 
                    `student_FN` LIKE '%$search%' 
                    ORDER BY `student_LN` ASC";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_id'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_LN'].", ".$row['student_FN']." ".$row['student_MI']."."  ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_email'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_course'] ?></td>
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['student_id'] ?>' href="javascript:void(0)">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['student_id']  ?>' data-name = '<?php echo $row['student_LN'].", ".$row['student_FN']." ".$row['student_MI']."."  ?>' href="javascript:void(0)">Delete</a></li>
                            
                        </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
                <?php if(!$qry->fetchArray()): ?>
                    <tr>
                        <th class="text-center p-0" colspan="7">No data display.</th>
                    </tr>
                    
                <?php endif; ?>
            </tbody>
        </table>
        