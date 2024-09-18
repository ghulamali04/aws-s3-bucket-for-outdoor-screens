<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Samsung Outdoor Screen</title>
    <link rel="stylesheet" href="<?php echo base_url('public'); ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public'); ?>/assets/css/style.css">
    <style>
.row {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display:         flex;
  flex-wrap: wrap;
}
.row > [class*='col-'] {
  display: flex;
  flex-direction: column;
}
.container-event{
    height:  100% !important;
}

.grayed-out{
    background-color: #808080 !important;
}

@media screen and (max-width: 540px) {
    .slot-time{
        font-size: 1.375rem !important;
        padding-bottom: 30px !important;
    }
    .slot-title{
        font-size: 1.375rem !important;
    }
    .event-content{
        font-size: 40pt !important;
    }
}
    </style>
  </head>
  <body>
    <main class="py-5 container-fluid main-container">
        <div class="row">
            <div class="col-12 col-md-12">
                <h1 class="text-center samsung-sharp-bold schedule-head mx-3">Today's</h1>
                <h1 class="text-center samsung-sharp-bold schedule-head mx-3">Schedule</h1>
            </div>
        </div>
        <div class="row mt-5 mb-3 mx-xxl-5 mx-md-5 mx-0 " id="slots">
            <?php
                if($date_slots->getResult()){

                foreach($date_slots->getResult() as $slot){
            ?>
            <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-3">
                <div class="container-event px-2 px-xxl-4 px-md-4 pt-4 pb-1 <?php
                    if((date('H:i') > $slot->start_time && date('H:i') < $slot->end_time) || date('H:i') == $slot->end_time || date('H:i') == $slot->start_time){
                    echo 'event-active ';     
                    }else{
                        if($slot->start_time < date('H:i')){
                            echo ' grayed-out';
                        }
                    }
                ?> ">
                    <div class="mb-5 pb-0 pb-xxl-5 pb-md-5">
                    <h1 class=" samsung-sharp-medium pb-xxl-2 pb-0 mb-0 mb-md-5 pb-md-2 mb-xxl-5 slot-time"><?= date('g', strtotime($slot->start_time)) ?>-<?= date('g a', strtotime($slot->end_time)) ?></h1>
                    </div>
                    <div class="mt-5 pt-0 pt-xxl-5 pt-md-5">
                        <h1 class=" samsung-sharp-bold slot-title"><?= $slot->title ?></h1>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="row mx-0 mx-xxl-5 mx-md-5 px-0 px-xxl-5 px-md-5">
            <div class="col-12 col-md-12">
                <h1 class="text-center samsung-sharp-bold mx-0 mx-xxl-5 mx-md-5 px-0 px-xxl-5 px-md-5 event-content">Open all day <br><?php if(@$event_date[0]){ echo date('g', strtotime(@$event_date[0]->min)); }  ?>-<?php if(@$event_date[0]) { echo date('g a', strtotime(@$event_date[0]->max)); }  ?></h1>
            </div>
        </div>
    </main>
    <script src="<?php echo base_url('public'); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('public'); ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script>
       

       var intervalId = window.setInterval(function(){
           $.ajax({
                   type: 'POST',
                   url: '<?= base_url() ?>/screen/screen2/update',
                   dataType: "JSON",
                   data: {
                       "user": '<?= $user ?>'
                   }
               }).done((response)=>{
                   let slots = response.date_slots;
                   let date = response.event_date;
                   if(slots.length > 0){
                    let html = ``;
                    for(var i=0; i<slots.length;i++){
                        html += `
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-3">
                <div class="container-event px-2 px-xxl-4 px-md-4 pt-4 pb-1
                
                `
                if(('<?= date('H:i') ?>' > slots[i].start_time && '<?= date('H:i') ?>' < slots[i].end_time) || '<?= date('H:i') ?>' == slots[i].start_time || '<?= date('H:i') ?>' == slots[i].end_time){
                    html+= `event-active `;
                }else{
                    if(slots[i].start_time < '<?= date('H:i') ?>'){
                        html+= ` grayed-out`;
                    }
                } 
                html += `">
                    <div class="mb-5 pb-0 pb-xxl-5 pb-md-5">
                    <h1 class=" samsung-sharp-medium pb-xxl-2 pb-0 mb-0 mb-md-5 pb-md-2 mb-xxl-5 slot-time">${tConvertSimple(slots[i].start_time)}-${tConvert(slots[i].end_time)}</h1>    
                    
                    </div>
                    <div class="mt-5 pt-0 pt-xxl-5 pt-md-5">
                    <h1 class=" samsung-sharp-bold slot-title">${slots[i].title}</h1>    
                    </div>
                </div>
            </div>
                        `;
                    }
                    $("#slots").html(html);
                    html = `Open all day <br>${tConvertSimple(date[0].min)}-${tConvert(date[0].max)}`;

                    $(".event-content").html(html);
                   }
               }).fail((response)=>{
//console.log(response)
               })
}, 5000);

function tConvert (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? 'am' : 'pm'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  let str = ''
  if(time[0] <= 9){
    str += time[0];
    }else{
        str += time[0];
    }
    str += ' '+time[5];
  return str; // return adjusted time or original string
}

function tConvertSimple (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? 'am' : 'pm'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  let str = ''
  if(time[0] <= 9){
    str += time[0];
    }else{
        str += time[0];
    }
    //str += ' '+time[5];
  return str; // return adjusted time or original string
}
   </script>
  </body>
</html>