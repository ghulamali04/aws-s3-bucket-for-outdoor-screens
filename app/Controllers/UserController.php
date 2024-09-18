<?php


namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class UserController extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        return view("dashboard/settings");
    }
    public function change_password()
    {
        helper(['form']);
        $rules = [
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirm_password'  => 'matches[password]'
        ];
        if($this->validate($rules)){
            $userModel = new UserModel();
                $data = [
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                ];
                $userModel->update(session()->get('id'),$data);
                $session = session();
        $session->setFlashdata('success', 'Password Changed');
            return redirect()->to('/dashboard/users/settings');
        }else{
            $data['validation'] = $this->validator;
            echo view('dashboard/settings', $data);
        }
    }
    public function update_profile()
    {
        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email,id,'.session()->get('id').']',
            //'password'      => 'required|min_length[4]|max_length[50]',
            //'confirm_password'  => 'matches[password]'
        ];
        if($this->validate($rules)){
            $userModel = new UserModel();
                $data = [
                    'name'     => $this->request->getVar('name'),
                    'email'    => $this->request->getVar('email'),
                    //'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                ];
                $userModel->update(session()->get('id'),$data);
                $session = session();
        $session->set('name' , $data['name']);
        $session->set('email' , $data['email']);
        $session->setFlashdata('success', 'User Updated');
            return redirect()->to('/dashboard/users/settings');
        }else{
            $data['validation'] = $this->validator;
            echo view('dashboard/settings', $data);
        }
    }
}