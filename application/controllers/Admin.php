<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('auth');
        }
        $this->load->model('user_model');
        $this->load->model('absensi_model');
        $this->load->model('invalid_model');
        $this->load->model('kelas_model');
    }

    private function _template($view, $data)
    {
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }

    private function _normalize_uid($uid)
    {
        return strtoupper(preg_replace('/\s+/', '', trim((string) $uid)));
    }

    private function _build_user_payload($uid = null)
    {
        $uid_value = ($uid !== null) ? $uid : $this->input->post('uid_rfid');
        $mata_kuliah_input = $this->input->post('mata_kuliah');
        $mata_kuliah_list = is_array($mata_kuliah_input) ? $mata_kuliah_input : [];
        $mata_kuliah_list = array_values(array_unique(array_filter(array_map('trim', $mata_kuliah_list))));

        return [
            'nama'          => trim($this->input->post('nama')),
            'no_hp'         => trim($this->input->post('no_hp')),
            'uid_rfid'      => $this->_normalize_uid($uid_value),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'kelas_id'      => null,
            'mata_kuliah'   => $mata_kuliah_list
        ];
    }

    private function _validate_user_payload($data)
    {
        if ($data['nama'] === '' || $data['uid_rfid'] === '') {
            return 'Nama dan UID RFID wajib diisi.';
        }

        if (!in_array($data['jenis_kelamin'], ['L', 'P'], true)) {
            return 'Jenis kelamin wajib dipilih.';
        }

        if (empty($data['mata_kuliah'])) {
            return 'Mata kuliah wajib dipilih minimal 1.';
        }

        foreach ($data['mata_kuliah'] as $mata_kuliah) {
            if (!$this->kelas_model->mata_kuliah_exists($mata_kuliah)) {
                return 'Mata kuliah tidak valid.';
            }
        }

        return null;
    }

    private function _build_kelas_payload()
    {
        return [
            'kelas'       => trim($this->input->post('kelas')),
            'mata_kuliah' => trim($this->input->post('mata_kuliah')),
            'ruangan'     => trim($this->input->post('ruangan')),
            'jam_masuk'   => trim($this->input->post('jam_masuk'))
        ];
    }

    private function _validate_kelas_payload($data)
    {
        if ($data['kelas'] === '' || $data['mata_kuliah'] === '' || $data['ruangan'] === '' || $data['jam_masuk'] === '') {
            return 'Kelas, mata kuliah, ruangan, dan jam masuk wajib diisi.';
        }

        if (!preg_match('/^\d{2}:\d{2}$/', $data['jam_masuk'])) {
            return 'Format jam masuk kelas tidak valid.';
        }

        return null;
    }

    private function _active_class_context()
    {
        $active_class = $this->absensi_model->get_active_kelas();

        return [
            'active_class'    => $active_class,
            'active_kelas_id' => $active_class ? (int) $active_class->id : null
        ];
    }

    private function _group_today_absensi($rows, $active_kelas_id)
    {
        $active_rows = [];
        $other_groups = [];

        foreach ($rows as $row) {
            $row_kelas_id = ($row->kelas_id === null) ? null : (int) $row->kelas_id;

            if ($active_kelas_id !== null && $row_kelas_id === (int) $active_kelas_id) {
                $active_rows[] = $row;
                continue;
            }

            $group_key = ($row_kelas_id === null) ? 'no_class' : (string) $row_kelas_id;

            if (!isset($other_groups[$group_key])) {
                $other_groups[$group_key] = [
                    'kelas_id'     => $row_kelas_id,
                    'kode_kelas'   => $row->kelas ? $row->kelas : '-',
                    'mata_kuliah'  => $row->mata_kuliah ? $row->mata_kuliah : '-',
                    'ruangan'      => $row->ruangan ? $row->ruangan : '-',
                    'jam_masuk'    => $row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-',
                    'items'        => []
                ];
            }

            $other_groups[$group_key]['items'][] = $row;
        }

        $other_groups = array_values($other_groups);

        usort($other_groups, function ($a, $b) {
            if ($a['jam_masuk'] === $b['jam_masuk']) {
                return strcmp($a['kode_kelas'], $b['kode_kelas']);
            }

            if ($a['jam_masuk'] === '-') {
                return 1;
            }

            if ($b['jam_masuk'] === '-') {
                return -1;
            }

            return strcmp($a['jam_masuk'], $b['jam_masuk']);
        });

        return [
            'active_rows'   => $active_rows,
            'other_groups'  => $other_groups
        ];
    }

    public function index()
    {
        $this->absensi_model->cleanup_orphan_today();
        $active_context = $this->_active_class_context();
        $jam_masuk = $active_context['active_class'] ? substr($active_context['active_class']->jam_masuk, 0, 5) : $this->absensi_model->get_setting('jam_masuk');

        $this->_template('dashboard', [
            'title'         => 'Dashboard',
            'active'        => 'dashboard',
            'total_murid'   => $this->user_model->count_all(),
            'total_absensi' => $this->absensi_model->count_today(),
            'total_hadir'   => $this->absensi_model->count_today_by_status('hadir'),
            'total_telat'   => $this->absensi_model->count_today_by_status('telat'),
            'total_invalid' => $this->invalid_model->count_all(),
            'jam_masuk'     => $jam_masuk,
            'active_class'  => $active_context['active_class']
        ]);
    }

    public function absensi()
    {
        $this->absensi_model->cleanup_orphan_today();
        $active_context = $this->_active_class_context();
        $jam_masuk = $active_context['active_class'] ? substr($active_context['active_class']->jam_masuk, 0, 5) : $this->absensi_model->get_setting('jam_masuk');
        $today_absensi = $this->absensi_model->get_today();
        $grouped_absensi = $this->_group_today_absensi($today_absensi, $active_context['active_kelas_id']);

        $this->_template('absensi', [
            'title'           => 'Absensi Hari Ini',
            'active'          => 'absensi',
            'absensi'         => $today_absensi,
            'active_absensi'  => $grouped_absensi['active_rows'],
            'other_absensi_groups' => $grouped_absensi['other_groups'],
            'users'           => $this->user_model->get_all(),
            'classes'         => $this->kelas_model->get_all(),
            'mata_kuliah_options' => $this->kelas_model->get_mata_kuliah_options(),
            'jam_masuk'       => $jam_masuk,
            'active_class'    => $active_context['active_class'],
            'active_kelas_id' => $active_context['active_kelas_id']
        ]);
    }

    public function create_user()
    {
        $data = $this->_build_user_payload();
        $error = $this->_validate_user_payload($data);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('admin/absensi');
            return;
        }

        if ($this->user_model->uid_exists($data['uid_rfid'])) {
            $this->session->set_flashdata('error', 'UID sudah terdaftar.');
            redirect('admin/absensi');
            return;
        }

        $this->user_model->insert($data);
        $this->invalid_model->delete_by_uid($data['uid_rfid']);

        $this->session->set_flashdata('success', 'User baru berhasil ditambahkan.');
        redirect('admin/absensi');
    }

    public function update_user($id)
    {
        $user = $this->user_model->get_by_id($id);

        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/absensi');
            return;
        }

        $data = $this->_build_user_payload();
        $error = $this->_validate_user_payload($data);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('admin/absensi');
            return;
        }

        if ($this->user_model->uid_exists($data['uid_rfid'], $id)) {
            $this->session->set_flashdata('error', 'UID sudah digunakan user lain.');
            redirect('admin/absensi');
            return;
        }

        $this->user_model->update($id, $data);
        $this->invalid_model->delete_by_uid($data['uid_rfid']);

        $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
        redirect('admin/absensi');
    }

    public function hapus_user($id)
    {
        $user = $this->user_model->get_by_id($id);

        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/absensi');
            return;
        }

        $this->user_model->delete($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('admin/absensi');
    }

    public function hapus_absensi($id)
    {
        $this->absensi_model->delete($id);
        $this->session->set_flashdata('success', 'Data absensi berhasil dihapus.');
        redirect('admin/absensi');
    }

    public function kelola_kelas()
    {
        $active_context = $this->_active_class_context();

        $this->_template('kelola_kelas', [
            'title'           => 'Kelola Kelas',
            'active'          => 'kelas',
            'classes'         => $this->kelas_model->get_all(),
            'active_class'    => $active_context['active_class'],
            'active_kelas_id' => $active_context['active_kelas_id']
        ]);
    }

    public function aktifkan_kelas($id)
    {
        $kelas = $this->kelas_model->get_by_id($id);

        if (!$kelas) {
            $this->session->set_flashdata('error', 'Data kelas tidak ditemukan.');
            redirect('admin/kelola_kelas');
            return;
        }

        $this->absensi_model->set_active_kelas($kelas->id);
        $this->absensi_model->assign_today_kelas($kelas->id);

        $this->session->set_flashdata('success', 'Kode kelas ' . $kelas->kelas . ' berhasil dijadikan presensi aktif. Scan berikutnya akan masuk ke kelas ini.');
        redirect('admin/kelola_kelas');
    }

    public function create_kelas()
    {
        $data = $this->_build_kelas_payload();
        $error = $this->_validate_kelas_payload($data);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('admin/kelola_kelas');
            return;
        }

        $this->kelas_model->insert($data);
        $this->session->set_flashdata('success', 'Kelas baru berhasil ditambahkan.');
        redirect('admin/kelola_kelas');
    }

    public function update_kelas($id)
    {
        $kelas = $this->kelas_model->get_by_id($id);

        if (!$kelas) {
            $this->session->set_flashdata('error', 'Data kelas tidak ditemukan.');
            redirect('admin/kelola_kelas');
            return;
        }

        $data = $this->_build_kelas_payload();
        $error = $this->_validate_kelas_payload($data);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('admin/kelola_kelas');
            return;
        }

        $this->kelas_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data kelas berhasil diperbarui.');
        redirect('admin/kelola_kelas');
    }

    public function hapus_kelas($id)
    {
        $kelas = $this->kelas_model->get_by_id($id);
        $active_kelas_id = $this->absensi_model->get_active_kelas_id();

        if (!$kelas) {
            $this->session->set_flashdata('error', 'Data kelas tidak ditemukan.');
            redirect('admin/kelola_kelas');
            return;
        }

        if ((int) $active_kelas_id === (int) $id) {
            $this->session->set_flashdata('error', 'Kelas yang sedang aktif untuk presensi hari ini tidak bisa dihapus.');
            redirect('admin/kelola_kelas');
            return;
        }

        $this->db->trans_start();
        $deleted_today_absensi = $this->absensi_model->delete_today_by_kelas($id);

        if ($this->kelas_model->count_other_by_mata_kuliah($id, $kelas->mata_kuliah) === 0) {
            $this->kelas_model->remove_mata_kuliah_from_users($kelas->mata_kuliah);
        }

        $this->kelas_model->delete($id);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            $this->session->set_flashdata('error', 'Data kelas gagal dihapus.');
            redirect('admin/kelola_kelas');
            return;
        }

        $message = 'Data kelas berhasil dihapus.';

        if ($deleted_today_absensi > 0) {
            $message .= ' Presensi hari ini untuk kelas ini juga ikut dibersihkan.';
        }

        $this->session->set_flashdata('success', $message);
        redirect('admin/kelola_kelas');
    }

    public function invalid_user()
    {
        $this->_template('invalid_user', [
            'title'         => 'Invalid User',
            'active'        => 'invalid',
            'invalid_cards' => $this->invalid_model->get_all(),
            'classes'       => $this->kelas_model->get_all(),
            'mata_kuliah_options' => $this->kelas_model->get_mata_kuliah_options()
        ]);
    }

    public function hapus_invalid($id)
    {
        $this->invalid_model->delete($id);
        $this->session->set_flashdata('success', 'Data invalid berhasil dihapus.');
        redirect('admin/invalid_user');
    }

    public function register_user()
    {
        $invalid_id = $this->input->post('invalid_id');
        $invalid = $this->invalid_model->get_by_id($invalid_id);

        if (!$invalid) {
            $this->session->set_flashdata('error', 'Data invalid tidak ditemukan.');
            redirect('admin/invalid_user');
            return;
        }

        $uid = $this->_normalize_uid($invalid->uid_rfid);
        $data = $this->_build_user_payload($uid);
        $error = $this->_validate_user_payload($data);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('admin/invalid_user');
            return;
        }

        if ($this->user_model->uid_exists($uid)) {
            $this->session->set_flashdata('error', 'UID sudah terdaftar.');
            redirect('admin/invalid_user');
            return;
        }

        $this->user_model->insert($data);
        $this->invalid_model->delete_by_uid($uid);

        $this->session->set_flashdata('success', 'User baru berhasil didaftarkan.');
        redirect('admin/invalid_user');
    }

    public function laporan()
    {
        $dari = $this->input->get('dari');
        $sampai = $this->input->get('sampai');
        $kelas_id = trim((string) $this->input->get('kelas_id'));
        $mata_kuliah = trim((string) $this->input->get('mata_kuliah'));
        $laporan = null;
        $summary = [
            'total' => 0,
            'hadir' => 0,
            'telat' => 0
        ];

        if ($dari && $sampai) {
            $laporan = $this->absensi_model->get_laporan(
                $dari,
                $sampai,
                $kelas_id !== '' ? (int) $kelas_id : null,
                $mata_kuliah !== '' ? $mata_kuliah : null
            );
            $summary['total'] = count($laporan);

            foreach ($laporan as $row) {
                if ($row->status === 'telat') {
                    $summary['telat']++;
                } else {
                    $summary['hadir']++;
                }
            }
        }

        $this->_template('laporan', [
            'title'               => 'Laporan',
            'active'              => 'laporan',
            'laporan'             => $laporan,
            'dari'                => $dari,
            'sampai'              => $sampai,
            'kelas_id'            => $kelas_id,
            'mata_kuliah'         => $mata_kuliah,
            'summary'             => $summary,
            'classes'             => $this->kelas_model->get_all(),
            'mata_kuliah_options' => $this->kelas_model->get_mata_kuliah_options(),
            'jam_masuk'           => $this->absensi_model->get_setting('jam_masuk')
        ]);
    }

    public function pengaturan()
    {
        $timezone_options = [
            'Asia/Jakarta'  => 'Asia/Jakarta (UTC+07:00)',
            'Asia/Makassar' => 'Asia/Makassar (UTC+08:00)',
            'Asia/Jayapura' => 'Asia/Jayapura (UTC+09:00)',
            'Asia/Singapore'=> 'Asia/Singapore (UTC+08:00)'
        ];

        $this->_template('pengaturan', [
            'title'            => 'Pengaturan Jam Presensi',
            'active'           => 'pengaturan',
            'jam_masuk'        => $this->absensi_model->get_setting('jam_masuk'),
            'timezone'         => $this->absensi_model->get_setting('timezone') ?: $this->app_timezone,
            'timezone_options' => $timezone_options,
            'server_now'       => date('Y-m-d H:i:s')
        ]);
    }

    public function simpan_pengaturan()
    {
        $jam_masuk = trim($this->input->post('jam_masuk'));
        $timezone = trim($this->input->post('timezone'));

        if (!preg_match('/^\d{2}:\d{2}$/', $jam_masuk)) {
            $this->session->set_flashdata('error', 'Format jam presensi tidak valid.');
            redirect('admin/pengaturan');
            return;
        }

        if (!in_array($timezone, timezone_identifiers_list(), true)) {
            $this->session->set_flashdata('error', 'Timezone tidak valid.');
            redirect('admin/pengaturan');
            return;
        }

        $this->absensi_model->update_setting('jam_masuk', $jam_masuk);
        $this->absensi_model->update_setting('timezone', $timezone);
        date_default_timezone_set($timezone);

        $this->session->set_flashdata('success', 'Jam presensi dan timezone berhasil disimpan.');
        redirect('admin/pengaturan');
    }
}
