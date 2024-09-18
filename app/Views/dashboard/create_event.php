<?= $this->extend('dashboard/base') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="filepond/dist/filepond.css">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Events</h1>
            <!--end::Title-->
            <!--begin::Separator-->
            <span class="h-20px border-gray-300 border-start mx-4"></span>
            <!--end::Separator-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

                <!--begin::Item-->
                <li class="breadcrumb-item text-dark"><?php if (@$event) {
                                                            echo @$event[0]->event_name;
                                                        } else {
                                                            echo 'Create';
                                                        } ?></li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

        <!--begin::Row-->
        <div class="row gy-5 g-xl-8">
            <?php if (!@$event) { ?>
                <!--begin::Col-->
                <div class="col-xl-12">
                    <form method="POST" id="kt_docs_formvalidation_event" action="<?= base_url() ?>/dashboard/events/save">
                        <!--begin::Tables Widget 9-->
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Create</span>
                                    <span class="text-muted mt-1 fw-bold fs-7"></span>
                                </h3>
                                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">

                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                        <label class="form-label required">Event Name</label>
                                        <input type="text" class="form-control form-control-solid" name="event_name" placeholder="Event Name">
                                    </div>
                                    <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                        <label class="form-label">Timezone</label>
                                        <select id="timezone"  name="timezone" aria-label="Select timezone" data-control="select2" data-placeholder="Select timezone..." class="form-select form-select-solid form-select-lg fw-bold ">
                                        <option value="" disabled selected>Select timezone</option>
                                        <option value="America/Chicago">
                                        America/Chicago
                                        </option>
                                        <option value="America/Denver">
                                        America/Denver
                                        </option>
                                        <option value="America/Phoenix">
                                        America/Phoenix
                                        </option>
                                        <option value="America/Los_Angeles">
                                        America/Los_Angeles
                                        </option>
                                        <option value="America/New_York">America/Washington</option>
                                    </select>
                                    </div>
                                    <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                        <label class="form-label required">Event Date</label>
                                        <input type="text" class="form-control form-control-solid" name="event_date" id="event_date" placeholder="Event Date">
                                    </div>
                                </div>
                            </div>
                            <!--begin::Body-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                <button type="submit" class="btn btn-primary" id="kt_docs_formvalidation_event_btn">Proceed</button>
                            </div>
                        </div>
                        <!--end::Tables Widget 9-->
                    </form>
                </div>

                <!--end::Col-->


            <?php } else {
                $event_dates = array();
                $alread_filled = array();
                if (str_contains(@$event[0]->event_date, 'to')) {
                    $range = explode('to', $event[0]->event_date);
                    $start = trim($range[0]);
                    $end = trim($range[1]);
                    $period = new DatePeriod(
                        new DateTime($start),
                        new DateInterval('P1D'),
                        new DateTime($end . ' 23:59:59')
                    );

                    foreach ($period as $key => $value) {
                        if (!in_array($value->format('Y-m-d'),  $already_saved_dates)) {
                            array_push($event_dates, $value->format('Y-m-d'));
                        }else{
                            array_push($alread_filled, $value->format('Y-m-d'));
                        }
                    }
                } else {
                    array_push($event_dates, $event[0]->event_date);
                }


            ?>

                <!--begin::Col-->
                <div class="col-xl-12">

                    <!---------------------------------------start::Stepper------------------------------------->
                    <!--begin::Stepper-->
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-lg-row" id="kt_stepper_example_vertical">
                        <!--begin::Aside-->
                        <div class="d-flex flex-row-auto w-100 w-lg-300px">
                            <!--begin::Nav-->
                            <div class="stepper-nav flex-cente">
                                <?php

                                $sr = 1;
                                $last = 1;
                                foreach($alread_filled as $date){
                                    ?>

<div class="stepper-item me-5 completed"  data-kt-stepper-element="nav">
                                        <!--begin::Line-->
                                        <div class="stepper-line w-40px"></div>
                                        <!--end::Line-->

                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number"><?= $sr ?></span>
                                        </div>
                                        <!--end::Icon-->

                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">
                                                <?= date('m-d-Y', strtotime($date)) ?>
                                            </h3>

                                            <div class="stepper-desc">
                                            completed
                                            </div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
<?php
$sr++;
$last = $sr;
                                }
                                foreach ($event_dates as $event_date) {
                                ?>
                                    <!--begin::Step 1-->
                                    <div class="stepper-item me-5 <?php if ($sr == $last) {
                                                                        echo 'current';
                                                                    } ?>" data-kt-stepper-element="nav">
                                        <!--begin::Line-->
                                        <div class="stepper-line w-40px"></div>
                                        <!--end::Line-->

                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number"><?= $sr ?></span>
                                        </div>
                                        <!--end::Icon-->

                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">
                                                <?= date('m-d-Y', strtotime($event_date)) ?>
                                            </h3>

                                            <div class="stepper-desc">

                                            </div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Step 1-->
                                <?php $sr++;
                                } ?>

                            </div>
                            <!--end::Nav-->
                        </div>
                        <?php $IDs = array();
                        $lastForm;
                        ?>
                        <!--begin::Content-->
                        <div class="flex-row-fluid">
                            <!--begin::Form-->
                            <div class="form mx-auto" novalidate="novalidate">
                                <!--begin::Group-->
                                <div class="mb-5">
                                    <?php
$sr = 1;
$last = 1;
foreach($alread_filled as $date){
    ?>
<div class="flex-column completed" data-kt-stepper-element="content">

                                                            </div>
    <?php
                                    $sr++;
                                    $last = $sr;
}
                                    foreach ($event_dates as $event_date) {
                                    ?>

                                        <div class="flex-column <?php if ($sr == $last) {
                                                                    echo 'current';
                                                                } ?>" data-kt-stepper-element="content">
                                            <form id="timeslot<?= $sr ?>" class="date-form" action="javascript:;" method="POST" data-id="<?= $sr ?>">
                                                <input type="hidden" name="event_id" value="<?= $event[0]->id ?>">
                                                <input type="hidden" name="event_date" value="<?= $event_date ?>">
                                                <!--begin::Tables Widget 9-->
                                                <div class="card card-xl-stretch mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                                                    <div class="card-header border-0 pt-5">
                                                        <h3 class="card-title align-items-start flex-column">
                                                            <span class="card-label fw-bolder fs-3 mb-1">Event Day: <?= date('m-d-Y', strtotime($event_date)) ?></span>
                                                            <span class="text-muted mt-1 fw-bold fs-7"></span>
                                                        </h3>
                                                        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">

                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Body-->
                                                    <div class="card-body py-3">

                                                        <div class="row">
                                                            <?php
                                                            $id = $sr;
                                                            array_push($IDs, $id);
                                                            ?>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">
                                                                    Open Hours
                                                                    <!--<br>
                                                                    <span class="text-danger" id="min<?= $id ?>"></span>
                                                                    <br>
                                                                    <span class="text-danger" id="max<?= $id ?>"></span>-->
                                                                </label>
                                                                <!--<div class="open_hours_slider" id="kt_slider_basic<?= $id ?>" data-id="<?= $id ?>"></div>-->
                                                                <input type="text" class="form-control form-control-solid start_hour mb-2" id="kt_slider_basic_min<?= $id; ?>" data-id="<?= $id ?>" value="07:00" name="start_hour" placeholder="Start Hour">
                                                                <input type="text" class="form-control form-control-solid end_hour" id="kt_slider_basic_max<?= $id; ?>" data-id="<?= $id ?>" value="23:59" name="end_hour" placeholder="End Hour">
                                                                <!--<input id="kt_slider_basic_min<?= $id; ?>" type="hidden" name="start_hour">
                                                                <input id="kt_slider_basic_max<?= $id; ?>" type="hidden" name="end_hour">-->
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#1
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="1" name="slot1_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="1" name="slot1_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="1" name="slot1_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="1" name="slot1_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>1" slot="1" data-id="<?= $id ?>" id="key<?= $id ?>1">
                                                                <input type="hidden" name="slot1_image" image-id="<?= $id ?>1" slot="1">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#2
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="2" name="slot2_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="2" name="slot2_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="2" name="slot2_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="2" name="slot2_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>2" slot="2" data-id="<?= $id ?>" id="key<?= $id ?>2">
                                                                <input type="hidden" name="slot2_image" image-id="<?= $id ?>2" slot="2">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#3
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="3" name="slot3_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="3" name="slot3_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="3" name="slot3_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="3" name="slot3_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>3" slot="3" data-id="<?= $id ?>" id="key<?= $id ?>3">
                                                                <input type="hidden" name="slot3_image" image-id="<?= $id ?>3" slot="3">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#4
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="4" name="slot4_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="4" name="slot4_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="4" name="slot4_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="4" name="slot4_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>4" slot="4" data-id="<?= $id ?>" id="key<?= $id ?>4">
                                                                <input type="hidden" name="slot4_image" image-id="<?= $id ?>4" slot="4">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#5
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="5" name="slot5_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="5" name="slot5_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="5" name="slot5_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="5" name="slot5_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>5" slot="5" data-id="<?= $id ?>" id="key<?= $id ?>5">
                                                                <input type="hidden" name="slot5_image" image-id="<?= $id ?>5" slot="5">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#6
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="6" name="slot6_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="6" name="slot6_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="6" name="slot6_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="6" name="slot6_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>6" slot="6" data-id="<?= $id ?>" id="key<?= $id ?>6">
                                                                <input type="hidden" name="slot6_image" image-id="<?= $id ?>6" slot="6">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#7
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="7" name="slot7_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="7" name="slot7_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="7" name="slot7_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="7" name="slot7_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>7" slot="7" data-id="<?= $id ?>" id="key<?= $id ?>7">
                                                                <input type="hidden" name="slot7_image" image-id="<?= $id ?>7" slot="7">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#8
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="8" name="slot8_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="8" name="slot8_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="8" name="slot8_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="8" name="slot8_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>8" slot="8" data-id="<?= $id ?>" id="key<?= $id ?>8">
                                                                <input type="hidden" name="slot8_image" image-id="<?= $id ?>8" slot="8">
                                                            </div>
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <h1 class="fs-5 fw-bold py-6">
                                                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor" />
                                                                        <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor" />
                                                                    </svg>
                                                                </span> Time Slot#9
                                                            </h1>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control form-control-solid" slot="9" name="slot9_title" data-id="<?= $id ?>" placeholder="Slot Title">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Subtitle</label>
                                                                <input type="text" class="form-control form-control-solid" slot="9" name="slot9_subtitle" data-id="<?= $id ?>" placeholder="Slot Subtitle">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr start-time" slot="9" name="slot9_start_time" timetype="start" time-id="<?= $id ?>" placeholder="Start Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">End Time</label>
                                                                <input type="text" class="form-control form-control-solid timepickr end-time" slot="9" name="slot9_end_time" timetype="end" time-id="<?= $id ?>" placeholder="End Time">
                                                            </div>
                                                            <div class="mb-10 col-md-12 form-group fv-row fv-plugins-icon-container">
                                                                <label class="form-label">Image</label>
                                                                <input type="file" class="filepond" data-field="<?= $id ?>9" slot="9" data-id="<?= $id ?>" id="key<?= $id ?>9">
                                                                <input type="hidden" name="slot9_image" image-id="<?= $id ?>9" slot="9">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!--end::Tables Widget 9-->
                                            </form>
                                        </div>

                                    <?php
                                        $lastForm = $sr;
                                        $sr++;
                                    } ?>
                                </div>
                                <!--end::Group-->

                                <!--begin::Actions-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="me-2">
                                        <button type="button" class="btn btn-light btn-active-light-primary d-none" data-kt-stepper-action="previous">
                                            Back
                                        </button>
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Wrapper-->
                                    <div>
                                        <button type="button" class="btn btn-primary finish" data-kt-stepper-action="submit">
                                            <span class="indicator-label">
                                                Finish
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-primary continue" data-kt-stepper-action="next">
                                            <span class="indicator-label">
                                                Continue
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                    </div>
                    <!--end::Stepper-->
                    <!---------------------------------------end::Stepper------------------------------------->

                </div>
                <!--end::Col-->



            <?php } ?>
        </div>
        <!--end::Row-->

    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?php if (@$event) { ?>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageEdit,
            FilePondPluginFileValidateType
        );
        var content_image = [];
        var inputElements = $(".filepond")
        inputElements.each((index) => {
            var inputElement = inputElements.eq(index)
            var id = inputElement.attr('data-field')
            // create a FilePond instance at the input element location
            FilePond.create(
                document.querySelector('#key' + id), {
                    name: 'attachment',
                    allowMultiple: false,
                    allowImagePreview: true,
                    imagePreviewFilterItem: false,
                    imagePreviewMarkupFilter: false,
                    dataMaxFileSize: "2MB",
                    // server
                    server: {
                        process: {
                            url: '<?= base_url(); ?>/file/upload',
                            method: 'POST',
                            headers: {
                                'x-customheader': 'Processing File',
                                "X-Requested-With": "XMLHttpRequest"
                            },
                            onload: (response) => {
                                console.log(response)
                                response = response;
                                var slot = $(`[image-id='${id}']`).attr('slot');
                                $(`[image-id='${id}']`).val(slot + '__' + response)
                                content_image.push(id + '__' + response);
                                return response;

                            },
                            onerror: (response) => {
                                console.log(response)
                                return response
                            },
                            ondata: (formData) => {
                                //console.log(formData)
                                window.h = formData;

                                return formData;
                            }
                        },
                        revert: (uniqueFileId, load, error) => {
                            const formData = new FormData();
                            formData.append("key", uniqueFileId);
                            console.log(uniqueFileId)
                            content_image = content_image.filter(function(ele) {
                                return ele != id + '__' + uniqueFileId;
                            });

                            fetch(`<?= base_url() ?>/file/revert?key=${uniqueFileId}`, {
                                    method: "DELETE",
                                    body: formData,
                                }).then(res => res.json())
                                .then(json => {
                                    // Should call the load method when done, no parameters required

                                    load();

                                })
                                .catch(err => {
                                    // Can call the error method if something is wrong, should exit after
                                    //error(err.message);
                                })
                        },



                        remove: (uniqueFileId, load, error) => {
                            // Should somehow send `source` to server so server can remove the file with this source
                            content_image = content_image.filter(function(ele) {
                                return ele != uniqueFileId;
                            });


                            // Should call the load method when done, no parameters required
                            load();
                        },

                    }
                }
            );
        })
    </script>


    <script>
        var convertValuesToTime = function(values, handle) {
            //console.log(values)
            values = values
                .map(value => Number(value) % 1440)
                .map(value => convertMinutesToHoursAndMinutes(value))
            //console.log(values[handle])
            //console.log(values)

        };

        var convertMinutesToHoursAndMinutes = function(minutes) {
            console.log(minutes)
            let hour = Math.floor(minutes / 60)
            let minute = minutes - hour * 60

            if (hour >= 0 && hour <= 9) {
                hour = '0' + hour;
            }
            if (minute >= 0 && minute <= 9) {
                minute = minute + '0'
            }
            return hour + ':' + minute

        }



        function recountVal(val) {
            switch (val) {
                case 0:
                    return '';
                    break;
                case 360:
                    return '6am';
                    break;
                case 720:
                    return '12am';
                    break;
                case 1080:
                    return '6pm';
                    break;
                case 1440:
                    return '12pm';
                    break;
                case 1800:
                    return '6am';
                    break;
                case 2160:
                    return '';
                    break;

            }
        }

        function tConvert(time) {
            // Check correct time format and split into components
            time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice(1); // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join(''); // return adjusted time or original string
        }

        let sliders = [];



        $('.noUi-value.noUi-value-horizontal.noUi-value-large').each(function() {
            var val = $(this).html();
            val = recountVal(parseInt(val));
            //console.log(val)
            $(this).html(val);

        });
    </script>

    <!--timepickr section start-->
    <script>
        let slot_validator = (id, slot, time) => {
            let slot1 = '';
            let slot2 = '';
            let slot3 = '';
            let slot4 = '';
            let slot5 = '';
            let slot6 = '';
            let slot7 = '';
            let slot8 = '';
            let slot9 = '';
            $(".start-time").each(function() {
                var pickr = $(this);
                if (pickr.attr('time-id') == id) {
                    if (pickr.attr('slot') == 1) {
                        slot1 += pickr.val();
                    }
                    if (pickr.attr('slot') == 2) {
                        slot2 += pickr.val();
                    }
                    if (pickr.attr('slot') == 3) {
                        slot3 += pickr.val();
                    }
                    if (pickr.attr('slot') == 4) {
                        slot4 += pickr.val();
                    }
                    if (pickr.attr('slot') == 5) {
                        slot5 += pickr.val();
                    }
                    if (pickr.attr('slot') == 6) {
                        slot6 += pickr.val();
                    }
                    if (pickr.attr('slot') == 7) {
                        slot7 += pickr.val();
                    }
                    if (pickr.attr('slot') == 8) {
                        slot8 += pickr.val();
                    }
                    if (pickr.attr('slot') == 9) {
                        slot9 += pickr.val();
                    }
                }
            })
            $(".end-time").each(function() {
                var pickr = $(this);
                if (pickr.attr('time-id') == id) {
                    if (pickr.attr('slot') == 1) {
                        slot1 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 2) {
                        slot2 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 3) {
                        slot3 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 4) {
                        slot4 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 5) {
                        slot5 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 6) {
                        slot6 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 7) {
                        slot7 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 8) {
                        slot8 += '-' + pickr.val();
                    }
                    if (pickr.attr('slot') == 9) {
                        slot9 += '-' + pickr.val();
                    }
                }
            })

            let valid = 1;
            let count_slots = 0;
            if (slot1 != '' && slot1 != '-') {
                var split = slot1.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot2 != '' && slot2 != '-') {
                var split = slot2.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot3 != '' && slot3 != '-') {
                var split = slot3.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot4 != '' && slot4 != '-') {
                var split = slot4.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot5 != '' && slot5 != '-') {
                var split = slot5.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot6 != '' && slot6 != '-') {
                var split = slot6.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot7 != '' && slot7 != '-') {
                var split = slot7.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot8 != '' && slot8 != '-') {
                var split = slot8.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            if (slot9 != '' && slot9 != '-') {
                var split = slot9.split("-");
                var starttime = split[0];
                var endtime = split[1];
                if (time > starttime && time < endtime) {
                    valid = 0
                }
            }
            return valid;
        }
        //console.log(slot_validator(1,1, '08:00'));
        let end_validation = (id, slot, time) => {
            // check end time != end time of other slots
            var error = 0;
            $(".end-time").each(function() {
                if ($(this).attr('time-id') == id) {
                    if ($(this).val() == time) {
                        error += 1;
                    }
                }
            });
            if (error > 1) {
                return 0;
            } else {
                return 1;
            }
            error = 0;
            //end time != start time of same slot
            $(".start-time").each(function() {
                if ($(this).attr('time-id') == id && slot == $(this).attr('slot')) {
                    if ($(this).val() == time) {
                        error += 1;
                    }
                }
            })
            if (error > 1) {
                return 0;
            } else {
                return 1;
            }
        }
        let start_validation = (id, slot, time) => {
            // check start time != start time of other slots
            var error = 0;
            $(".start-time").each(function() {
                if ($(this).attr('time-id') == id) {
                    if ($(this).val() == time) {
                        error += 1;
                    }
                }
            });
            if (error > 1) {
                return 0;
            } else {
                return 1;
            }
            error = 0;
            //start time != end time of same slot
            $(".end-time").each(function() {
                if ($(this).attr('time-id') == id && slot == $(this).attr('slot')) {
                    if ($(this).val() == time) {
                        error += 1;
                    }
                }
            })
            if (error > 1) {
                return 0;
            } else {
                return 1;
            }
        }

        // Stepper lement
        var element = document.querySelector("#kt_stepper_example_vertical");
        var options = {
            startIndex: parseInt('<?= $last; ?>')
        };
        // Initialize Stepper
        var stepper = new KTStepper(element, options);
        var validations = [];
        //form validation start
        $(".date-form").each(function() {
            var id = $(this).attr('data-id');
            var form = document.getElementById('timeslot' + id);
            var valueMin = document.querySelector("#kt_slider_basic_min" + id);
            var valueMax = document.querySelector("#kt_slider_basic_max" + id);
            var obj = new Object();
            for (var i = 1; i <= 9; i++) {
                obj['slot' + i + '_title'] = {
                    validators: {
                        notEmpty: {
                            message: 'title is required'
                        }
                    }
                };
                obj['slot' + i + '_subtitle'] = {
                    validators: {
                        notEmpty: {
                            message: 'subtitle is required'
                        }
                    }
                };
                obj['slot' + i + '_start_time'] = {
                    validators: {
                        notEmpty: {
                            message: 'start time is required'
                        },
                        callback: {
                            message: "",
                            callback: (input) => {
                                if (input.value >= valueMin.value && input.value <= valueMax.value) {
                                    var field = input.field
                                    var slot = field.replace(/[^0-9]/g, '')
                                    var start_valid = start_validation(id, slot, input.value);
                                    if (start_valid == 1) {
                                        var slot_valid = slot_validator(id, slot, input.value);
                                        if (slot_valid == 1) {
                                            return {
                                                valid: true, // or false
                                                message: ''
                                            };
                                        } else {
                                            return {
                                                valid: false, // or false
                                                message: 'Slots Should not overlap'
                                            };
                                        }
                                    } else {
                                        return {
                                            valid: false, // or false
                                            message: 'Start time is invalid'
                                        };
                                    }
                                }
                                return {
                                    valid: false, // or false
                                    message: 'Start time should be within open hours'
                                };
                            }
                        }
                    }
                };
                obj['slot' + i + '_end_time'] = {
                    validators: {
                        notEmpty: {
                            message: 'end time is required'
                        },
                        callback: {
                            message: "",
                            callback: (input) => {
                                if (input.value >= valueMin.value && input.value <= valueMax.value) {
                                    var field = input.field
                                    var slot = field.replace(/[^0-9]/g, '')
                                    var end_valid = end_validation(id, slot, input.value);
                                    if (end_valid == 1) {
                                        var slot_valid = slot_validator(id, slot, input.value);
                                        if (slot_valid == 1) {
                                            return {
                                                valid: true, // or false
                                                message: ''
                                            };
                                        } else {
                                            return {
                                                valid: false, // or false
                                                message: 'Slots should not overlap'
                                            };
                                        }
                                    } else {
                                        return {
                                            valid: false, // or false
                                            message: 'End time is invalid'
                                        };
                                    }
                                }
                                return {
                                    valid: false, // or false
                                    message: 'End time should be within open hours'
                                };
                            }
                        }
                    }
                };
                obj['slot' + i + '_image'] = {
                    validators: {
                        notEmpty: {
                            message: 'image is required'
                        }
                    }
                }
            }
            var validator = FormValidation.formValidation(
                form, {
                    fields: obj,

                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: 'is-invalid',
                            eleValidClass: 'is-valid'
                        })
                    }
                }
            );
            var store = new Object();
            store[id] = validator;
            validations.push(store)
        });
        //form validation end

        $(".finish").on('click', function() {
            var id = '<?= $lastForm ?>';
            var validator;
            validations.forEach((element, index) => {
                if (element[id] != undefined) {
                    validator = element[id];
                }
            });
            if (validator) {
                validator.validate().then(function(status) {
                    if (status == 'Valid') {
                        var form = $(`#timeslot${id}`).serialize();
                        console.log(form)
                        $.ajax({
                            type: 'POST',
                            url: '<?= base_url() ?>/dashboard/events/details/save',
                            data: form,
                            dataType: 'JSON',
                            beforeSend: (e) => {
                                $(".finish").attr("data-kt-indicator", "on")
                            },
                        }).done((response) => {
                            window.location.href = "<?php echo base_url(); ?>/dashboard/events";
                        }).fail((response) => {
                            console.log(response)
                            $(".finish").attr("data-kt-indicator", "")
                            Swal.fire({
                                html: `Enable to save try again`,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            })
                        });
                    }
                });
            }
        });
        // Handle next step
        stepper.on("kt.stepper.next", function(stepper) {
            var id = stepper.getCurrentStepIndex();
            var validator;
            validations.forEach((element, index) => {
                if (element[id] != undefined) {
                    validator = element[id];
                }
            });
            if (validator) {
                validator.validate().then(function(status) {
                    if (status == 'Valid') {
                        var form = $(`#timeslot${id}`).serialize();
                        $.ajax({
                            type: 'POST',
                            url: '<?= base_url() ?>/dashboard/events/details/save',
                            data: form,
                            dataType: 'JSON',
                            beforeSend: (e) => {
                                $(".continue").attr("data-kt-indicator", "on")
                            },
                        }).done((response) => {
                            if ('<?= $lastForm ?>' == id) {

                                window.location.href = "<?php echo base_url(); ?>/dashboard/events";
                            }
                            $(".continue").attr("data-kt-indicator", "")
                            stepper.goNext(); // go next step
                        }).fail((response) => {
                            $(".continue").attr("data-kt-indicator", "")
                            Swal.fire({
                                html: `Enable to save try again`,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            })
                        });
                    }
                });
            }
        });

        // Handle previous step
        stepper.on("kt.stepper.previous", function(stepper) {
            //stepper.goPrevious(); // go previous step
        });



        $(".timepickr").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: 'h:i K',
        });
        $(".start_hour").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: 'h:i K',
        })

        $(".end_hour").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: 'h:i K',
        })
    </script>
    <!--timepickr section end-->




<?php } else {
?>



    <script>
        $("#event_date").flatpickr({
            altInput: true,
            altFormat: "m-d-Y",
            dateFormat: "Y-m-d",
            mode: "range"
        });



        // Define form element
        const form = document.getElementById('kt_docs_formvalidation_event');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'event_name': {
                        validators: {
                            notEmpty: {
                                message: 'Event name is required'
                            }
                        }
                    },
                    'event_date': {
                        validators: {
                            notEmpty: {
                                message: 'Event date is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = document.getElementById('kt_docs_formvalidation_event_btn');
        submitButton.addEventListener('click', function(e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function(status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        form.submit()
                    }
                });
            }
        });
    </script>
<?php } ?>
<?= $this->endSection() ?>