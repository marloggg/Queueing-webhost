<?php
require_once("./../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `student_list` where student_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="student-form">
        <input type="hidden" name="id" value="<?php echo isset($student_id) ? $student_id : '' ?>">
        <div class="form-group">
        <label style="color: red; font-size: 12px;"><b>NOTE: ALL FIELDS ARE REQUIRED</b></label>
        </div>
        <div class="form-group">
            <label></label>
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Student #</label>
            <input type="text" name="student_id" id="student_id" required class="form-control form-control-sm rounded-0" value="<?php echo isset($student_id) ? $student_id : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Last Name</label>
            <input type="text" name="student_LN" id="student_LN" required class="form-control form-control-sm rounded-0" value="<?php echo isset($student_LN) ? $student_LN : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">First Name</label>
            <input type="text" name="student_FN" id="student_FN" required class="form-control form-control-sm rounded-0" value="<?php echo isset($student_FN) ? $student_FN : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Middle Initial</label>
            <input type="text" name="student_MI" id="student_MI" required class="form-control form-control-sm rounded-0" maxlength = "1" value="<?php echo isset($student_MI) ? $student_MI : '' ?>">
        </div>
        <div class="form-group">
            <label for="email_address" class="control-label">Email Address</label>
            <input type="text" name="student_email" autofocus id="student_email" required class="form-control form-control-sm rounded-0" value="<?php echo isset($student_email) ? $student_email : '' ?>">
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#student-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./Actions.php?a=save_student',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($student_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>