<?php

namespace App\Controllers;


use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use CodeIgniter\HTTP\Response;
use Aws\Credentials\Credentials;
use DateInterval;
use DatePeriod;
use DateTime;

class EventController extends BaseController
{
    public function index()
    {
        $pager = service('pager');
        $page = (@$_GET['page']) ? $_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $builder = $this->db->table('events');
        $events = $builder->where('is_deleted', 0)->where('user_id', session()->get('id'))->orderBy('id', 'desc')
            ->get($perPage, $offset)
            ->getResult();
        $total = $builder->where('is_deleted', 0)->countAllResults();
        return view("dashboard/events", [
            "events" => $events,
            'links' => $pager->makeLinks($page, $perPage, $total, 'bs_full')
        ]);
    }
    public function create_event()
    {
        helper(['form']);
        if (@$_GET['event']) {
            $builder = $this->db->table('events');
            $event = $builder->where('id', $_GET['event'])->where('user_id', session()->get('id'))->where('is_deleted', 0)->get(1);
            $builder = $this->db->table('event_dates');
            if (@$event->getResult()[0]) {
                $event_dates = $builder->where('event_id', $event->getResult()[0]->id)
                    ->where('user_id', session()->get('id'))
                    ->get()->getResult();
                if (count($event_dates) > 0) {
                    $event_dates_to_save = array();
                    if (str_contains(@$event->getResult()[0]->event_date, 'to')) {
                        $range = explode('to', $event->getResult()[0]->event_date);
                        $start = trim($range[0]);
                        $end = trim($range[1]);
                        $period = new DatePeriod(
                            new DateTime($start),
                            new DateInterval('P1D'),
                            new DateTime($end . ' 23:59:59')
                        );

                        foreach ($period as $key => $value) {
                            array_push($event_dates_to_save, $value->format('Y-m-d'));
                        }
                    } else {
                        array_push($event_dates_to_save, @$event->getResult()[0]->event_date);
                    }
                    if (count($event_dates) == count($event_dates_to_save)) {
                        return redirect()->to('/dashboard/events/edit/' . $_GET['event']);
                    }
                    $already_saved_dates = array();
                    foreach ($event_dates as $event_date) {
                        array_push($already_saved_dates, $event_date->date);
                    }
                    return view("dashboard/create_event", [
                        "event" => $event->getResult(),
                        "already_saved_dates" =>  $already_saved_dates,
                    ]);
                }
                return view("dashboard/create_event", [
                    "event" => $event->getResult(),
                    "already_saved_dates" => array(),
                ]);
            }
        }
        return view("dashboard/create_event");
    }
    public function upload_file()
    {
        $file = $this->request->getFile('attachment');
        if ($file->isValid()) {
            $attachment = $file->getName();
            $fileExt = explode('.', $attachment);
            $fileActualExt = strtolower(end($fileExt));
            $key = md5(uniqid()) . '.' . $fileActualExt;
            $file->move('./public/temp_files', $key);
            $credentials = new Credentials('', '');
            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'credentials' => $credentials
            ]);
            try {
                $s3->putObject([
                    'Bucket' => 'turtletransit-schedule',
                    'Key' => $key,
                    'Body'   => fopen('./public/temp_files/'.$key,'r'),
                    'ACL'    => 'public-read',
                    "ContentType" => "image/".$fileActualExt,
                ]);
                return json_encode($key);
            } catch (S3Exception $e) {
                return json_encode("There was an error uploading the file.\n");
            }
        }
    }
    public function revert_file()
    {
        $key = str_replace('"', "", $_GET['key']);
        unlink('./public/temp_files/' . $key);
        echo json_encode(1);
    }
    public function save_event()
    {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $timezone = @$_POST['timezone'] ? @$_POST['timezone'] : 'America/Chicago';
        $builder = $this->db->table('events');
        $builder->insert(array(
            "user_id" => session()->get('id'),
            "event_name" => $event_name,
            "event_date" => $event_date,
            "timezone" => $timezone
        ));
        $builder = $this->db->table('events');
        $event = $builder->db()->insertID();
        return redirect()->to('/dashboard/events/create?event=' . $event);
    }
    public function save_detail()
    {
        helper(['form']);
        $event_id = $this->request->getPost('event_id');
        $event_date = $this->request->getPost('event_date');
        $start_hour = $this->request->getPost('start_hour');
        $end_hour = $this->request->getPost('end_hour');
        $open_hours = $this->request->getPost('slider_range_min') . '-' . $this->request->getPost('slider_range_max');
        $builder = $this->db->table('event_dates');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'date' => $event_date,
            'min' => $start_hour,
            'max' => $end_hour,
            'open_hours' => $open_hours,
        ));
        $builder = $this->db->table('events');
        $event_date_id = $builder->db()->insertID();

        $slot1_title = $this->request->getPost('slot1_title');
        $slot1_subtitle = $this->request->getPost('slot1_subtitle');
        $slot1_start_time = $this->request->getPost('slot1_start_time');
        $slot1_end_time = $this->request->getPost('slot1_end_time');
        $slot1_image = $this->request->getPost('slot1_image');
        $str = str_replace('"', "", $slot1_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 1,
            'title' => $slot1_title,
            'subtitle' => $slot1_subtitle,
            'start_time' => $slot1_start_time,
            'end_time' => $slot1_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot2_title = $this->request->getPost('slot2_title');
        $slot2_subtitle = $this->request->getPost('slot2_subtitle');
        $slot2_start_time = $this->request->getPost('slot2_start_time');
        $slot2_end_time = $this->request->getPost('slot2_end_time');
        $slot2_image = $this->request->getPost('slot2_image');
        $str = str_replace('"', "", $slot2_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 2,
            'title' => $slot2_title,
            'subtitle' => $slot2_subtitle,
            'start_time' => $slot2_start_time,
            'end_time' => $slot2_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot3_title = $this->request->getPost('slot3_title');
        $slot3_subtitle = $this->request->getPost('slot3_subtitle');
        $slot3_start_time = $this->request->getPost('slot3_start_time');
        $slot3_end_time = $this->request->getPost('slot3_end_time');
        $slot3_image = $this->request->getPost('slot3_image');
        $str = str_replace('"', "", $slot3_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 3,
            'title' => $slot3_title,
            'subtitle' => $slot3_subtitle,
            'start_time' => $slot3_start_time,
            'end_time' => $slot3_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot4_title = $this->request->getPost('slot4_title');
        $slot4_subtitle = $this->request->getPost('slot4_subtitle');
        $slot4_start_time = $this->request->getPost('slot4_start_time');
        $slot4_end_time = $this->request->getPost('slot4_end_time');
        $slot4_image = $this->request->getPost('slot4_image');
        $str = str_replace('"', "", $slot4_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 4,
            'title' => $slot4_title,
            'subtitle' => $slot4_subtitle,
            'start_time' => $slot4_start_time,
            'end_time' => $slot4_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot5_title = $this->request->getPost('slot5_title');
        $slot5_subtitle = $this->request->getPost('slot5_subtitle');
        $slot5_start_time = $this->request->getPost('slot5_start_time');
        $slot5_end_time = $this->request->getPost('slot5_end_time');
        $slot5_image = $this->request->getPost('slot5_image');
        $str = str_replace('"', "", $slot5_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 5,
            'title' => $slot5_title,
            'subtitle' => $slot5_subtitle,
            'start_time' => $slot5_start_time,
            'end_time' => $slot5_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot6_title = $this->request->getPost('slot6_title');
        $slot6_subtitle = $this->request->getPost('slot6_subtitle');
        $slot6_start_time = $this->request->getPost('slot6_start_time');
        $slot6_end_time = $this->request->getPost('slot6_end_time');
        $slot6_image = $this->request->getPost('slot6_image');
        $str = str_replace('"', "", $slot6_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 6,
            'title' => $slot6_title,
            'subtitle' => $slot6_subtitle,
            'start_time' => $slot6_start_time,
            'end_time' => $slot6_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot7_title = $this->request->getPost('slot7_title');
        $slot7_subtitle = $this->request->getPost('slot7_subtitle');
        $slot7_start_time = $this->request->getPost('slot7_start_time');
        $slot7_end_time = $this->request->getPost('slot7_end_time');
        $slot7_image = $this->request->getPost('slot7_image');
        $str = str_replace('"', "", $slot7_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 7,
            'title' => $slot7_title,
            'subtitle' => $slot7_subtitle,
            'start_time' => $slot7_start_time,
            'end_time' => $slot7_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot8_title = $this->request->getPost('slot8_title');
        $slot8_subtitle = $this->request->getPost('slot8_subtitle');
        $slot8_start_time = $this->request->getPost('slot8_start_time');
        $slot8_end_time = $this->request->getPost('slot8_end_time');
        $slot8_image = $this->request->getPost('slot8_image');
        $str = str_replace('"', "", $slot8_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 8,
            'title' => $slot8_title,
            'subtitle' => $slot8_subtitle,
            'start_time' => $slot8_start_time,
            'end_time' => $slot8_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        $slot9_title = $this->request->getPost('slot9_title');
        $slot9_subtitle = $this->request->getPost('slot9_subtitle');
        $slot9_start_time = $this->request->getPost('slot9_start_time');
        $slot9_end_time = $this->request->getPost('slot9_end_time');
        $slot9_image = $this->request->getPost('slot9_image');
        $str = str_replace('"', "", $slot9_image);
        $arr = explode("__", $str);
        $builder = $this->db->table('date_slots');
        $builder->insert(array(
            'user_id' => session()->get('id'),
            'event_id' => $event_id,
            'event_date_id' => $event_date_id,
            'slot_no' => 9,
            'title' => $slot9_title,
            'subtitle' => $slot9_subtitle,
            'start_time' => $slot9_start_time,
            'end_time' => $slot9_end_time,
            'image' => $arr[1],
        ));
        unlink('./public/temp_files/' . @$arr[1]);
        echo json_encode(array("status" => TRUE));
    }
    public function change_status()
    {
        helper(['form']);
        $event_id = $this->request->getPost('event_id');

        $builder = $this->db->table('events');
        $builder->where('user_id', session()->get('id'));
        $builder->set("status", 0);
        $builder->update();

        $builder = $this->db->table('events');
        $event = $builder->where('id', $event_id)->where('is_deleted', 0)->get()->getResult();;

        $builder = $this->db->table('events');
        $builder->where('id', $event_id);
        $builder->set("status", $event[0]->status == 1 ? 0 : 1);
        $builder->update();


        $builder = $this->db->table('events');
        $event = $builder->where('id', $event_id)->where('is_deleted', 0)->get()->getResult();;
        echo json_encode(array("status" => $event[0]->status));
    }
    public function edit_event($id)
    {
        helper(['form']);
        $builder = $this->db->table('events');
        $event = $builder->where('id', $id)->where('user_id', session()->get('id'))->where('is_deleted', 0)->get()->getResult();
        if (@$event[0]->id) {
            $builder = $this->db->table('event_dates');
            $event_dates = $builder->where('event_id', $event[0]->id)
                ->where('user_id', session()->get('id'))
                ->get()->getResult();
            if (count($event_dates) > 0) {
                $event_dates_to_save = array();
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
                        array_push($event_dates_to_save, $value->format('Y-m-d'));
                    }
                } else {
                    array_push($event_dates_to_save, @$event[0]->event_date);
                }
                if (count($event_dates) == count($event_dates_to_save)) {
                    return view("dashboard/update_event", [
                        "event" => $event,
                        "event_dates" => $event_dates,
                        "db" => $this->db
                    ]);
                }
            }
            session()->setFlashdata('error', 'Event is missing date information. Fill it out.');
            return redirect()->to('/dashboard/events/create?event=' . $id);
        }
    }
    public function update_event()
    {
        $event_id = $_POST['event_id'];
        $event_name = $_POST['event_name'];
        $timezone = @$_POST['timezone'] ? @$_POST['timezone'] : 'America/Chicago';
        $builder = $this->db->table('events');
        $builder->where('id', $event_id);
        $builder->where('user_id', session()->get('id'));
        $builder->set("event_name", $event_name);
        $builder->set("timezone", $timezone);
        $builder->update();

        return redirect()->to('/dashboard/events/edit/' . $event_id);
    }
    public function update_detail()
    {
        helper(['form']);
        $event_id = $this->request->getPost('event_id');
        $event_date_id = $this->request->getPost('event_date');
        $start_hour = $this->request->getPost('start_hour');
        $end_hour = $this->request->getPost('end_hour');
        $open_hours = $this->request->getPost('slider_range_min') . '-' . $this->request->getPost('slider_range_max');
        $builder = $this->db->table('event_dates');
        $builder->where('id', $event_date_id);
        $builder->set("min", $start_hour);
        $builder->set("max", $end_hour);
        $builder->set("open_hours", $open_hours);
        $builder->update();

        $slot1_id = $this->request->getPost('slot1_id');
        $slot1_title = $this->request->getPost('slot1_title');
        $slot1_subtitle = $this->request->getPost('slot1_subtitle');
        $slot1_start_time = $this->request->getPost('slot1_start_time');
        $slot1_end_time = $this->request->getPost('slot1_end_time');
        $slot1_image = $this->request->getPost('slot1_image');
        $slot1_verify = $this->request->getPost('slot1_image_verify');
        if ($slot1_verify == 1) {

            $str = str_replace('"', "", $slot1_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot1_image = $arr[1];
        }

        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot1_id);
        $builder->set("title", $slot1_title);
        $builder->set("subtitle", $slot1_subtitle);
        $builder->set("start_time", $slot1_start_time);
        $builder->set("end_time", $slot1_end_time);
        $builder->set("image", $slot1_image);
        $builder->update();

        $slot2_id = $this->request->getPost('slot2_id');
        $slot2_title = $this->request->getPost('slot2_title');
        $slot2_subtitle = $this->request->getPost('slot2_subtitle');
        $slot2_start_time = $this->request->getPost('slot2_start_time');
        $slot2_end_time = $this->request->getPost('slot2_end_time');
        $slot2_image = $this->request->getPost('slot2_image');
        $slot2_verify = $this->request->getPost('slot2_image_verify');
        if ($slot2_verify == 1) {
            $str = str_replace('"', "", $slot2_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot2_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot2_id);
        $builder->set("title", $slot2_title);
        $builder->set("subtitle", $slot2_subtitle);
        $builder->set("start_time", $slot2_start_time);
        $builder->set("end_time", $slot2_end_time);
        $builder->set("image", $slot2_image);
        $builder->update();

        $slot3_id = $this->request->getPost('slot3_id');
        $slot3_title = $this->request->getPost('slot3_title');
        $slot3_subtitle = $this->request->getPost('slot3_subtitle');
        $slot3_start_time = $this->request->getPost('slot3_start_time');
        $slot3_end_time = $this->request->getPost('slot3_end_time');
        $slot3_image = $this->request->getPost('slot3_image');
        $slot3_verify = $this->request->getPost('slot3_image_verify');

        if ($slot3_verify == 1) {
            $str = str_replace('"', "", $slot3_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot3_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot3_id);
        $builder->set("title", $slot3_title);
        $builder->set("subtitle", $slot3_subtitle);
        $builder->set("start_time", $slot3_start_time);
        $builder->set("end_time", $slot3_end_time);
        $builder->set("image", $slot3_image);
        $builder->update();

        $slot4_id = $this->request->getPost('slot4_id');
        $slot4_title = $this->request->getPost('slot4_title');
        $slot4_subtitle = $this->request->getPost('slot4_subtitle');
        $slot4_start_time = $this->request->getPost('slot4_start_time');
        $slot4_end_time = $this->request->getPost('slot4_end_time');
        $slot4_image = $this->request->getPost('slot4_image');
        $slot4_verify = $this->request->getPost('slot4_image_verify');
        if ($slot4_verify == 1) {
            $str = str_replace('"', "", $slot4_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot4_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot4_id);
        $builder->set("title", $slot4_title);
        $builder->set("subtitle", $slot4_subtitle);
        $builder->set("start_time", $slot4_start_time);
        $builder->set("end_time", $slot4_end_time);
        $builder->set("image", $slot4_image);
        $builder->update();

        $slot5_id = $this->request->getPost('slot5_id');
        $slot5_title = $this->request->getPost('slot5_title');
        $slot5_subtitle = $this->request->getPost('slot5_subtitle');
        $slot5_start_time = $this->request->getPost('slot5_start_time');
        $slot5_end_time = $this->request->getPost('slot5_end_time');
        $slot5_image = $this->request->getPost('slot5_image');
        $slot5_verify = $this->request->getPost('slot5_image_verify');
        if ($slot5_verify == 1) {
            $str = str_replace('"', "", $slot5_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot5_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot5_id);
        $builder->set("title", $slot5_title);
        $builder->set("subtitle", $slot5_subtitle);
        $builder->set("start_time", $slot5_start_time);
        $builder->set("end_time", $slot5_end_time);
        $builder->set("image", $slot5_image);
        $builder->update();

        $slot6_id = $this->request->getPost('slot6_id');
        $slot6_title = $this->request->getPost('slot6_title');
        $slot6_subtitle = $this->request->getPost('slot6_subtitle');
        $slot6_start_time = $this->request->getPost('slot6_start_time');
        $slot6_end_time = $this->request->getPost('slot6_end_time');
        $slot6_image = $this->request->getPost('slot6_image');
        $slot6_verify = $this->request->getPost('slot6_image_verify');
        if ($slot6_verify == 1) {
            $str = str_replace('"', "", $slot6_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot6_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot6_id);
        $builder->set("title", $slot6_title);
        $builder->set("subtitle", $slot6_subtitle);
        $builder->set("start_time", $slot6_start_time);
        $builder->set("end_time", $slot6_end_time);
        $builder->set("image", $slot6_image);
        $builder->update();

        $slot7_id = $this->request->getPost('slot7_id');
        $slot7_title = $this->request->getPost('slot7_title');
        $slot7_subtitle = $this->request->getPost('slot7_subtitle');
        $slot7_start_time = $this->request->getPost('slot7_start_time');
        $slot7_end_time = $this->request->getPost('slot7_end_time');
        $slot7_image = $this->request->getPost('slot7_image');
        $slot7_verify = $this->request->getPost('slot7_verfiy');
        if ($slot7_verify == 1) {
            $str = str_replace('"', "", $slot7_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot7_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot7_id);
        $builder->set("title", $slot7_title);
        $builder->set("subtitle", $slot7_subtitle);
        $builder->set("start_time", $slot7_start_time);
        $builder->set("end_time", $slot7_end_time);
        $builder->set("image", $slot7_image);
        $builder->update();

        $slot8_id = $this->request->getPost('slot8_id');
        $slot8_title = $this->request->getPost('slot8_title');
        $slot8_subtitle = $this->request->getPost('slot8_subtitle');
        $slot8_start_time = $this->request->getPost('slot8_start_time');
        $slot8_end_time = $this->request->getPost('slot8_end_time');
        $slot8_image = $this->request->getPost('slot8_image');
        $slot8_verify = $this->request->getPost('slot8_image_verify');
        if ($slot8_verify == 1) {
            $str = str_replace('"', "", $slot8_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot8_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot8_id);
        $builder->set("title", $slot8_title);
        $builder->set("subtitle", $slot8_subtitle);
        $builder->set("start_time", $slot8_start_time);
        $builder->set("end_time", $slot8_end_time);
        $builder->set("image", $slot8_image);
        $builder->update();

        $slot9_id = $this->request->getPost('slot9_id');
        $slot9_title = $this->request->getPost('slot9_title');
        $slot9_subtitle = $this->request->getPost('slot9_subtitle');
        $slot9_start_time = $this->request->getPost('slot9_start_time');
        $slot9_end_time = $this->request->getPost('slot9_end_time');
        $slot9_image = $this->request->getPost('slot9_image');
        $slot9_verify = $this->request->getPost('slot9_image_verify');
        if ($slot9_verify) {
            $str = str_replace('"', "", $slot9_image);
            $arr = explode("__", $str);
            unlink('./public/temp_files/' . @$arr[1]);
            $slot9_image = $arr[1];
        }
        $builder = $this->db->table('date_slots');
        $builder->where('id', $slot9_id);
        $builder->set("title", $slot9_title);
        $builder->set("subtitle", $slot9_subtitle);
        $builder->set("start_time", $slot9_start_time);
        $builder->set("end_time", $slot9_end_time);
        $builder->set("image", $slot9_image);
        $builder->update();
        echo json_encode(array("status" => TRUE));
    }
    public function delete_event($id)
    {
        helper(['form']);
        $builder = $this->db->table('events');
        $event = $builder->where('id', $id)->where('user_id', session()->get('id'))
            ->where('is_deleted', 0)->get(1)
            ->getResult();
        if (@$event[0]->id) {
            $builder = $this->db->table('events');
            $builder->where('id', $id);
            $builder->where('user_id', session()->get('id'));
            $builder->set("is_deleted", 1);
            $builder->update();
            $session = session();
            $session->setFlashdata('success', 'Event deleted');
            return redirect()->to('/dashboard/events/');
        }
        $session = session();
        $session->setFlashdata('error', 'enable to delete event');
        return redirect()->to('/dashboard/events/');
    }
}
