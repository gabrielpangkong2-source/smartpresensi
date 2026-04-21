<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function login($username, $password)
    {
        $admin = $this->db->get_where('admins', ['username' => $username])->row();
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        return false;
    }
}
