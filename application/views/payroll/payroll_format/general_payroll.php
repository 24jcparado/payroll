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
                    <h5 class="mb-0">Payroll Computation</h5>
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
                                    <option value="<?= $row->employee_id ?>">
                                        <?= htmlspecialchars(
                                            $row->name . ' ' .
                                            (!empty($row->middle_name)
                                                ? strtoupper(substr($row->middle_name, 0, 1)) . '. '
                                                : ''
                                            ) .
                                            $row->last_name
                                        ) ?>
                                        (SG-<?= $row->sg ?> STEP-<?= $row->step ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                        <!-- Readonly input for edit mode -->
                        <input type="text" id="employee_name" class="form-control" readonly style="display:none;">
                    </div>

                    <form id="payrollForm" class="container-fluid" style="font-size:14px;">
                        <input type="hidden" name="employee_id" id="employee_id">
                        <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <div class="row g-4">

                            <!-- ================= EARNINGS ================= -->
                            <div class="col-lg-12">

                                <div class="section-title section-earnings">
                                    Earnings
                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Basic Salary</label>
                                        <input type="text" id="basic_salary" name="basic_salary"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">LWOP (Days)</label>
                                        <input type="number" step="0.001" min="0"
                                            id="lwop_days" name="lwop_days"
                                            class="form-control payroll-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Salary LWOP</label>
                                        <input type="text" id="salary_lwop" name="salary_lwop"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">PERA LWOP</label>
                                        <input type="text" id="pera" name="pera" class="form-control payroll-input readonly-field" readonly>
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

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">GSIS</label>
                                        <input type="text" id="gsis" name="gsis"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">PhilHealth</label>
                                        <input type="text" id="philhealth" name="philhealth"
                                            class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Pag-IBIG</label>
                                        <input type="text" id="pagibig" name="pagibig"
                                            class="form-control payroll-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Withholding Tax</label>
                                        <input type="text" id="tax" name="tax"
                                            class="form-control payroll-input">
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

                                    <div class="col-md-6">
                                        <label class="form-label text-success">1st Quincena</label>
                                        <input type="text" id="net_pay_first" name="net_pay_first"
                                            class="form-control payroll-input readonly-field text-success fw-semibold" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-success">2nd Quincena</label>
                                        <input type="text" id="net_pay_second" name="net_pay_second"
                                            class="form-control payroll-input readonly-field text-success fw-semibold" readonly>
                                    </div>

                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
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
                                            <h5 class="mb-0 fw-bold text-success" id="total_gross_pay">
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
                                            <h5 class="mb-0 fw-bold text-danger" id="total_deduction">
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
                                                <a class="dropdown-item" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf/'.$period_id) ?>">
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
                                                    <a class="dropdown-item submit_payroll" href="#"
                                                    data-period_id="<?= $period_id ?>"
                                                    data-payroll_number="<?= $payroll_number ?>"> <!-- add payroll_number -->
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
                                    <th>Basic</th>
                                    <th>Gross Pay</th>
                                    <th>Total Deductions</th>
                                    <th>Net Pay</th>
                                    <th>1st Quincena</th>
                                    <th>2nd Quincena</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>

const PERA = 2000;
const GSIS_RATE = 0.09;
const PHIL_RATE = 0.025;
const WORK_DAYS = 22;
let EMPLOYEE_LOANS = 0;


$('#employee_select').on('change', function () {
    const empId = $(this).val();
    if (!empId) return;
     const payrollType = "<?= $payroll_type ?>"; 

    $.post("<?= base_url('payroll/ajax_get_salary') ?>",
        { 
            employee_id: empId,
            payroll_type: payrollType 
        },

        function (res) {

            $('#employee_id').val(res.employee_id);
            $('#basic_salary').val(res.basic_salary);
            $('#lwop_days').val(0);
            $('#pagibig').val(200.00);
            renderOtherDeductions(res.loans || []);
            computePayroll();
        },
        'json'
    );
    loadEmployeePayrollHistory(empId);
});


function computePayroll() {

    let basic   = round2(parseFloat($('#basic_salary').val()) || 0);
    let lwop    = round2(parseFloat($('#lwop_days').val()) || 0);
    let pagibig = round2(parseFloat($('#pagibig').val()) || 0);
    let tax = round2(parseFloat($('#tax').val()) || 0);

    // LWOP calculations
    let lwopDeduction  = round2((basic / WORK_DAYS) * lwop);
    let peraDeduction  = round2((PERA / WORK_DAYS) * lwop);

    let calPera        = round2(PERA - peraDeduction);
    let salary_lwop    = round2(basic - lwopDeduction);
    let lwop_amount    = round2(lwopDeduction + peraDeduction);

    // Contributions
    let gsis = round2(basic * GSIS_RATE);

    let phil = basic <= 99999
        ? round2(basic * PHIL_RATE)
        : 2500.00;

    // Gross
    let gross = round2(salary_lwop + calPera);
    let total = round2(
        round2(gsis) +
        round2(phil) +
        round2(pagibig) +
        round2(EMPLOYEE_LOANS)+
        round2(tax)
    );

    // Net pay
    let net = round2(gross - total);

    // Quincena split
    let first  = round2(net / 2);
    let second = round2(net - first);

    // UI output
    $('#lwop_amount').val(lwop_amount.toFixed(2));
    $('#gross_pay').val(gross.toFixed(2));
    $('#gsis').val(gsis.toFixed(2));
    $('#pera').val(calPera.toFixed(2));
    $('#salary_lwop').val(salary_lwop.toFixed(2));
    $('#philhealth').val(phil.toFixed(2));
    $('#total_deductions').val(total.toFixed(2));
    $('#net_pay').val(net.toFixed(2));
    $('#net_pay_first').val(first.toFixed(2));
    $('#net_pay_second').val(second.toFixed(2));
}



$('#lwop_days').on('input', computePayroll);
$('#pagibig').on('input', computePayroll);
$('#tax').on('input', computePayroll);

function round2(num) {
    return Math.round((num + Number.EPSILON) * 100) / 100;
}

$('#payrollForm').on('submit', function(e){
    e.preventDefault();

    const payrollId = $('#payroll_id').val();
    const isEdit = payrollId && payrollId !== '';

    const url = isEdit
        ? "<?= base_url('payroll/updatePayroll') ?>"
        : "<?= base_url('payroll/insertPayroll') ?>";

    $.post(url, $(this).serialize(), function(res){

        console.log(res);

        if (!res.status) {
            Swal.fire('Error', res.message, 'error');
            return;
        }

        // ============================
        // ✅ UPDATE MODE
        // ============================
        if (isEdit) {

            const row = $('#savedPayrollTable tr[data-payroll-id="'+res.payroll_id+'"]');

            row.html(`
                <td>${res.name}</td>
                <td class="text-end">₱ ${parseFloat(res.basic_salary).toFixed(2)}</td>
                <td class="text-end">₱ ${parseFloat(res.gross_pay).toFixed(2)}</td>
                <td class="text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-decoration-none fw-bold p-0" data-bs-toggle="dropdown">
                            ₱ ${parseFloat(res.total_deductions).toFixed(2)}
                            <i class="bi bi-caret-down-fill ms-1 small"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end p-2 shadow-sm" style="min-width: 260px">
                            <li class="small"><strong>GSIS:</strong> <span class="float-end">₱ ${parseFloat(res.gsis).toFixed(2)}</span></li>
                            <li class="small"><strong>PhilHealth:</strong> <span class="float-end">₱ ${parseFloat(res.philhealth).toFixed(2)}</span></li>
                            <li class="small"><strong>Pag-IBIG:</strong> <span class="float-end">₱ ${parseFloat(res.pagibig).toFixed(2)}</span></li>
                            <li class="small"><strong>LWOP:</strong> <span class="float-end">₱ ${parseFloat(res.lwop_amount).toFixed(2)}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="small fw-semibold text-muted">Other Deductions</li>
                            <li class="small">${formatOtherDeductions(res.other_deductions)}</li>
                        </ul>
                    </div>
                </td>

                <td class="text-end">₱ ${parseFloat(res.net_pay).toFixed(2)}</td>
                <td class="text-end">₱ ${parseFloat(res.net_pay_first).toFixed(2)}</td>
                <td class="text-end">₱ ${parseFloat(res.net_pay_second).toFixed(2)}</td>

                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-warning edit-payroll-btn">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </button>
                </td>
            `);

            Swal.fire('Updated', 'Payroll updated successfully', 'success');

            // reset form
            $('#payrollForm')[0].reset();
            $('#payroll_id').val('');
            $('#loan-deductions-container').empty();

            return; // ❗ STOP — no auto-next in edit mode
        }

        // ============================
        // ✅ INSERT MODE (your original logic)
        // ============================
        if ($('#payroll_period_id').val() == res.payroll_period_id) {

            $('#savedPayrollTable tbody').append(`
                <tr data-payroll-id="${res.payroll_id}" data-employee-id="${res.employee_id}">
                    <td>${res.name}</td>
                    <td class="text-end">₱ ${parseFloat(res.basic_salary).toFixed(2)}</td>
                    <td class="text-end">₱ ${parseFloat(res.gross_pay).toFixed(2)}</td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-decoration-none fw-bold p-0" data-bs-toggle="dropdown">
                                ₱ ${parseFloat(res.total_deductions).toFixed(2)}
                                <i class="bi bi-caret-down-fill ms-1 small"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end p-2 shadow-sm" style="min-width: 260px">
                                <li class="small"><strong>GSIS:</strong> <span class="float-end">₱ ${parseFloat(res.gsis).toFixed(2)}</span></li>
                                <li class="small"><strong>PhilHealth:</strong> <span class="float-end">₱ ${parseFloat(res.philhealth).toFixed(2)}</span></li>
                                <li class="small"><strong>Pag-IBIG:</strong> <span class="float-end">₱ ${parseFloat(res.pagibig).toFixed(2)}</span></li>
                                <li class="small"><strong>LWOP:</strong> <span class="float-end">₱ ${parseFloat(res.lwop_amount).toFixed(2)}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="small fw-semibold text-muted">Other Deductions</li>
                                <li class="small">${formatOtherDeductions(res.other_deductions)}</li>
                            </ul>
                        </div>
                    </td>

                    <td class="text-end">₱ ${parseFloat(res.net_pay).toFixed(2)}</td>
                    <td class="text-end">₱ ${parseFloat(res.net_pay_first).toFixed(2)}</td>
                    <td class="text-end">₱ ${parseFloat(res.net_pay_second).toFixed(2)}</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning edit-payroll-btn">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                    </td>
                </tr>
            `);
        }

        // ============================
        // ✅ AUTO NEXT (ONLY INSERT)
        // ============================

        const $select = $('#employee_select');
        let currentIndex = $select.prop('selectedIndex');

        $select.find('option[value="'+res.employee_id+'"]').remove();

        const totalOptions = $select.find('option').length;

        if (currentIndex >= totalOptions) {
            currentIndex = totalOptions - 1;
        }

        $('#payrollForm')[0].reset();
        $('#loan-deductions-container').empty();

        if (totalOptions > 0 && currentIndex >= 0) {

            const nextName = $select.find('option').eq(currentIndex).text().trim();

            Swal.fire({
                icon: 'info',
                title: 'Next Employee',
                html: `<strong>${nextName}</strong>`,
                confirmButtonText: 'OK'
            }).then(() => {
                $select.prop('selectedIndex', currentIndex).trigger('change');
            });

        } else {
            $select.val('');
            Swal.fire('Done', 'All employees processed.', 'success');
        }

    }, 'json');
});

$(document).ready(function() {
    const period_id = $('#payroll_period_id').val();
    loadPayroll(period_id);
});

function loadPayroll(period_id) {
    $.post("<?= base_url('payroll/fetchPayrollByPeriod') ?>",
        { payroll_period_id: period_id },
        function(res) {

            let tbody = $('#savedPayrollTable tbody');
            tbody.empty();

            let totalGross = 0;
            let totalDeduction = 0;

            if (!res.data || !res.data.length) {
                tbody.append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No payroll records found
                        </td>
                    </tr>
                `);

                $('#total_gross_pay').text('₱0.00');
                $('#total_deduction').text('₱0.00');
                return;
            }

            res.data.forEach(row => {

                totalGross += parseFloat(row.gross_pay || 0);
                totalDeduction += parseFloat(row.total_deductions || 0);

                tbody.append(`
                    <tr data-payroll-id="${row.payroll_id}" data-employee-id="${row.employee_id}">
                        <td>${row.name}</td>
                        <td class="text-end">₱ ${parseFloat(row.basic_salary).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.gross_pay).toFixed(2)}</td>
                        <td class="text-end">
                            <div class="dropdown">

                                <!-- TOTAL (CLICKABLE) -->
                                <button
                                    class="btn btn-sm btn-link text-decoration-none fw-bold p-0"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    ₱ ${parseFloat(row.total_deductions).toFixed(2)}
                                    <i class="bi bi-caret-down-fill ms-1 small"></i>
                                </button>

                                <!-- DROPDOWN CONTENT -->
                                <ul class="dropdown-menu dropdown-menu-end p-2 shadow-sm" style="min-width: 260px">

                                    <li class="small">
                                        <strong>GSIS:</strong>
                                        <span class="float-end">₱ ${parseFloat(row.gsis).toFixed(2)}</span>
                                    </li>

                                    <li class="small">
                                        <strong>PhilHealth:</strong>
                                        <span class="float-end">₱ ${parseFloat(row.philhealth).toFixed(2)}</span>
                                    </li>

                                    <li class="small">
                                        <strong>Pag-IBIG:</strong>
                                        <span class="float-end">₱ ${parseFloat(row.pagibig).toFixed(2)}</span>
                                    </li>

                                    <li class="small">
                                        <strong>LWOP:</strong>
                                        <span class="float-end">₱ ${parseFloat(row.lwop_amount).toFixed(2)}</span>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li class="small fw-semibold text-muted">
                                        Other Deductions
                                    </li>

                                    <li class="small">
                                        ${formatOtherDeductions(row.other_deductions)}
                                    </li>

                                </ul>
                            </div>
                        </td>

                        <td class="text-end">₱ ${parseFloat(row.net_pay).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.net_pay_first).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.net_pay_second).toFixed(2)}</td>
                        <!-- EDIT BUTTON -->
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning edit-payroll-btn">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </td>
                    </tr>
                `);
            });

            $('#total_gross_pay').text(
                '₱' + totalGross.toLocaleString('en-PH', { minimumFractionDigits: 2 })
            );

            $('#total_deduction').text(
                '₱' + totalDeduction.toLocaleString('en-PH', { minimumFractionDigits: 2 })
            );
        },
        'json'
    );
}
function renderOtherDeductions(loans = []) {

    let container = $('#loan-deductions-container');
    container.empty();

    EMPLOYEE_LOANS = 0;

    if (!loans.length) {
        container.append(`
            <div class="col-12 text-muted small">
                No other deductions
            </div>
        `);
        computePayroll();
        return;
    }

    loans.forEach(loan => {

        let amt = parseFloat(loan.monthly_deduction) || 0;
        EMPLOYEE_LOANS += amt;

        container.append(`
            <div class="col-md-6">
                <label class="small fw-semibold">${loan.deduction_name}</label>

                <input type="hidden" name="deduction_id[]" value="${loan.deduction_id}">
                <input type="hidden" name="deduction_name[]" value="${loan.deduction_name}">

                <input type="text"
                       name="deduction_amount[]"
                       class="form-control text-end deduction-amount"
                       value="${amt.toFixed(2)}">
            </div>
        `);
    });
    computePayroll();
}

$(document).on('input', '.deduction-amount', function () {

    this.value = this.value
        .replace(/[^0-9.]/g, '')
        .replace(/(\..*)\./g, '$1');

    recomputeOtherDeductions();
});

function recomputeOtherDeductions() {

    let total = 0;

    $('.deduction-amount').each(function () {
        total += parseFloat($(this).val()) || 0;
    });

    // round to cents
    EMPLOYEE_LOANS = Math.round(total * 100) / 100;

    computePayroll();
}

$(document).on('blur', '.deduction-amount', function () {
    let val = parseFloat($(this).val()) || 0;
    $(this).val(val.toFixed(2));
});


document.getElementById('btnPrint').addEventListener('click', function () {
    const url = this.getAttribute('data-url');
    window.open(url, '_blank');
});

function loadEmployeePayrollHistory(employee_id) {

    $.post("<?= base_url('payroll/fetchPayrollByEmployee') ?>",
        { employee_id: employee_id },
        function (res) {

            let tbody = $('#savedPayrollTable tbody');
            tbody.empty();

            if (!res.data || !res.data.length) {
                tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No previous payroll records found
                        </td>
                    </tr>
                `);
                return;
            }

            res.data.forEach(row => {
                tbody.append(`
                    <tr class="table-secondary">
                        <td>${row.name}</td>
                        <td class="text-end">₱ ${parseFloat(row.basic_salary).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.gross_pay).toFixed(2)}</td>
                        <td class="text-end">
                            <div class="fw-bold">
                                ₱ ${parseFloat(row.total_deductions).toFixed(2)}
                            </div>

                            <div class="small text-muted">
                                GSIS: ₱ ${parseFloat(row.gsis).toFixed(2)}<br>
                                PhilHealth: ₱ ${parseFloat(row.philhealth).toFixed(2)}<br>
                                Pag-IBIG: ₱ ${parseFloat(row.pagibig).toFixed(2)}<br>
                                LWOP Amount: ₱ ${parseFloat(row.lwop_amount).toFixed(2)}<br>
                                <span class="fw-semibold">Other Deductions:</span><br>
                                ${formatOtherDeductions(row.other_deductions)}
                            </div>
                        </td>

                        <td class="text-end">₱ ${parseFloat(row.net_pay).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.net_pay_first).toFixed(2)}</td>
                        <td class="text-end">₱ ${parseFloat(row.net_pay_second).toFixed(2)}</td>
                    </tr>
                `);
            });
        },
        'json'
    );
}

function formatOtherDeductions(otherStr) {

    if (!otherStr) {
        return '<span class="text-muted">None</span>';
    }

    return otherStr
        .split(',')
        .map(item => {
            let [name, amount] = item.split(':');
            amount = parseFloat(amount || 0).toFixed(2);

            return `${name.trim()}: ₱ ${amount}`;
        })
        .join('<br>');
}

function generatePayslips(period_id) {
    window.open(
        "<?= base_url('payroll/payslips/') ?>" + period_id,
        "_blank"
    );
}

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


 var currentStatus = <?= (int)$status ?>;

$(document).ready(function () {

    const flow = [
        { status: 1, step: '#step-1' }, // HR
        { status: 2, step: '#step-2' }, // Admin
        { status: 3, step: '#step-3' }, // Budget
        { status: 4, step: '#step-4' }, // Accounting
        { status: 5, step: '#step-5' }, // VP
        { status: 6, step: '#step-6' }  // Cashier
    ];

    // reset tracker
    $('.tracker-step').removeClass('active current');

    let reachedCurrent = false;

    flow.forEach(item => {

        if (reachedCurrent) return;

        if (item.status === currentStatus) {

            $(item.step).addClass('current');
            reachedCurrent = true;

        } else {

            $(item.step).addClass('active');

        }

    });

});

$(document).ready(function () {

    const status = <?= (int)$status ?>;

    if (status >= 2) {
        $('#employee_select').prop('disabled', true);
        $('#payrollForm :input').prop('disabled', true);
        $('#payrollForm button[type="submit"]').hide();
    }

});

const status = <?= (int)$status ?>;

for (let i = 1; i <= 5; i++) {

    const step = document.getElementById("step-" + i);

    if (i < status) {
        step.classList.add("completed");
    } 
    else if (i === status) {
        step.classList.add("current");
    } 
    else {
        step.classList.add("pending");
    }

}
$(document).on('click', '.edit-payroll-btn', function () {
    const payrollId = $(this).closest('tr').data('payroll-id');
    console.log('Payroll ID being sent:', payrollId);

    $.post("<?= base_url('payroll/getPayrollByIdGP') ?>",
        { payroll_id: payrollId },
        function(res) {
            console.log('Full payroll data:', res);

            if (!res.status) {
                Swal.fire('Error', 'Payroll data not found', 'error');
                return;
            }

            // ======== Populate Form ========
            $('#payroll_id').val(res.payroll_id);
            $('#employee_id').val(res.employee_id);
             $('#employee_name').show().val(res.name);
             $('#employee_select').hide();


            // Earnings
            $('#basic_salary').val(res.basic_salary || 0);
            $('#lwop_days').val(res.lwop_days || 0);
            $('#salary_lwop').val(res.salary_lwop || 0);
            $('#pera').val(res.pera_lwop || 0); // or change id to 'pera_lwop'

            $('#lwop_amount').val(res.lwop_amount || 0);
            $('#gross_pay').val(res.gross_pay || 0);

            // Mandatory deductions
            $('#gsis').val(res.gsis || 0);
            $('#philhealth').val(res.philhealth || 0);
            $('#pagibig').val(res.pagibig || 0);
            $('#tax').val(res.tax || 0);

            // Other deductions
            renderOtherDeductions(res.other_deductions || []);

            // Payroll summary
            $('#total_deductions').val(res.total_deductions || 0);
            $('#net_pay').val(res.net_pay || 0);
            $('#net_pay_first').val(res.net_pay_first || 0);
            $('#net_pay_second').val(res.net_pay_second || 0);

            // Scroll to form
            $('html, body').animate({
                scrollTop: $('#payrollForm').offset().top
            }, 300);

        }, 'json'
    );
});

</script>


