<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('kelas', 'ASC')
            ->order_by('mata_kuliah', 'ASC')
            ->get('kelas')
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('kelas', ['id' => $id])->row();
    }

    public function exists($id)
    {
        return $this->db->where('id', $id)->count_all_results('kelas') > 0;
    }

    public function count_all()
    {
        return $this->db->count_all('kelas');
    }

    public function count_other_by_mata_kuliah($id, $mata_kuliah)
    {
        return $this->db
            ->where('mata_kuliah', $mata_kuliah)
            ->where('id !=', $id)
            ->count_all_results('kelas');
    }

    public function remove_mata_kuliah_from_users($mata_kuliah)
    {
        return $this->db->delete('user_mata_kuliah', ['mata_kuliah' => $mata_kuliah]);
    }

    public function mata_kuliah_exists($mata_kuliah)
    {
        return $this->db
            ->where('mata_kuliah', $mata_kuliah)
            ->count_all_results('kelas') > 0;
    }

    public function get_mata_kuliah_options()
    {
        $rows = $this->db
            ->distinct()
            ->select('mata_kuliah')
            ->from('kelas')
            ->where('mata_kuliah !=', '')
            ->order_by('mata_kuliah', 'ASC')
            ->get()
            ->result();

        return array_map(function ($row) {
            return $row->mata_kuliah;
        }, $rows);
    }

    public function insert($data)
    {
        return $this->db->insert('kelas', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('kelas', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('kelas', ['id' => $id]);
    }
}
