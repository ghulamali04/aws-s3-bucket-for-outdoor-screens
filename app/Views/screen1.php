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
        <div class="row ms-0 ms-xxl-5 ms-md-5">
            <div class="col-4 col-md-4">
                <img src="<?php echo base_url('public'); ?>/assets/svg/Nightography.svg" class="img-fluid">
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-4">
                <img class="img-fluid" src="<?php echo base_url('public'); ?>/assets/svg/StarStruck.svg">
            </div>
        </div>
        <div class="row mb-2 mb-xxl-5 mb-md-5 pb-0 pb-xxl-5 pb-md-5 mx-xxl-5 mx-md-5 mx-0">
            <div class="col-12 col-md-12 pt-xxl-5 pt-md-5 pt-0  my-xxl-5 my-md-5 my-3">
                <img src="<?php echo base_url('public'); ?>/assets/svg/IntoTheNight.svg" class="img-fluid">
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-4 px-0">
                <img class="img-fluid" src="<?php echo base_url('public'); ?>/assets/svg/CaptureTheNight.svg">
            </div>
        </div>
        <div class="row mb-0 mb-xxl-5 mb-md-5">
            <div class="col-md-12">
                <div class="mt-0 mt-xxl-5 mt-md-5  pt-0 pt-xxl-5 pt-md-5">
                    <?php
                       $active_event = "";
                    if(@$event[0]->id){
                        $range = $event[0]->event_date;
                    if (str_contains(@$range, 'to')) {
                        
                        $ex = explode("to", $range);
                        $start = trim($ex[0]);
                        $end = trim($ex[1]);
                        $m1 = date('m', strtotime($start));
                        $m2 = date('m', strtotime($end));
                        if ($m1 == $m2) {
                            
                            $active_event = date('F', strtotime($start));
                            if (strlen($active_event) > 4) {
                                $active_event = substr($active_event, 0, 3);
                            }
                            $active_event .= " " . date('d', strtotime($start)) . "-" . date('d', strtotime($end));
                        } else {
                            $start = date('F', strtotime($start));
                            $end = date('F', strtotime($end));
                            if (strlen($start) > 4) {
                                $start = substr($start, 0, 3);
                            }
                            if (strlen($end) > 4) {
                                $end = substr($end, 0, 3);
                            }
                            $active_event = $start . " " . date('d', strtotime($start)) . "-" . $end . date('d', strtotime($end));
                        }
                    } else {
                        $active_event = date('F', strtotime(@$range));
                        if (strlen($active_event) > 4) {
                            $active_event = substr($active_event, 0, 3);
                        }
                        $active_event .= " ". date('d', strtotime(@$range));
                    }
                    }
                    ?>
                    <h1 class="samsung-sharp-bold text-center date active-event"><?= $active_event ?></h1>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo base_url('public'); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('public'); ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script>
       

        var intervalId = window.setInterval(function(){
            $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/screen/screen1/update',
                    dataType: "JSON",
                    data: {
                        "user": '<?= $user ?>'
                    }
                }).done((response)=>{
                    $(".active-event").html(response.active_event);
                });
}, 5000);
    </script>
</body>

</html>