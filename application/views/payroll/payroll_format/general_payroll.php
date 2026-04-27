<style>
    :root {
        --maroon: #6b0f1a;
        --success-soft: #ecfdf5;
        --danger-soft: #fef2f2;
    }

    /* Modern Stepper */
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 2rem; position: relative; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; z-index: 2; }
    .stepper-item::before { content: ""; position: absolute; top: 20px; left: -50%; width: 100%; height: 2px; background: #e2e8f0; z-index: -1; }
    .stepper-item:first-child::before { content: none; }
    .step-counter { width: 40px; height: 40px; background: white; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; font-weight: 700; transition: 0.3s; }
    .stepper-item.completed .step-counter { background: #10b981; border-color: #10b981; color: white; }
    .stepper-item.current .step-counter { background: #f59e0b; border-color: #f59e0b; color: white; }
    .step-name { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; }

    .payroll-input { border-radius: 8px; font-weight: 600; text-align: right; }
    .readonly-field { background-color: #f8fafc !important; border-style: dashed; font-weight: 600; }
    
    .section-title-custom {
        font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        padding: 8px 12px; border-radius: 6px; margin-bottom: 15px; display: inline-block;
    }
    .bg-earnings { background: var(--success-soft); color: #065f46; }
    .bg-deductions { background: var(--danger-soft); color: #991b1b; }
    .bg-loans { background: #fff7ed; color: #9a3412; }
    .bg-summary { background: #eff6ff; color: #1e40af; }

    /* Financial Emphasis Styling */
    .ledger-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }

    .amount-input-group {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .amount-input-group:focus-within {
        border-color: var(--maroon);
        box-shadow: 0 0 0 3px rgba(107, 15, 26, 0.1);
    }

    .currency-symbol {
        padding: 0 10px;
        font-weight: 800;
        color: #94a3b8;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        height: 100%;
        display: flex;
        align-items: center;
        font-size: 14px;
    }

    .money-field {
        border: none !important;
        background: transparent !important;
        font-family: 'JetBrains Mono', 'Monaco', monospace; /* Monospaced fonts align numbers better */
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        color: #1e293b;
        padding: 10px 12px !important;
        text-align: right !important;
        width: 100%;
    }

    .readonly-money {
        background: #f1f5f9 !important;
        color: #475569;
    }

    /* Summary Highlighting */
    .summary-card-total {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px;
        border: 2px solid #e2e8f0;
    }

    .net-pay-highlight {
        background: #ecfdf5;
        border: 2px solid #10b981;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .net-pay-amount {
        font-size: 2rem !important;
        color: #047857 !important;
        letter-spacing: -1px;
    }

    .quincena-box {
        background: #eff6ff;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        border: 1px solid #dbeafe;
    }

    .quincena-amount {
        font-size: 1.25rem !important;
        color: #1d4ed8 !important;
    }

    .lwop-badge {
        font-size: 10px;
        background: #fff1f2;
        color: #e11d48;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 5px;
    }
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
                    <div class="stepper-item" id="step-1"><div class="step-counter">1</div><div class="step-name">HR Draft</div></div>
                    <div class="stepper-item" id="step-2"><div class="step-counter">2</div><div class="step-name">Admin</div></div>
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
                        <h6 class="mb-0 fw-bold">Payroll Computation</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Employee</label>
                            <select id="employee_select" class="form-select border-2">
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employees as $row): ?>
                                    <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                        <option value="<?= $row->employee_id ?>">
                                            <?= htmlspecialchars($row->name . ' ' . $row->last_name) ?>
                                            (SG-<?= $row->sg ?> STEP-<?= $row->step ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" id="employee_name" class="form-control readonly-field" readonly style="display:none;">
                        </div>
                        <form id="payrollForm">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">
                            <input type="hidden" name="payroll_id" id="payroll_id">

                            <div class="section-title-custom bg-earnings mb-3">Earnings & Gross</div>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <span class="ledger-label">Monthly Basic Salary</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="basic_salary" name="basic_salary" class="money-field readonly-field" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <span class="ledger-label">LWOP <span class="lwop-badge">Days</span></span>
                                    <input type="number" step="0.001" id="lwop_days" name="lwop_days" class="form-control form-control-lg fw-bold text-end border-2" placeholder="0.000">
                                </div>
                                
                                <div class="col-6">
                                    <span class="ledger-label">Salary after LWOP</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="salary_lwop" name="salary_lwop" class="money-field readonly-field" readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <span class="ledger-label">PERA (Adjusted for LWOP)</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="pera" name="pera" class="money-field readonly-field" readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <span class="ledger-label text-primary">Total Gross Pay</span>
                                    <div class="amount-input-group" style="border: 2px solid #3b82f6;">
                                        <span class="currency-symbol" style="background:#eff6ff; color:#3b82f6;">₱</span>
                                        <input type="text" id="gross_pay" name="gross_pay" class="money-field text-primary fw-bold" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title-custom bg-deductions mb-3">Deductions</div>
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <span class="ledger-label">GSIS (9%)</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="gsis" name="gsis" class="money-field readonly-field" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">PhilHealth</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="philhealth" name="philhealth" class="money-field readonly-field" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">Pag-IBIG</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="pagibig" name="pagibig" class="money-field">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">Withholding Tax</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="tax" name="tax" class="money-field">
                                    </div>
                                </div>
                            </div>

                            <div class="section-title-custom bg-loans mb-3">Loans & Variations</div>
                            <div id="loan-deductions-container" class="row g-2 mb-4">
                                </div>

                            <div class="section-title-custom bg-summary mb-3">Payroll Summary</div>
                            <div class="summary-card-total mb-4 shadow-sm">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <span class="ledger-label text-danger">Total Deductions</span>
                                        <div class="amount-input-group mb-3">
                                            <span class="currency-symbol" style="background:#fef2f2; color:#ef4444;">₱</span>
                                            <input type="text" id="total_deductions" name="total_deductions" class="money-field text-danger fw-bold" readonly>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="net-pay-highlight">
                                            <span class="ledger-label text-success">Net Take Home Pay</span>
                                            <input type="text" id="net_pay" name="net_pay" class="money-field net-pay-amount fw-bold" readonly>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="quincena-box">
                                            <span class="ledger-label text-primary">1st Quincena</span>
                                            <input type="text" id="net_pay_first" name="net_pay_first" class="money-field quincena-amount fw-bold" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="quincena-box">
                                            <span class="ledger-label text-primary">2nd Quincena</span>
                                            <input type="text" id="net_pay_second" name="net_pay_second" class="money-field quincena-amount fw-bold" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-lg fw-bold fs-5">
                                <i class="bi bi-shield-check me-2"></i> Confirm & Save Entry
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
                                <small class="text-uppercase opacity-75 fw-bold x-small">Period Gross Total</small>
                                <h3 class="mb-0 fw-bold" id="total_gross_pay">₱0.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                            <div class="card-body py-4">
                                <small class="text-uppercase opacity-75 fw-bold x-small">Period Deductions</small>
                                <h3 class="mb-0 fw-bold" id="total_deduction">₱0.00</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Processed Members (<?= $unit ?>)</h6>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border rounded-pill px-3 dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1 text-primary"></i> Operations
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                <li><a class="dropdown-item py-2" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf/'.$period_id) ?>">
                                    <i class="bi bi-file-pdf me-2 text-danger"></i>Download Payroll PDF
                                </a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('payroll/export_transmittal_pdf/'. $period_id) ?>">
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
                            <table class="table table-hover align-middle mb-0" id="savedPayrollTable" style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-light text-center border-bottom">
                                        <th colspan="1" class="border-end">Employee Info</th>
                                        <th colspan="2" class="border-end bg-earnings bg-opacity-10 text-dark">Earnings</th>
                                        <th colspan="5" class="border-end bg-deductions bg-opacity-10 text-danger">Mandatory Deductions</th>
                                        <th colspan="1" class="border-end bg-loans bg-opacity-10 text-warning">Variations</th>
                                        <th colspan="1" class="bg-summary bg-opacity-10 text-success">Disbursement</th>
                                    </tr>
                                    <tr class="x-small text-muted text-uppercase">
                                        <th class="ps-4 border-end">Name</th>
                                        <th class="text-end">Basic</th>
                                        <th class="text-end border-end">Gross</th>
                                        <th class="text-end">GSIS</th>
                                        <th class="text-end">PhilH</th>
                                        <th class="text-end">PagIBIG</th>
                                        <th class="text-end">Tax</th>
                                        <th class="text-end border-end text-danger">LWOP</th>
                                        <th class="text-end border-end">Other Loans</th>
                                        <th class="text-end pe-4 fw-bold text-success">Net Pay</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tbody>
                            </table>
                        </div>
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

$(document).ready(function() {
    // 1. Initialize Stepper
    const status = <?= (int)$status ?>;
    $('.stepper-item').each(function(index) {
        const stepNum = index + 1;
        if (stepNum < status) $(this).addClass('completed');
        else if (stepNum === status) $(this).addClass('current');
    });

    // 2. Load Table
    loadPayroll(<?= $period_id ?>);

    // 3. Form Submission RESTORED
    $('#payrollForm').on('submit', function(e){
        e.preventDefault();
        const payrollId = $('#payroll_id').val();
        const isEdit = payrollId && payrollId !== '';
        const url = isEdit ? "<?= base_url('payroll/updatePayroll') ?>" : "<?= base_url('payroll/insertPayroll') ?>";

        $.post(url, $(this).serialize(), function(res){
            if (!res.status) {
                Swal.fire('Error', res.message, 'error');
                return;
            }

            if (isEdit) {
                Swal.fire('Updated', 'Payroll updated successfully', 'success').then(() => {
                    location.reload(); 
                });
            } else {
                // RESTORED: Auto-Next Logic
                const $select = $('#employee_select');
                let currentIndex = $select.prop('selectedIndex');
                $select.find('option[value="'+res.employee_id+'"]').remove();
                
                const totalOptions = $select.find('option').length;
                if (currentIndex >= totalOptions) currentIndex = totalOptions - 1;

                $('#payrollForm')[0].reset();
                $('#loan-deductions-container').empty();
                $('#payroll_id').val('');

                if (totalOptions > 1) {
                    const nextName = $select.find('option').eq(currentIndex).text().trim();
                    Swal.fire({
                        icon: 'success', title: 'Saved!', text: 'Next: ' + nextName, timer: 1000, showConfirmButton: false
                    }).then(() => {
                        $select.prop('selectedIndex', currentIndex).trigger('change');
                        loadPayroll(<?= $period_id ?>);
                    });
                } else {
                    location.reload();
                }
            }
        }, 'json');
    });

    // Lock Form if submitted
    if (status >= 2) {
        $('#payrollForm :input').prop('disabled', true);
        $('#employee_select').prop('disabled', true);
        $('button[type="submit"]').hide();
    }
});

// COMPUTE LOGIC RESTORED
function computePayroll() {
    let basic   = round2(parseFloat($('#basic_salary').val()) || 0);
    let lwop    = round2(parseFloat($('#lwop_days').val()) || 0);
    let pagibig = round2(parseFloat($('#pagibig').val()) || 0);
    let tax     = round2(parseFloat($('#tax').val()) || 0);

    let lwopDeduction = round2((basic / WORK_DAYS) * lwop);
    let peraDeduction = round2((PERA / WORK_DAYS) * lwop);

    let calPera     = round2(PERA - peraDeduction);
    let salary_lwop = round2(basic - lwopDeduction);
    let lwop_amount = round2(lwopDeduction + peraDeduction);

    let gsis = round2(basic * GSIS_RATE);
    let phil = basic <= 99999 ? round2(basic * PHIL_RATE) : 2500.00;

    let gross = round2(salary_lwop + calPera);
    let total = round2(gsis + phil + pagibig + EMPLOYEE_LOANS + tax);
    let net   = round2(gross - total);

    let first  = round2(net / 2);
    let second = round2(net - first);

    $('#salary_lwop').val(salary_lwop.toFixed(2));
    $('#pera').val(calPera.toFixed(2));
    $('#gross_pay').val(gross.toFixed(2));
    $('#gsis').val(gsis.toFixed(2));
    $('#philhealth').val(phil.toFixed(2));
    $('#total_deductions').val(total.toFixed(2));
    $('#net_pay').val(net.toFixed(2));
    $('#net_pay_first').val(first.toFixed(2));
    $('#net_pay_second').val(second.toFixed(2));
}

// RESTORED AJAX GET SALARY
$('#employee_select').on('change', function () {
    const empId = $(this).val();
    if (!empId) return;
    $.post("<?= base_url('payroll/ajax_get_salary') ?>", { 
        employee_id: empId, 
        payroll_type: "<?= $payroll_type ?>" 
    }, function (res) {
        $('#employee_id').val(res.employee_id);
        $('#basic_salary').val(res.basic_salary);
        $('#lwop_days').val(0);
        $('#pagibig').val(200.00);
        renderOtherDeductions(res.loans || []);
    }, 'json');
});

function renderOtherDeductions(loans = []) {
    let container = $('#loan-deductions-container');
    container.empty();
    EMPLOYEE_LOANS = 0;
    if (!loans.length) { container.append('<div class="col-12 small text-muted ps-2 italic">None</div>'); computePayroll(); return; }
    loans.forEach(loan => {
        let amt = parseFloat(loan.monthly_deduction) || 0;
        EMPLOYEE_LOANS += amt;
        container.append(`
            <div class="col-6">
                <label class="x-small fw-bold text-muted text-uppercase">${loan.deduction_name}</label>
                <input type="hidden" name="deduction_id[]" value="${loan.deduction_id}">
                <input type="hidden" name="deduction_name[]" value="${loan.deduction_name}">
                <input type="text" name="deduction_amount[]" class="form-control form-control-sm text-end deduction-amount" value="${amt.toFixed(2)}">
            </div>
        `);
    });
    computePayroll();
}

$(document).on('input', '#lwop_days, #pagibig, #tax, .deduction-amount', function() {
    if($(this).hasClass('deduction-amount')) {
        let total = 0;
        $('.deduction-amount').each(function() { total += parseFloat($(this).val()) || 0; });
        EMPLOYEE_LOANS = round2(total);
    }
    computePayroll();
});

function loadPayroll(period_id) {
    $.post("<?= base_url('payroll/fetchPayrollByPeriod') ?>", { payroll_period_id: period_id }, function(res) {
        let tbody = $('#savedPayrollTable tbody');
        tbody.empty();
        let tg = 0, td = 0;

        if (!res.data || !res.data.length) {
            tbody.append('<tr><td colspan="10" class="text-center py-5 text-muted">No records found.</td></tr>');
            return;
        }

        res.data.forEach(row => {
            tg += parseFloat(row.gross_pay);
            td += parseFloat(row.total_deductions);

            // Sum up only the dynamic loans (excluding mandatory and LWOP)
            let otherLoansTotal = 0;
            if (row.other_deductions) {
                row.other_deductions.split(',').forEach(item => {
                    let [name, amt] = item.split(':');
                    otherLoansTotal += parseFloat(amt || 0);
                });
            }

            tbody.append(`
                <tr data-payroll-id="${row.payroll_id}">
                    <td class="ps-4 border-end fw-bold text-dark">${row.name}</td>
                    
                    <td class="text-end text-muted small">₱${parseFloat(row.basic_salary).toFixed(2)}</td>
                    <td class="text-end border-end fw-bold">₱${parseFloat(row.gross_pay).toFixed(2)}</td>
                    
                    <td class="text-end small">₱${parseFloat(row.gsis || 0).toFixed(2)}</td>
                    <td class="text-end small">₱${parseFloat(row.philhealth || 0).toFixed(2)}</td>
                    <td class="text-end small">₱${parseFloat(row.pagibig || 0).toFixed(2)}</td>
                    <td class="text-end small">₱${parseFloat(row.tax || 0).toFixed(2)}</td>
                    <td class="text-end border-end text-danger small">₱${parseFloat(row.lwop_amount || 0).toFixed(2)}</td>
                    
                    <td class="text-end border-end bg-light bg-opacity-25" style="min-width: 160px;">
                        <div class="px-1">
                            <?php 
                            // Logic to process the deductions string
                            // Assuming row.other_deductions format is "LoanName:Amount,LoanName:Amount"
                            ?>
                            
                            ${row.other_deductions ? row.other_deductions.split(',').map(item => {
                                const [name, amount] = item.split(':');
                                return `
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted fw-bold" style="font-size: 9px; text-transform: uppercase;">
                                            ${name.trim()}
                                        </span>
                                        <span class="fw-bold ms-2" style="font-size: 11px;">
                                            ₱${parseFloat(amount || 0).toFixed(2)}
                                        </span>
                                    </div>
                                `;
                            }).join('') : '<span class="text-muted small italic">None</span>'}

                            <div class="border-top mt-1 pt-1 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark" style="font-size: 10px;">TOTAL</span>
                                <span class="badge bg-white text-dark border fw-bold shadow-sm" style="font-size: 11px;">
                                    ₱${otherLoansTotal.toFixed(2)}
                                </span>
                            </div>
                        </div>
                    </td>
                    
                    <td class="text-end pe-4 fw-bold text-success" style="background: rgba(16, 185, 129, 0.03);">
                        ₱${parseFloat(row.net_pay).toFixed(2)}
                        <button type="button" class="btn btn-sm p-0 ms-2 edit-payroll-btn"><i class="bi bi-pencil-square text-primary"></i></button>
                    </td>
                </tr>
            `);
        });

        // Update total stats
        $('#total_gross_pay').text('₱' + tg.toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#total_deduction').text('₱' + td.toLocaleString(undefined, {minimumFractionDigits: 2}));
        
    }, 'json');
}
// RESTORED EDIT TRIGGER
$(document).on('click', '.edit-payroll-btn', function () {
    const payrollId = $(this).closest('tr').data('payroll-id');
    $.post("<?= base_url('payroll/getPayrollByIdGP') ?>", { payroll_id: payrollId }, function(res) {
        $('#payroll_id').val(res.payroll_id);
        $('#employee_id').val(res.employee_id);
        $('#employee_name').show().val(res.name);
        $('#employee_select').hide();
        $('#basic_salary').val(res.basic_salary);
        $('#lwop_days').val(res.lwop_days);
        $('#tax').val(res.tax);
        $('#pagibig').val(res.pagibig);
        renderOtherDeductions(res.other_deductions_array || []);
        computePayroll();
    }, 'json');
});

$(document).on('click', '.submit_payroll', function (e) {
    e.preventDefault();
    let period_id = $(this).data('period_id');
    let payroll_number = $(this).data('payroll_number');

    Swal.fire({
        title: 'Submit Payroll?',
        text: "This will forward the records to the Admin Office for approval.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b0f1a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Submit Now',
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
                        Swal.fire('Submitted!', 'Payroll has been forwarded.', 'success')
                            .then(() => { location.reload(); });
                    } else {
                        Swal.fire('Error', 'Submission failed.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Server Error', 'Could not process submission.', 'error');
                }
            });
        }
    });
});

function round2(num) { return Math.round((num + Number.EPSILON) * 100) / 100; }
document.getElementById('btnPrint').addEventListener('click', function () { window.open(this.getAttribute('data-url'), '_blank'); });
function generatePayslips(id) { window.open("<?= base_url('payroll/payslips/') ?>" + id, "_blank"); }
</script>