<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

class Register extends Controller {

    public function __construct() {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function index() {
        return view('register_view');
    }

    public function submit() {
        $this->validation->setRules([
            'username' => 'required|is_unique[users.username]',
            'password' => 'required',
        ]);

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            return view('register_view', ['validation' => $this->validation]);
        } else {
            $userModel = new User_model();
            $data = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password')[0], PASSWORD_BCRYPT)
            ];
            $userModel->insert_user($data);
            $this->session->setFlashdata('success', 'Registration successful! You can now login.');
            return redirect()->to('login');
        }
    }
}
