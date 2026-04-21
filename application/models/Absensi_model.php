<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {

    public function get_active_kelas_id()
    {
        $value = $this->get_setting('active_kelas_id');

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    public function get_active_kelas()
    {
        $kelas_id = $this->get_active_kelas_id();

        if (!$kelas_id) {
            return null;
        }

        return $this->db->get_where('kelas', ['id' => $kelas_id])->row();
    }

    public function set_active_kelas($kelas_id)
    {
        return $this->update_setting('active_kelas_id', (string) $kelas_id);
    }

    public function assign_today_kelas($kelas_id)
    {
        return $this->db
            ->where('tanggal', date('Y-m-d'))
            ->where('kelas_id IS NULL', null, false)
            ->update('absensi', ['kelas_id' => $kelas_id]);
    }

    public function sudah_absen($user_id, $kelas_id = null)
    {
        $this->db->from('absensi');
        $this->db->where('user_id', $user_id);
        $this->db->where('tanggal', date('Y-m-d'));

        if ($kelas_id === null) {
            $this->db->where('kelas_id IS NULL', null, false);
        } else {
            $this->db->where('kelas_id', $kelas_id);
        }

        return $this->db->get()->row();
    }

    public function insert($data)
    {
        return $this->db->insert('absensi', $data);
    }

    public function cleanup_orphan_today()
    {
        $sql = "DELETE absensi
                FROM absensi
                LEFT JOIN kelas ON kelas.id = absensi.kelas_id
                WHERE absensi.tanggal = ?
                AND absensi.kelas_id IS NOT NULL
                AND kelas.id IS NULL";

        $this->db->query($sql, [date('Y-m-d')]);

        return $this->db->affected_rows();
    }

    public function delete_today_by_kelas($kelas_id)
    {
        $this->db
            ->where('tanggal', date('Y-m-d'))
            ->where('kelas_id', $kelas_id)
            ->delete('absensi');

        return $this->db->affected_rows();
    }

    public function get_today()
    {
        $this->db->select('absensi.id, absensi.kelas_id, users.nama, users.no_hp, users.uid_rfid, users.jenis_kelamin, kelas.kelas, kelas.mata_kuliah, kelas.ruangan, kelas.jam_masuk, absensi.waktu, absensi.status');
        $this->db->from('absensi');
        $this->db->join('users', 'users.id = absensi.user_id');
        $this->db->join('kelas', 'kelas.id = absensi.kelas_id', 'left');
        $this->db->where('absensi.tanggal', date('Y-m-d'));
        $this->db->order_by('absensi.waktu', 'DESC');
        return $this->db->get()->result();
    }

    public function count_today()
    {
        return $this->db->where('tanggal', date('Y-m-d'))->count_all_results('absensi');
    }

    public function count_today_by_status($status)
    {
        return $this->db->where([
            'tanggal' => date('Y-m-d'),
            'status'  => $status
        ])->count_all_results('absensi');
    }

    public function delete($id)
    {
        return $this->db->delete('absensi', ['id' => $id]);
    }

    public function get_setting($key)
    {
        $row = $this->db->get_where('settings', ['setting_key' => $key])->row();
        return $row ? $row->setting_value : null;
    }

    public function update_setting($key, $value)
    {
        $exists = $this->db->get_where('settings', ['setting_key' => $key])->row();

        if ($exists) {
            return $this->db->where('setting_key', $key)->update('settings', ['setting_value' => $value]);
        }

        return $this->db->insert('settings', [
            'setting_key'   => $key,
            'setting_value' => $value
        ]);
    }

    public function get_laporan($dari, $sampai, $kelas_id = null, $mata_kuliah = null)
    {
        $this->db->select('absensi.id, absensi.kelas_id, users.nama, users.uid_rfid, users.jenis_kelamin, kelas.kelas, kelas.mata_kuliah, kelas.ruangan, kelas.jam_masuk, absensi.tanggal, absensi.waktu, absensi.status');
        $this->db->from('absensi');
        $this->db->join('users', 'users.id = absensi.user_id');
        $this->db->join('kelas', 'kelas.id = absensi.kelas_id', 'left');
        $this->db->where('absensi.tanggal >=', $dari);
        $this->db->where('absensi.tanggal <=', $sampai);

        if ($kelas_id !== null) {
            $this->db->where('absensi.kelas_id', $kelas_id);
        }

        if ($mata_kuliah !== null && $mata_kuliah !== '') {
            $this->db->where('kelas.mata_kuliah', $mata_kuliah);
        }

        $this->db->order_by('absensi.tanggal', 'DESC');
        $this->db->order_by('absensi.waktu', 'DESC');
        return $this->db->get()->result();
    }
}
