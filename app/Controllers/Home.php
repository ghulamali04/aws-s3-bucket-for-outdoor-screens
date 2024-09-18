<?php

namespace App\Controllers;

use PHPUnit\Util\Json;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/dashboard/login');
        //return view('welcome_message');
    }
    public  function dashboard()
    {
        $builder = $this->db->table('events');
        $events = $builder->where('is_deleted', 0)->where('user_id', session()->get('id'))
            ->get()
            ->getResult();

        $builder = $this->db->table('events');
        $active =  $builder->where('is_deleted', 0)->where('status', 1)->where('user_id', session()->get('id'))
            ->get()
            ->getResult();

        $builder = $this->db->table('events');
        $inactive = $builder->where('is_deleted', 0)->where('status', 0)->where('user_id', session()->get('id'))
            ->get()
            ->getResult();

        return view("dashboard/home", array(
            "events" => $events,
            "active" => $active,
            "inactive" => $inactive,
        ));
    }
    public function screen_1($id)
    {
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        return view("screen1", array(
            "event" => $event,
            "user" => $id,
        ));
    }
    public function screen_2($id)
    {
        
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        $builder = $this->db->table('event_dates');
        $event_date = $builder->where('event_id', @$event[0]->id)
            ->where('date', date('Y-m-d'))
            ->get(1)->getResult();
        
        $builder = $this->db->table('date_slots');
        $date_slots = $builder->where('event_id', @$event[0]->id)
            ->where('event_date_id', @$event_date[0]->id)
            ->orderBy('slot_no', 'asc')
            ->get();
        return view("screen2", array(
            "event_date" => $event_date,
            "date_slots" => $date_slots,
            "user" => $id,
        ));
    }
    public function screen_3($id)
    {
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        $builder = $this->db->table('event_dates');
        $event_date = $builder->where('event_id', @$event[0]->id)
            ->where('date', date('Y-m-d'))
            ->get(1)->getResult();
        $builder = $this->db->table('date_slots');
        $date_slots = $builder->where('event_id', @$event[0]->id)
            ->where('event_date_id', @$event_date[0]->id)
            ->orderBy('slot_no', 'asc')
            ->get();
        $slot = '';
        foreach ($date_slots->getResult() as $ds) {
            if ((date('H:i') > $ds->start_time && date('H:i') < $ds->end_time) || date('H:i') == $ds->end_time || date('H:i') == $ds->start_time) {
                $slot = $ds;
            }
        }
        return view("screen3", array(
            "event_date" => $event_date,
            "slot" => $slot,
            "user" => $id,
        ));
    }
    public function update_screen_1()
    {
        helper(['form']);
        $id = $this->request->getPost('user');
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        $active_event = "";
        if (@$event[0]->id) {
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
                $active_event .= " " . date('d', strtotime(@$range));
            }
        }
        echo json_encode(array("active_event" => $active_event));
    }
    public function update_screen_2()
    {
        helper(['form']);
        $id = $this->request->getPost('user');
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        $builder = $this->db->table('event_dates');
        $event_date = $builder->where('event_id', @$event[0]->id)
            ->where('date', date('Y-m-d'))
            ->get(1)->getResult();
        $builder = $this->db->table('date_slots');
        $date_slots = $builder->where('event_id', @$event[0]->id)
            ->where('event_date_id', @$event_date[0]->id)
            ->orderBy('slot_no', 'asc')
            ->get();
        echo json_encode(array(
            "event_date" => $event_date,
            "date_slots" => $date_slots->getResult(),
        ));
    }
    public function update_screen_3()
    {
        helper(['form']);
        $id = $this->request->getPost('user');
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
            if(@$event[0]->timezone != '' && @$event[0]->timezone != null){
                date_default_timezone_set(@$event[0]->timezone);
            }
        $builder = $this->db->table('event_dates');
        $event_date = $builder->where('event_id', @$event[0]->id)
            ->where('date', date('Y-m-d'))
            ->get(1)->getResult();
        $builder = $this->db->table('date_slots');
        $date_slots = $builder->where('event_id', @$event[0]->id)
            ->where('event_date_id', @$event_date[0]->id)
            ->orderBy('slot_no', 'asc')
            ->get();
        $slot = '';
        foreach ($date_slots->getResult() as $ds) {
            if ((date('H:i') > $ds->start_time && date('H:i') < $ds->end_time) || date('H:i') == $ds->end_time || date('H:i') == $ds->start_time) {
                $slot = $ds;
            }
        }
        $title = '';
        $subtitle = '';
        $image = '';
        $time = '';
        if($slot != ''){
            $time = date('ha', strtotime(@$slot->start_time));
            $title = date('ha', strtotime(@$slot->start_time)).' '.@$slot->title;
            $subtitle = @$slot->subtitle;
            $image = 'https://s3.us-east-1.amazonaws.com/turtletransit-schedule/'.$slot->image;
        }
        echo json_encode(array(
            "title" => $title,
            "subtitle" => $subtitle,
            "image" => $image,
            "time" => $time,
        ));
    }

    public function api($id)
    {
        $builder = $this->db->table('events');
        $event = $builder->where('user_id', $id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(1)->getResult();
        $builder = $this->db->table('event_dates');
        $event_date = $builder->where('event_id', @$event[0]->id)
            //->where('date', date('Y-m-d'))
            ->get(1)->getResult();
        $builder = $this->db->table('date_slots');
        $date_slots = $builder->where('event_id', @$event[0]->id)
            //->where('event_date_id', @$event_date[0]->id)
            ->orderBy('slot_no', 'asc')
            ->get();

            echo json_encode(array(
               "event" => $event,
               "event_days" => $event_date,
               "slots" => $date_slots,
            ));  
    }
}
