<style>
    :root {
        --maroon: #6b0f1a;
        --success-soft: #ecfdf5;
        --danger-soft: #fef2f2;
    }

    /* Modern Stepper Logic */
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 2rem; position: relative; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; z-index: 2; }
    .stepper-item::before { content: ""; position: absolute; top: 20px; left: -50%; width: 100%; height: 2px; background: #e2e8f0; z-index: -1; }
    .stepper-item:first-child::before { content: none; }
    .step-counter { width: 40px; height: 40px; background: white; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; font-weight: 700; transition: 0.3s; }
    .stepper-item.completed .step-counter { background: #10b981; border-color: #10b981; color: white; }
    .stepper-item.current .step-counter { background: #f59e0b; border-color: #f59e0b; color: white; }
    .step-name { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; }

    /* Financial Input Styling */
    .ledger-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; display: block; }
    .amount-input-group { position: relative; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; transition: all 0.2s ease; display: flex; align-items: center; overflow: hidden; }
    .amount-input-group:focus-within { border-color: var(--maroon); box-shadow: 0 0 0 3px rgba(107, 15, 26, 0.1); }
    .currency-symbol { padding: 0 10px; font-weight: 800; color: #94a3b8; background: #f8fafc; border-right: 1px solid #e2e8f0; height: 100%; display: flex; align-items: center; font-size: 14px; }
    .money-field { border: none !important; background: transparent !important; font-family: 'JetBrains Mono', monospace; font-weight: 700 !important; font-size: 1.1rem !important; color: #1e293b; padding: 10px 12px !important; text-align: right !important; width: 100%; }
    .readonly-money { background: #f8fafc !important; color: #475569; }

    /* Section Headers */
    .section-title-custom { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px; border-radius: 6px; margin-bottom: 15px; display: inline-block; }
    .bg-earnings { background: var(--success-soft); color: #065f46; }
    .bg-deductions { background: var(--danger-soft); color: #991b1b; }
    .bg-summary { background: #eff6ff; color: #1e40af; }

    /* Net Pay Highlight */
    .net-pay-highlight { background: #ecfdf5; border: 2px solid #10b981; padding: 20px; border-radius: 12px; text-align: center; }
    .net-pay-amount { font-size: 2rem !important; color: #047857 !important; letter-spacing: -1px; }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark"><?=$payroll_type?> - <?=$unit?></h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body pt-5">
                <div class="stepper-wrapper">
                    <div class="stepper-item <?= ($status >= 1) ? 'completed' : 'current' ?>" id="step-1"><div class="step-counter">1</div><div class="step-name">HR Draft</div></div>
                    <div class="stepper-item <?= ($status >= 2) ? 'completed' : ($status == 1 ? '' : 'current') ?>" id="step-2"><div class="step-counter">2</div><div class="step-name">Admin</div></div>
                    <div class="stepper-item" id="step-3"><div class="step-counter">3</div><div class="step-name">Budget</div></div>
                    <div class="stepper-item" id="step-4"><div class="step-counter">4</div><div class="step-name">Accounting</div></div>
                    <div class="stepper-item" id="step-5"><div class="step-counter">5</div><div class="step-name">VP Approval</div></div>
                    <div class="stepper-item" id="step-6"><div class="step-counter">6</div><div class="step-name">Cashier</div></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 border-top border-primary border-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">Bonus Computation</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="ledger-label">Employee Selection</label>
                            <select id="employee_select" class="form-select border-2">
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employees as $row): ?>
                                    <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                        <option value="<?= $row->employee_id ?>">
                                            <?= htmlspecialchars($row->name . ' ' . $row->last_name) ?> (SG-<?= $row->sg ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <form id="payrollForm">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <input type="hidden" name="payroll_period_id" value="<?= $period_id ?>">

                            <div class="section-title-custom bg-earnings mb-3">Earnings</div>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <span class="ledger-label">Monthly Basic Salary</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="basic_salary" name="basic_salary" class="money-field" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span class="ledger-label">Gross Mid-Year Bonus</span>
                                    <div class="amount-input-group" style="border: 2px solid #3b82f6;">
                                        <span class="currency-symbol" style="background:#eff6ff; color:#3b82f6;">₱</span>
                                        <input type="text" id="gross_pay" name="gross_pay" class="money-field text-primary fw-bold" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title-custom bg-deductions mb-3">Less: Deductions</div>
                            <div class="row g-2 mb-4">
                                <div class="col-12">
                                    <span class="ledger-label">Withholding Tax</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="number" step="0.01" id="tax" name="tax" class="money-field" placeholder="0.00">
                                    </div>
                                </div>
                                <div id="loan-deductions-container" class="col-12">
                                    </div>
                            </div>

                            <div class="section-title-custom bg-summary mb-3">Summary</div>
                            <div class="net-pay-highlight mb-4">
                                <span class="ledger-label text-success">Net Bonus Amount</span>
                                <input type="text" id="net_pay" name="net_pay" class="money-field net-pay-amount fw-bold" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-lg fw-bold fs-5">
                                <i class="bi bi-shield-check me-2"></i> Save Bonus Entry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="col-12 mb-4">
                    <?php if($status >= 2 && $status < 7): ?>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-8 p-4" style="background: #f8faff; border-left: 5px solid #4f46e5;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="bi bi-cloud-check-fill text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">Submitted to Admin Office</h6>
                                            <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Document Verification Stage</small>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-4">
                                        This payroll batch has been officially timestamped and transmitted. The computation is currently <strong>locked</strong> to preserve data integrity during the review process.
                                    </p>

                                    <div class="d-flex gap-3">
                                        <div class="p-3 bg-white rounded-3 border flex-fill">
                                            <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 9px;">Tracking Token</label>
                                            <div class="h5 mb-0 fw-bold text-primary" style="font-family: 'JetBrains Mono', monospace;">
                                                <?= $token_id ?>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-white rounded-3 border flex-fill">
                                            <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 9px;">Current Status</label>
                                            <div class="h6 mb-0 fw-bold text-warning">
                                                <i class="bi bi-hourglass-split me-1"></i> PENDING APPROVAL
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center p-4" style="background: #ffffff; border-left: 1px dashed #e2e8f0;">
                                    <div class="text-center">
                                        <img src="<?= base_url($qr_code) ?>" 
                                            alt="Payroll Token QR" 
                                            class="img-fluid rounded-3 mb-2 border p-1 bg-white shadow-sm"
                                            style="width: 200px; height: 200px;">
                                        <div class="fw-bold text-dark" style="font-size: 10px; letter-spacing: 2px;">SCAN TO VERIFY</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif($status == 1): ?>
                        <div class="alert border-0 shadow-sm rounded-4 d-flex align-items-center p-3" style="background: #fffbeb; border-left: 5px solid #f59e0b !important;">
                            <div class="spinner-grow text-warning spinner-grow-sm me-3"></div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark small">DRAFT MODE: Currently encoding entries.</h6>
                                <p class="mb-0 x-small text-muted">Batch is open for modifications. <strong>Operations > Finalize & Submit</strong> to lock batch.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                            <div class="card-body py-4">
                                <small class="text-uppercase opacity-75 fw-bold x-small">Batch Total Gross</small>
                                <h3 class="mb-0 fw-bold" id="total_gross_pay">₱ 0.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                            <div class="card-body py-4">
                                <small class="text-uppercase opacity-75 fw-bold x-small">Batch Total Deductions</small>
                                <h3 class="mb-0 fw-bold" id="total_deduction">₱ 0.00</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Bonus Registry</h6>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border rounded-pill px-3 dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1 text-primary"></i> Operations
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                <li><a class="dropdown-item py-2" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf_mid/'.$period_id) ?>">
                                    <i class="bi bi-file-pdf me-2 text-danger"></i>Download Payroll PDF
                                </a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('payroll/export_transmittal_pdf_mid/'. $period_id) ?>">
                                    <i class="bi bi-send-check me-2 text-primary"></i>Download Transmittal
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#" onclick="generatePayslips(<?= $period_id ?>)">
                                    <i class="bi bi-receipt me-2 text-success"></i>Generate Payslips
                                </a></li>
                                <?php if($status == 1): ?>
                                <li><hr class="dropdown-divider"></li>

                    
                                <li><a class="dropdown-item py-2 text-primary fw-bold submit_payroll" href="#" data-period_id="<?= $period_id ?>" data-payroll_number="<?= $payroll_number ?>">
                                    <i class="bi bi-check-all me-2"></i>Finalize & Submit
                                </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="savedPayrollTable">
                                <thead class="table-light">
                                    <tr class="x-small text-muted text-uppercase">
                                        <th class="ps-4">Name</th>
                                        <th>Position</th>
                                        <th class="text-end">Basic</th>
                                        <th class="text-end">Bonus</th>
                                        <th class="text-end">Deductions</th>
                                        <th class="text-end pe-4">Net Pay</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
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

    // Safety function: returns "0" if the field is empty/undefined
    const getCleanVal = (id) => {
        let el = document.getElementById(id);
        return (el && el.value) ? el.value.replace(/,/g, '') : '0';
    };

    // Prevent submission if no employee selected
    if (!$('#employee_id').val()) {
        Swal.fire('Wait!', 'Please select an employee first.', 'warning');
        return;
    }

    // Update fields with "clean" numbers before sending to PHP
    $('#basic_salary').val(getCleanVal('basic_salary'));
    $('#gross_pay').val(getCleanVal('gross_pay'));
    $('#tax').val(getCleanVal('tax'));
    $('#total_deductions').val(getCleanVal('total_deductions'));
    $('#net_pay').val(getCleanVal('net_pay'));

   $.ajax({
        url: "<?= base_url('payroll/save_midyear_payroll') ?>",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res) {
            if (res.status === 'success') {
                const currentId = $('#employee_id').val();
                const $select = $('#employee_select');
                
                // 1. Find the NEXT option in the list after the current one
                const $currentOption = $select.find(`option[value="${currentId}"]`);
                const $nextOption = $currentOption.nextAll('option').filter(function() {
                    return $(this).val() !== "";
                }).first();

                // 2. Prepare the Alert message
                let alertTitle = 'Entry Saved!';
                let alertText = 'The payroll record has been added.';
                let confirmText = 'OK';

                if ($nextOption.length > 0) {
                    const nextName = $nextOption.text().trim();
                    alertText = `Payroll saved. Next up: <strong>${nextName}</strong>`;
                    confirmText = `Process ${nextName}`;
                } else {
                    alertText = 'All employees in this unit have been processed.';
                    confirmText = 'Finish Batch';
                }

                // 3. Display the Alert with the name and Button
                Swal.fire({
                    title: alertTitle,
                    html: alertText, // using 'html' allows the bold tags
                    icon: 'success',
                    confirmButtonColor: '#6b0f1a', // Using your maroon theme
                    confirmButtonText: confirmText,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 4. Remove processed employee
                        $currentOption.remove();
                        
                        // 5. Load the next one
                        resetPayrollForm();
                        autoSelectNext(); 
                        
                        // 6. Refresh summary table
                        loadSavedPayroll();
                    }
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }
    });
});
function autoSelectNext() {
    const $select = $('#employee_select');
    
    // Find the first valid option remaining
    const firstAvailable = $select.find('option').filter(function() {
        return $(this).val() !== "";
    }).first();

    if (firstAvailable.length > 0) {
        $select.val(firstAvailable.val()).trigger('change');
    } else {
        // Fallback if the list is empty
        $select.val("").trigger('change');
    }
}
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

$(document).ready(function() {

    // Handle Payroll PDF Download
    $('#btnPrint').on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        window.open(url, '_blank'); // Opens PDF in new tab
    });

    // Handle Payslip Generation via AJAX
    window.generatePayslips = function(period_id) {
        Swal.fire({
            title: 'Generate Payslips?',
            text: "This will prepare individual payslips for all processed employees.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Yes, Generate'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => { Swal.showLoading() }
                });

                $.post("<?= base_url('payroll/generate_payslips_mid/') ?>" + period_id, function(res) {
                    if(res.status === 'success') {
                        Swal.fire('Success!', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    };
});

</script>

