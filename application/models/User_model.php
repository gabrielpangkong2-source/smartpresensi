<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private function _base_query()
    {
        $this->db->select("users.*, (SELECT GROUP_CONCAT(DISTINCT um.mata_kuliah ORDER BY um.mata_kuliah SEPARATOR '|||') FROM user_mata_kuliah um WHERE um.user_id = users.id) AS mata_kuliah_raw");
        $this->db->from('users');
    }

    private function _decorate_user($user)
    {
        if (!$user) {
            return null;
        }

        $items = [];

        if (!empty($user->mata_kuliah_raw)) {
            $items = array_values(array_unique(array_filter(array_map('trim', explode('|||', $user->mata_kuliah_raw)))));
        }

        $user->mata_kuliah_items = $items;
        $user->mata_kuliah_list = empty($items) ? '-' : implode(', ', $items);
        unset($user->mata_kuliah_raw);

        return $user;
    }

    private function _decorate_users($rows)
    {
        foreach ($rows as $index => $row) {
            $rows[$index] = $this->_decorate_user($row);
        }

        return $rows;
    }

    public function get_mata_kuliah_by_user_id($user_id)
    {
        $rows = $this->db
            ->select('mata_kuliah')
            ->from('user_mata_kuliah')
            ->where('user_id', $user_id)
            ->order_by('mata_kuliah', 'ASC')
            ->get()
            ->result();

        return array_map(function ($row) {
            return $row->mata_kuliah;
        }, $rows);
    }

    public function has_mata_kuliah($user_id, $mata_kuliah)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('mata_kuliah', $mata_kuliah)
            ->count_all_results('user_mata_kuliah') > 0;
    }

    public function sync_mata_kuliah($user_id, $mata_kuliah_list)
    {
        $this->db->where('user_id', $user_id)->delete('user_mata_kuliah');

        foreach ($mata_kuliah_list as $mata_kuliah) {
            $this->db->insert('user_mata_kuliah', [
                'user_id'      => $user_id,
                'mata_kuliah'  => $mata_kuliah
            ]);
        }
    }

    public function get_by_uid($uid)
    {
        $this->_base_query();
        $this->db->where('users.uid_rfid', $uid);
        return $this->_decorate_user($this->db->get()->row());
    }

    public function get_by_id($id)
    {
        $this->_base_query();
        $this->db->where('users.id', $id);
        return $this->_decorate_user($this->db->get()->row());
    }

    public function uid_exists($uid, $exclude_id = null)
    {
        $this->db->from('users');
        $this->db->where('uid_rfid', $uid);

        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results() > 0;
    }

    public function count_all()
    {
        return $this->db->count_all('users');
    }

    public function get_all()
    {
        $this->_base_query();
        $this->db->order_by('users.nama', 'ASC');
        return $this->_decorate_users($this->db->get()->result());
    }

    public function insert($data)
    {
        $mata_kuliah_list = isset($data['mata_kuliah']) ? $data['mata_kuliah'] : [];
        unset($data['mata_kuliah']);

        $this->db->trans_start();
        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();
        $this->sync_mata_kuliah($user_id, $mata_kuliah_list);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update($id, $data)
    {
        $mata_kuliah_list = isset($data['mata_kuliah']) ? $data['mata_kuliah'] : [];
        unset($data['mata_kuliah']);

        $this->db->trans_start();
        $this->db->where('id', $id)->update('users', $data);
        $this->sync_mata_kuliah($id, $mata_kuliah_list);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->delete('user_mata_kuliah', ['user_id' => $id]);
        $this->db->delete('users', ['id' => $id]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
