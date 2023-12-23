<style>
    .text-box {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        border-radius: 5px;
    }
</style>

<h3><center>Welcome to SWU Queuing System</center></h3>
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
    <li class="nav-item">
        <a class="nav-link" id="theme-tab" data-toggle="tab" href="#theme-settings">Theme Settings</a>
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
                    <button class="btn btn-primary" type="submit">Upload</button>
                    

                </center>
            </div>
        </form>
    </div>
</div>
</div>
<!-- Name Settings -->
<div class="tab-pane fade" id="sName-settings">
    <div class="col-12">
        <div class="col-md-12">
            <?php
            $file_path = '../text/text_content.txt';
            if (file_exists($file_path)) {
                $storedText = file_get_contents($file_path);
                if (!empty($storedText)) {
                    echo '<center>';
                    echo '<div class="text-box" style="font-size: 40px; font-weight: bold;">';
                    echo '<div style="height: 20%; width: 100%;">' . $storedText . '</div>';
                    echo '</div>';
                    echo '</center>';
                } else {
                    echo '<center><div>No text available.</div></center>';
                }
            } else {
                echo '<center><div>Text file not found.</div></center>';
            }
            ?>

            <form action="" id="upload-formtext">
                <input type="hidden" name="text_input" value="<?php echo $textInput; ?>">
                <div class="row justify-content-center my-2">
                    <div class="form-group col-md-5">
                        <label for="text_input" class="control-label">Enter School name</label>
                        <input type="text" name="text_input" id="text_input" class="form-control" required>
                    </div>
                </div>
                <div class="row justify-content-center my-2">
                    <center>
                        <button class="btn btn-primary" type="submit">Upload School Name</button>
                    </center>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Theme Settings -->
    <div class="tab-pane fade" id="theme-settings">
    <!-- Logo settings content goes here -->
    <div class="col-12">
        <div class="col-md-12">
            <!-- Add your logo settings content here -->
            <p style="font-size: 16px; color: #333;"> <!-- Example inline styles -->
                <?php
                $navColorFilePath = "../text/text_navcolor.txt"; // Relative path to the navigation bar color text file
                $fontColorFilePath = "../text/text_fontcolor.txt"; // Relative path to the font color text file
                $labelStyle = "font-weight: bold;"; // Define label style
                $inputStyle = "width: 60px;"; // Define input style
                $submitButtonStyle = "background-color: #007bff; color: #fff; border: none; padding: 5px 10px;"; // Define submit button style

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['nav_color']) && isset($_POST['font_color'])) {
                        $selectedNavColor = $_POST['nav_color'];
                        $selectedFontColor = $_POST['font_color'];

                        // Save the selected colors to their respective text files
                        file_put_contents($navColorFilePath, $selectedNavColor);
                        file_put_contents($fontColorFilePath, $selectedFontColor);

                        echo "<p>You selected the navigation bar color: <span style='color:$selectedNavColor;'>$selectedNavColor</span></p>";
                        echo "<p>You selected the font color: <span style='color:$selectedFontColor;'>$selectedFontColor</span></p>";
                    } else {
                        echo "<p>No color selected.</p>";
                    }
                }
                ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="nav_color" style="<?php echo $labelStyle; ?>">Select a Color for Navigation Bar:</label>
                    <input type="color" id="nav_color" name="nav_color" style="<?php echo $inputStyle; ?>" value="<?php echo $selectedNavColor; ?>">
                    
                    <br>
                    <br>
                    
                    <label for="font_color" style="<?php echo $labelStyle; ?>">Select a Font Color:</label>
                    <input type="color" id="font_color" name="font_color" style="<?php echo $inputStyle; ?>" value="<?php echo $selectedFontColor; ?>">

                    <br>
                    <br>
                    
                    <input class="btn btn-primary" type="submit" value="Submit" style="<?php echo $submitButtonStyle; ?>">
                </form>
            </p>
        </div>
    </div>
</div>




<!-- insert here -->
<!-- always left one div -->
</div>




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

    //Name
    $(function() {
    $('#upload-formtext').submit(function(e) {
        e.preventDefault();
        $('.pop_msg').remove();
        var _this = $(this);
        var _el = $('<div>').addClass('pop_msg');
        _this.find('button').attr('disabled', true);
        _this.find('button[type="submit"]').text('Submitting text...');

        $.ajax({
            url: './../Actions.php?a=update_text',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                _el.addClass('alert alert-danger');
                _el.text("An error occurred. Please refresh the page.");
                _this.prepend(_el);
                _el.show('slow');
                _this.find('button').attr('disabled', false);
                _this.find('button[type="submit"]').text('Submit Text');
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    // Update the HTML content to display the submitted text
                    $('#uploaded_text').text(resp.storedText);

                    _el.addClass('alert alert-success');
                    location.reload()
                } else {
                    _el.addClass('alert alert-danger');
                }
                _el.text(resp.msg);

                _el.hide();
                _this.prepend(_el);
                _el.show('slow');
                _this.find('button').attr('disabled', false);
                _this.find('button[type="submit"]').text('Submit Text');
            }
        });
    });
});


    
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
