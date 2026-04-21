<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('absensi_model');
        $this->load->model('invalid_model');
    }

    private function _normalize_uid($uid)
    {
        return strtoupper(preg_replace('/\s+/', '', trim((string) $uid)));
    }

    public function absen()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            $this->_json('error', 'Method not allowed');
            return;
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $uid = '';

        if (is_array($json) && isset($json['uid'])) {
            $uid = $json['uid'];
        } else {
            $uid = $this->input->post('uid');
        }

        $uid = $this->_normalize_uid($uid);

        if ($uid === '') {
            $this->_json('error', 'UID kartu kosong', [
                'line1' => 'Scan gagal',
                'line2' => 'UID kosong'
            ]);
            return;
        }

        $user = $this->user_model->get_by_uid($uid);

        if (!$user) {
            $this->invalid_model->insert([
                'uid_rfid' => $uid,
                'tanggal'  => date('Y-m-d'),
                'waktu'    => date('H:i:s')
            ]);

            $this->_json('invalid', 'Kartu belum terdaftar', [
                'uid'   => $uid,
                'line1' => 'Kartu invalid',
                'line2' => 'Belum terdaftar'
            ]);
            return;
        }

        $active_kelas = $this->absensi_model->get_active_kelas();

        if (!$active_kelas) {
            $this->_json('error', 'Belum ada kelas aktif untuk presensi', [
                'line1' => 'Kelas belum aktif',
                'line2' => 'Pilih di admin'
            ]);
            return;
        }

        if (!$this->user_model->has_mata_kuliah($user->id, $active_kelas->mata_kuliah)) {
            $this->_json('denied', 'User tidak terdaftar pada mata kuliah aktif', [
                'nama'        => $user->nama,
                'mata_kuliah' => $active_kelas->mata_kuliah,
                'line1'       => $user->nama,
                'line2'       => 'MK tidak sesuai'
            ]);
            return;
        }

        $kelas_id = (int) $active_kelas->id;
        $jam_masuk = $active_kelas->jam_masuk;
        $jam_masuk = $jam_masuk ? substr($jam_masuk, 0, 5) : '07:00';

        if ($this->absensi_model->sudah_absen($user->id, $kelas_id)) {
            $this->_json('already', 'Presensi untuk kode kelas ini sudah tercatat hari ini', [
                'nama'   => $user->nama,
                'status' => 'already',
                'line1'  => $user->nama,
                'line2'  => 'Sudah presensi'
            ]);
            return;
        }

        $waktu = date('H:i:s');
        $status = ($waktu <= $jam_masuk . ':00') ? 'hadir' : 'telat';

        $this->absensi_model->insert([
            'user_id' => $user->id,
            'kelas_id' => $kelas_id,
            'tanggal' => date('Y-m-d'),
            'waktu'   => $waktu,
            'status'  => $status
        ]);

        $message = ($status === 'telat') ? 'Anda terlambat' : 'Presensi berhasil';

        $this->_json('success', $message, [
            'nama'           => $user->nama,
            'waktu'          => $waktu,
            'status'         => $status,
            'batas_presensi' => $jam_masuk,
            'line1'          => $user->nama,
            'line2'          => $message
        ]);
    }

    private function _json($status, $message, $data = null)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => $status,
                'message' => $message,
                'data'    => $data
            ]));
    }
}
