<h3><center>Welcome to Cashier Queuing System</center></h3>
<hr>

<!-- Tabbed navigation -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" id="video-tab" data-toggle="tab" href="#video-settings">Video Settings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo-settings">Logo Settings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sName-tab" data-toggle="tab" href="#sName-settings">Name Settings</a>
    </li>
</ul>

<!-- Content sections for each setting -->
<div class="tab-content">
    <div class="tab-pane fade show active" id="video-settings">
        <!-- Video settings content goes here -->
        <div class="col-12">
            <div class="col-md-12">
                <form action="" id="upload-form">
                    <input type="hidden" name="video" value="<?php echo $video; ?>">
                    <div class="row justify-content-center my-2">
                        <div class="form-group col-md-10">
                            <label for="vid" class="control-label">Update Video</label>
                            <input type="file" name="vid" id="vid" class="form-control" accept="video/*" required>
                        </div>
                    </div>
                    <div class="row justify-content-center my-2">
                        <center>
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Video Name</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="video-list">
                    <!-- Video list will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" id="videoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Video Preview</h5>
                    <button type="button" id="closedVideoButton" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video controls id="previewVideo" style="width: 100%; height: auto;"></video>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <div class="tab-pane fade" id="logo-settings">
        <!-- Logo settings content goes here -->
        <div class="col-12">
    <div class="col-md-12">
    <?php 
    $imageFiles = scandir('./../images');
    $image = isset($imageFiles[2]) ? $imageFiles[2] : ""; // Check if index 2 exists before accessing
    if ($image):
    ?>
    <center><img src="./../images/<?php echo $image; ?>" alt="Uploaded Image" style="height: 15%; width: 25%;" class="bg-dark"></center>
    <?php 
    endif; 
    ?>

        <form action="" id="upload-formimage">
            <input type="hidden" name="image" value="<?php echo $image; ?>">
            <div class="row justify-content-center my-2">
                <div class="form-group col-md-4">
                    <label for="img" class="control-label">Update Logo</label>
                    <input type="file" name="img" id="img" class="form-control" accept="image/*" required>
                </div>
            </div>
            <div class="row justify-content-center my-2">
                <center>
                    <button class="btn btn-primary" type="submit">Update</button>
                    

                </center>
            </div>
        </form>
    </div>
</div>
</div>
<!-- insert here -->
<div class="tab-pane fade" id="sName-settings">
        <!-- Logo settings content goes here -->
        <div class="col-md-12">
    <?php 
    $textInput = isset($_POST['text_input']) ? $_POST['text_input'] : ""; // Check if text input is submitted
    ?>

    <form action="" id="upload-formtext">
        <input type="hidden" name="text_input" value="<?php echo $textInput; ?>">
        <div class="row justify-content-center my-2">
            <div class="form-group col-md-4">
                <label for="text_input" class="control-label">Enter Text</label>
                <input type="text" name="text_input" id="text_input" class="form-control" required>
            </div>
        </div>
        <div class="row justify-content-center my-2">
            <center>
                <button class="btn btn-primary" type="submit">Submit Text</button>
            </center>
        </div>
    </form>
</div>
</div>

<!-- insert here -->
<div class="tab-pane fade" id="sName-settings">
        <!-- Logo settings content goes here -->
        <div class="col-12">
            <div class="col-md-12">
                <!-- Add your logo settings content here -->
                <p>School Name Settings Here</p>
            </div>
        </div>
    </div>
</div>

<!-- insert here -->


<script>
    // video
        $(function(){
        $('#upload-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
            _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Uploading video(s)...')

            $.ajax({
                url: './../Actions.php?a=update_video', 
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred. Please refresh the page.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Upload Video')
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        location.reload()
                        // Clear the input field after successful upload
                        _this.get(0).reset();
                        // Update the video list
                        updateVideoList();
                    } else {
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Upload Video')
                }
            });
        });

        
        function updateVideoList() {
            $.ajax({
                url: '../administrator/get_videos.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var videoList = $('#video-list');
                    videoList.empty();
                    $.each(data, function(index, video) {
                        var row = $('<tr>');
                        row.append('<td>' + video.name + '</td>');
                        row.append('<td><button class="btn btn-danger delete-video" data-id="' + video.id + '">Delete</button></td>');
                        row.append('<td><button class="btn btn-success view-video" data-id="' + video.id + '">View</button></td>');
                        videoList.append(row);
                    });
                }
            });
        }

        updateVideoList();

        
        $('#video-list').on('click', '.delete-video', function() {
            var videoId = $(this).data('id');
            var confirmDeletion = confirm("Are you sure you want to delete this video?");
            if (confirmDeletion) {
            $.ajax({
                url: '../administrator/delete_video.php?id=' + videoId, 
                method: 'DELETE',
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        updateVideoList();
                    } else {
                        console.error('Video deletion failed: ' + resp.msg);
                    }
                }
            });
        }
        });
    });
        $('#video-list').on('click', '.view-video', function() {
            var videoName = $(this).closest('tr').find('td:first').text();
            var videoPath = './../video/' + videoName;
            $('#previewVideo').attr('src', videoPath);
            $('#videoModal').modal('show');
        });

        $('#videoModal').on('hidden.bs.modal', function() {
            $('#previewVideo').get(0).pause();
            $('#videoModal').modal('hide');
            });

        $(document).ready(function() {
            $('#closedVideoButton').on('click', function() {
                $('#previewVideo').get(0).pause();
                $('#videoModal').modal('hide');
            });
        });

        // image
        $(function(){
        $('#upload-formimage').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled', true)
            _this.find('button[type="submit"]').text('Uploading image...')
            
            
            $.ajax({
                url: './../Actions.php?a=update_image',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred. Please refresh the page.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled', false)
                    _this.find('button[type="submit"]').text('Upload Image')
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        location.reload()
                        if("<?php echo isset($department_id) ?>" != 1)
                            _this.get(0).reset();
                    } else {
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled', false)
                    _this.find('button[type="submit"]').text('Upload Image')
                }
            })
        })
    })
    
    // tab
    $(document).ready(function() {
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
        });
    });    
    $('#upload-form').submit(function(e) {
        e.preventDefault();        
    });



    </script>
