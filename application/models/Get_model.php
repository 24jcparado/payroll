<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class Get_model extends CI_Model{
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

        public function getUserByEmail($email)
        {
            return $this->db
                        ->where('username', $email)
                        ->limit(1)
                        ->get('user')
                        ->result_array();
        }

		public function generate_payroll_number()
            {
                $year = date('Y');
                $prefix = 'P' . $year . '-';
                $this->db->like('payroll_number', $prefix, 'after');
                $this->db->order_by('payroll_period_id', 'DESC');
                $this->db->limit(1);
                $last = $this->db->get('tbl_py_payroll_period')->row();

                if ($last) {
                    $last_number = intval(substr($last->payroll_number, 6));
                    $next_number = $last_number + 1;
                } else {
                    $next_number = 1;
                }

                $next_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);

                return $prefix . $next_number;
            }
        public function get_payrolls()
            {
                return $this->db->order_by('payroll_period_id', 'DESC')->get('tbl_py_payroll_period')->result();
            }

        public function get_recent_received_payrolls($role)
        {
            // Build dynamic column name
            $column = 'date_time_received_' . strtolower($role);

            return $this->db
                ->where("$column IS NOT NULL", null, false)
                ->where("$column >=", date('Y-m-d H:i:s', strtotime('-1 day')))
                ->order_by('payroll_period_id', 'DESC')
                ->get('tbl_py_payroll_period')
                ->result();
        }
        public function get_received_payrolls($role)
        {
            // Build dynamic column name
            $column = 'date_time_received_' . strtolower($role);

            return $this->db
                ->where("$column IS NOT NULL", null, false)
                ->order_by('payroll_period_id', 'DESC')
                ->get('tbl_py_payroll_period')
                ->result();
        }

         public function get_fund()
            {
                return $this->db->get('tbl_py_funds')->result();
            }

        public function getEmployee($campus = 'MAIN')
            {
                return $this->db
                    ->select('tbl_employee.*, tbl_item.item')
                    ->from('tbl_employee')
                    ->join('tbl_item', 'tbl_item.item_id = tbl_employee.item_id', 'left')
                    ->where('tbl_employee.campus', $campus)
                    ->order_by('tbl_employee.name', 'ASC')
                    ->get()
                    ->result();
            }

        public function run_payroll($period_id)
        {
            $employees = $this->db->get('tbl_employee')->result();

            foreach ($employees as $emp) {

                $ded = $this->db->get_where('tbl_deductions', [
                    'employee_id' => $emp->employee_id
                ])->row();

                $gross = $emp->basic_salary;
                $total_deductions =
                    ($ded->gsis ?? 0) +
                    ($ded->philhealth ?? 0) +
                    ($ded->pagibig ?? 0) +
                    ($ded->tax ?? 0) +
                    ($ded->loans ?? 0);

                $net = $gross - $total_deductions;

                $this->db->insert('tbl_payroll_summary', [
                    'payroll_period_id' => $period_id,
                    'employee_id' => $emp->employee_id,
                    'gross_pay' => $gross,
                    'total_deductions' => $total_deductions,
                    'net_pay' => $net,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->db->update(
                'tbl_payroll_period',
                ['status' => 'PROCESSED'],
                ['payroll_period_id' => $period_id]
            );

            return true;
        }

         public function get_period($period_id)
        {
            return $this->db->get_where('tbl_py_payroll_period', [
                'payroll_period_id' => $period_id
            ])->row();
        }


        public function get_payroll_entries($period_id)
        {
            $this->db->select('s.*, e.name, e.unit, e.position, e.sg, e.step');
            $this->db->from('tbl_py_payroll_summary s');
            $this->db->join('tbl_employee e', 'e.employee_id = s.employee_id', 'left');
            $this->db->where('s.payroll_period_id', $period_id);
            $this->db->order_by('e.name', 'ASC');
            return $this->db->get()->result();
        }


        public function get_employees_for_payroll($period_id, $unit, $campus = 'MAIN')
        {
            $this->db->select('
                e.*,
                sg.amount AS basic_salary,
                d.gsis,
                d.philhealth,
                d.pagibig,
                d.tax,
                IFNULL(ed.total_deductions, 0) AS other_deductions
            ');

            $this->db->from('tbl_employee e');

            // 🔹 Join Salary Grade Table (SSL 2026)
            $this->db->join(
                'tbl_py_salary_grade sg',
                'sg.salary_grade = e.sg 
                AND sg.step = e.step 
                AND sg.year = 2026',
                'left'
            );

            $this->db->join(
                'tbl_py_deductions d',
                'd.employee_id = e.employee_id',
                'left'
            );

            $this->db->join(
                'tbl_py_payroll_summary s',
                's.employee_id = e.employee_id 
                AND s.payroll_period_id = ' . (int)$period_id,
                'left'
            );

            $this->db->join(
                '(SELECT employee_id, SUM(amount) AS total_deductions
                FROM tbl_py_employee_loans
                WHERE status = 1
                GROUP BY employee_id) ed',
                'ed.employee_id = e.employee_id',
                'left'
            );

            $this->db->where('s.id IS NULL');
            $this->db->where('e.campus', $campus);
            $this->db->where('e.assignment', $unit);

            $this->db->order_by('e.name', 'ASC');

            return $this->db->get()->result();
        }


        public function get_all_deductions()
        {
            return $this->db->order_by('deduction_id', 'ASC')->get('tbl_py_list_deductions')->result();
        }
        public function get_deductionbyID($deduction_id)
        {
            return $this->db->where('tbl_py_employee_loans.deduction_id', $deduction_id)
                            ->join('tbl_employee', 'tbl_employee.employee_id = tbl_py_employee_loans.employee_id')
                            ->join('tbl_py_list_deductions', 'tbl_py_list_deductions.deduction_id = tbl_py_employee_loans.deduction_id')
                            ->get('tbl_py_employee_loans')->result();
        }

        public function getEmployeeDeductions($emp_id)
            {
                return $this->db
                    ->select('d.deduction_id, d.deduction_name, ed.amount, ed.monthly_deduction')
                    ->from('tbl_py_employee_loans ed')
                    ->join('tbl_py_list_deductions d', 'd.deduction_id = ed.deduction_id')
                    ->where('ed.employee_id', $emp_id)
                    ->where('ed.status', 1)
                    ->where('d.is_active', 1)
                    ->get()
                    ->result();
            }
        public function getActiveEmployeeDeductions($emp_id)
            {
                return $this->db
                    ->select('d.deduction_name, ed.amount, ed.type')
                    ->from('tbl_employee_deductions ed')
                    ->join('tbl_deductions d', 'd.deduction_id = ed.deduction_id')
                    ->where('ed.employee_id', $emp_id)
                    ->where('ed.status', 'ACTIVE')
                    ->get()
                    ->result();
            }
        public function getEmployeeSalary($employee_id)
        {
            return $this->db
                ->select('e.employee_id, sg.amount')
                ->from('tbl_employee e')
                ->join(
                    'tbl_py_salary_grade sg',
                    'sg.salary_grade = e.sg 
                    AND sg.step = e.step 
                    AND sg.year = 2026', // Change to dynamic year if needed
                    'left'
                )
                ->where('e.employee_id', $employee_id)
                ->get()
                ->row();
        }

        public function getAllSalaryGrades($year = 2026)
        {
            return $this->db
                ->from('tbl_py_salary_grade')
                ->where('year', $year)
                ->order_by('salary_grade ASC, step ASC')
                ->get()
                ->result();
        }

        public function getEmployeeName($employee_id)
        {
            return $this->db
                ->where('employee_id', $employee_id)
                ->get('tbl_employee')
                ->row();
        }
        public function getPayrollByPeriod($payroll_period_id)
        {
            return $this->db
                ->select('
                    p.*,
                    e.*,
                    d.date_period,
                    d.qr_code,
                    p.name
                ')
                ->from('tbl_py_payroll p')
                ->join('tbl_employee e', 'e.employee_id = p.employee_id')
                ->join('tbl_py_payroll_period d', 'd.payroll_period_id = p.payroll_period_id')
                ->where('p.payroll_period_id', $payroll_period_id)
                ->order_by('e.name', 'DESC')
                ->get()
                ->result();
        }


        public function getPayrollByPeriodDW($payroll_period_id)
        {
            return $this->db
                ->select('
                    p.*,
                    d.date_period,
                    d.qr_code,
                    p.name,
                    e.position,
                    e.unit
                ')
                ->from('tbl_py_payroll_dw p')
                ->join('tbl_employee e', 'e.employee_id = p.employee_id')
                ->join('tbl_py_payroll_period d', 'd.payroll_period_id = p.payroll_period_id')
                ->where('p.payroll_period_id', $payroll_period_id)
                ->order_by('e.name', 'DESC')
                ->get()
                ->result();
        }
        public function get_midyear_payroll($period_id)
        {
            $this->db->select("
                p.*,
                CONCAT(e.last_name, ', ', e.name, ' ', LEFT(e.middle_name,1), '.') as name,
                e.position
            ");

            $this->db->from('tbl_py_midyear_bonus p');
            $this->db->join('tbl_employee e','e.employee_id = p.employee_id','left');

            $this->db->where('p.payroll_period_id', $period_id);
            $this->db->order_by('e.last_name','ASC');

            return $this->db->get()->result();
        }
        public function getPayrollByPeriodMY($payroll_period_id)
        {
            return $this->db
            ->select('*')
            ->from('tbl_py_midyear_bonus')
            ->where('payroll_period_id', $payroll_period_id)
            ->get()
            ->result_array(); 
        }

        public function getPayrollByPeriodOJT($payroll_period_id)
        {
            return $this->db
            ->select('*')
            ->from('tbl_py_ojt_honorarium')
            ->where('payroll_period_id', $payroll_period_id)
            ->get()
            ->result_array(); 
        }
        public function getEmployeeLoans($employee_id, $payroll_type)
        {
             return $this->db
                ->select('d.deduction_id as deduction_id, d.deduction_name, l.amount, l.monthly_deduction')
                ->from('tbl_py_employee_loans l')
                ->join('tbl_py_list_deductions d', 'd.deduction_id = l.deduction_id')
                ->where('l.employee_id', $employee_id)
                ->where('l.status', '1')
                ->where('d.applicable', $payroll_type)
                ->get()
                ->result_array();
            }
        public function getPayrollHistoryByEmployee($employee_id)
        {
            return $this->db
                ->select('p.*, e.name')
                ->from('tbl_py_payroll p')
                ->join('tbl_employee e', 'e.employee_id = p.employee_id')
                ->where('p.employee_id', $employee_id)
                ->order_by('p.created_at', 'DESC')
                ->get()
                ->result();
        }

        public function getPayrollHistoryMY($employee_id)
        {
            return $this->db
                ->select('
                    m.*,
                    e.name,
                    e.position
                ')
                ->from('tbl_py_midyear_bonus m')
                ->join('tbl_employee e', 'e.employee_id = m.employee_id', 'left')
                ->where('m.employee_id', $employee_id)
                ->order_by('m.created_at', 'DESC')
                ->get()
                ->result();
        }


        public function get_employee_ledger($employee_id, $from = null, $to = null)
        {
            $this->db->select('
                p.*,
                pp.*
            ');
            $this->db->from('tbl_py_payroll p');
            $this->db->join('tbl_py_payroll_period pp', 'pp.payroll_period_id = p.payroll_period_id');
            $this->db->where('p.employee_id', $employee_id);
            return $this->db->get()->result();
        }

        public function getBirthdaysByMonth($month)
        {
            return $this->db
                        ->where('MONTH(date_of_birth)', $month)
                        ->get('tbl_employee')
                        ->result_array();
        }

        public function get_employees()
        {
            $campus = $this->session->userdata('campus'); // get usertype from session
            $this->db->where('campus', $campus);
            $employees = $this->db->get('tbl_employee')->result();
            return $employees;
        }

        public function get_rate($employee_id)
        {
            return $this->db
                ->where('employee_id', $employee_id)
                ->get('tbl_py_employee_rate')
                ->row();
        }

        public function get_rate_per_hour()
        {
            $this->db->select('r.*, e.name, e.middle_name, e.last_name, e.position');
            $this->db->from('tbl_py_employee_rate r');
            $this->db->join('tbl_employee e', 'e.employee_id = r.employee_id', 'inner');
            $this->db->order_by('e.last_name', 'asc');
            return $this->db->get()->result();
        }

        public function get_employees_with_rate($payroll_period_id, $unit)
        {
            $this->db->select('e.employee_id, e.name, e.middle_name, e.last_name, e.position, r.rate_per_hour, r.tax');
            $this->db->from('tbl_py_employee_rate r');
            $this->db->join('tbl_employee e', 'e.employee_id = r.employee_id', 'left'); // left join, in case rae not set yet
            $this->db->where('e.assignment', $unit);
            $this->db->order_by('e.last_name', 'asc');
            return $this->db->get()->result();
        }
        public function get_rate_per_employee_period($employee_id, $period_id)
            {
                return $this->db->get_where('tbl_py_payroll_opt', [
                    'employee_id' => $employee_id,
                    'payroll_period_id' => $period_id
                ])->row();
            }

        public function save_rate($data)
        {
            $existing = $this->get_rate($data['employee_id']);

            if ($existing) {
                $this->db->where('employee_id', $data['employee_id']);
                return $this->db->update('employee_rates', [
                    'rate_per_hour' => $data['rate_per_hour'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                return $this->db->insert('tbl_py_employee_rate', $data);
            }
        }

        function getPayrollOPT($period_id)
        {
          $payrolls = $this->db
            ->select([
                'p.*',                  // Payroll details
                'r.*',       // Employee rate
                'e.name', 
                'e.middle_name', 
                'e.last_name',
                'e.ext',  
                'e.position'
            ])
            ->from('tbl_py_payroll_opt p')
            ->join('tbl_employee e', 'p.employee_id = e.employee_id', 'left')
            ->join('tbl_py_employee_rate r', 'p.employee_id = r.employee_id', 'left')
            ->where('p.payroll_period_id', $period_id)
            ->order_by('e.name', 'ASC')
            ->get()
            ->result();

        return $payrolls;
        }

        // Verify Payroll Token
       public function get_payroll_by_token($token)
        {
            return $this->db
                ->where('token_id', $token)
                ->get('tbl_py_payroll_period')
                ->row();
        }

        public function get_grouped_payroll($id)
        {
            $data = $this->db
                ->where('payroll_period_id', $id)
                ->get('tbl_py_payroll_opt')
                ->result();

            $grouped = [];

            foreach ($data as $row) {
                $grouped[$row->school_year][] = $row;
            }

            return $grouped;
        }
        public function get_grouped_general_payroll($id)
        {
            return $this->db
                ->where('payroll_period_id', $id)
                ->join('tbl_employee e', 'p.employee_id = e.employee_id', 'left')
                ->get('tbl_py_payroll p')
                ->result();
        }
        public function get_grouped_midyear_payroll($id)
        {
            return $this->db
                ->where('payroll_period_id', $id)
                ->join('tbl_employee e', 'p.employee_id = e.employee_id', 'left')
                ->get('tbl_py_midyear_bonus p')
                ->result();
        }

        public function get_signatories()
        {
            return $this->db->get('tbl_py_signatory')->result();
        }

        public function getPayrollByIdGP($id)
        {
            $this->db->where('payroll_id', $id);
            $query = $this->db->get('tbl_py_payroll');

            $row = $query->row_array();

            if (!$row) {
                return [];
            }
            if (!empty($row['other_deductions'])) {
                $row['other_deductions'] = json_decode($row['other_deductions'], true);
            } else {
                $row['other_deductions'] = [];
            }

            return $row;
        }

        public function get_proof_listGP($payroll_period_id)
        {
            $this->db->select('
                p.employee_id,
                e.name,
                e.middle_name,
                e.last_name,
                e.account_no,
                p.basic_salary,
                p.gross_pay,
                p.total_deductions,
                p.net_pay
            ');
            $this->db->from('tbl_py_payroll p');
            $this->db->join('tbl_employee e', 'e.employee_id = p.employee_id');
            $this->db->where('p.payroll_period_id', $payroll_period_id);
            $this->db->order_by('e.last_name', 'ASC');

            return $this->db->get()->result();
        }



        public function get_user_receiver($username) {
            return $this->db
                ->where('username', $username)
                ->get('tbl_py_payroll_receivers')
                ->row();
        }

        public function get_receiver(){
            return $this->db
                ->get('tbl_py_payroll_receivers')
                ->result();
        }

        public function get_payroll_by_id($id)
        {
            return $this->db
                ->where('payroll_period_id', $id)
                ->get('tbl_py_payroll_period')
                ->row();
        }

        public function get_employee_deductions($period_id)
        {
            $result = $this->db
                ->select('employee_id, deduction_name, amount')
                ->from('tbl_py_payroll')
                ->where('period_id', $period_id)
                ->get()
                ->result();

            $data = [];

            foreach ($result as $row) {
                $data[$row->employee_id][$row->deduction_name] = $row->amount;
            }

            return $data;
        }

        public function get_other_deduction_columns($period_id)
        {
            return $this->db
                ->select('DISTINCT deduction_name')
                ->from('tbl_py_payroll')
                ->where('period_id', $period_id)
                ->order_by('deduction_name', 'ASC')
                ->get()
                ->result();
        }

        public function getPayrollDW()
        {
            $this->db->select('
                tbl_py_payroll_dw.*,
                CONCAT(tbl_employee.name, " ", tbl_employee.last_name) AS employee_name,
                tbl_employee.position
            ');

            $this->db->from('tbl_py_payroll_dw');
            $this->db->join(
                'tbl_employee',
                'tbl_employee.employee_id = tbl_py_payroll_dw.employee_id',
                'left'
            );

            return $this->db->get()->result();
        }

        public function getGSISDataByPayroll($payroll_period_id)
        {
            $campus = $this->session->userdata('campus');

            $this->db->select('e.*, p.gsis, p.pagibig, p.philhealth');
            $this->db->from('tbl_employee e');
            $this->db->join('tbl_py_payroll p', 'p.employee_id = e.employee_id');

            $this->db->where('p.payroll_period_id', $payroll_period_id);

            if (!empty($campus)) {
                $this->db->where('e.campus', $campus);
            }

            return $this->db->get()->result();
        }

        public function getPayrollInfo($payroll_id)
        {
            return $this->db
                ->where('payroll_id', $payroll_id)
                ->get('tbl_py_payroll')
                ->row();
        }

        public function getEmployeesWithoutGSIS()
        {
            $this->db->from('tbl_employee');

            // gsis_no is NULL or empty
            $this->db->group_start();
                $this->db->where('gsis_no IS NULL', null, false);
                $this->db->or_where('gsis_no', '');
            $this->db->group_end();

            return $this->db->get()->result();
        }

    }