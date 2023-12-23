<?php
// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Members data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>
<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
    <div class="col-xs-12">
    <div class="alert <?php echo $statusType; ?>">
        
        <?php echo $statusMsg; ?>
        <span class="closebtn" style="float: right; margin-left: 10px; cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
        
    </div>
</div>
<?php } ?>


<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Students List</h3>

        <div class="card-tools align-middle">
                <div class="input-group col-md-12" id="search">
                    <input type="text" class="form-control" placeholder="Search here..." name="keyword" required="required"/>
                    <label>&nbsp&nbsp</label>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" name="search" onclick="searchCashier()"><span class="search"></span>Search</button>
                    </span>
                </div>
        </div>
        <div class="card-tools align-middle">
            <div class="col-md-12 head">
        <div class="float-right">
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
        </div>
    </div>
    </div>
    <!-- CSV file upload form -->
    <div class="card-tools align-middle">
    <div class="col-md-12" id="importFrm" style="display: none;">
        <form action="import_data_student.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
        </form>
    </div>
        </div>

        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button>
        </div>
        <div class="card-tools align-middle">
                <div class="input-group col-md-12" id="search">
                <h5>Template: </h5>
                <label>&nbsp&nbsp</label>
                <a href="./../download.php?file=templates/template_student.csv">Download</a>
                </div>
        </div>
    </div>

    <?php
$sql = "SELECT COUNT(*) FROM `student_list`";
$total_results = $conn->querySingle($sql);


$limit = 20; 

$total_pages = ceil($total_results / $limit);

$page = isset($_GET['page_num']) ? $_GET['page_num'] : 1; // current page, default to 1
$start = ($page - 1) * $limit; // starting index for the query

 // total number of results

$sql = "SELECT * FROM `student_list` ORDER BY `student_LN` ASC LIMIT $start, $limit";
$qry = $conn->query($sql);
$i = $start + 1;

 // total number of pages
$prev_page = $page - 1; // previous page
$next_page = $page + 1; // next page

?>

    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
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
                    <th class="text-center p-0">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_id'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_LN'].", ".$row['student_FN']." ".$row['student_MI']."."  ?></td>
                    <td class="py-0 px-1"><?php echo $row['student_email'] ?></td>
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['student_id'] ?>' href="javascript:void(0)">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['student_id'] ?>' data-name = '<?php echo $row['student_LN'].", ".$row['student_FN']." ".$row['student_MI']."."  ?>' href="javascript:void(0)">Delete</a></li>
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
    </div>
</div>

<div>
<label></label>
</div>
<nav>
    <ul class="pagination justify-content-center">
        <?php if($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page=students&page_num=<?php echo $prev_page ?>">Previous</a></li>
        <?php endif; ?>
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php if($page == $i) echo 'active'; ?>"><a class="page-link" href="?page=students&page_num=<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php endfor; ?>
        <?php if($page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?page=students&page_num=<?php echo $next_page ?>">Next</a></li>
        <?php endif; ?>
    </ul>
</nav>
<script>

<!-- Show/hide CSV upload form -->

function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}

    $(function(){
        $('#create_new').click(function(){
            uni_modal('Add New Student',"./manage_student.php")
        })
        $('.edit_data').click(function(){
            uni_modal('Edit Cashier Details',"./manage_edit_student.php?id="+$(this).attr('data-id'))
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from list?",'delete_data',[JSON.stringify($(this).attr('data-id'))])

        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_student',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>')
                    el.addClass('alert alert-danger pop-msg')
                    el.text(resp.msg)
                    el.hide()
                    $('#confirm_modal .modal-body').prepend(el)
                    el.show('slow')
                }else{
                    alert("An error occurred.")
                }
                $('#confirm_modal button').attr('disabled',false)

            }
        })
    }

    function searchCashier() {

var search = $('#search input').val()

$.ajax({
   url: "./search_alldata_student.php",
   type: "POST",
   data: {
      search: $('#search input').val()
   },
         beforeSend:function(){
           $(".card-body").html("<span>Searching</span>");
         },
         success:function(data){
           $(".card-body").html(data);
         },
         error:function(jqXHR, textStatus, errorThrown){
           $(".card-body").html("<span>Error: " + textStatus + "</span>");
         }

});
}
</script>