<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MY_Controller {

    function __construct(){
		parent::__construct();

		if (!$this->session->userdata('logged_in')) {
            redirect('welcome');
            exit;
        }
		 $this->output
            ->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0")
            ->set_header("Pragma: no-cache");
	}
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
            redirect('welcome');
            exit;
        }
		$data['period'] = 'Dashboard';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/dashboard', $data);
		$this->load->view('template/admin_footer');
	}
	// public function index()
	// {
	// 	echo '<pre>';
	// 	print_r($this->session->userdata());
	// 	exit;
	// }

	public function period()
	{
		$data['period'] = 'Payroll Period';
		$data['employee'] = $this->Get_model->getEmployee();
		$data['payroll_number'] = $this->Get_model->generate_payroll_number();
		$data['payroll'] = $this->Get_model->get_payrolls();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/periods', $data);
		$this->load->view('template/admin_footer');
	}
	public function fund()
	{
		$data['period'] = 'Payroll Fund';
		$data['employee'] = $this->Get_model->getEmployee();
		$data['fund'] = $this->Get_model->get_fund();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/fund', $data);
		$this->load->view('template/admin_footer');
	}
	public function employees()
	{
		$data['period'] = 'Employees';
		$data['payroll_number'] = $this->Get_model->generate_payroll_number();
		$data['payroll'] = $this->Get_model->get_payrolls();
		$data['employee'] = $this->Get_model->getEmployee();
		$data['total_employees'] = $this->db->count_all('tbl_employee');

		$data['by_unit'] = $this->db
			->select('unit, COUNT(*) as total')
			->group_by('unit')
			->get('tbl_employee')
			->result();

		$data['by_position'] = $this->db
			->select('position, COUNT(*) as total')
			->group_by('position')
			->get('tbl_employee')
			->result();
			
		$data['by_status'] = $this->db
			->select('status, COUNT(*) as total')
			->group_by('status')
			->get('tbl_employee')
			->result();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/employees', $data);
		$this->load->view('template/admin_footer');
	}

	  public function deductions()
    {
        $data['period'] = "Deductions"; 
        $data['deductions'] = $this->Get_model->get_all_deductions();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/deduction', $data);
		$this->load->view('template/admin_footer');
    }
    

    public function add_entry()
	{
		$payroll_type = $this->input->post('payroll_type', TRUE);
		$this->form_validation->set_rules('payroll_number', 'Payroll Number', 'required|trim');
		$this->form_validation->set_rules('unit', 'Unit', 'required|trim');
		$this->form_validation->set_rules('payroll_type', 'Type', 'required');


		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$date_from = $this->input->post('date_from', TRUE); // Expected input format: YYYY-MM-DD
		$date_to   = $this->input->post('date_to', TRUE);
		$start = date('m/d/Y', strtotime($date_from));
		$end   = date('m/d/Y', strtotime($date_to));
		$formatted_period = $start . ' - ' . $end;
		$data = [
			'payroll_number' => $this->input->post('payroll_number', TRUE),
			'unit'           => $this->input->post('unit', TRUE),
			'particulars'    => $this->input->post('particulars', TRUE),
			'date_period'    => $formatted_period, // The new combined column
			'payroll_type'   => $payroll_type,
			'status'         => 1,
			'created_at'     => date('Y-m-d H:i:s')
		];

		if($payroll_type == 'OJT HONORARIUM'){

			$data['date_period'] = $this->input->post('current_year', TRUE);
			$data['particulars'] = $this->input->post('particulars', TRUE);
		}

		if ($this->Insert_model->insert_payroll_entry($data)) {
			$this->session->set_flashdata('success', 'Payroll entry added successfully.');
		} else {
			$this->session->set_flashdata('error', 'Failed to add payroll entry.');
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	public function view($period_id)
	{
		$data['period'] = $this->Get_model->get_period($period_id);
		$data['employees'] = $this->Get_model->get_payroll_entries($period_id);
		$this->load->view('payroll/view_payroll', $data);
	}

	public function run($period_id)
	{
		$period = $this->Get_model->get_period($period_id);
		if (!$period) {
			show_404();
		}
		switch ($period->payroll_type) {
			case 'GENERAL PAYROLL':
				redirect('payroll/run_general_payroll/' . $period_id);
				break;
			case 'OVERLOAD':
				redirect('payroll/run_overload/' . $period_id);
				break;
			case 'PART-TIME':
				redirect('payroll/run_overload/' . $period_id);
				break;
			case 'MID-YEAR BONUS':
				redirect('payroll/run_midyear_bonus/' . $period_id);
				break;
			case 'OJT HONORARIUM':
				redirect('payroll/run_ojt_honorarium/' . $period_id);
				break;
			case 'DAILY WAGE':
				redirect('payroll/run_daily_wage/' . $period_id);
				break;

			case 'CONTRACT OF SERVICE':
				redirect('payroll/run_daily_wage/' . $period_id);
				break;
			default:
				show_error('Invalid payroll type.');
		}
	}


	public function run_general_payroll($period_id)
	{
		
		$period = $this->Get_model->get_period($period_id);
		$employees = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$paid_employees = $this->Get_model->getPayrollByPeriod($period->payroll_period_id);
		$paid_ids = array_column($paid_employees, 'employee_id');
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'token_id'        => $period->token_id,
			'qr_code'         => $period->qr_code,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);
		$data['period'] = 'General Payroll for Employees';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/payroll_format/general_payroll', $data);
		$this->load->view('template/admin_footer');
	}

	public function get_employee_loans()
	{
		$employee_id = $this->input->post('employee_id');

		$loans = $this->db
			->select('
				l.employee_loan_id,
				l.employee_id,
				l.deduction_id,
				l.amount,
				l.monthly_deduction,
				l.start_period,
				l.end_period,
				d.deduction_name
			')
			->from('tbl_py_employee_loans l')
			->join('tbl_py_list_deductions d', 'd.deduction_id = l.deduction_id', 'left')
			->where('l.employee_id', $employee_id)
			->where('l.status', 1)
			->get()
			->result();

		echo json_encode($loans);
	}

	public function run_daily_wage($period_id)
	{
		
		$period = $this->Get_model->get_period($period_id);
		$employees = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$paid_employees = $this->Get_model->getPayrollByPeriodDW($period->payroll_period_id);
		$paid_ids = array_column($paid_employees, 'employee_id');
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'token_id'        => $period->token_id,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriodDW($period_id);
		$data['period'] = 'General Payroll for Employees';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/payroll_format/daily_wage', $data);
		$this->load->view('template/admin_footer');
	}

	public function run_midyear_bonus($period_id)
	{
		$period = $this->Get_model->get_period($period_id);
		$employees = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$paid_ids_array = $this->Get_model->getPayrollByPeriodMY($period_id);
		$paid_ids = array_column($paid_ids_array, 'employee_id'); // get plain array of IDs
		
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'token_id'        => $period->token_id,
			'qr_code'        => $period->qr_code,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);
		$data['period'] = 'Mid-Year Bonus';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/payroll_format/mid_year', $data);
		$this->load->view('template/admin_footer');
	}

	public function export_pdf_mid($period_id) 
		{
			$this->load->library('pdf');
    
			$period  = $this->Get_model->get_period($period_id);
			$payroll = $this->Get_model->getPayrollByPeriodMY($period_id);

			if (empty($payroll)) {
				$this->session->set_flashdata('error', 'No records found.');
				redirect($_SERVER['HTTP_REFERER']);
			}

			$allColumns = [];

			// 2. Process the rows
			foreach ($payroll as &$row) {
				// --- THE FIX ---
				// If $row is an array, cast it to an object so $row->basic_salary works
				if (is_array($row)) {
					$row = (object)$row; 
				}

				$row->parsed_deductions = []; 
				
				// Use your database attribute 'less'
				if (!empty($row->less)) { 
					$items = explode(',', $row->less);
					foreach ($items as $item) {
						$parts = explode(':', trim($item));
						if (count($parts) == 2) {
							$name   = trim($parts[0]);
							$amount = (float) trim($parts[1]);
							$row->parsed_deductions[$name] = $amount;
							
							// Collect unique loan names for headers
							$allColumns[$name] = $name;
						}
					}
				}
			}

			// 3. Prepare data for the View
			$data = [
				'period'       => $period,
				'payroll'      => $payroll, 
				'otherColumns' => array_values($allColumns)
			];

			// 4. Generate PDF
			$html = $this->load->view('payroll/layout/midyear_payroll_pdf', $data, true);
			$this->pdf->loadHtml($html);
			$this->pdf->setPaper([0, 0, 612, 936], 'landscape'); // Long Bond Landscape
			$this->pdf->render();
			
			$this->pdf->stream("MIDYEAR_BONUS_PAYROLL.pdf", ['Attachment' => 1]);
		}

	public function run_ojt_honorarium($period_id)
	{
		$period = $this->Get_model->get_period($period_id);
		$employees = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$paid_ids_array = $this->Get_model->getPayrollByPeriodOJT($period_id);
		$paid_ids = array_column($paid_ids_array, 'employee_id'); // get plain array of IDs
		
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'token_id'        => $period->token_id,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);
		$data['period'] = 'OJT HONORARIUM';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/payroll_format/ojt_honorarium_payroll.php', $data);
		$this->load->view('template/admin_footer');
	}

	public function run_overload($period_id)
	{
		$period = $this->Get_model->get_period($period_id);
		$employees = $this->Get_model->get_employees_with_rate(
			$period->payroll_period_id,
			$period->unit
		);

		$paid_employees = $this->Get_model->getPayrollByPeriod($period->payroll_period_id);
		$paid_ids = array_column($paid_employees, 'employee_id');

		$data = [
			'page'           => 'Payroll',
			'employees'      => $employees,
			'paid_ids'       => $paid_ids,
			'payroll_number' => $period->payroll_number,
			'unit'           => $period->unit,
			'payroll_type'   => $period->payroll_type,
			'period_id'      => $period->payroll_period_id,
			'status'         => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollOPT($period_id);
		$data['period'] = 'Payroll for Overload and Part-Time Employees';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/payroll_format/overload_parttime', $data);
		$this->load->view('template/admin_footer');
	}
	public function get_employee_deductions()
		{
			$emp_id = $this->input->post('emp_id');

			if (empty($emp_id)) {
				echo json_encode([
					'status' => false,
					'message' => 'Employee ID is missing'
				]);
				return;
			}

			$deductions = $this->Get_model->getEmployeeDeductions($emp_id);

			echo json_encode([
				'status' => true,
				'data' => $deductions
			]);
		}

	public function add_fund()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fund', 'Name of Fund', 'required|trim');
		$this->form_validation->set_rules('units[]', 'Units Under', 'required');
		$this->form_validation->set_rules('allocation', 'Allocation', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {

			$this->session->set_flashdata('error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);

		} else {
			$allocation_raw = $this->input->post('allocation', TRUE);
			$allocation_formatted = '₱' . number_format((float)$allocation_raw, 2);
			$data = [
				'fund'        => $this->input->post('fund', TRUE),
				'units'       => implode(',', $this->input->post('units', TRUE)),
				'allocation'  => $allocation_formatted,
				'created_at'  => date('Y-m-d H:i:s')
			];

			if ($this->Insert_model->insert_fund($data)) {
				$this->session->set_flashdata('success', 'Fund added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Failed to add fund.');
			}

			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function add_deduction()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('deduction_name', 'Name of Deduction', 'required|trim');
		// Added 'Contribution' to in_list because it's an option in your HTML
		$this->form_validation->set_rules('deduction_type', 'Deduction Type', 'required|in_list[Contribution,Loan,Other]');
		$this->form_validation->set_rules('is_mandatory', 'Mandatory', 'required|in_list[0,1]');
		
		// REMOVE or make 'is_active' optional since it's not in the form
		// Or just default it to 1 in the $data array

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$data = [
				'deduction_name' => $this->input->post('deduction_name', TRUE),
				'deduction_type' => $this->input->post('deduction_type', TRUE),
				'applicable'     => $this->input->post('applicable', TRUE),
				'is_mandatory'   => $this->input->post('is_mandatory', TRUE),
				'is_active'      => 1, // Force active by default
				'created_at'     => date('Y-m-d H:i:s')
			];

			if ($this->Insert_model->insert_deduction($data)) {
				$this->session->set_flashdata('success', 'Deduction added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Failed to add deduction.');
			}
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function add_employee_loan() {
		$this->form_validation->set_rules('employee_id', 'Employee', 'required');
		$this->form_validation->set_rules('deduction_id', 'Loan Type', 'required');
		$this->form_validation->set_rules('monthly_deduction', 'Monthly Deduction', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$data = [
				'employee_id'       => $this->input->post('employee_id', TRUE),
				'deduction_id'      => $this->input->post('deduction_id', TRUE),
				'amount'       => $this->input->post('amount', TRUE),
				'monthly_deduction' => $this->input->post('monthly_deduction', TRUE),
				'start_period'        => $this->input->post('start_period', TRUE),
				'end_period'          => $this->input->post('end_period', TRUE),
				'status'            => '1',
			];
			if ($this->Insert_model->insert_employee_loan($data)) {
				$this->session->set_flashdata('success', 'Loan assigned successfully.');
			} else {
				$this->session->set_flashdata('error', 'Failed to assign loan.');
			}
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	public function add_employee($deduction_id) {
		
		$data['deduction'] = $this->Get_model->get_deductionbyID($deduction_id);
		$data['employees'] = $this->Get_model->getEmployee();
		$data['deduction_id'] = $deduction_id;
		$data['period'] = 'Employee Loans';
		$this->load->view('template/admin_header');
		$this->load->view('payroll/employee_loans', $data);
		$this->load->view('template/admin_footer');
		
	}
	public function ajax_get_salary()
	{
		$emp_id = $this->input->post('employee_id');
		$payroll_type = $this->input->post('payroll_type');

		$salary = $this->Get_model->getEmployeeSalary($emp_id);
		$loans  = $this->Get_model->getEmployeeLoans($emp_id, $payroll_type);

		echo json_encode([

			'employee_id' => $emp_id,
			'basic_salary'  => $salary->amount ?? 0,
			'loans'       => $loans
		]);

	}


	public function salary_grade(){
		$data['period'] = 'Salary Grade';
		$data['salary_grades'] = $this->Get_model->getAllSalaryGrades(2026);
		$this->load->view('template/admin_header');
		$this->load->view('payroll/salary_grade', $data);
		$this->load->view('template/admin_footer');
	}


	 public function insertPayroll()
	{
		/* ================= VALIDATION ================= */
		$this->form_validation->set_rules('employee_id', 'Employee', 'required|integer');
		$this->form_validation->set_rules('basic_salary', 'Basic Salary', 'required|numeric');
		$this->form_validation->set_rules('gross_pay', 'Gross Pay', 'required|numeric');
		$this->form_validation->set_rules('net_pay', 'Net Pay', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
		}

		$employee_id = (int)$this->input->post('employee_id');
		$employee_details = $this->Get_model->getEmployeeName($employee_id);

		if (!$employee_details) {
			echo json_encode([
				'status' => false,
				'message' => 'Employee not found.'
			]);
			return;
		}

		/* ================= BUILD NAME & POSITION ================= */
		$name = $employee_details->name . ' ' .
			(!empty($employee_details->middle_name)
				? strtoupper(substr($employee_details->middle_name, 0, 1)) . '. '
				: '') .
			$employee_details->last_name;

		$position = $employee_details->position ?? 'N/A';

		/* ================= OTHER DEDUCTIONS ================= */
		$deduction_names   = $this->input->post('deduction_name') ?? [];
		$deduction_amounts = $this->input->post('deduction_amount') ?? [];

		$other_deduction_parts = [];

		if (is_array($deduction_names) && is_array($deduction_amounts)) {
			foreach ($deduction_names as $i => $dname) {
				$amount = (float)($deduction_amounts[$i] ?? 0);

				if (!empty($dname) && $amount > 0) {
					$other_deduction_parts[] =
						trim($dname) . ':' . number_format($amount, 2, '.', '');
				}
			}
		}

		$other_deduction = implode(', ', $other_deduction_parts);

		$this->db->trans_begin();

		try {

			/* ================= PAYROLL DATA ================= */
			$payrollData = [
				'employee_id'      => $employee_id,
				'name'             => $name,
				'position'         => $position,
				'payroll_period_id'=> (int)$this->input->post('payroll_period_id'),
				'basic_salary'     => (float)$this->input->post('basic_salary'),
				'pera'             => (float)$this->input->post('pera_lwop'),
				'lwop_days'        => (float)$this->input->post('lwop_days'),
				'lwop_amount'      => (float)$this->input->post('lwop_amount'),
				'salary_lwop'      => (float)$this->input->post('salary_lwop'),
				'gross_pay'        => (float)$this->input->post('gross_pay'),
				'gsis'             => (float)$this->input->post('gsis'),
				'philhealth'       => (float)$this->input->post('philhealth'),
				'pagibig'          => (float)$this->input->post('pagibig'),
				'other_deductions' => $other_deduction,
				'total_deductions' => (float)$this->input->post('total_deductions'),
				'net_pay'          => (float)$this->input->post('net_pay'),
				'net_pay_first'    => (float)$this->input->post('net_pay_first'),
				'net_pay_second'   => (float)$this->input->post('net_pay_second')
			];

			$payroll_id = $this->Insert_model->insertPayroll($payrollData);

			if (!$payroll_id) {
				throw new Exception('Payroll insert failed');
			}

			/* ================= SAVE OTHER DEDUCTIONS ================= */
			$deduction_ids = $this->input->post('deduction_id');
			$amounts       = $this->input->post('deduction_amount');

			$batch = [];

			if (is_array($deduction_ids)) {
				foreach ($deduction_ids as $i => $deduction_id) {

					$amount = (float)($amounts[$i] ?? 0);

					if ($deduction_id && $amount > 0) {
						$batch[] = [
							'payroll_id'   => $payroll_id,
							'deduction_id' => (int)$deduction_id,
							'amount'       => $amount
						];
					}
				}
			}

			if (!empty($batch)) {
				$this->Insert_model->insertOtherDeductionsBatch($batch);
			}

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed');
			}

			$this->db->trans_commit();

			/* ================= RETURN CLEAN JSON ================= */
			echo json_encode([
				'status'            => true,
				'message'           => 'Payroll saved successfully',
				'payroll_id'        => $payroll_id,
				'payroll_period_id' => $payrollData['payroll_period_id'],
				'employee_id'       => $employee_id,
				'name'              => $name,
				'position'          => $position,
				'basic_salary'      => $payrollData['basic_salary'],
				'gross_pay'         => $payrollData['gross_pay'],
				'total_deductions'  => $payrollData['total_deductions'],
				'net_pay'           => $payrollData['net_pay'],
				'net_pay_first'     => $payrollData['net_pay_first'],
				'net_pay_second'    => $payrollData['net_pay_second'],
				'gsis'              => $payrollData['gsis'],
				'philhealth'        => $payrollData['philhealth'],
				'pagibig'           => $payrollData['pagibig'],
				'lwop_amount'       => $payrollData['lwop_amount'],
				'other_deductions'  => $other_deduction
			]);

		} catch (Exception $e) {

			$this->db->trans_rollback();

			echo json_encode([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}

    private function _json($success, $message)
    {
        echo json_encode([
            'status'  => $success ? 'success' : 'error',
            'message' => $message
        ]);
    }
	public function fetchPayrollByPeriod()
	{
		$payroll_period_id = $this->input->post('payroll_period_id');
		$data = $this->Get_model->getPayrollByPeriod($payroll_period_id);
		echo json_encode([
			'status' => true,
			'data'   => $data
		]);
	}
	
	public function fetchPayrollByPeriodMY()
	{
		$payroll_period_id = $this->input->post('payroll_period_id');
		$data = $this->Get_model->getPayrollByPeriodMY($payroll_period_id);
		echo json_encode([
			'status' => true,
			'data'   => $data
		]);
	}


	// Payroll Layout
	public function general_payroll($payroll_period_id){
		$data['period'] = $this->Get_model->get_period($payroll_period_id);
		$data['payroll'] = $this->Get_model->getPayrollByPeriod($payroll_period_id);
		$this->load->view('payroll/layout/general_payroll',$data);
	}
	public function export_pdf($period_id)
	{
		$this->load->library('pdf');

		$period  = $this->Get_model->get_period($period_id);
		$payroll = $this->Get_model->getPayrollByPeriod($period_id);

		$allColumns = [];

		// 🔥 PARSE deductions
		foreach ($payroll as $row) {
			$row->parsed_deductions = [];
			if (!empty($row->other_deductions)) {
				$items = explode(',', $row->other_deductions);
				foreach ($items as $item) {
					$parts = explode(':', trim($item));
					if (count($parts) == 2) {
						$name   = trim($parts[0]);
						$amount = (float) trim($parts[1]);
						$row->parsed_deductions[$name] = $amount;

						// collect all unique deduction names
						$allColumns[$name] = $name;
					}
				}
			}
		}

		// convert to indexed array
		$otherColumns = array_values($allColumns);

		$data = [
			'period'        => $period,
			'payroll'       => $payroll,
			'otherColumns'  => $otherColumns
		];

		$html = $this->load->view('payroll/layout/general_payroll', $data, true);

		$this->pdf->loadHtml($html);
		$this->pdf->setPaper([0, 0, 612, 936], 'landscape'); // long bond
		$this->pdf->render();

		$this->pdf->stream(
			"GENERAL_PAYROLL_{$period->date_period}.pdf",
			['Attachment' => true]
		);
	}

	// 2. Download Transmittal
	public function export_transmittal_pdf_mid($period_id) {
		$data['summary'] = $this->Get_model->get_midyear_summary($period_id);
		$html = $this->load->view('payroll/reports/transmittal_pdf', $data, true);
		
		$this->pdf_library->generate($html, 'Transmittal_'.$period_id.'.pdf');
	}

	// 3. Generate Payslips (AJAX call)
	public function generate_payslips_mid($period_id) {
		// Logic to flag payslips as generated or pre-calculate values
		$success = $this->Update_model->set_payslips_ready($period_id);
		
		if($success) {
			echo json_encode(['status' => 'success', 'message' => 'Payslips have been generated successfully.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to generate payslips.']);
		}
	}

	public function export_pdf_dw($period_id)
	{
		$this->load->library('pdf');

		$period  = $this->Get_model->get_period($period_id);
		$payroll = $this->Get_model->getPayrollByPeriodDW($period_id);

		$allColumns = [];

		// 🔥 PARSE deductions
		foreach ($payroll as $row) {

			$row->parsed_deductions = [];

			if (!empty($row->other_deductions)) {

				$items = explode(',', $row->other_deductions);

				foreach ($items as $item) {

					$parts = explode(':', trim($item));

					if (count($parts) == 2) {
						$name   = trim($parts[0]);
						$amount = (float) trim($parts[1]);

						$row->parsed_deductions[$name] = $amount;

						// collect all unique deduction names
						$allColumns[$name] = $name;
					}
				}
			}
		}

		// convert to indexed array
		$otherColumns = array_values($allColumns);

		$data = [
			'period'        => $period,
			'payroll'       => $payroll,
			'otherColumns'  => $otherColumns
		];

		$html = $this->load->view('payroll/layout/daily_wage', $data, true);

		$this->pdf->loadHtml($html);
		$this->pdf->setPaper([0, 0, 612, 936], 'landscape'); // long bond
		$this->pdf->render();

		$this->pdf->stream(
			"GENERAL_PAYROLL_{$period->date_period}.pdf",
			['Attachment' => true]
		);
	}

	public function export_transmittal_pdf($period_id)
	{
		$this->load->library('pdf');
		$period = $this->Get_model->get_period($period_id);
		$code = $this->Get_model->get_payroll_by_id($period_id);
		$data = [
			'period'      => $period,
			'qr_code'   => $code,
			'prepared_by' => $this->session->userdata('name'), // optional
			'date_today'  => date('Y-m-d')
		];
		$html = $this->load->view('payroll/layout/transmittal_pdf', $data, true);
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait'); // Transmittal usually portrait
		$this->pdf->render();
		$this->pdf->stream(
			"TRANSMITTAL_{$period->date_period}.pdf",
			['Attachment' => true]
		);
	}

	public function export_excel($period_id)
	{
		$payroll = $this->Get_model->getPayrollByPeriod($period_id);
		$period  = $this->Get_model->get_period($period_id);

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Header
		$sheet->setCellValue('A1', 'GENERAL PAYROLL');
		$sheet->mergeCells('A1:P1');

		$sheet->setCellValue('A2', 'EASTERN VISAYAS STATE UNIVERSITY');
		$sheet->mergeCells('A2:P2');

		// Column headers
		$headers = [
			'No','Name','Position','Basic Salary','PERA','Gross Pay',
			'LWOP','Tax','GSIS','PhilHealth','Pag-ibig','Others',
			'Total Deduction','Net Pay','1st Quincena','2nd Quincena'
		];

		$col = 'A';
		foreach ($headers as $header) {
			$sheet->setCellValue($col.'4', $header);
			$sheet->getColumnDimension($col)->setWidth(20);
			$col++;
		}

		// Data
		$rowNum = 5;
		$i = 1;
		foreach ($payroll as $row) {
			$sheet->fromArray([
				$i++,
				$row->name,
				$row->position,
				$row->basic_salary,
				$row->pera,
				$row->gross_pay,
				$row->lwop_amount,
				$row->tax,
				$row->gsis,
				$row->philhealth,
				$row->pagibig,
				$row->other_deductions,
				$row->total_deductions,
				$row->net_pay,
				$row->net_pay_first,
				$row->net_pay_second
			], null, 'A'.$rowNum++);
		}

		// Output
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="GENERAL_PAYROLL_'.$period->date_period.'.xlsx"');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function edit_sg()
    {
        $salary = $this->input->post('salary', TRUE);
        $this->Update_model->save_salary_matrix($salary);
		$this->session->set_flashdata('success', 'Salary grades updated.');
        redirect($_SERVER['HTTP_REFERER']);
    }

	public function fetchPayrollByEmployee()
	{
		$employee_id = $this->input->post('employee_id');

		$data = $this->Get_model->getPayrollHistoryByEmployee($employee_id);

		echo json_encode([
			'status' => true,
			'data'   => $data
		]);
	}
	public function fetchPayrollByEmployeeMY()
	{
		$employee_id = $this->input->post('employee_id');

		$data = $this->Get_model->getPayrollHistoryMY($employee_id);

		echo json_encode([
			'data' => $data
		]);
	}

	public function payslips($period_id)
	{
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);

		if (empty($data['payrolls'])) {
			show_error('No payroll records found.');
		}

		$this->load->view('payroll/layout/payslips', $data);
	}
	
	public function generate_token() {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$token = '';
		for ($i = 0; $i < 9; $i++) {
			$token .= $chars[random_int(0, strlen($chars) - 1)];
		}

		// Format as XXX-XXX-XXX
		return substr($token,0,3) . '-' . substr($token,3,3) . '-' . substr($token,6,3);
	}
	public function submit_payroll()
	{
		$base_url = 'https://hris.evsu.edu.ph/';
		$period_id      = $this->input->post('period_id');
		$payroll_number = $this->input->post('payroll_number');
		

		if (!$period_id || !$payroll_number) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Missing payroll ID or number'
			]);
			return;
		}
		$this->db->set('status', 'status+1', FALSE);
		$this->db->set('date_time_forwarded_hr', date('Y-m-d H:i:s'));
		$this->db->where('payroll_period_id', $period_id);
		$update = $this->db->update('tbl_py_payroll_period');

		if ($update) {
			$token_id = $this->generate_token();
			$this->load->library('phpqrcode/qrlib');
			// $qrData = base_url('payroll/verify/' . $token_id);
			$qrData = $base_url . 'welcome/verify_payroll/' . $token_id;
			$qrPath = FCPATH . 'assets/qr/';
			if (!file_exists($qrPath)) {
				mkdir($qrPath, 0755, true);
			}
			$qrFileName = 'payroll_' . $token_id . '.png';
			$qrFile = $qrPath . $qrFileName;

			QRcode::png($qrData, $qrFile, QR_ECLEVEL_H, 4);
			$this->db->where('payroll_period_id', $period_id)
					->update('tbl_py_payroll_period', [
						'qr_code'  => 'assets/qr/' . $qrFileName,
						'token_id' => $token_id
					]);
			echo json_encode(['status' => 'success', 'token_id' => $token_id]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Database update failed'
			]);
		}
	}

	public function verify($payroll_number = null)
	{
		if (!$payroll_number) {
			show_error('Invalid payroll reference.', 404);
		}

		// Fetch payroll record
		$period = $this->db->where('payroll_number', $payroll_number)
						->get('tbl_py_payroll_period')
						->row();

		if (!$period) {
			$data['status']  = 'invalid';
			$data['message'] = 'Payroll record not found.';
		} else {
			$data['status']  = 'valid';
			$data['period']  = $period;
		}

		$this->load->view('payroll/verify_view', $data);
	}

	public function employee_ledger($employee_id)
	{
		$data['employee'] = $this->db
			->where('employee_id', $employee_id)
			->get('tbl_employee')
			->row();

		$data['ledger'] = $this->Get_model
			->get_employee_ledger($employee_id);

		$this->load->view('template/admin_header');
		$this->load->view('payroll/employee_ledger', $data);
		$this->load->view('template/admin_footer');
		
	}


	public function track_payroll()
	
	{
		$data['employee'] = $this->Get_model->getEmployee();
		$data['payroll_number'] = $this->Get_model->generate_payroll_number();
		$data['payroll'] = $this->Get_model->get_payrolls();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/track_payroll', $data);
		$this->load->view('template/admin_footer');
		
	}

	public function reports(){
		$this->load->view('payroll/reports');
	}

	public function rate_per_hour(){
		$data['period'] = 'Rate Per Hour';
		$data['rates'] = $this->Get_model->get_rate_per_hour();
		$data['employees'] = $this->Get_model->get_employees();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/rate_hour', $data);
		$this->load->view('template/admin_footer');
		
	}

	 public function save_rate()
    {
        $this->form_validation->set_rules('employee_id', 'Employee', 'required');
        $this->form_validation->set_rules('rate_per_hour', 'Rate Per Hour', 'required|numeric');
        $this->form_validation->set_rules('tax', 'Tax', 'required|numeric');
		
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
		
        $data = [
            'employee_id' => $this->input->post('employee_id'),
            'rate_per_hour' => $this->input->post('rate_per_hour'),
			'tax' => $this->input->post('tax'),
			'created_at'     => date('Y-m-d H:i:s')
        ];
		
        $this->Insert_model->save_rate($data);
        echo json_encode(['status' => true]);
		// print_r($data);
    }

	public function edit_rate()
	{
		$this->form_validation->set_rules('employee_id', 'Employee', 'required');
		$this->form_validation->set_rules('rate_per_hour', 'Rate', 'required|numeric');
		$this->form_validation->set_rules('tax', 'Tax', 'required|numeric');

		if ($this->form_validation->run() == false) {
			echo json_encode(['status' => false, 'message' => validation_errors()]);
			return;
		} else {
			$data = [
				'rate_per_hour' => $this->input->post('rate_per_hour'),
				'tax' => $this->input->post('tax'),
				'updated_at'     => date('Y-m-d H:i:s')
			];

			$this->Update_model->edit_rate($data);
			echo json_encode(['status' => true]);
		}
	}

	public function save_dw()
	{
		$payroll_type = $this->input->post('payroll_type', TRUE);

		// fallback safety
		if (!$payroll_type) {
			$payroll_type = 'DAILY WAGE';
		}

		$gsis_value = $this->input->post('gsis');

		// initialize ALWAYS (important)
		$gsis = 0;
		$sss  = 0;

		if ($payroll_type === 'CONTRACT OF SERVICE') {
			$sss = $gsis_value;   // COS uses SSS
		} else {
			$gsis = $gsis_value;  // DW uses GSIS
		}

		$data = [
			'employee_id'        => $this->input->post('employee_id'),
			'payroll_period_id'  => $this->input->post('payroll_period_id'),
			'name'               => $this->input->post('employee_name'),
			'position'           => $this->input->post('position'),
			'days_worked'        => $this->input->post('days_worked'),
			'rate_per_day'       => $this->input->post('rate_per_day'),
			'basic_salary'       => $this->input->post('basic_salary'),
			'lwop_days'          => $this->input->post('lwop_days'),
			'lwop_amount'        => $this->input->post('lwop_amount'),
			'salary_lwop'        => $this->input->post('salary_lwop'),
			'pera'               => $this->input->post('pera'),
			'gross_pay'          => $this->input->post('gross_pay'),

			// mandatory mapping
			'gsis'               => $gsis,
			'sss'                => $sss,

			'philhealth'         => $this->input->post('philhealth'),
			'pagibig'            => $this->input->post('pagibig'),
			'tax'                => $this->input->post('tax'),
			'total_deductions'   => $this->input->post('total_deductions'),
			'net_pay'            => $this->input->post('net_pay'),
			'other_deductions'   => $this->input->post('other_deductions'),

			'created_at'         => date('Y-m-d H:i:s')
		];

		if (!empty($this->input->post('payroll_id'))) {

			// UPDATE EXISTING RECORD
			$this->db->where('payroll_id', $this->input->post('payroll_id'));
			$result = $this->db->update('tbl_py_payroll_dw', $data);

		} else {

			// INSERT NEW RECORD
			$result = $this->Insert_model->insert_dw($data);
		}

		if ($result) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => $this->db->error() // 👈 IMPORTANT DEBUG
			]);
		}
	}

public function delete_dw()
{
    $id = $this->input->post('payroll_id');

    $this->db->where('payroll_id', $id);
    $result = $this->db->delete('tbl_py_payroll_dw');

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

public function get_single_dw()
{
    $id = $this->input->post('payroll_id');

    $data = $this->db
        ->where('payroll_id', $id)
        ->get('tbl_py_payroll_dw')
        ->row();

    echo json_encode($data);
}

	public function fetchPayrollDW()
	{
		$period_id = $this->input->get('period_id');

		$result = $this->Get_model->getPayrollByPeriodDW($period_id);

		$used = array_values(array_unique(array_column($result, 'employee_id')));

		echo json_encode([
			'data' => $result,
			'used' => $used
		]);
	}

	public function save_payroll_opt()
	{
		$employee_id = $this->input->post('employee_id');
		$school_year = $this->input->post('school_year');
		$total_hours = $this->input->post('total_hours');
		$total_net = $this->input->post('total_net');
		$tax_amount = $this->input->post('tax_amount');
		$gross_amount = $this->input->post('gross_amount');
		$rate_per_hour = $this->input->post('rate_per_hour');
		$payroll_period_id = $this->input->post('payroll_period_id');

		if (!$employee_id || !$rate_per_hour) {
			echo json_encode(['status' => false, 'message' => 'Employee and rate are required.']);
			return;
		}

		$months = ['jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dece'];
		$monthly_hours = [];
		foreach ($months as $month) {
			$monthly_hours[$month] = floatval($this->input->post('hours_'.$month) ?? 0);
		}

		$data = [
			'employee_id' => $employee_id,
			'payroll_period_id' => $payroll_period_id,
			'rate_per_hour' => floatval($rate_per_hour),
			'total_hours' => floatval($total_hours),
			'gross_amount' => floatval($gross_amount),
			'total_net' => floatval($total_net),
			'tax_amount' => floatval($tax_amount),
			'school_year' => $school_year,
		] + $monthly_hours;
		
		$this->Insert_model->save_payroll_opt($data);
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function getPayrollByIdGP()
	{
		$id = $this->input->post('payroll_id');

		$data = $this->Get_model->getPayrollByIdGP($id);

		if (!$data) {
			echo json_encode(['status' => false, 'message' => 'Payroll not found']);
			return;
		}

		// Merge status manually
		$response = $data;
		$response['status'] = true;

		echo json_encode($response);
	}

	public function updatePayroll()
	{
		$data = $this->input->post();

		if (empty($data['payroll_id'])) {
			echo json_encode(['status' => false, 'message' => 'Missing payroll ID']);
			return;
		}

		$result = $this->Update_model->updatePayrollGP($data);

		echo json_encode([
			'status' => $result ? true : false
		]);
	}

	public function getOjtHonorarium($period_id)
	{
		$this->db->where('payroll_period_id', $period_id);
		$this->db->order_by('py_ojt_id', 'DESC');
		$result = $this->db->get('tbl_py_ojt_honorarium')->result();

		echo json_encode($result);
	}

	public function saveOjtHonorarium()
	{
		$employee_ids        = $this->input->post('employee_id');
		$payroll_period_id   = $this->input->post('payroll_period_id');
		$o_name              = $this->input->post('o_name');
		$o_position          = $this->input->post('o_position');
		$o_subject           = $this->input->post('o_subject');
		$o_total_amount      = $this->input->post('o_total_amount');
		$o_amount_accrued    = $this->input->post('o_amount_accrued');
		$o_tax               = $this->input->post('o_tax');
		$o_net               = $this->input->post('o_net');

		$count = max(
			count($employee_ids ?? []),
			count($o_name ?? []),
			count($o_position ?? []),
			count($o_subject ?? [])
		);

		$data = [];
		for($i = 0; $i < $count; $i++){
			if(empty($employee_ids[$i])) continue;

			$data[] = [
				'employee_id'       => $employee_ids[$i] ?? null,
				'o_name'            => $o_name[$i] ?? '',
				'payroll_period_id' => $payroll_period_id[$i] ?? null,
				'o_position'        => $o_position[$i] ?? '',
				'o_subject'         => str_replace('%','',$o_subject[$i] ?? 0),
				'o_total_amount'    => $o_total_amount[$i] ?? 0,
				'o_amount_accrued'  => $o_amount_accrued[$i] ?? 0,
				'o_tax'             => $o_tax[$i] ?? 0,
				'o_net'             => $o_net[$i] ?? 0,
				'o_date_created'    => date('Y-m-d H:i:s')
			];
		}

		if(!empty($data)){
			$this->db->insert_batch('tbl_py_ojt_honorarium', $data);
			echo json_encode([
				'status' => 'success',
				'data'   => $data,
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message'=> 'No valid data to save.',
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		}
	}

	public function ov_pt_payroll_format($period_id)
	{
		$data['payrolls'] = $this->Get_model->getPayrollOPT($period_id);
		$data['period']   = $this->Get_model->get_period($period_id);
		$this->load->view('payroll/layout/ov_pt', $data);
	}
	public function tax_rate(){
		$data['period'] = 'Tax Rate';
		$data['employees'] = $this->Get_model->get_employees();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/tax_rate', $data);
		$this->load->view('template/admin_footer');
	}
	public function account_no(){
		$data['period'] = 'Account No.';
		$data['employees'] = $this->Get_model->get_employees();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/account_no', $data);
		$this->load->view('template/admin_footer');
	}

	public function signatories(){
		$data['period'] = 'Signatories';
		$data['signatories'] = $this->Get_model->get_signatories();
		$data['employees'] = $this->Get_model->get_employees();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/signatories', $data);
		$this->load->view('template/admin_footer');
	}
	public function save_signatory() {
		$this->form_validation->set_rules('sig_name', 'Signatory Name', 'required|trim');
		$this->form_validation->set_rules('sig_designation', 'Designation', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			// Validation failed
			$errors = validation_errors();
			echo json_encode(['status' => 'error', 'message' => $errors]);
			return;
		}

		// Prepare data
		$data = [
			'sig_name' => $this->input->post('sig_name', true),
			'sig_designation'    => $this->input->post('sig_designation', true),
		];

		$inserted = $this->Insert_model->insert('tbl_py_signatory', $data);
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function save_midyear_payroll() {
    // 1. Capture and Clean Inputs
		$employee_id = $this->input->post('employee_id');
		$payroll_period_id = $this->input->post('payroll_period_id');
		
		// Convert to float to avoid DB "Incorrect decimal value" errors
		$basic_salary = floatval(str_replace(',', '', $this->input->post('basic_salary')));
		$gross_pay = floatval(str_replace(',', '', $this->input->post('gross_pay')));
		$tax = floatval(str_replace(',', '', $this->input->post('tax')));
		$total_deductions = floatval(str_replace(',', '', $this->input->post('total_deductions')));
		$net_pay = floatval(str_replace(',', '', $this->input->post('net_pay')));

		// 2. Process Loans (String concatenation for the 'less' column)
		$loans = $this->input->post('loans');
		$deductions_str = '';
		if(!empty($loans) && is_array($loans)){
			$tmp = [];
			foreach($loans as $loan){
				$l_name = $loan['name'] ?? 'Loan';
				$l_amount = floatval($loan['amount'] ?? 0);
				if($l_amount > 0) {
					$tmp[] = $l_name . ':' . $l_amount;
				}
			}
			$deductions_str = implode(', ', $tmp);
		}

		// 3. Fetch Employee Details for the snapshot
		$employee_details = $this->Get_model->getEmployeeName($employee_id);
		if(!$employee_details) {
			echo json_encode(['status'=>'error', 'message'=>'Employee not found']);
			return;
		}

		$name = $employee_details->last_name . ', ' . $employee_details->name;
		$position = $employee_details->position ?? 'N/A';

		// 4. Map to your EXACT database columns
		$data = [
			'employee_id'       => $employee_id,
			'payroll_period_id' => $payroll_period_id,
			'name'              => $name,
			'position'          => $position,
			'basic_salary'      => $basic_salary,
			'gross_pay'         => $gross_pay,
			'total_deductions'  => $total_deductions,
			'less'              => $deductions_str, 
			'tax'               => $tax,
			'net_pay'           => $net_pay,
			'created_at'        => date('Y-m-d H:i:s')
		];

		// 5. Execution
		if($this->Insert_model->insert('tbl_py_midyear_bonus', $data)) {
			echo json_encode(['status'=>'success']);
		} else {
			echo json_encode(['status'=>'error', 'message'=>'Database Insert Failed']);
		}
	}
	public function get_saved_midyear($period_id)
	{
		$records = $this->Get_model->get_midyear_payroll($period_id);

		if(!$records){
			echo json_encode([]);
			return;
		}

		echo json_encode($records);
	}

	public function updateTaxRate()
	{
		$employee_id = $this->input->post('employee_id');
		$tax_rate = $this->input->post('tax_rate');

		if (!$employee_id || $tax_rate === null) {
			echo json_encode(['status' => false, 'message' => 'Invalid input']);
			return;
		}

		$this->db->where('employee_id', $employee_id);
		$updated = $this->db->update('tbl_employee', ['tax_rate' => $tax_rate]);

		if ($updated) {
			echo json_encode(['status' => true, 'employee_id' => $employee_id, 'tax_rate' => $tax_rate]);
		} else {
			echo json_encode(['status' => false, 'message' => 'Update failed']);
		}
	}
	

	public function updateAccountNo()
	{
		$employee_id = $this->input->post('employee_id');
		$account_no = $this->input->post('account_no');

		if (!$employee_id || $account_no === null) {
			echo json_encode(['status' => false, 'message' => 'Invalid input']);
			return;
		}

		$this->db->where('employee_id', $employee_id);
		$updated = $this->db->update('tbl_employee', ['account_no' => $account_no]);

		if ($updated) {
			echo json_encode(['status' => true, 'employee_id' => $employee_id, 'account_no' => $account_no]);
		} else {
			echo json_encode(['status' => false, 'message' => 'Update failed']);
		}
	}

	public function users(){
		$data['period'] = 'Receivers';
		$data['users'] = $this->Get_model->get_receiver();
		$this->load->view('template/admin_header');
		$this->load->view('payroll/users', $data);
		$this->load->view('template/admin_footer');
	}

    // ---------------------------
    // STORE (ADD NEW USER)
    // ---------------------------
    public function add_py_receiver()
    {
        $first_name  = $this->input->post('first_name');
        $middle_name = $this->input->post('middle_name');
        $last_name   = $this->input->post('last_name');
        $username    = $this->input->post('username');
        $email       = $this->input->post('email');
        $password    = $this->input->post('password');
        $role        = $this->input->post('role');
        $status      = $this->input->post('status');

        // Basic validation
        if(empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password)){
            echo json_encode([
                'status' => false,
                'message' => 'Please fill all required fields.'
            ]);
            return;
        }

        // Check if username already exists
        $check = $this->db->get_where('tbl_py_payroll_receivers', ['username' => $username])->row();
        if($check){
            echo json_encode([
                'status' => false,
                'message' => 'Username already exists.'
            ]);
            return;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'first_name'  => $first_name,
            'middle_name' => $middle_name,
            'last_name'   => $last_name,
            'username'    => $username,
            'email'       => $email,
            'password_hash'    => $hashed_password,
            'role'        => $role,
            'status'      => $status,
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('tbl_py_payroll_receivers', $data);

        if($insert){
            echo json_encode(['status' => true]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to save user.'
            ]);
        }
    }

    // ---------------------------
    // UPDATE USER
    // ---------------------------
    public function update_py_receiver($id = null)
    {
        if(!$id){
            echo json_encode([
                'status' => false,
                'message' => 'Invalid user ID.'
            ]);
            return;
        }

        $data = [
            'first_name'  => $this->input->post('first_name'),
            'middle_name' => $this->input->post('middle_name'),
            'last_name'   => $this->input->post('last_name'),
            'username'    => $this->input->post('username'),
            'email'       => $this->input->post('email'),
            'role'        => $this->input->post('role'),
            'status'      => $this->input->post('status'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        // Check duplicate username (exclude current ID)
        $this->db->where('username', $data['username']);
        $this->db->where('receiver_id !=', $id);
        $check = $this->db->get('tbl_py_payroll_receivers')->row();

        if($check){
            echo json_encode([
                'status' => false,
                'message' => 'Username already exists.'
            ]);
            return;
        }

        // Handle password (optional)
        $password = $this->input->post('password');
        if(!empty($password)){
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->db->where('receiver_id', $id);
        $update = $this->db->update('tbl_py_payroll_receivers', $data);

        if($update){
            echo json_encode(['status' => true]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to update user.'
            ]);
        }
    }

	public function gsis_remittance_list()
	{
		$data['period'] = 'GSIS Remittance';
		$data['payroll'] = $this->Get_model->get_payrolls();
		$data['all_employees'] = $this->Get_model->get_employees();
		// $data['employees'] = $this->Get_model->getEmployeesWithoutGSIS();
		$this->load->view('template/admin_header');
		$this->load->view('remittances/gsis', $data);
		$this->load->view('template/admin_footer');
	}

	public function gsis_remittance($payroll_period_id = null)
	{
		if (!$payroll_period_id) {
			show_404();
		}
		$period = $this->Get_model->get_period($payroll_period_id);
		$data['employees'] = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$data['period'] = 'GSIS Remittance';
		$data['payroll'] = $this->Get_model->get_period($payroll_period_id);
		$this->load->view('template/admin_header');
		$this->load->view('remittances/gsis_remittance', $data);
		$this->load->view('template/admin_footer');
	}

	public function pagibig_remittance_list()
	{
		$data['period'] = 'Pagibig Remittance';
		$data['payroll'] = $this->Get_model->get_payrolls();
		$data['all_employees'] = $this->Get_model->get_employees();
		// $data['employees'] = $this->Get_model->getEmployeesWithoutGSIS();
		$this->load->view('template/admin_header');
		$this->load->view('remittances/pagibig', $data);
		$this->load->view('template/admin_footer');
	}

	public function pagibig_remittance($payroll_period_id = null)
	{
		if (!$payroll_period_id) {
			show_404();
		}
		$period = $this->Get_model->get_period($payroll_period_id);
		$data['employees'] = $this->Get_model->getPayrollByPeriod($payroll_period_id);
		$data['period'] = 'GSIS Remittance';
		$data['payroll'] = $this->Get_model->get_period($payroll_period_id);
		$this->load->view('template/admin_header');
		$this->load->view('remittances/pagibig_remittance', $data);
		$this->load->view('template/admin_footer');
	}

	public function philhealth_remittance_list()
	{
		$data['period'] = 'PhilHealth Remittance';
		$data['payroll'] = $this->Get_model->get_payrolls();
		$data['all_employees'] = $this->Get_model->get_employees();
		
		$this->load->view('template/admin_header');
		// Ensure you create this view file: views/remittances/philhealth.php
		$this->load->view('remittances/philhealth', $data); 
		$this->load->view('template/admin_footer');
	}

	public function philhealth_remittance($payroll_period_id = null)
	{
		if (!$payroll_period_id) {
			show_404();
		}
		
		// Fetch specific payroll period details
		$payroll_data = $this->Get_model->get_period($payroll_period_id);
		
		$data['period'] = 'PhilHealth Remittance Details';
		$data['payroll'] = $payroll_data;
		// Fetches employees linked to this specific payroll run
		$data['employees'] = $this->Get_model->getPayrollByPeriod($payroll_period_id);
		
		$this->load->view('template/admin_header');
		// Ensure you create this view file: views/remittances/philhealth_remittance.php
		$this->load->view('remittances/philhealth_remittance', $data);
		$this->load->view('template/admin_footer');
	}

	public function save_bp_number()
	{
		$employee_id = $this->input->post('employee_id');
		$gsis_no     = $this->input->post('bp_no');

		// Basic validation
		if (empty($employee_id) || empty($gsis_no)) {
			echo json_encode([
				'status' => false,
				'message' => 'Employee and BP Number are required.'
			]);
			return;
		}

		$this->db->where('gsis_no', $gsis_no);
		$this->db->where('employee_id !=', $employee_id);
		$exists = $this->db->get('tbl_employee')->row();

		if ($exists) {
			echo json_encode([
				'status' => false,
				'message' => 'GSIS number already assigned to another employee.'
			]);
			return;
		}

		// UPDATE employee
		$this->db->where('employee_id', $employee_id);
		$updated = $this->db->update('tbl_employee', [
			'gsis_no' => $gsis_no
		]);

		echo json_encode([
			'status' => $updated
		]);
	}

	public function save_pagibig_number()
	{
		$employee_id = $this->input->post('employee_id');
		$pagibig_no     = $this->input->post('pagibig_no');

		// Basic validation
		if (empty($employee_id) || empty($pagibig_no)) {
			echo json_encode([
				'status' => false,
				'message' => 'Employee and BP Number are required.'
			]);
			return;
		}

		$this->db->where('pagibig_no', $pagibig_no);
		$this->db->where('employee_id !=', $employee_id);
		$exists = $this->db->get('tbl_employee')->row();

		if ($exists) {
			echo json_encode([
				'status' => false,
				'message' => 'GSIS number already assigned to another employee.'
			]);
			return;
		}

		// UPDATE employee
		$this->db->where('employee_id', $employee_id);
		$updated = $this->db->update('tbl_employee', [
			'pagibig_no' => $pagibig_no
		]);

		echo json_encode([
			'status' => $updated
		]);
	}

	public function save_philhealth_number()
	{
		$employee_id   = $this->input->post('employee_id');
		$philhealth_no = $this->input->post('philhealth_no');

		// 1. Basic validation
		if (empty($employee_id) || empty($philhealth_no)) {
			echo json_encode([
				'status' => false,
				'message' => 'Employee and PhilHealth PIN are required.'
			]);
			return;
		}

		// 2. Check for Duplicates
		// Ensure this PhilHealth number isn't already used by someone else
		$this->db->where('philhealth_no', $philhealth_no);
		$this->db->where('employee_id !=', $employee_id);
		$exists = $this->db->get('tbl_employee')->row();

		if ($exists) {
			echo json_encode([
				'status' => false,
				'message' => 'This PhilHealth number is already assigned to another employee.'
			]);
			return;
		}

		// 3. Update Registry
		$this->db->where('employee_id', $employee_id);
		$updated = $this->db->update('tbl_employee', [
			'philhealth_no' => $philhealth_no
		]);

		// 4. Return Response
		if ($updated) {
			echo json_encode([
				'status' => true,
				'message' => 'PhilHealth PIN successfully updated.'
			]);
		} else {
			echo json_encode([
				'status' => false,
				'message' => 'Database error: Unable to update record.'
			]);
		}
	}
}
