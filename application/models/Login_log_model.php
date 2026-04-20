<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_log_model extends CI_Model {
    public function add_log($data) {
        $this->db->insert('tbl_login_logs', $data);
    }
    public function get_logs_by_date($start_date = null, $end_date = null)
        {
            $this->db->select('*');
            $this->db->from('tbl_login_logs');

            if ($start_date && $end_date) {
                $this->db->where('created_at >=', $start_date . ' 00:00:00');
                $this->db->where('created_at <=', $end_date . ' 23:59:59');
            }
            
            $this->db->order_by('id', 'DESC');

            return $this->db->get()->result_array();
        }



}
