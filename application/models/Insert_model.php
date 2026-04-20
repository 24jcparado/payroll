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
    }