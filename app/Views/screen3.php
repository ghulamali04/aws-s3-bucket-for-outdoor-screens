<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Samsung Outdoor Screen</title>
    <link rel="stylesheet" href="<?php echo base_url('public'); ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public'); ?>/assets/css/style.css">
</head>

<body>
    <main class="py-5 container-fluid main-container">
        <div class="row mx-0 mx-xxl-5 mx-md-5">
            <div class="col-12 col-md-12">
                <h1 class="text-center samsung-sharp-bold event-name"><?php if (@$slot != ' ') {
                                                                            echo date('ha', strtotime(@$slot->start_time)) . ' ' . @$slot->title;
                                                                        } ?></h1>
            </div>
        </div>
        <div class="row my-5 mx-0 mx-xxl-5 mx-md-5">
            <div class="col-xl-12 justify-content-center text-cennter d-flex">
                <img src="<?php if (@$slot != '') {
                                echo 'https://s3.us-east-1.amazonaws.com/turtletransit-schedule/' . $slot->image;
                            } ?>" class="img-fluid event-file">
            </div>
        </div>
        <div class="row mx-0 mx-xxl-5 mx-md-5 px-0 px-xxl-5 px-md-5">
            <div class="col-12 col-md-12">
                <h1 class="text-center samsung-sharp-bold mx-0 mx-xxl-5 mx-md-5 px-0 px-xxl-5 px-md-5 event-content"><?php if (@$slot != '') {
                                                                                                                            echo @$slot->subtitle;
                                                                                                                        } ?></h1>
            </div>
        </div>
    </main>
    <script src="<?php echo base_url('public'); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('public'); ?>/assets/js/jquery-3.6.0.min.js"></script>
    <?php 
    $start_time = "";
    if (@$slot != ' ') {
        $start_time = date('ha', strtotime(@$slot->start_time));
    }  
    ?>
    <script>
        var old_time = "<?= $start_time; ?>"
        var intervalId = window.setInterval(function() {
            if (old_time != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/screen/screen3/update',
                    dataType: "JSON",
                    data: {
                        "user": '<?= $user ?>'
                    }
                }).done((response) => {
                    if (response.time != '' && old_time != response.time) {
                        if (response.title != '') {
                            $(".event-name").html(response.title).hide().fadeIn(4000);
                        }
                        if (response.subtitle != '') {
                            $(".event-content").html(response.subtitle).hide().fadeIn(4000);
                        }
                        if (response.image != '') {
                            $(".event-file").attr('src', response.image).hide().fadeIn(4000);
                        }
                        old_time = response.time;
                    }
                });
            }
        }, 5000);
    </script>
</body>

</html>