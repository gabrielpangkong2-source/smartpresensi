<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
    {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->view('login');
    }

    public function proses()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->auth_model->login($username, $password);

        if ($admin) {
            $this->session->set_userdata([
                'admin_logged_in' => true,
                'admin_id'        => $admin->id,
                'admin_username'  => $admin->username
            ]);
            redirect('admin');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
