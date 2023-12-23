<?php
require_once("./../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `cashier_list` where cashier_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="cashier-form">
    <input type="hidden" name="id" value="<?php echo isset($cashier_id) ? $cashier_id : '' ?>">
    <div class="form-group">
            <label for="username" class="control-label">Employee #</label>
            <input type="text" name="username" id="username" required class="form-control form-control-sm rounded-0" value="<?php echo isset($username) ? $username : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Last Name</label>
            <input type="text" name="lastname" id="lastname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($lastname) ? $lastname : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">First Name</label>
            <input type="text" name="firstname" id="firstname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($firstname) ? $firstname : '' ?>">
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Middle Initial</label>
            <input type="text" name="MI" id="MI" required class="form-control form-control-sm rounded-0" maxlength = "1" value="<?php echo isset($MI) ? $MI : '' ?>">
        </div>
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email_address" class="control-label">Email Address</label>
            <input type="text" name="email_address" id="email_address" required class="form-control form-control-sm rounded-0"  value="<?php echo isset($email_address) ? $email_address : '' ?>">
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <input type="text" name="old_password" autofocus id="old_password" required class="form-control form-control-sm rounded-0" value="<?php echo isset($password) ? $password : '' ?>">
        </div>

        <div class="form-group">
            <label for="password" class="control-label">NewPassword</label>
            <input type="text" name="password" id="password" required class="form-control form-control-sm rounded-0" value="">
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#cashier-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./Actions.php?a=update_Ccredentials',
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
                        if("<?php echo isset($cashier_id) ?>" != 1)
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