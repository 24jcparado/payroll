<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
class Receiver extends MY_Controller {

	function __construct(){
		parent::__construct();
        if (!$this->session->userdata('receiver_logged_in')) {
            redirect('welcome');
            exit;
        }
		 $this->output
            ->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0")
            ->set_header("Pragma: no-cache");
	}

    public function dashboard(){
        $role = $this->session->userdata('receiver_role');
        $data['recent_payrolls'] = $this->Get_model->get_recent_received_payrolls($role);
        $data['period'] = "Dashboard"; 
        $this->load->view('template/receiver_header');
		$this->load->view('verify/dashboard', $data);
		$this->load->view('template/admin_footer');
        
    }

    public function verify_token_ajax()
    {
        $token = strtoupper(trim($this->input->post('token_id')));
        if(empty($token)){
            echo json_encode(['status'=>'error','message'=>'Token ID is required']);
            return;
        }

        $payroll = $this->Get_model->get_payroll_by_token($token);
        if(!$payroll){
            echo json_encode(['status'=>'error','message'=>'Payroll not found']);
            return;
        }

        // Format date
        $payroll->created_at = date('F d, Y h:i A', strtotime($payroll->created_at));
        if($payroll->date_time_received_accounting){
            $payroll->date_time_received_accounting = date('F d, Y h:i A', strtotime($payroll->date_time_received_accounting));
        }

        echo json_encode(['status'=>'success','payroll'=>$payroll]);
    }

	// public function verify_token($token = null)
    // {
    //     if ($this->input->post('token_id')) {
    //         $token = strtoupper(trim($this->input->post('token_id')));
    //     }
    //     if (empty($token)) {
    //         $this->session->set_flashdata('error', 'Token ID does not exist or is invalid.');
    //         redirect('welcome');
    //         return;
    //     }

    //     $payroll = $this->Get_model->get_payroll_by_token($token);

    //     if (!$payroll) {
    //         $this->session->set_flashdata('error', 'The Payroll Token ID you entered does not exist.');
    //         redirect('welcome');
    //         return;
    //     }
    //     $type = strtoupper(trim($payroll->payroll_type));
    //     $data['payroll'] = $payroll;

    //     if ($type === 'GENERAL PAYROLL') {
    //         $this->load->view('verify/general_payroll', $data);
    //     } 
    //     elseif ($type === 'OVERLOAD' || $type === 'PART_TIME') {
    //         $this->load->view('verify/overload_parttime', $data);
    //     } 
    //     elseif ($type === 'MID-YEAR BONUS' || $type === 'MID-YEAR BONUS') {
    //         $this->load->view('verify/mid_year_bonus', $data);
    //     } 
    //     else {
    //         $this->session->set_flashdata('error', 'Unsupported payroll type.');
    //         redirect('welcome');
    //     }
    // }

    public function view_full($period_id)
{
    $period = $this->Get_model->get_period($period_id);

    if (!$period) {
        show_error('Payroll period not found.', 404);
    }

    $type = strtoupper(trim($period->payroll_type));

    $period = $this->Get_model->get_period($period_id);

    // ===============================
    // GENERAL PAYROLL
    // ===============================
    if ($type === 'GENERAL PAYROLL') {

        $employees_gen = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);
		$paid_employees = $this->Get_model->getPayrollByPeriod($period->payroll_period_id);
		$paid_ids = array_column($paid_employees, 'employee_id');
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees_gen,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);
        $data['period'] = 'General Payroll for Employees';
        $this->load->view('verify/download/view_general_payroll', $data);

    }
    // ===============================
    // DAILY WAGE
    // ===============================
    elseif ($type === 'DAILY WAGE') {

        $employees_gen = $this->Get_model->get_employees_for_payroll(
			$period->payroll_period_id,
			$period->unit
		);

		$paid_employees = $this->Get_model->getPayrollByPeriodDW($period->payroll_period_id);
		$paid_ids = array_column($paid_employees, 'employee_id');
		$data = [
			'page'            => 'Payroll',
			'employees'       => $employees_gen,
			'paid_ids'        => $paid_ids,
			'payroll_number'  => $period->payroll_number,
			'unit'            => $period->unit,
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriodDW($period_id);
        $data['period'] = 'General Payroll for Daily Wage';
        $this->load->view('verify/download/view_daily_wage', $data);

    }

    // ===============================
    // OVERLOAD / PART-TIME
    // ===============================
    elseif ($type === 'OVERLOAD' || $type === 'PART_TIME') {

        
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
        $this->load->view('verify/download/view_overload_partime', $data);

    }
     // ===============================
    // Mid-Year
    // ===============================
    elseif ($type === 'MID-YEAR BONUS' || $type === 'MID-YEAR BONUS') {

        
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
			'payroll_type'    => $period->payroll_type,
			'period_id'       => $period->payroll_period_id,
			'status'          => $period->status
		];
		$data['payrolls'] = $this->Get_model->getPayrollByPeriodMY($period_id);
        $data['period'] = 'Payroll for Mid-Year Bonus';
        $this->load->view('verify/download/view_mid_year_bonus', $data);

    }

    // ===============================
    // UNKNOWN TYPE
    // ===============================
    else {
        show_error('Unsupported payroll type.', 400);
    }
}


public function download_pdf($period_id)
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
			"GENERAL_PAYROLL_{$period->payroll_number}.pdf",
			['Attachment' => true]
		);
	}

  public function download_pdf_midyear_bonus($id)
{
    require_once(APPPATH . '../vendor/autoload.php'); // Composer autoload

    $payroll_period = $this->Get_model->get_period($id);
    if (!$payroll_period) {
        show_error('Payroll period not found.', 404);
    }

    $records = $this->Get_model->get_grouped_midyear_payroll($id);
    if (empty($records)) {
        show_error('No payroll records found.', 404);
    }

    // Determine all deductions present
    $deduction_names = [];
    foreach ($records as $row) {
        if (!empty($row->less)) {
            $items = explode(',', $row->less);
            foreach ($items as $item) {
                $parts = explode(':', $item);
                $deduction = trim($parts[0]);
                if (!in_array($deduction, $deduction_names)) {
                    $deduction_names[] = $deduction;
                }
            }
        }
    }

    // Custom bond paper size: 8 x 13 inches (landscape)
    $width_mm = 203.2; // 8 inches
    $height_mm = 330.2; // 13 inches
    $pdf = new \TCPDF('L', 'mm', [$height_mm, $width_mm], true, 'UTF-8', false);
    $pdf->SetTitle("Mid-Year Bonus Report");
    $pdf->AddPage();

    // --- HEADER ---
    $headerHtml = "
        <h3 style='text-align:center;'>Eastern Visayas State University</h3>
        <h4 style='text-align:center;'>Tacloban City</h4>
        <h4 style='text-align:center;'>MID-YEAR BONUS 2025</h4>
        <p style='text-align:center;'>Payroll Period: C. Y. 2025</p>
        <p style='text-align:center;'>COLLEGE OF EDUCATION</p>
        <br>
    ";

    // --- TABLE START ---
    $html = $headerHtml;
    $html .= "<table cellpadding='3' cellspacing='0' style='font-size:10px; border-collapse: collapse; width:100%;'>";

    // Table header
    $html .= "<tr style='font-weight:bold; text-align:center;'>";
    $html .= "<th style='border:1px solid black;'>Name</th>";
    $html .= "<th style='border:1px solid black;'>Position</th>";
    $html .= "<th style='border:1px solid black;'>Basic Pay</th>";
    $html .= "<th style='border:1px solid black;'>Total Mid-Year Benefits</th>";

    foreach ($deduction_names as $d) {
        $html .= "<th style='border:1px solid black;'>{$d}</th>";
    }

    $html .= "<th style='border:1px solid black;'>Tax</th>";
    $html .= "<th style='border:1px solid black;'>Net Pay</th>";
    $html .= "</tr>";


    foreach ($records as $row) {
        $less_values = [];
        if (!empty($row->less)) {
            $items = explode(',', $row->less);
            foreach ($items as $item) {
                $parts = explode(':', $item);
                $less_values[trim($parts[0])] = number_format(floatval($parts[1] ?? 0), 2);
            }
        }

        $html .= "<tr style='font-size:10px;'>";
        $html .= "<td style='border:1px solid black; text-align:left;'>{$row->name}</td>";
        $html .= "<td style='border:1px solid black; text-align:left;'>{$row->position}</td>";
        $html .= "<td style='border:1px solid black; text-align:right;'>₱ ".number_format($row->basic_salary,2)."</td>";
        $html .= "<td style='border:1px solid black; text-align:right;'>₱ ".number_format($row->gross_pay,2)."</td>";

        foreach ($deduction_names as $d) {
            $html .= "<td style='border:1px solid black; text-align:right;'>₱ ".($less_values[$d] ?? '')."</td>";
        }

        $html .= "<td style='border:1px solid black; text-align:right;'>₱ ".number_format($row->tax,2)."</td>";
        $html .= "<td style='border:1px solid black; text-align:right;'>₱ ".number_format($row->net_pay,2)."</td>";
        $html .= "</tr>";
    }

    $html .= "</table>";

    if (ob_get_length()) ob_end_clean();

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("MidYearBonus_{$payroll_period->payroll_number}.pdf", 'D');
}

    public function download_payslip($id)
    {
        $this->load->library('tcpdf');

        $payroll = $this->Get_model->get_period($id);

        if (!$payroll) {
            show_error('Payroll details not found.', 404);
        }

        $pdf = new TCPDF();

        foreach ($payroll as $row) {

            $pdf->AddPage();

            $html = "
                <h4>Payslip</h4>
                <p><strong>Name:</strong> {$row->employee_name}</p>
                <p><strong>Gross Pay:</strong> {$row->gross_pay}</p>
                <p><strong>Total Deductions:</strong> {$row->total_deductions}</p>
                <p><strong>Net Pay:</strong> {$row->net_pay}</p>
            ";

            $pdf->writeHTML($html);
        }
        $pdf->Output("Payslips.pdf", 'D');
    }

    public function download_excel($id)
    {
        $records = $this->Get_model->get_grouped_payroll($id);
        if (empty($records)) {
            show_error('No payroll records found.', 404);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Header
        $headers = [
            'A1'=>'#',
            'B1'=>'Name',
            'C1'=>'Rate/Hour',
            'D1'=>'Particular',
            'E1'=>'Jan',
            'F1'=>'Feb',
            'G1'=>'Mar',
            'H1'=>'Apr',
            'I1'=>'May',
            'J1'=>'Jun',
            'K1'=>'Jul',
            'L1'=>'Aug',
            'M1'=>'Sep',
            'N1'=>'Oct',
            'O1'=>'Nov',
            'P1'=>'Dec',
            'Q1'=>'No. HRS',
            'R1'=>'Amount Accrued',
            'S1'=>'Less W/Tax',
            'T1'=>'Net Due'
        ];
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowNumber = 2;
        foreach ($records as $school_year => $rows) {
            $sheet->setCellValue("A$rowNumber", "SCHOOL YEAR: $school_year");
            $sheet->mergeCells("A$rowNumber:T$rowNumber");
            $sheet->getStyle("A$rowNumber")->getFont()->setBold(true);
            $rowNumber++;
            $i=0;
            foreach ($rows as $row) {
                $i++;
                $sheet->setCellValue("A$rowNumber", $i);
                $sheet->setCellValue("B$rowNumber", $row->name);
                $sheet->setCellValue("C$rowNumber", $row->rate_per_hour);
                $sheet->setCellValue("D$rowNumber", $row->particulars ?? '-');

                $months = ['jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dece'];
                $col = 'E';
                foreach ($months as $m) {
                    $sheet->setCellValue("$col$rowNumber", $row->$m ?? 0);
                    $col++;
                }
                $sheet->setCellValue("Q$rowNumber", $row->total_hours);
                $sheet->setCellValue("R$rowNumber", $row->gross_amount);
                $sheet->setCellValue("S$rowNumber", $row->tax_amount);
                $sheet->setCellValue("T$rowNumber", $row->total_net);
                $rowNumber++;
            }
        }

        foreach(range('A','T') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Clear any previous output
        if (ob_get_length()) ob_end_clean();

        // Headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Payroll.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function download_excel_general_payroll($id)
    {
        $records = $this->Get_model->get_grouped_general_payroll($id);

        if (empty($records)) {
            show_error('No payroll records found.', 404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Payroll');

        // Header
        $headers = [
            'A1' => 'Name',
            'B1' => 'Basic',
            'C1' => 'Salary LWOP',
            'D1' => 'PERA',
            'E1' => 'Gross Pay',
            'F1' => 'GSIS',
            'G1' => 'PHILHEALTH',
            'H1' => 'PAG_IBIG',
            'I1' => 'Withholding Tax',
            'J1' => 'Other deductions',
            'K1' => 'Total Deductions',
            'L1' => 'Net Pay',
            'M1' => '1st Quincena',
            'N1' => '2nd Quincena'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowNumber = 2;
        foreach ($records as $row) {
            $sheet->setCellValue("A$rowNumber", $row->name);
            $sheet->setCellValue("B$rowNumber", $row->basic_salary);
            $sheet->setCellValue("C$rowNumber", $row->salary_lwop);
            $sheet->setCellValue("D$rowNumber", $row->pera);
            $sheet->setCellValue("E$rowNumber", $row->gross_pay);
            $sheet->setCellValue("F$rowNumber", $row->gsis);
            $sheet->setCellValue("G$rowNumber", $row->philhealth);
            $sheet->setCellValue("H$rowNumber", $row->pagibig);
            $sheet->setCellValue("I$rowNumber", $row->tax);
            $sheet->setCellValue("J$rowNumber", $row->other_deductions);
            $sheet->setCellValue("K$rowNumber", $row->total_deductions);
            $sheet->setCellValue("L$rowNumber", $row->net_pay);
            $sheet->setCellValue("M$rowNumber", $row->net_pay_first);
            $sheet->setCellValue("N$rowNumber", $row->net_pay_second);
            $rowNumber++;
        }
        $sheet->getStyle("B2:G$rowNumber")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Payroll.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function download_excel_midyear_payroll($id)
    {
        $records = $this->Get_model->get_grouped_midyear_payroll($id);
        if (empty($records)) {
            show_error('No payroll records found.', 404);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mid-Year Bonus');

        // === DETERMINE ALL DEDUCTIONS PRESENT IN RECORDS ===
        $deduction_names = [];
        foreach ($records as $row) {
            if (!empty($row->less)) {
                $items = explode(',', $row->less);
                foreach ($items as $item) {
                    $parts = explode(':', $item);
                    $deduction = trim($parts[0]);
                    if (!in_array($deduction, $deduction_names)) {
                        $deduction_names[] = $deduction;
                    }
                }
            }
        }

        // === HEADER ROWS ===
        // Merge main headers
        $sheet->mergeCells('A1:A2'); // NAME
        $sheet->mergeCells('B1:B2'); // POSITION
        $sheet->mergeCells('C1:C2'); // BASIC PAY
        $sheet->mergeCells('D1:D2'); // TOTAL Mid-Year Benefits
        $deduction_start_col = 'E';
        $deduction_end_col = chr(ord('E') + count($deduction_names) - 1);
        $sheet->mergeCells("{$deduction_start_col}1:{$deduction_end_col}1"); // LESS span
        $sheet->mergeCells(chr(ord($deduction_end_col)+1).'1:'.chr(ord($deduction_end_col)+1).'2'); // TAX
        $sheet->mergeCells(chr(ord($deduction_end_col)+2).'1:'.chr(ord($deduction_end_col)+2).'2'); // NET PAY

        // Set main headers
        $sheet->setCellValue('A1', 'NAME');
        $sheet->setCellValue('B1', 'POSITION');
        $sheet->setCellValue('C1', 'BASIC PAY');
        $sheet->setCellValue('D1', 'TOTAL Mid-Year Benefits');
        $sheet->setCellValue($deduction_start_col.'1', 'LESS:');
        $sheet->setCellValue(chr(ord($deduction_end_col)+1).'1', 'TAX DUE');
        $sheet->setCellValue(chr(ord($deduction_end_col)+2).'1', 'NET AMOUNT DUE');

        // Set deduction sub-headers
        $col = 'E';
        foreach ($deduction_names as $d) {
            $sheet->setCellValue($col.'2', $d);
            $col++;
        }

        // Style headers bold and centered
        $sheet->getStyle('A1:'.chr(ord($deduction_end_col)+2).'2')->getFont()->setBold(true);
        $sheet->getStyle('A1:'.chr(ord($deduction_end_col)+2).'2')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // === FILL DATA ===
        $rowNumber = 3;
        foreach ($records as $row) {
            $less_values = [];
            if (!empty($row->less)) {
                $items = explode(',', $row->less);
                foreach ($items as $item) {
                    $parts = explode(':', $item);
                    $less_values[trim($parts[0])] = floatval($parts[1] ?? 0);
                }
            }

            $sheet->setCellValue("A$rowNumber", $row->name);
            $sheet->setCellValue("B$rowNumber", $row->position);
            $sheet->setCellValue("C$rowNumber", $row->basic_salary);
            $sheet->setCellValue("D$rowNumber", $row->gross_pay);

            // Fill only deductions present for this row
            $col = 'E';
            foreach ($deduction_names as $d) {
                $sheet->setCellValue($col.$rowNumber, $less_values[$d] ?? '');
                $col++;
            }

            $sheet->setCellValue($col.$rowNumber, $row->tax);
            $col++;
            $sheet->setCellValue($col.$rowNumber, $row->net_pay);

            $rowNumber++;
        }

        // Format currency for numeric columns
        $highestCol = chr(ord($deduction_end_col)+2);
        $sheet->getStyle("C3:{$highestCol}{$rowNumber}")
            ->getNumberFormat()
            ->setFormatCode('"₱"#,##0.00');

        // Auto-size columns
        foreach (range('A', $highestCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MidYearBonus.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function download_philhealth($id)
    {
    
        
        $records = $this->Get_model->get_grouped_general_payroll($id);
        $period = $this->Get_model->get_period($id);
        $payroll_no = $period ? $period->payroll_number : 'Unknown';

        if (empty($records)) {
            show_error('No payroll records found.', 404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($payroll_no);

        // Header
        $headers = [
            'A1' => 'REMITTANCE REPORT',
            'A2' => 'PHILHEATH NO:',
            'A3' => 'EMPLOYEER TIN:',
            'A4' => 'EMPLOYEE SSS NO:',
            'A5' => '',
            'A6' => 'EMPLOYEER NAME',
            'B1' => 'PHILHEALTH CONTRIBUTION',
            'B2' => '144737000001',
            'B3' => '000611739000',
            'B4' => '', 
            'B5' => '', 
            'B6' => 'EASTERN VISAYAS STATE UNIVERSITY',
            'A7' => '',
            'A8' => '#',
            'B8' => 'Name',
            'C8' => 'PIN/SSS',
            'D8' => 'CONTRIBUTIONS',
        ];

        foreach ($headers as $cell => $value) {

            // Force specific cells to text
            if (in_array($cell, ['B2', 'B3'])) {
                $sheet->setCellValueExplicit($cell, $value, DataType::TYPE_STRING);
                 $sheet->getStyle($cell)->getFont()->setBold(true);
            } else {
                $sheet->setCellValue($cell, $value);
            }
        }

        $rowNumber = 9;
        $i = 0;
        foreach ($records as $row) {
            $i++;
           $sheet->setCellValue("A{$rowNumber}", $i);
           $sheet->setCellValue("B{$rowNumber}", $row->name);
        //    $sheet->setCellValue("C{$rowNumber}", $row->philhealth_number);
           $sheet->setCellValue("D{$rowNumber}", $row->philhealth);
            $rowNumber++;
        }
        $sheet->getStyle("B2:G$rowNumber")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $cleanFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $payroll_no);
        header('Content-Disposition: attachment; filename="' . $cleanFileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function download_gsis($id)
    {
        $details = $this->Get_model->get_period($id);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=GSIS_Remittance.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['Employee Name', 'GSIS Contribution']);

        foreach ($details as $row) {
            fputcsv($output, [
                $row->employee_name,
                $row->gsis_deduction
            ]);
        }

        fclose($output);
    }

    public function download_pagibig($id)
    {

        $records = $this->Get_model->get_grouped_general_payroll($id);

        if (empty($records)) {
            show_error('No payroll records found.', 404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Payroll');

        // Header
        $headers = [
            'A1' => 'EMPLOYEE ID',
            'A2' => 'EMPLOYEE NAME:',
            'A3' => 'ADDRESS',
            'B1' => '2062-0115-0006',
            'B2' => 'EASTERN VISAYAS STATE UNIVERSITY',
            'B3' => 'TACLOBAN CITY',
            'A1' => 'Name',
            'B1' => 'Basic',
            'C1' => 'Salary LWOP',
            'D1' => 'PERA',
            'E1' => 'Gross Pay',
            'F1' => 'GSIS',
            'G1' => 'PHILHEALTH',
            'H1' => 'PAG_IBIG',
            'I1' => 'Withholding Tax',
            'J1' => 'Other deductions',
            'K1' => 'Total Deductions',
            'L1' => 'Net Pay',
            'M1' => '1st Quincena',
            'N1' => '2nd Quincena'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowNumber = 2;
        foreach ($records as $row) {
            $sheet->setCellValue("A$rowNumber", $row->name);
            $sheet->setCellValue("B$rowNumber", $row->basic_salary);
            $sheet->setCellValue("C$rowNumber", $row->salary_lwop);
            $sheet->setCellValue("D$rowNumber", $row->pera);
            $sheet->setCellValue("E$rowNumber", $row->gross_pay);
            $sheet->setCellValue("F$rowNumber", $row->gsis);
            $sheet->setCellValue("G$rowNumber", $row->philhealth);
            $sheet->setCellValue("H$rowNumber", $row->pagibig);
            $sheet->setCellValue("I$rowNumber", $row->tax);
            $sheet->setCellValue("J$rowNumber", $row->other_deductions);
            $sheet->setCellValue("K$rowNumber", $row->total_deductions);
            $sheet->setCellValue("L$rowNumber", $row->net_pay);
            $sheet->setCellValue("M$rowNumber", $row->net_pay_first);
            $sheet->setCellValue("N$rowNumber", $row->net_pay_second);
            $rowNumber++;
        }
        $sheet->getStyle("B2:G$rowNumber")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Payroll.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function mark_received_accounting($id)
    {
        $token = $this->input->post('token_id');
        if (empty($id)) {
            show_error('Invalid Payroll ID');
        }
        $updated = $this->Update_model->mark_received_accounting($id);

        if (!$updated) {
            $this->session->set_flashdata('error', 'Already received or not found.');
        } else {
            $this->session->set_flashdata('success', 'Payroll marked as received.');
        }

        redirect('receiver/dashboard/'.$token);
    }


    public function mark_received_admin($id)
    {
        $token = $this->input->post('token_id');
        if (empty($id)) {
            show_error('Invalid Payroll ID');
        }
        $updated = $this->Update_model->mark_received_admin($id);

        if (!$updated) {
            $this->session->set_flashdata('error', 'Already received or not found.');
        } else {
            $this->session->set_flashdata('success', 'Payroll marked as received.');
        }

        redirect('receiver/dashboard/'.$token);
    }

    public function payslips($period_id)
	{
		$data['payrolls'] = $this->Get_model->getPayrollByPeriod($period_id);

		if (empty($data['payrolls'])) {
			show_error('No payroll records found.');
		}

		$this->load->view('payroll/layout/payslips', $data);
	}

    // Proof List
    public function download_proof_list_gp($payroll_period_id)
    {
        $records = $this->Get_model->get_proof_listGP($payroll_period_id);

        require_once(APPPATH . '../vendor/autoload.php');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->SetFont('courier', '', 9); 

        $rows_per_page = 30;
        $total_amount_all = 0;
        $total_accounts_all = count($records); // Get total count of records
        
        $page_total_amount = 0;
        $page_account_count = 0;

        // Helper to render the header
        if (!function_exists('renderHeader')) {
            function renderHeader($pdf) {
                $pdf->SetFont('courier', 'B', 12);
                $pdf->Cell(0, 6, 'EASTERN VISAYAS STATE UNIVERSITY', 0, 1, 'C');
                $pdf->SetFont('courier', '', 11);
                $pdf->Cell(0, 6, 'Payroll Prooflist for January 13, 2026', 0, 1, 'C');
                $pdf->Ln(2);

                $pdf->SetFont('courier', 'B', 9);
                $pdf->Cell(0, 4, str_repeat('=', 105), 0, 1, 'L');
                $pdf->Cell(50, 6, 'Account Number', 0, 0, 'L');
                $pdf->Cell(80, 6, 'NAME', 0, 0, 'L');
                $pdf->Cell(35, 6, 'Amount', 0, 1, 'R');
                $pdf->Cell(0, 4, str_repeat('=', 105), 0, 1, 'L');
            }
        }

        $pdf->AddPage();
        renderHeader($pdf);

        foreach ($records as $index => $row) {
            // Check for Page Break
            if ($page_account_count == $rows_per_page) {
                // Print Page Totals before breaking
                $pdf->SetFont('courier', 'B', 9);
                $pdf->Ln(2);
                $pdf->Cell(0, 5, 'PAGE TOTAL:', 0, 1, 'L');
                $pdf->Cell(130, 5, '   Number of Accounts Posted: ' . $page_account_count, 0, 0, 'L');
                $pdf->Cell(35, 5, number_format($page_total_amount, 2), 0, 1, 'R');
                
                $pdf->AddPage();
                renderHeader($pdf);

                // Reset page trackers
                $page_account_count = 0;
                $page_total_amount = 0;
            }

            $pdf->SetFont('courier', '', 9);
            $name = strtoupper($row->last_name . ', ' . $row->name);

            $pdf->Cell(50, 6, $row->account_no, 0, 0, 'L');
            $pdf->Cell(80, 6, $name, 0, 0, 'L');
            $pdf->Cell(35, 6, number_format($row->net_pay, 2), 0, 1, 'R');

            $page_total_amount += $row->net_pay;
            $total_amount_all += $row->net_pay;
            $page_account_count++;
        }

        $pdf->SetFont('courier', 'B', 9);
        $pdf->Ln(2);
        $pdf->Cell(0, 5, 'PAGE TOTAL:', 0, 1, 'L');
        $pdf->Cell(130, 5, '   Number of Accounts Posted: ' . $page_account_count, 0, 0, 'L');
        $pdf->Cell(35, 5, number_format($page_total_amount, 2), 0, 1, 'R');

        $pdf->Ln(4);
        $pdf->Cell(0, 5, 'OVERALL TOTAL:', 0, 1, 'L');
        $pdf->Cell(50, 5, '   Number of Accounts:', 0, 0, 'L');
        $pdf->Cell(30, 5, $total_accounts_all, 1, 0, 'C'); // Boxed like your image
        $pdf->Cell(50, 5, 'Total Amount :', 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($total_amount_all, 2), 0, 1, 'R');

        // 3. Nothing Follows
        $pdf->SetFont('courier', 'B', 10);
        $pdf->Cell(0, 10, '*** Nothing Follows ***', 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->Output('Proof_List.pdf', 'I');
    }

    public function verified_payroll(){
        $role = $this->session->userdata('receiver_role');
        $data['recent_payrolls'] = $this->Get_model->get_received_payrolls($role);
        $data['period'] = "Received Payroll"; 
        $this->load->view('template/receiver_header');
		$this->load->view('verify/received_payroll', $data);
		$this->load->view('template/admin_footer');
    }

    public function get_payroll_details_ajax($id){
        $payroll = $this->Get_model->get_payroll_by_id($id);

        if(!$payroll){
            echo json_encode([
                'status' => 'error',
                'message' => 'Payroll not found'
            ]);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'payroll' => $payroll
        ]);
    }
    

}