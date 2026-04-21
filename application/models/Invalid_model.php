<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invalid_model extends CI_Model {

    public function count_all()
    {
        return $this->db->count_all('invalid_cards');
    }

    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get('invalid_cards')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('invalid_cards', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('invalid_cards', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('invalid_cards', ['id' => $id]);
    }

    public function delete_by_uid($uid)
    {
        return $this->db->where('uid_rfid', $uid)->delete('invalid_cards');
    }
}
