<style>
    #savedPayrollTable th,
    #savedPayrollTable td {
        white-space: normal !important;
        word-break: break-word;
        font-size: 0.85rem;
        padding: 0.35rem 0.5rem;
    }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 10px 15px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
        background: #f8f9fa;
    }

    .section-earnings { border-left-color: #198754; }
    .section-deductions { border-left-color: #dc3545; }
    .section-loans { border-left-color: #fd7e14; }
    .section-summary { border-left-color: #0d6efd; }

    .payroll-input {
        text-align: right;
    }

    .readonly-field {
        background-color: #f1f3f5;
        font-weight: 600;
    }


    /* tracker */
    .payroll-tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 40px 0;
        flex-wrap: wrap; /* allow wrapping on small screens */
    }

    .payroll-tracker::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e9ecef;
        z-index: 1;
    }

    .tracker-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1 1 150px; /* flexible width with minimum */
        margin-bottom: 20px; /* spacing when wrapped */
    }

    .tracker-circle {
        width: 40px;
        height: 40px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #dee2e6;
        line-height: 40px;
        font-weight: bold;
    }

    .tracker-step.active .tracker-circle {
        background: #28a745;
        color: #fff;
    }

    .tracker-step.current .tracker-circle {
        background: #ffc107;
        color: #000;
    }

    /* SUB PROCESS */
    .sub-process {
        margin-top: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap; /* wrap sub-steps if needed */
    }

    .sub-step {
        font-size: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #dee2e6;
        white-space: nowrap; /* prevent breaking inside words */
    }

    .sub-step.active {
        background: #28a745;
        color: #fff;
    }

    .sub-step.current {
        background: #ffc107;
        color: #000;
    }

    /* LEGEND */
    .tracker-legend {
        display: flex;
        gap: 15px;
        align-items: center;
        font-size: 14px;
        flex-wrap: wrap; /* wrap legend items if needed */
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .legend-item::before {
        content: '';
        width: 15px;
        height: 15px;
        display: inline-block;
        border-radius: 50%;
    }

    .active-legend::before {
        background-color: #28a745;
    }

    .current-legend::before {
        background-color: #ffc107;
    }

    .pending-legend::before {
        background-color: #dee2e6;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .payroll-tracker {
            flex-direction: column; /* stack steps vertically */
            align-items: flex-start;
        }

        .tracker-step {
            flex: 1 1 100%;
            text-align: left;
        }

        .sub-process {
            justify-content: flex-start;
        }
    }
</style>
<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <div class="row">
        <div class="col-4 col-sm-4 col-lg-4">
            <!-- PAYROLL FORM -->
            <div class="card shadow mb-4">
                <div class="card-header">

                    <div class="row align-items-center g-2">

                        <!-- LEFT: TITLE -->
                        <div class="col-12 col-md-6">
                            <h5 class="mb-0">Payroll Computation</h5>
                        </div>

                        <!-- RIGHT: RATE CONTROL -->
                        <div class="col-12 col-md-6">

                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-md-end gap-2">

                                <label class="fw-semibold small text-muted mb-0">Rate / Day</label>

                                <input type="number"
                                    id="rate_input"
                                    class="form-control form-control-sm"
                                    step="0.01"
                                    style="max-width:140px;">

                                <button type="button"
                                        class="btn btn-sm btn-primary w-100 w-sm-auto"
                                        onclick="saveRate()">
                                    Save
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <!-- EMPLOYEE SELECT -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Employee</label>

                        <!-- Select for adding new payroll -->
                        <select id="employee_select" class="form-select">
                            <option value="">-- Select Employee --</option>
                            <?php foreach ($employees as $row): ?>
                                <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                    <option value="<?= $row->employee_id ?>" data-position="<?= htmlspecialchars($row->position) ?>" data-name="<?= htmlspecialchars(trim($row->name . ' ' . $row->middle_name . ' ' . $row->last_name)) ?>">
                                        <?= htmlspecialchars(
                                            $row->name . ' ' .
                                            (!empty($row->middle_name)
                                                ? strtoupper(substr($row->middle_name, 0, 1)) . '. '
                                                : ''
                                            ) .
                                            $row->last_name
                                        ) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                        <!-- Readonly input for edit mode -->
                        <input type="text" id="employee_name" class="form-control" readonly style="display:none;">
                    </div>

                    <form class="container-fluid" style="font-size:14px;">
                        <input type="hidden" name="employee_id" id="employee_id">
                        <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">
                        <input type="hidden" id="employee_name_hidden" name="employee_name">
                        <input type="hidden" id="position_hidden" name="position">
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <div class="row g-4">

                            <!-- ================= EARNINGS ================= -->
                            <div class="col-lg-12">

                                <div class="section-title section-earnings">
                                    Earnings
                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Days Worked</label>
                                        <input type="number" step="0.001" min="0"
                                            id="days_worked" name="days_worked"
                                            class="form-control payroll-input">
                                    </div>
                                    

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">LWOP (Days)</label>
                                        <input type="number" step="0.001" min="0"
                                            id="lwop_days" name="lwop_days"
                                            class="form-control payroll-input">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Basic Salary</label>
                                        <input type="text" id="basic_salary" name="basic_salary"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Salary LWOP</label>
                                        <input type="text" id="salary_lwop" name="salary_lwop"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-12" id="pera_container">
                                        <label class="form-label">PERA LWOP</label>
                                        <input type="text" id="pera" name="pera"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Total LWOP Deduction</label>
                                        <input type="text" id="lwop_amount" name="lwop_amount"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Gross Pay</label>
                                        <input type="text" id="gross_pay" name="gross_pay"
                                            class="form-control payroll-input readonly-field fw-bold" readonly>
                                    </div>

                                </div>
                            </div>


                            <!-- ================= MANDATORY DEDUCTIONS ================= -->
                                <hr>
                            <div class="col-lg-12">

                                <div class="section-title section-deductions">
                                    Mandatory Deductions
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="mandatory_switch">
                                        <label class="form-check-label fw-bold">
                                            Enable Mandatory Deductions
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3">

                                    <!-- GSIS or SSS (dynamic label handled by JS) -->
                                    <div class="col-md-6" id="gov_container">
                                        <label class="form-label" id="gov_label">GSIS</label>
                                        <input type="text" id="gsis" class="form-control payroll-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">PhilHealth</label>
                                        <input type="text" id="philhealth" class="form-control payroll-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Pag-IBIG</label>
                                        <input type="text" id="pagibig" class="form-control payroll-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Withholding Tax</label>
                                        <input type="text" id="tax" class="form-control payroll-input">
                                    </div>

                                </div>
                            </div>


                            <!-- ================= OTHER DEDUCTIONS ================= -->
                                <hr>
                            <div class="col-12 mt-3">

                                <div class="section-title section-loans">
                                    Other Deductions (Loans / Adjustments)
                                </div>

                                <div id="loan-deductions-container" class="row g-3"></div>
                            </div>


                            <!-- ================= PAYROLL SUMMARY ================= -->
                                <hr>
                            <div class="col-12 mt-4">

                                <div class="section-title section-summary">
                                    Payroll Summary
                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Total Deductions</label>
                                        <input type="text" id="total_deductions" name="total_deductions"
                                            class="form-control payroll-input readonly-field fw-bold" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-success">Total Net Pay</label>
                                        <input type="text" id="net_pay" name="net_pay"
                                            class="form-control payroll-input readonly-field fw-bold text-success" readonly>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" id="btnSavePayroll" class="btn btn-primary px-4">
                                        Save Payroll
                                    </button>
                                </div>

                            </div>

                        </div>
                    </form>


                </div>
            </div>
        </div>
        <div class="col-8 col-sm-8 col-lg-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        <?= $unit?> ( <?= $payroll_type ?>) 
                    </h5>

                    <div>
                        <?php if($status == 1): ?>
                            <span class="badge bg-secondary">Draft Payroll</span>

                        <?php elseif($status >= 2 && $status < 7): ?>
                            <span class="badge bg-warning text-dark">
                                Pending Admin Approval
                            </span>

                        <?php elseif($status == 7): ?>
                            <span class="badge bg-success">
                                Approved
                            </span>

                        <?php elseif($status == 9): ?>
                            <span class="badge bg-primary">
                                Released
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($status >= 2 && $status < 7): ?>
                        <div class="alert alert-warning d-flex align-items-center mb-3">
                            <i class="bi bi-hourglass-split me-2"></i>
                            <div>
                                This payroll has been <strong>submitted by HR</strong> and is currently
                                <strong>awaiting approval from the Admin Office</strong><br>
                                <strong>Token ID: <?= $token_id ?></strong>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row g-3 mb-3">
                        <!-- <div class="card-body">

                            <div class="tracker-legend mb-4">
                                <span class="legend-item active-legend">Completed</span>
                                <span class="legend-item current-legend">Current</span>
                                <span class="legend-item pending-legend">Pending</span>
                            </div>

                            <div class="payroll-tracker">

                                <div class="tracker-step" id="step-1">
                                    <div class="tracker-circle">1</div>
                                    <div>HR</div>
                                </div>

                                <div class="tracker-step" id="step-2">
                                    <div class="tracker-circle">2</div>
                                    <div>Admin Office</div>
                                </div>

                                <div class="tracker-step" id="step-3">
                                    <div class="tracker-circle">3</div>
                                    <div>Budget Office</div>
                                </div>

                                <div class="tracker-step" id="step-4">
                                    <div class="tracker-circle">4</div>
                                    <div>Accounting Office</div>
                                </div>

                                <div class="tracker-step" id="step-5">
                                    <div class="tracker-circle">5</div>
                                    <div>VP for Signing</div>
                                </div>

                                <div class="tracker-step" id="step-6">
                                    <div class="tracker-circle">6</div>
                                    <div>Cashier (Bank Release)</div>
                                </div>

                            </div>

                        </div> -->
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Gross Pay
                                            </div>
                                            <h5 class="mb-0 fw-bold text-success" id="total_dw_gross_pay">
                                                ₱0.00
                                            </h5>
                                        </div>
                                        <div class="text-success dashboard-icon">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Deductions
                                            </div>
                                            <h5 class="mb-0 fw-bold text-danger" id="total_dw_deduction">
                                                ₱0.00
                                            </h5>
                                        </div>
                                        <div class="text-danger dashboard-icon">
                                            <i class="bi bi-dash-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="col-12 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body d-flex justify-content-center align-items-center">

                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-secondary btn-sm w-100 dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> Actions
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end w-100 shadow">

                                            <!-- PRINT -->
                                            <li>
                                                <a class="dropdown-item" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf_dw/'.$period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Download Payroll
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('payroll/export_transmittal_pdf/'. $period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Download Transmittal PDF
                                                </a>
                                            </li>

                                            <!-- SUBMIT -->
                                           <?php if($status == 1): ?>
                                                <li>
                                                    <a class="dropdown-item submit_payroll" href="#" data-period_id="<?= $period_id ?>" data-payroll_number="<?= $payroll_number ?>"> <!-- add payroll_number -->
                                                        <i class="bi bi-check-circle me-2"></i> Submit Payroll
                                                    </a>
                                                </li>
                                                <?php else: ?>
                                                <li>
                                                    <span class="dropdown-item text-muted">
                                                        <i class="bi bi-hourglass-split me-2"></i>
                                                        Payroll Submitted
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <!-- GENERATE PAYSLIPS -->
                                            <li>
                                                <a class="dropdown-item"
                                                href="#"
                                                onclick="generatePayslips(<?= $period_id ?>)">
                                                    <i class="bi bi-receipt me-2"></i> Generate Payslips
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm table-bordered" id="savedPayrollTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Gross Pay</th>
                                    <th>GSIS</th>
                                    <th>Phil-Health</th>
                                    <th>Pagibig</th>
                                    <th>Other Deduction</th>
                                    <th>Total Deduction</th>
                                    <th>Net Pay</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end">TOTAL</td>
                                    <td id="totalGross" class="text-end"></td>
                                    <td id="totalGSIS" class="text-end"></td>
                                    <td id="totalPhilhealth" class="text-end"></td>
                                    <td id="totalPagibig" class="text-end"></td>
                                    <td></td>
                                    <td id="totalDeductions" class="text-end"></td>
                                    <td id="totalNetPay" class="text-end text-success"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
const BASE_URL = "<?= base_url() ?>";
const CSRF_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
const CSRF_HASH = "<?= $this->security->get_csrf_hash(); ?>";
const PERIOD_ID = "<?= $period_id ?>";
const PAYROLL_TYPE = "<?= $payroll_type ?>";

function getRatePerDay() {
    let key = 'rate_per_day_' + PAYROLL_TYPE.replace(/\s+/g, '_');

    let rate = localStorage.getItem(key);
    return rate ? parseFloat(rate) : 608.55;
}

function setRatePerDay(rate) {
    let key = 'rate_per_day_' + PAYROLL_TYPE.replace(/\s+/g, '_');
    localStorage.setItem(key, rate);
}

function saveRate() {
    let rate = parseFloat($('#rate_input').val()) || 608.55;
    setRatePerDay(rate);
    alert("Rate for " + PAYROLL_TYPE + " updated to " + rate);
}


// ================= HELPERS =================
function formatMoney(num) {
    return parseFloat(num || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function parseNumber(val) {
    return parseFloat((val || '').toString().replace(/,/g, '')) || 0;
}

// ================= COMPUTE =================
const isCOS = PAYROLL_TYPE === 'CONTRACT OF SERVICE';

$(document).ready(function () {

    setupPayrollUI();
    computePayroll();

});

// ================= UI SETUP =================
function setupPayrollUI() {

    if (isCOS) {

        // COS: GSIS becomes SSS
        $('#gov_label').text('SSS');

        // COS: switch OFF by default
        $('#mandatory_switch').prop('checked', false);

        // COS: optional deductions
        $('#gsis, #philhealth, #pagibig').prop('readonly', false);

    } else {

        // DAILY WAGE: GSIS applies
        $('#gov_label').text('GSIS');

        // DAILY WAGE: switch ON by default
        $('#mandatory_switch').prop('checked', true);

        // DAILY WAGE: controlled deductions
        $('#gsis, #philhealth').prop('readonly', true);
        $('#pagibig').prop('readonly', false);
    }
}

// ================= COMPUTATION =================
function computePayroll() {

    let ratePerDay = getRatePerDay();

    let daysWorked = parseNumber($('#days_worked').val());
    let lwopDays = parseNumber($('#lwop_days').val());

    let basicSalary = daysWorked * ratePerDay;
    let lwopDeduction = lwopDays * ratePerDay;
    let salaryAfterLWOP = basicSalary - lwopDeduction;

    // ================= PERA =================
    let pera = 0;

    if (!isCOS) {
        let peraPerDay = 2000 / 22;
        pera = (peraPerDay * daysWorked) - (peraPerDay * lwopDays);
    }

    $('#basic_salary').val(formatMoney(basicSalary));
    $('#salary_lwop').val(formatMoney(salaryAfterLWOP));
    $('#lwop_amount').val(formatMoney(lwopDeduction));
    $('#pera').val(formatMoney(pera));

    let grossPay = salaryAfterLWOP + pera;
    $('#gross_pay').val(formatMoney(grossPay));

    // ================= MANDATORY =================
    let gov = 0;
    let philhealth = 0;
    let pagibig = 0;

    if ($('#mandatory_switch').is(':checked')) {

        if (isCOS) {

            // COS: user-controlled (NO AUTO COMPUTE)
            gov = parseNumber($('#gsis').val());
            philhealth = parseNumber($('#philhealth').val());
            pagibig = parseNumber($('#pagibig').val());

        } else {

            // DAILY WAGE: AUTO COMPUTE
            let monthlyBase = ratePerDay * 22;

            gov = monthlyBase * 0.09;        // GSIS
            philhealth = monthlyBase * 0.025;
            pagibig = 200;
        }
    }

    $('#gsis').val(formatMoney(gov));
    $('#philhealth').val(formatMoney(philhealth));
    $('#pagibig').val(formatMoney(pagibig));

    let tax = parseNumber($('#tax').val());

    // ================= OTHER DEDUCTIONS =================
    let otherDeductions = 0;
    $('#loan-deductions-container .loan-item').each(function () {
        otherDeductions += parseNumber($(this).find('.loan-input').val());
    });

    let totalDeductions = gov + philhealth + pagibig + tax + otherDeductions;

    $('#total_deductions').val(formatMoney(totalDeductions));

    let netPay = grossPay - totalDeductions;
    $('#net_pay').val(formatMoney(netPay));
}

$(document).ready(function () {

    let savedRate = getRatePerDay();
    $('#rate_input').val(savedRate);

    // initialize switch state
    toggleMandatory($('#mandatory_switch').is(':checked'));

    // 🔥 FORCE INITIAL COMPUTATION
    computePayroll();

});
// ================= SAVE =================
function savePayroll() {

    let otherDeductionsStr = '';

    $('#loan-deductions-container .loan-item').each(function () {

        let name = $(this).find('label').text().trim();
        let amount = parseNumber($(this).find('.loan-input').val());

        if (amount > 0) {
            otherDeductionsStr += `${name}:${amount.toFixed(2)},`;
        }
    });

    otherDeductionsStr = otherDeductionsStr.replace(/,$/, '');

    let data = {
        employee_id: $('#employee_id').val(),
        payroll_period_id: $('#payroll_period_id').val(),

        employee_name: $('#employee_name_hidden').val(),
        position: $('#position_hidden').val(),
        days_worked: parseNumber($('#days_worked').val()),
        rate_per_day: parseNumber($('#rate_input').val()),                                  

        basic_salary: parseNumber($('#basic_salary').val()),
        lwop_days: parseNumber($('#lwop_days').val()),
        lwop_amount: parseNumber($('#lwop_amount').val()),
        salary_lwop: parseNumber($('#salary_lwop').val()),
        pera: parseNumber($('#pera').val()),
        gross_pay: parseNumber($('#gross_pay').val()),

        gsis: parseNumber($('#gsis').val()),
        philhealth: parseNumber($('#philhealth').val()),
        pagibig: parseNumber($('#pagibig').val()),

        tax: parseNumber($('#tax').val()),
        total_deductions: parseNumber($('#total_deductions').val()),
        net_pay: parseNumber($('#net_pay').val()),
        other_deductions: otherDeductionsStr,

        payroll_type: PAYROLL_TYPE,

        [CSRF_NAME]: CSRF_HASH
    };

    $.ajax({
        url: BASE_URL + "payroll/save_dw",
        type: "POST",
        data: data,
        dataType: "json",

        success: function(res) {
            if (res.status === 'success') {
                Swal.fire('Success', 'Payroll saved successfully!', 'success')
                .then(() => location.reload());
            } else {
                alert(res.message || 'Saving failed.');
            }
        }
    });
}

// ================= LOANS =================
function loadEmployeeLoans(employee_id) {

    $.ajax({
        url: BASE_URL + "payroll/get_employee_loans",
        type: "POST",
        data: {
            employee_id: employee_id,
            [CSRF_NAME]: CSRF_HASH
        },
        dataType: "json",

        success: function(res) {

    let html = "";

    if (res.length > 0) {
        res.forEach(loan => {
            html += `
                <div class="col-md-6 loan-item">
                    <label class="form-label fw-semibold">
                        ${loan.deduction_name}
                    </label>
                    <input type="text"
                        class="form-control loan-input text-end"
                        value="${parseFloat(loan.monthly_deduction || 0).toFixed(2)}">
                </div>
            `;
        });
    } else {
        html = `<div class="col-12 text-muted">No active loans found</div>`;
    }

    // 🔥 inject to DOM FIRST
    $('#loan-deductions-container').html(html);

    // 🔥 THEN compute (VERY IMPORTANT ORDER)
    computePayroll();
}
    });
}

// ================= FORMAT OTHER DEDUCTIONS =================
function formatOtherDeductions(otherStr) {
    if (!otherStr) return '<span class="text-muted">None</span>';

    return otherStr.split(',').map(item => {
        let [name, amount] = item.split(':');
        return `${name}: ₱ ${parseFloat(amount || 0).toFixed(2)}`;
    }).join('<br>');
}

// ================= LOAD PAYROLL TABLE =================
function loadPayrollDW() {

    $.ajax({
        url: BASE_URL + "payroll/fetchPayrollDW?period_id=" + PERIOD_ID,
        type: "GET",
        dataType: "json",

        success: function(res) {

            let tbody = $("#savedPayrollTable tbody");
            tbody.empty();
            let usedEmployees = res.used || [];
            // ================= TOTALS =================
            let totalGrossPay = 0;
            let totalGSIS = 0;
            let totalPhilhealth = 0;
            let totalPagibig = 0;
            let totalDeductions = 0;
            let totalNetPay = 0;

            if (!res.data || res.data.length === 0) {

                tbody.html(`
                    <tr>
                        <td colspan="10" class="text-center text-muted">
                            No payroll records found
                        </td>
                    </tr>
                `);

                $("#total_dw_gross_pay").text("₱0.00");
                $("#total_dw_deduction").text("₱0.00");

                $("#totalGross").text("₱0.00");
                $("#totalGSIS").text("₱0.00");
                $("#totalPhilhealth").text("₱0.00");
                $("#totalPagibig").text("₱0.00");
                $("#totalDeductions").text("₱0.00");
                $("#totalNetPay").text("₱0.00");

                return;
            }

            const isCOS = PAYROLL_TYPE === 'CONTRACT OF SERVICE';

            res.data.forEach(row => {

                let gross = parseFloat(row.gross_pay || 0);

                let contribution = isCOS
                    ? parseFloat(row.sss || 0)
                    : parseFloat(row.gsis || 0);

                let philhealth = parseFloat(row.philhealth || 0);
                let pagibig = parseFloat(row.pagibig || 0);
                let other = isCOS ? 0 : parseOtherDeductions(row.other_deductions);
                let deduction = parseFloat(row.total_deductions || 0) - (isCOS ? other : 0);
                let net = parseFloat(row.net_pay || 0);

                totalGrossPay += gross;
                totalGSIS += contribution;
                totalPhilhealth += philhealth;
                totalPagibig += pagibig;
                totalDeductions += deduction;
                totalNetPay += net;

                tbody.append(`
                    <tr>
                        <td>${row.name || ''}</td>
                        <td>${row.position || ''}</td>

                        <td class="text-end">₱ ${gross.toFixed(2)}</td>
                        <td class="text-end">₱ ${contribution.toFixed(2)}</td>
                        <td class="text-end">₱ ${philhealth.toFixed(2)}</td>
                        <td class="text-end">₱ ${pagibig.toFixed(2)}</td>

                        ${!isCOS ? `
                            <td class="small">
                                ${formatOtherDeductions(row.other_deductions)}
                            </td>
                            ` : ''}

                        <td class="text-end">₱ ${deduction.toFixed(2)}</td>

                        <td class="text-end text-success fw-bold">
                            ₱ ${net.toFixed(2)}
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-warning edit-payroll-btn"
                                data-id="${row.payroll_id}">
                                Edit
                            </button>
                        </td>
                    </tr>
                `);
            });

             $("#employee_select option").each(function () {
                let empId = $(this).val();

                if (usedEmployees.includes(String(empId))) {
                    $(this).prop('disabled', true)
                        .text($(this).text() + " (Already Added)");
                }
            });

            // ================= DASHBOARD =================
            $("#total_dw_gross_pay").text("₱ " + totalGrossPay.toFixed(2));
            $("#total_dw_deduction").text("₱ " + totalDeductions.toFixed(2));

            // ================= FOOTER TOTALS =================
            $("#totalGross").text("₱ " + totalGrossPay.toFixed(2));
            $("#totalGSIS").text("₱ " + totalGSIS.toFixed(2));
            $("#totalPhilhealth").text("₱ " + totalPhilhealth.toFixed(2));
            $("#totalPagibig").text("₱ " + totalPagibig.toFixed(2));
            $("#totalDeductions").text("₱ " + totalDeductions.toFixed(2));
            $("#totalNetPay").text("₱ " + totalNetPay.toFixed(2));
        }
    });
}

// ================= EVENTS =================
$(document).on('input', '#days_worked, #lwop_days, #tax', computePayroll);
$(document).on('input', '.loan-input', computePayroll);
$(document).on('change', '#mandatory_switch', computePayroll);

$(document).on('click', '#btnSavePayroll', function(e){
    e.preventDefault();
    savePayroll();
});

$(document).on('change', '#employee_select', function () {

    let emp_id = $(this).val();
    let selected = $("#employee_select option:selected");

    $('#employee_id').val(emp_id);

    if (emp_id) {

        // ================= CLEAN EMPLOYEE NAME =================
        let rawName = selected.data('name') || selected.text() || '';

        let employeeName = rawName
            .toString()
            .replace(/\s+/g, ' ')
            .trim();

        $('#employee_name_hidden').val(employeeName);

        // ================= POSITION =================
        let position = (selected.data('position') || '')
            .toString()
            .replace(/\s+/g, ' ')
            .trim();

        $('#position_hidden').val(position);

        // ================= LOAD LOANS =================
        loadEmployeeLoans(emp_id);

    } else {

        $('#employee_name_hidden').val('');
        $('#position_hidden').val('');
        $('#loan-deductions-container').html('');
    }
});


$(document).ready(function () {
    loadPayrollDW();
});
document.getElementById('btnPrint').addEventListener('click', function () {
    const url = this.getAttribute('data-url');
    window.open(url, '_blank');
});

$(document).on('click', '.submit_payroll', function () {

    let period_id = $(this).data('period_id');
    let payroll_number = $(this).data('payroll_number');

    Swal.fire({
        title: 'Submit Payroll?',
        text: "Once submitted, this payroll will be forwarded for processing.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "<?= base_url('payroll/submit_payroll') ?>",
                type: "POST",
                data: {
                    period_id: period_id,
                    payroll_number: payroll_number
                },
                dataType: "json",
                success: function (res) {

                    if (res.status === 'success') {

                        Swal.fire({
                            title: 'Submitted!',
                            text: 'Payroll submitted successfully.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong.',
                            icon: 'error'
                        });

                    }
                },
                error: function () {

                    Swal.fire({
                        title: 'Server Error',
                        text: 'Unable to process request.',
                        icon: 'error'
                    });

                }
            });

        }

    });
});

$(document).ready(function () {

    const isCOS = PAYROLL_TYPE === 'CONTRACT OF SERVICE';

    // =====================================================
    // CONTRACT OF SERVICE RULES
    // =====================================================
    if (isCOS) {

        // =========================
        // REMOVE PERA FIELD FROM UI
        // =========================
        $('#pera_container').hide();
        $('#pera').val(0);

        // =========================
        // GSIS → SSS CONVERSION
        // =========================
        $('#gsis')
            .val(0)
            .prop('readonly', false);

        $('#gsis').closest('.col-md-6').find('label').text('SSS');

        // =========================
        // FORCE ZERO VALUES
        // =========================
        $('#pagibig').val(0).prop('readonly', false);
        $('#philhealth').val(0).prop('readonly', false);

    }

    // =====================================================
    // NON-COS RULES
    // =====================================================
    else {
        // SHOW PERA AGAIN
        $('#pera_container').show();

        $('#gsis').prop('readonly', true);
        $('#pagibig').prop('readonly', false);
        $('#philhealth').prop('readonly', true);
    }

});

$(document).ready(function () {

    const isCOS = PAYROLL_TYPE === 'CONTRACT OF SERVICE';

    // =====================================================
    // SWITCH INITIAL STATE CONTROL
    // =====================================================
    if (isCOS) {

        // FORCE SWITCH OFF
        $('#mandatory_switch').prop('checked', false);

        toggleMandatory(false);

    } else {

        // DEFAULT: ON for other payroll types
        $('#mandatory_switch').prop('checked', true);

        toggleMandatory(true);
    }

    // =====================================================
    // USER TOGGLE LISTENER
    // =====================================================
    $('#mandatory_switch').on('change', function () {
        toggleMandatory(this.checked);
    });

});


// =====================================================
// TOGGLE FUNCTION
// =====================================================
function toggleMandatory(isEnabled) {

    if (isEnabled) {

        $('#sss, #pagibig, #philhealth').prop('readonly', false);

    } else {

        $('#sss, #pagibig, #philhealth').prop('readonly', true);
    }
}
function parseOtherDeductions(str) {
    if (!str) return 0;

    let total = 0;

    str.split(',').forEach(item => {
        let parts = item.split(':');
        if (parts.length === 2) {
            total += parseFloat(parts[1]) || 0;
        }
    });

    return total;
}

if (isCOS) {
    // remove "Other Deduction" column header (7th column)
    $('#savedPayrollTable thead th:nth-child(7)').remove();
    $('#savedPayrollTable tfoot td:nth-child(7)').remove();
}


$(document).on('click', '.edit-payroll-btn', function () {

    let payroll_id = $(this).data('id');

    $.ajax({
        url: BASE_URL + "payroll/get_single_dw",
        type: "POST",
        data: {
            payroll_id: payroll_id,
            [CSRF_NAME]: CSRF_HASH
        },
        dataType: "json",

        success: function (res) {

            if (!res) {
                alert("Record not found");
                return;
            }

            // ================= SET FORM VALUES =================
            $('#payroll_id').val(res.payroll_id);
            $('#employee_id').val(res.employee_id);

            $('#employee_name_hidden').val(res.name);
            $('#position_hidden').val(res.position);

            $('#days_worked').val(res.days_worked);
            $('#lwop_days').val(res.lwop_days);

            $('#basic_salary').val(res.basic_salary);
            $('#salary_lwop').val(res.salary_lwop);
            $('#lwop_amount').val(res.lwop_amount);
            $('#pera').val(res.pera);
            $('#gross_pay').val(res.gross_pay);

            // ================= HANDLE GSIS / SSS =================
            if (PAYROLL_TYPE === 'CONTRACT OF SERVICE') {
                $('#gsis').val(res.sss || 0); // mapped
            } else {
                $('#gsis').val(res.gsis || 0);
            }

            $('#philhealth').val(res.philhealth);
            $('#pagibig').val(res.pagibig);
            $('#tax').val(res.tax);

            $('#total_deductions').val(res.total_deductions);
            $('#net_pay').val(res.net_pay);

            // ================= TRIGGER RECOMPUTE =================
            computePayroll();

            // ================= UX =================
            $('html, body').animate({
                scrollTop: $(".card").offset().top
            }, 300);
        }
    });
});

$(document).on('click', '.delete-payroll-btn', function () {

    let payroll_id = $(this).data('id');

    Swal.fire({
        title: 'Delete Payroll?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: BASE_URL + "payroll/delete_dw",
                type: "POST",
                data: {
                    payroll_id: payroll_id,
                    [CSRF_NAME]: CSRF_HASH
                },
                dataType: "json",

                success: function (res) {

                    if (res.status === 'success') {

                        Swal.fire('Deleted!', 'Payroll removed.', 'success');

                        loadPayrollDW(); // reload table

                    } else {
                        Swal.fire('Error', 'Delete failed.', 'error');
                    }
                }
            });

        }

    });
});


</script>


