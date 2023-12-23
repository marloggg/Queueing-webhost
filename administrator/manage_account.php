<?php
require_once("./../DBConnection.php");
$qry = $conn->query("SELECT * FROM `administrator_list` where admin_id = '{$_SESSION['admin_id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
?>
<h3>Manage Profile</h3>
<hr>
<div class="col-md-6">
<div class="form-group d-flex w-100 justify-content-end">
    <button class="btn btn-sm btn-primary rounded-0 my-1" id="edit-btn">Edit</button>
    <button class="btn btn-sm btn-primary rounded-0 my-1 d-none" id="save-btn">Cancel Edit</button>
</div>
    <form action="" id="user-form">
        <input type="hidden" name="id" value="<?php echo isset($admin_id) ? $admin_id : '' ?>">
        <div class="form-group">
            <label for="username" class="control-label">Employee #</label>
            <input type="text" name="username" id="username" required class="form-control form-control-sm rounded-0" value="<?php echo isset($username) ? $username : '' ?>" disabled>
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Last Name</label>
            <input type="text" name="lastname" id="lastname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($lastname) ? $lastname : '' ?>" disabled>
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">First Name</label>
            <input type="text" name="firstname" id="firstname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($firstname) ? $firstname : '' ?>" disabled>
        </div>
        <div class="form-group">
            <label for="fullname" class="control-label">Middle Initial</label>
            <input type="text" name="MI" id="MI" required class="form-control form-control-sm rounded-0" maxlength = "1" value="<?php echo isset($MI) ? $MI : '' ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email_address" class="control-label">Email Address</label>
            <input type="text" name="email_address" id="email_address" required class="form-control form-control-sm rounded-0" value="<?php echo isset($email_address) ? $email_address : '' ?>" disabled>
        </div>
        <div class="form-group d-flex w-100 justify-content-end">
            <button class="btn btn-sm btn-primary rounded-0 my-1" disabled>Update</button>
        </div>
    </form>

   

<script>
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');
    const form = document.getElementById('user-form');

    editBtn.addEventListener('click', () => {
        // enable all inputs
        Array.from(form.elements).forEach((element) => {
            element.disabled = false;
        });

        // show save button, hide edit button
        saveBtn.classList.remove('d-none');
        editBtn.classList.add('d-none');
    });

    saveBtn.addEventListener('click', () => {
        // disable all inputs
        Array.from(form.elements).forEach((element) => {
            element.disabled = true;
        });

        // show edit button, hide save button
        editBtn.classList.remove('d-none');
        saveBtn.classList.add('d-none');
    });
</script>
</div>

<script>
    $(function(){
        $('#user-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=update_credentials',
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
                            location.reload()
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

<script>
  const editBtn = document.getElementById('edit-btn');
  const userForm = document.getElementById('user-form');
  const formElements = userForm.elements;
  
  function toggleFormDisabledState() {
    for (let i = 0; i < formElements.length; i++) {
      const element = formElements[i];
      if (element.nodeName === 'BUTTON') continue; // exclude the Edit button itself
      element.disabled = !element.disabled;
    }
  }
  
  editBtn.addEventListener('click', () => {
    editable = !editable;
    editBtn.textContent = editable ? 'Cancel' : 'Edit';
    toggleFormDisabledState();
  });
</script>