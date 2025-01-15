<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

class Login extends Controller {

    public function __construct() {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function index() {
        return view('login_view', ['session' => $this->session]);
    }

    public function submit() {
    $this->validation->setRules([
        'username' => 'required',
        'password' => 'required'
    ]);

    if ($this->validation->withRequest($this->request)->run() == FALSE) {
        return view('login_view', [
            'validation' => $this->validation,
            'session' => $this->session
        ]);
    } else {
        $userModel = new User_model();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user = $userModel->get_user($username);

        if ($user && password_verify($password[0], $user['password'])) {
            $this->session->set([
                'user_id' => $user['id'],
                'username' => $username,
                'logged_in' => true
            ]);
            return redirect()->to('dashboard');
        } else {
            $this->session->setFlashdata('error', 'Invalid login credentials.');
            return view('login_view', [
                'session' => $this->session
            ]);
        }
    }
}


    public function logout() {
        $this->session->destroy();
        return redirect()->to('login');
    }
}
