<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class Insert_model extends CI_Model{
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		 public function insert_payroll_entry($data){
            return $this->db->insert('tbl_py_payroll_period', $data);
        }
		public function insert_fund($data){
			return $this->db->insert('tbl_py_funds', $data);
		}
		public function insert_deduction($data)
        {
            return $this->db->insert('tbl_py_list_deductions', $data);
        }
		public function insert_employee_loan($data)
    	{
        return $this->db->insert('tbl_py_employee_loans', $data);
    	}

		public function insertPayroll(array $data)
		{
			$this->db->insert('tbl_py_payroll', $data);
			return $this->db->affected_rows() ? $this->db->insert_id() : false;
		}

		public function insertOtherDeductionsBatch(array $data)
		{
			return $this->db->insert_batch(
				'tbl_py_payroll_other_deductions',
				$data
			);
		}

		public function save_rate($data)
    	{
        return $this->db->insert('tbl_py_employee_rate', $data);
    	}

		public function save_payroll_opt($data)
		{
		return $this->db->insert('tbl_py_payroll_opt', $data);
		}
		public function insert($table, $data) {
			return $this->db->insert($table, $data);
		}

		public function insert_dw($data)
		{
			return $this->db->insert('tbl_py_payroll_dw', $data);
		}

		public function save_subsistence_payroll($data) {
			$this->db->where('payroll_period_id', $data['payroll_period_id']);
			$this->db->where('employee_id', $data['employee_id']);
			$existing_record = $this->db->get('tbl_py_subsistence')->row();

			if ($existing_record) {
				$this->db->where('id', $existing_record->id);
				unset($data['created_at']); 
				$data['updated_at'] = date('Y-m-d H:i:s');
				return $this->db->update('tbl_py_subsistence', $data);
			} else {
				return $this->db->insert('tbl_py_subsistence', $data);
			}
		}
    }