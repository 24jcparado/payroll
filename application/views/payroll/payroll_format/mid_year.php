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
                        <label class="form-label fw-bold">Select Employee</label>
                        <select id="employee_select" class="form-select" required>
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

                    </div>
                    <form id="payrollForm" class="container-fluid" style="font-size:14px;">
                        <input type="hidden" name="employee_id" id="employee_id">
                        <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">

                        <div class="row g-4">

                            <!-- ================= EARNINGS ================= -->
                            <div class="col-lg-12">

                                <div class="section-title section-earnings">
                                    Earnings
                                </div>

                                <div class="row g-3">

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Basic Monthly Salary</label>
                                        <input type="text" id="basic_salary" name="basic_salary" class="form-control payroll-input readonly-field" readonly>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Mid-Year Bonus</label>
                                        <input type="text"
                                            id="gross_pay"
                                            name="gross_pay"
                                            class="form-control payroll-input readonly-field fw-bold text-success"
                                            readonly>
                                    </div>

                                </div>
                            </div>


                            <!-- ================= Less ================= -->
                                <hr>
                            <div class="col-lg-12">

                                <div class="section-title section-deductions">
                                    Less: Deductions
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">Withholding Tax</label>
                                            <input type="number"
                                                step="0.01"
                                                id="tax"
                                                name="tax"
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
                                        <input type="text"
                                            id="total_deductions"
                                            name="total_deductions"
                                            class="form-control payroll-input readonly-field fw-bold"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-success">Net Pay</label>
                                        <input type="text"
                                            id="net_pay"
                                            name="net_pay"
                                            class="form-control payroll-input readonly-field fw-bold text-success"
                                            readonly>
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
                        <div class="card-body">

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

                        </div>
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
                                                <a class="dropdown-item"
                                                href="#"
                                                id="btnPrint"
                                                data-url="<?= base_url('payroll/general_payroll/'.$period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Print Payroll
                                                </a>
                                            </li>

                                            <!-- SUBMIT -->
                                           <?php if($status == 1): ?>
                                                <li>
                                                    <a class="dropdown-item submit_payroll"
                                                    href="#"
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
                    <table class="table table-xsm table-bordered" id="savedPayrollTable">
                        <thead class="table-light">
                            <tr>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>BASIC PAY</th>
                                <th>MIDYEAR BONUS</th>
                                <th>LESS</th>
                                <th>TAX</th>
                                <th>NET PAY</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php if($status >= 2): ?>
<script>
$(function(){
    $('#payrollForm :input').prop('disabled', true);
});
</script>
<?php endif; ?>

<script>

const MIDYEAR_MONTHS = 1; // midyear bonus equivalent
let EMPLOYEE_LOANS = [];


/* ===============================
    EMPLOYEE SELECTION
================================ */

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
        let basic = parseFloat(res.basic_salary) || 0;
        $('#basic_salary').val(basic.toFixed(2));
        EMPLOYEE_LOANS = res.loans || [];
        renderOtherDeductions(EMPLOYEE_LOANS);
        computePayroll();
    }, 'json');
    loadEmployeePayrollHistory(empId);
});


function loadEmployeePayrollHistory(employee_id) {

    $.post("<?= base_url('payroll/fetchPayrollByEmployeeMY') ?>",
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

                let loanTotal = parseFloat(row.loan_total || 0);
                let tax = parseFloat(row.tax || 0);

                tbody.append(`
                    <tr class="table-secondary">

                        <td>${row.name}</td>

                        <td>${row.position ?? ''}</td>

                        <td class="text-end">
                            ₱ ${parseFloat(row.basic_salary).toLocaleString('en-PH',{minimumFractionDigits:2})}
                        </td>

                        <td class="text-end fw-bold text-success">
                            ₱ ${parseFloat(row.gross_pay).toLocaleString('en-PH',{minimumFractionDigits:2})}
                        </td>

                        <td class="text-end">
                            <div class="fw-bold text-danger">
                                ₱ ${parseFloat(row.total_deductions).toLocaleString('en-PH',{minimumFractionDigits:2})}
                            </div>

                            <div class="small text-muted">
                                Tax: ₱ ${tax.toLocaleString('en-PH',{minimumFractionDigits:2})}<br>
                                Loans: ₱ ${loanTotal.toLocaleString('en-PH',{minimumFractionDigits:2})}
                            </div>
                        </td>

                        <td class="text-end fw-bold text-primary">
                            ₱ ${parseFloat(row.net_pay).toLocaleString('en-PH',{minimumFractionDigits:2})}
                        </td>

                    </tr>
                `);
            });

        },
        'json'
    );
}


/* ===============================
    RENDER LOANS
================================ */

function renderOtherDeductions(loans){
    const container = $('#loan-deductions-container');
    container.empty();

    if(!loans.length) return;

    loans.forEach(function(loan){
        let html = `
        <div class="col-md-6">
            <label class="form-label">${loan.deduction_name}</label>
            <input type="number" step="0.01" class="form-control payroll-input loan-input" data-deduction-id="${loan.deduction_id}" data-deduction-name="${loan.deduction_name}" name="loans[${loan.deduction_id}][amount]" value="${loan.amount}">
            <input type="hidden" name="loans[${loan.deduction_id}][name]" value="${loan.deduction_name}">
        </div>
        `;
        container.append(html);
    });
}


/* ===============================
    PAYROLL COMPUTATION
================================ */

function computePayroll() {
    let basic = parseFloat($('#basic_salary').val()) || 0;
    let midyear_bonus = basic * MIDYEAR_MONTHS;
    let tax = parseFloat($('#tax').val()) || 0;
    let loan_total = 0;
    $('.loan-input').each(function() {
        loan_total += parseFloat($(this).val()) || 0;
    });
    let total_deductions = round2(tax + loan_total);
    let net = round2(midyear_bonus - total_deductions);
    $('#gross_pay').val(midyear_bonus.toFixed(2));
    $('#total_deductions').val(total_deductions.toFixed(2));
    $('#net_pay').val(net.toFixed(2));
    $('#total_gross_pay').text("₱ " + formatMoney(midyear_bonus));
    $('#total_deduction').text("₱ " + formatMoney(total_deductions));
}


/* ===============================
    AUTO RECALCULATE
================================ */

$(document).on('keyup change', '#tax', function(){
    computePayroll();
});

$(document).on('keyup change', '.loan-input', function(){
    computePayroll();
});


/* ===============================
    SAVE PAYROLL
================================ */

$('#payrollForm').on('submit', function(e) {
    e.preventDefault();

    // Remove commas for numeric fields before sending
    $('#basic_salary').val($('#basic_salary').val().replace(/,/g,''));
    $('#gross_pay').val($('#gross_pay').val().replace(/,/g,''));
    $('#total_deductions').val($('#total_deductions').val().replace(/,/g,''));
    $('#net_pay').val($('#net_pay').val().replace(/,/g,''));

    let formData = $(this).serialize();

    $.post("<?= base_url('payroll/save_midyear_payroll') ?>", formData, function(res) {
        if (res.status === 'success') {

            // Refresh saved payroll if needed
            loadSavedPayroll();

            // Store current employee id before resetting form
            let currentEmpId = $('#employee_id').val();

            // Reset form fields (keep hidden payroll period)
            resetPayrollForm();

            // Remove saved employee from select
            $('#employee_select option[value="'+currentEmpId+'"]').remove();

            // Move to next employee
            selectNextEmployee();

        } else {
            console.error(res.message);
            alert('Failed to save payroll.');
        }
    }, 'json').fail(function() {
        alert('Server error. Could not save payroll.');
    });
});

// Helper: Reset payroll form
function resetPayrollForm() {
    $('#employee_id').val('');
    $('#basic_salary').val('');
    $('#gross_pay').val('');
    $('#tax').val('');
    $('#total_deductions').val('');
    $('#net_pay').val('');
    $('#loan-deductions-container').empty();
    EMPLOYEE_LOANS = [];
}

// Helper: Select next employee and trigger change
function selectNextEmployee() {
    const $select = $('#employee_select');
    let currentIndex = $select.prop('selectedIndex');
    const total = $select.find('option').length;

    let nextIndex = currentIndex;

    while (++nextIndex < total) {
        let option = $select.find('option').eq(nextIndex);
        if (option.val() !== "") {
            $select.prop('selectedIndex', nextIndex).trigger('change');
            return;
        }
    }

    // No more employees
    alert('All employees have been processed.');
    $select.prop('selectedIndex', 0);
}


/* ===============================
    LOAD SAVED PAYROLL TABLE
================================ */

function loadSavedPayroll(){

    $.get("<?= base_url('payroll/get_saved_midyear/'.$period_id) ?>",
    function(res){

        const tbody = $('#savedPayrollTable tbody');
        const thead = $('#savedPayrollTable thead');
        const table = $('#savedPayrollTable');

        tbody.empty();
        table.find('tfoot').remove();

        if(!res.length){
            tbody.append(`
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No records found
                    </td>
                </tr>
            `);
            return;
        }

        let gross_total = 0;
        let tax_total = 0;
        let net_total = 0;

        let deductionNames = [];
        let deductionTotals = {};

        // Collect deduction names
        res.forEach(function(row){
            if(row.less){
                row.less.split(',').forEach(function(item){
                    let name = item.split(':')[0].trim();
                    if(!deductionNames.includes(name)){
                        deductionNames.push(name);
                        deductionTotals[name] = 0;
                    }
                });
            }
        });

        // Build header
        let header1 = `
        <tr>
            <th rowspan="2">NAME</th>
            <th rowspan="2">POSITION</th>
            <th rowspan="2">BASIC PAY</th>
            <th rowspan="2">MIDYEAR BONUS</th>
            <th colspan="${deductionNames.length}">LESS</th>
            <th rowspan="2">TAX</th>
            <th rowspan="2">NET PAY</th>
        </tr>`;

        let header2 = `<tr>`;
        deductionNames.forEach(function(name){
            header2 += `<th>${name}</th>`;
        });
        header2 += `</tr>`;

        thead.html(header1 + header2);

        // Render rows
        res.forEach(function(row){

            let gross = parseFloat(row.gross_pay) || 0;
            let tax = parseFloat(row.tax) || 0;
            let net = parseFloat(row.net_pay) || 0;

            gross_total += gross;
            tax_total += tax;
            net_total += net;

            let lessMap = {};

            if(row.less){
                row.less.split(',').forEach(function(item){
                    let parts = item.split(':');
                    let name = parts[0].trim();
                    let amount = parseFloat(parts[1]) || 0;

                    lessMap[name] = amount;
                    deductionTotals[name] += amount;
                });
            }

            let tr = `
            <tr>
                <td>${row.name}</td>
                <td>${row.position}</td>
                <td class="text-end">₱ ${formatMoney(row.basic_salary)}</td>
                <td class="text-end">₱ ${formatMoney(gross)}</td>
            `;

            deductionNames.forEach(function(name){
                let val = lessMap[name] ? formatMoney(lessMap[name]) : '';
                tr += `<td class="text-end">${val}</td>`;
            });

            tr += `
                <td class="text-end">₱ ${formatMoney(tax)}</td>
                <td class="text-end fw-bold">₱ ${formatMoney(net)}</td>
            </tr>
            `;

            tbody.append(tr);

        });

        // Build TOTAL row
        let totalRow = `
        <tfoot>
        <tr class="fw-bold table-light">
            <td colspan="3" class="text-end">TOTAL</td>
            <td class="text-end">₱ ${formatMoney(gross_total)}</td>
        `;

        deductionNames.forEach(function(name){
            totalRow += `<td class="text-end">₱ ${formatMoney(deductionTotals[name])}</td>`;
        });

        totalRow += `
            <td class="text-end">₱ ${formatMoney(tax_total)}</td>
            <td class="text-end">₱ ${formatMoney(net_total)}</td>
        </tr>
        </tfoot>
        `;

        table.append(totalRow);

    },'json');
}


/* ===============================
    UTILITIES
================================ */

function round2(num){
    return Math.round((num + Number.EPSILON) * 100) / 100;
}

function formatMoney(num){

    num = parseFloat(num) || 0;

    return num.toLocaleString('en-PH',{
        minimumFractionDigits:2,
        maximumFractionDigits:2
    });
}

$(document).ready(function(){
    loadSavedPayroll();
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

    const status = <?= (int)$status ?>;

    if (status >= 2) {
        $('#employee_select').prop('disabled', true);
        $('#payrollForm :input').prop('disabled', true);
        $('#payrollForm button[type="submit"]').hide();
    }

});

</script>

