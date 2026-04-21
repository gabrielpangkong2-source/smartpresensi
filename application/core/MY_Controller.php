<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $app_timezone = 'Asia/Makassar';

    public function __construct()
    {
        parent::__construct();

        $timezone = 'Asia/Makassar';

        try {
            $row = $this->db->get_where('settings', ['setting_key' => 'timezone'])->row();

            if ($row && in_array($row->setting_value, timezone_identifiers_list(), true)) {
                $timezone = $row->setting_value;
            }
        } catch (Exception $e) {
            $timezone = 'Asia/Makassar';
        }

        date_default_timezone_set($timezone);
        $this->app_timezone = $timezone;
    }
}
