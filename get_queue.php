<?php
require_once('./DBConnection.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `queue_list` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `queue_list_liv` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `queue_list_sa` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `enrollment` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `medicine` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container fluid">
    <?php if(isset($_GET['success']) && $_GET['success'] == true): ?>
        <center><div class="alert alert-success">Your Queue Number is successfully generated.</div></center>
    <?php endif; ?>
    <div id="outprint">
        <div class="row justify-content-end">
            <div class="col-12">
                <div class="card border-0 border-0  rounded-0 border-0 border-0">
                    <div class="fs-3 fw-bold text-center">   
                    <center><?php
                    $file_path = './text/text_content.txt';
                    if (file_exists($file_path)) {
                        $storedText = file_get_contents($file_path);
                        if (!empty($storedText)) {
                            echo $storedText ;
                        } else {
                            echo '<center><div>No text available.</div></center>';
                        }
                    } else {
                        echo '<center><div>Text file not found.</div></center>';
                    }
                    ?></center>                
                    <?php 
                    if(isset($_GET['id'])){
                        $label = null;
                        $latestRecord = null;

                        // Query the "RAD" table
                        $qry = $conn->query("SELECT * FROM `queue_list` where queue_id = '$queue_id' ORDER BY date_created DESC LIMIT 1");
                        $row = $qry->fetchArray();
                        if($row){
                            $latestRecord = $row;
                            $label = "SHS";
                        }
                        
                        // Query the "LIVE" table
                        $qry = $conn->query("SELECT * FROM `queue_list_liv` where queue_id = '$queue_id' ORDER BY date_created DESC LIMIT 1");
                        $row = $qry->fetchArray();
                        if($row && (!$latestRecord || $row['date_created'] > $latestRecord['date_created'])){
                            $latestRecord = $row;
                            $label = "F";
                        }
                        
                        // Query the "SA" table
                        $qry = $conn->query("SELECT * FROM `queue_list_sa` where queue_id = '$queue_id' ORDER BY date_created DESC LIMIT 1");
                        $row = $qry->fetchArray();
                        if($row && (!$latestRecord || $row['date_created'] > $latestRecord['date_created'])){
                            $label = "T";
                        }

                        // Query the "enrollment" table
                        $qry = $conn->query("SELECT * FROM `enrollment` where queue_id = '$queue_id' ORDER BY date_created DESC LIMIT 1");
                        $row = $qry->fetchArray();
                        if($row && (!$latestRecord || $row['date_created'] > $latestRecord['date_created'])){
                            $label = "ENR";
                        }

                        // Query the "enrollment" table
                        $qry = $conn->query("SELECT * FROM `medicine` where queue_id = '$queue_id' ORDER BY date_created DESC LIMIT 1");
                        $row = $qry->fetchArray();
                        if($row && (!$latestRecord || $row['date_created'] > $latestRecord['date_created'])){
                            $label = "MED";
                        }
                        
                        echo $label;
                    }
                    
                    ?>-<?php echo $queue ?></div>
                
                    <center><?php echo $customer_name ?></center>
                
                    <center><?php echo $date_created ?></center>
                    
                    <center>Lapse number <b>will not be entertained</b> </center>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-2 mx-0 justify-content-end align-items-center">
    <center>
        <button class="btn btn-success rounded-0 me-2 col-sm-4" id="print" type="button"><i class="fa fa-print"></i> Print</button>
        <button class="btn btn-dark rounded-0 col-sm-4" data-bs-dismiss="modal" type="button"><i class="fa fa-times"></i> Close</button>
        </center>
    </div>
</div>
<script>
    $(function(){ 
        $('#print').click(function(){
                var imagePath = './images/'
        $.ajax({
    url: imagePath,
    success: function (data) {
        // Parse the HTML content to find image file names
        var imageNames = [];
        $(data).find("a:contains('.jpg'),a:contains('.png'),a:contains('.gif')").each(function () {
        var filename = $(this).text();
        imageNames.push(filename);
        });

        // Choose a random image from the list
        var randomIndex = Math.floor(Math.random() * imageNames.length);
        var randomImageName = imageNames[randomIndex];

        // Construct the full path to the randomly selected image
        var fullImagePath = imagePath + randomImageName;

        // Create and display the image
        var _img2 = $('<img>').attr('src', fullImagePath).attr('alt', 'SWU Phinma Logo').attr('width', '50').attr('height', '50');
        $('body').append(_img2);
    
            var _el = $('<div>');
            var _h = $('head').clone();
            var _div = $('<div>').css('text-align', 'center');
            var _img1 = $('<img>').attr('src', './phinma.png').attr('alt', 'SWU Phinma Logo').attr('width', '50').attr('height', '50');
            //var _img2 = $('<img>').attr('src', './swu.png').attr('alt', 'SWU Phinma Logo').attr('width', '50').attr('height', '50');

            _div.append(_img1).append(_img2);
            var _p = $('#outprint').clone();
            _h.find('title').text("Queue Number - Print");
            _el.append(_h);
            _el.append(_div);
            _el.append(_p);
            var nw = window.open('','_blank','width=700,height=500,top=75,left=200');
            nw.document.write(_el.html());
            nw.document.close();
            setTimeout(() => {
                nw.print();
                setTimeout(() => {
                    nw.close();
                    $('#uni_modal').modal('hide');
                }, 200);
            }, 500);
        }
        });
    });
});
</script>

