<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class Update_model extends CI_Model{
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

        public function save_salary_matrix($salary)
        {
            foreach ($salary as $sg => $steps) {
                foreach ($steps as $step => $amount) {
                    if ($amount === '' || $amount === null) continue;

                    $this->db->replace('tbl_py_salary_grade', [
                        'salary_grade' => $sg,
                        'step'         => $step,
                        'amount'       => $amount
                    ]);
                }
            }
        }

        public function update_password($user_id, $new_password)
        {
            return $this->db->where('user_id', $user_id)
                            ->update('user', [
                                'password' => password_hash($new_password, PASSWORD_BCRYPT),
                                'force_change' => 1
                            ]);
        }

        public function lastLogin($user_id) {
		    $this->db->where('user_id', $user_id)->update('user', ['last_login' => date('Y-m-d H:i:s')]);
		}

        public function edit_rate($data) {
		    $this->db->where('employee_id', $this->input->post('employee_id'));
			$this->db->update('tbl_py_employee_rate', $data);
		}

        public function mark_received_accounting($id)
        {
            return $this->db
                ->where('payroll_period_id', $id)
                ->update('tbl_py_payroll_period', [
                    'date_time_received_accounting' => date('Y-m-d H:i:s')
                ]);
        }

        public function mark_received_admin($id)
        {
            return $this->db
                ->where('payroll_period_id', $id)
                ->update('tbl_py_payroll_period', [
                    'date_time_received_admin' => date('Y-m-d H:i:s')
                ]);
        }

         public function updatePayrollGP($data)
        {
            $payroll_id = $data['payroll_id'];

            if (isset($data['other_deductions']) && is_array($data['other_deductions'])) {
                $data['other_deductions'] = json_encode($data['other_deductions']);
            }

            unset($data['payroll_id']);

            $this->db->where('payroll_id', $payroll_id);

            if (!$this->db->update('tbl_py_payroll', $data)) {
                log_message('error', print_r($this->db->error(), true));
                return false;
            }

            return true;
        }

        public function update_login_receiver($id) {
            $this->db->where('receiver_id', $id);
            $this->db->update('tbl_py_payroll_receivers', [
                'last_login' => date('Y-m-d H:i:s')
            ]);
        }

    }