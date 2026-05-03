<style>
    :root {
        --maroon: #6b0f1a;
        --success-soft: #ecfdf5;
        --danger-soft: #fef2f2;
    }
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 2rem; position: relative; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; z-index: 2; }
    .stepper-item::before { content: ""; position: absolute; top: 20px; left: -50%; width: 100%; height: 2px; background: #e2e8f0; z-index: -1; }
    .stepper-item:first-child::before { content: none; }
    .step-counter { width: 40px; height: 40px; background: white; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; font-weight: 700; transition: 0.3s; }
    .stepper-item.completed .step-counter { background: #10b981; border-color: #10b981; color: white; }
    .stepper-item.current .step-counter { background: #f59e0b; border-color: #f59e0b; color: white; }
    .step-name { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; }

    .ledger-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; display: block; }
    .amount-input-group { position: relative; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; transition: all 0.2s ease; display: flex; align-items: center; overflow: hidden; }
    .amount-input-group:focus-within { border-color: var(--maroon); box-shadow: 0 0 0 3px rgba(107, 15, 26, 0.1); }
    .currency-symbol { padding: 0 10px; font-weight: 800; color: #94a3b8; background: #f8fafc; border-right: 1px solid #e2e8f0; height: 100%; display: flex; align-items: center; font-size: 14px; }
    .money-field { border: none !important; background: transparent !important; font-family: 'JetBrains Mono', monospace; font-weight: 700 !important; font-size: 1.1rem !important; color: #1e293b; padding: 10px 12px !important; text-align: right !important; width: 100%; }
    .readonly-money { background: #f8fafc !important; color: #475569; }

    .section-title-custom { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px; border-radius: 6px; margin-bottom: 15px; display: inline-block; }
    .bg-earnings { background: var(--success-soft); color: #065f46; }
    .bg-deductions { background: var(--danger-soft); color: #991b1b; }
    .bg-summary { background: #eff6ff; color: #1e40af; }
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
                        <h6 class="mb-0 fw-bold">Entry Computation</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="ledger-label">Employee Selection</label>
                            <?php 
                                usort($employees, function($a, $b) {
                                    $lastNameComparison = strcasecmp(trim($a->last_name), trim($b->last_name));
                                    if ($lastNameComparison === 0) {
                                        return strcasecmp(trim($a->name), trim($b->name));
                                    }
                                    return $lastNameComparison;
                                });
                            ?>
                            <select id="employee_select" class="form-select border-2">
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employees as $row): ?>
                                    <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                        <option value="<?= $row->employee_id ?>">
                                            <?php 
                                                $lastName = trim($row->last_name);
                                                $firstName = trim($row->name); 
                                                $middleName = !empty($row->middle_name) ? ' ' . trim($row->middle_name) : '';
                                                $extension = !empty($row->ext) ? ' ' . trim($row->ext) : '';
                                                $fullName = $lastName . ', ' . $firstName . $middleName . $extension;
                                            ?>
                                            <?= htmlspecialchars($fullName) ?> (SG-<?= htmlspecialchars($row->sg) ?> STEP-<?= htmlspecialchars($row->step) ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <form id="payrollForm">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <input type="hidden" name="payroll_period_id" value="<?= $period_id ?>">

                            <div class="section-title-custom bg-earnings mb-3">Allowance Computation</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4 col-6">
                                    <span class="ledger-label">Days</span>
                                    <div class="amount-input-group">
                                        <input type="number" step="0.5" id="days_present" name="days_present" class="money-field text-center px-1" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <span class="ledger-label">Rate</span>
                                    <div class="amount-input-group readonly-money">
                                        <input type="text" id="daily_rate" name="daily_rate" class="money-field text-center px-1" value="50.00" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <span class="ledger-label text-primary">Base Allow.</span>
                                    <div class="amount-input-group readonly-money" style="border: 1px solid #3b82f6;">
                                        <span class="currency-symbol" style="background:#eff6ff;">₱</span>
                                        <input type="text" id="base_allowance" name="base_allowance" class="money-field text-primary" value="0.00" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span class="ledger-label">Laundry Allowance</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="number" step="0.01" id="laundry_allowance" name="laundry_allowance" class="money-field" value="150.00" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span class="ledger-label">Total Gross Allowance</span>
                                    <div class="amount-input-group" style="border: 2px solid #10b981;">
                                        <span class="currency-symbol" style="background:#ecfdf5; color:#10b981;">₱</span>
                                        <input type="text" id="gross_pay" name="gross_pay" class="money-field text-success fw-bold" readonly>
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
                                <div id="loan-deductions-container" class="col-12"></div>
                            </div>

                            <div class="section-title-custom bg-summary mb-3">Summary</div>
                            <div class="net-pay-highlight mb-4">
                                <span class="ledger-label text-success">Net Amount Due</span>
                                <input type="text" id="net_pay" name="net_pay" class="money-field net-pay-amount fw-bold" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-lg fw-bold fs-5">
                                <i class="bi bi-shield-check me-2"></i> Save Entry
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
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3"><i class="bi bi-cloud-check-fill text-primary fs-4"></i></div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">Submitted to Admin Office</h6>
                                            <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Document Verification Stage</small>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-4">This payroll batch has been officially timestamped and transmitted. The computation is currently <strong>locked</strong>.</p>
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
                        <h6 class="mb-0 fw-bold">Batch Registry</h6>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border rounded-pill px-3 dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1 text-primary"></i> Operations
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                <li><a class="dropdown-item py-2" href="#" id="btnPrintSubsistence" data-url="<?= base_url('payroll/export_pdf_subsistence/'.$period_id) ?>"><i class="bi bi-file-pdf me-2 text-danger"></i>Download PDF</a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('payroll/export_transmittal_pdf/'. $period_id) ?>"><i class="bi bi-send-check me-2 text-primary"></i>Download Transmittal</a></li>
                                <li><a class="dropdown-item py-2" href="#" onclick="generatePayslips(<?= $period_id ?>)"><i class="bi bi-receipt me-2 text-success"></i>Generate Payslips</a></li>
                                <?php if($status == 1): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-primary fw-bold submit_payroll" href="#" data-period_id="<?= $period_id ?>" data-payroll_number="<?= $payroll_number ?>"><i class="bi bi-check-all me-2"></i>Finalize & Submit</a></li>
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
                                        <th class="text-center">Days</th>
                                        <th class="text-end">Base Allow</th>
                                        <th class="text-end">Laundry</th>
                                        <th class="text-end">Gross Allow</th>
                                        <th class="text-center">Deductions</th>
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
<script>$(function(){ $('#payrollForm :input').prop('disabled', true); });</script>
<?php endif; ?>

<script>
let EMPLOYEE_LOANS = [];

/* ===============================
    EMPLOYEE SELECTION & LOAD
================================ */
$('#employee_select').on('change', function () {
    const empId = $(this).val();
    if (!empId) return;
    const payrollType = "<?= $payroll_type ?>"; 

    $.post("<?= base_url('payroll/ajax_get_salary') ?>", { employee_id: empId, payroll_type: payrollType },
    function (res) {
        $('#employee_id').val(res.employee_id);
        EMPLOYEE_LOANS = res.loans || [];
        renderOtherDeductions(EMPLOYEE_LOANS);
        computePayroll();
    }, 'json');
});

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
        </div>`;
        container.append(html);
    });
}

/* ===============================
    ALLOWANCE COMPUTATION
================================ */
function computePayroll() {
    let days = parseFloat($('#days_present').val()) || 0;
    let daily_rate = parseFloat($('#daily_rate').val()) || 50;
    let laundry = parseFloat($('#laundry_allowance').val()) || 0;
    
    let base_allowance = (days * daily_rate);
    $('#base_allowance').val(base_allowance.toFixed(2));
    
    let gross_allowance = base_allowance + laundry;
    
    let tax = parseFloat($('#tax').val()) || 0;
    let loan_total = 0;
    $('.loan-input').each(function() { loan_total += parseFloat($(this).val()) || 0; });
    
    let total_deductions = round2(tax + loan_total);
    let net = round2(gross_allowance - total_deductions);
    
    $('#gross_pay').val(gross_allowance.toFixed(2));
    $('#total_deductions').val(total_deductions.toFixed(2));
    $('#net_pay').val(net.toFixed(2));
    
    $('#total_gross_pay').text("₱ " + formatMoney(gross_allowance));
    $('#total_deduction').text("₱ " + formatMoney(total_deductions));
}

$(document).on('keyup change', '#days_present, #laundry_allowance, #tax, .loan-input', function(){
    computePayroll();
});

/* ===============================
    SAVE ENTRY (AJAX)
================================ */
$('#payrollForm').on('submit', function(e) {
    e.preventDefault();

    let selectedEmpId = $('#employee_select').val();

    if (!selectedEmpId) {
        Swal.fire('Wait!', 'Please select an employee first.', 'warning');
        return;
    }

    $('#employee_id').val(selectedEmpId);

    const getCleanVal = (id) => {
        let el = document.getElementById(id);
        return (el && el.value) ? el.value.replace(/,/g, '') : '0';
    };

    $('#base_allowance').val(getCleanVal('base_allowance'));
    $('#gross_pay').val(getCleanVal('gross_pay'));
    $('#tax').val(getCleanVal('tax'));
    $('#net_pay').val(getCleanVal('net_pay'));
    $('#days_present').val(getCleanVal('days_present'));
    $('#laundry_allowance').val(getCleanVal('laundry_allowance'));

    $.ajax({
        url: "<?= base_url('payroll/save_subsistence_allowance') ?>",
        type: "POST",
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        data: $(this).serialize(),
        dataType: "json",
        success: function(res) {
            if (res.status === 'success') {
                const currentId = $('#employee_id').val();
                const $select = $('#employee_select');
                const $currentOption = $select.find(`option[value="${currentId}"]`);

                let alertText = 'The record has been added.';
                let confirmText = 'OK';


                Swal.fire({
                    title: 'Entry Saved!', html: alertText, icon: 'success', confirmButtonColor: '#6b0f1a', confirmButtonText: confirmText, allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $currentOption.remove();
                        resetPayrollForm(); 
                        loadSavedPayroll();
                    }
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function(xhr) { 
            console.error(xhr.responseText); 
            Swal.fire('Server Error', 'Failed to connect. Check Network tab.', 'error'); 
        }
    });
});

function resetPayrollForm() {
    $('#employee_id').val('');
    $('#days_present').val('');
    $('#base_allowance').val('0.00');
    $('#laundry_allowance').val('150.00');
    $('#gross_pay').val('');
    $('#tax').val('');
    $('#net_pay').val('');
    $('#loan-deductions-container').empty();
    EMPLOYEE_LOANS = [];
}

/* ===============================
    LOAD BATCH TABLE
================================ */
function loadSavedPayroll(){
    $.get("<?= base_url('payroll/get_saved_subsistence/'.$period_id) ?>", function(res){
        const tbody = $('#savedPayrollTable tbody');
        const thead = $('#savedPayrollTable thead');
        const table = $('#savedPayrollTable');

        tbody.empty();
        table.find('tfoot').remove();

        if(!res.length){
            tbody.append(`<tr><td colspan="9" class="text-center text-muted">No records found</td></tr>`);
            return;
        }

        let laundry_total = 0; let base_total = 0; let gross_total = 0; let tax_total = 0; let net_total = 0;
        let deductionNames = []; let deductionTotals = {};

        res.forEach(function(row){
            if(row.less){
                row.less.split(',').forEach(function(item){
                    let name = item.split(':')[0].trim();
                    if(!deductionNames.includes(name)){ deductionNames.push(name); deductionTotals[name] = 0; }
                });
            }
        });

        // Updated Header with Days and Laundry
        let header1 = `<tr>
            <th rowspan="2" class="ps-4">NAME</th>
            <th rowspan="2">POSITION</th>
            <th rowspan="2" class="text-center">DAYS</th>
            <th rowspan="2" class="text-end">BASE ALLOW</th>
            <th rowspan="2" class="text-end">LAUNDRY</th>
            <th rowspan="2" class="text-end">GROSS</th>
            <th colspan="${deductionNames.length == 0 ? 1 : deductionNames.length}" class="text-center">LESS</th>
            <th rowspan="2" class="text-end">TAX</th>
            <th rowspan="2" class="text-end pe-4">NET PAY</th>
        </tr>`;
        
        let header2 = `<tr>`; 
        if(deductionNames.length == 0) { header2 += `<th class="text-center">-</th>`; }
        deductionNames.forEach(function(name){ header2 += `<th class="text-end">${name}</th>`; }); 
        header2 += `</tr>`;
        thead.html(header1 + header2);

        // Populate Rows
        res.forEach(function(row){
            let days = parseFloat(row.days_present) || 0;
            let laundry = parseFloat(row.laundry_allowance) || 0;
            let base = parseFloat(row.base_allowance) || 0;
            let gross = parseFloat(row.gross_pay) || 0;
            let tax = parseFloat(row.tax) || 0;
            let net = parseFloat(row.net_pay) || 0;

            laundry_total += laundry; base_total += base; gross_total += gross; tax_total += tax; net_total += net;

            let lessMap = {};
            if(row.less){
                row.less.split(',').forEach(function(item){
                    let parts = item.split(':');
                    lessMap[parts[0].trim()] = parseFloat(parts[1]) || 0;
                    deductionTotals[parts[0].trim()] += parseFloat(parts[1]) || 0;
                });
            }

            let tr = `<tr>
                <td class="ps-4 fw-bold">${row.name}</td>
                <td>${row.position}</td>
                <td class="text-center">${days}</td>
                <td class="text-end text-primary fw-bold">₱ ${formatMoney(base)}</td>
                <td class="text-end">₱ ${formatMoney(laundry)}</td>
                <td class="text-end text-success fw-bold">₱ ${formatMoney(gross)}</td>`;
            
            if(deductionNames.length == 0) { tr += `<td class="text-center">-</td>`; }
            deductionNames.forEach(function(name){ tr += `<td class="text-end">${lessMap[name] ? formatMoney(lessMap[name]) : ''}</td>`; });
            
            tr += `<td class="text-end">₱ ${formatMoney(tax)}</td>
                <td class="text-end pe-4 fw-bold">₱ ${formatMoney(net)}</td>
            </tr>`;
            tbody.append(tr);
        });

        // Footer Totals
        let totalRow = `<tfoot><tr class="fw-bold table-light">
            <td colspan="2" class="text-end">TOTAL</td>
            <td class="text-center">-</td>
            <td class="text-end text-primary">₱ ${formatMoney(base_total)}</td>
            <td class="text-end">₱ ${formatMoney(laundry_total)}</td>
            <td class="text-end text-success">₱ ${formatMoney(gross_total)}</td>`;
            
        if(deductionNames.length == 0) { totalRow += `<td class="text-center">-</td>`; }
        deductionNames.forEach(function(name){ totalRow += `<td class="text-end">₱ ${formatMoney(deductionTotals[name])}</td>`; });
        
        totalRow += `<td class="text-end">₱ ${formatMoney(tax_total)}</td>
            <td class="text-end pe-4">₱ ${formatMoney(net_total)}</td>
        </tr></tfoot>`;
        table.append(totalRow);
    },'json');
}

function round2(num){ return Math.round((num + Number.EPSILON) * 100) / 100; }
function formatMoney(num){ return (parseFloat(num) || 0).toLocaleString('en-PH',{ minimumFractionDigits:2, maximumFractionDigits:2 }); }

$(document).ready(function(){
    loadSavedPayroll();
    $('#employee_select').select2({ placeholder: "-- Select Employee --", allowClear: true });
});


$(document).ready(function() {
    $('#btnPrintSubsistence').on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        window.open(url, '_blank'); // Opens PDF in new tab
    });
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

                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => { Swal.showLoading() }
                });
                $.post("<?= base_url('payroll/process_payslips/') ?>" + period_id, function(res) {
                    if(res.status === 'success') {
                        Swal.fire('Success!', res.message, 'success').then(() => {
                            window.open("<?= base_url('payroll/view_payslips/') ?>" + period_id, '_blank');
                        });

                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    };
});

</script>