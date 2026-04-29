<style>
    :root {
        --maroon: #6b0f1a;
        --success-soft: #ecfdf5;
        --danger-soft: #fef2f2;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* Modern Stepper */
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 1rem; position: relative; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; z-index: 2; }
    .stepper-item::before { content: ""; position: absolute; top: 20px; left: -50%; width: 100%; height: 2px; background: #e2e8f0; z-index: -1; }
    .stepper-item:first-child::before { content: none; }
    .step-counter { width: 40px; height: 40px; background: white; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; font-weight: 700; transition: 0.3s; }
    .stepper-item.completed .step-counter { background: #10b981; border-color: #10b981; color: white; }
    .stepper-item.current .step-counter { background: #f59e0b; border-color: #f59e0b; color: white; }
    .step-name { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b; }

    /* Custom Form Layouts */
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
        font-family: 'JetBrains Mono', 'Monaco', monospace; 
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        color: #1e293b;
        padding: 10px 12px !important;
        text-align: right !important;
        width: 100%;
        outline: none;
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

    .lwop-badge {
        font-size: 10px;
        background: #fff1f2;
        color: #e11d48;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 5px;
    }

    /* Table Styles */
    #savedPayrollTable th,
    #savedPayrollTable td {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
        white-space: nowrap;
    }
</style>
<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">

        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4 mt-3">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark"><?=$payroll_type?> - <?=$unit?></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <?php if($status == 1): ?>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Draft Mode</span>
                <?php elseif($status >= 2 && $status < 7): ?>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending Admin Approval</span>
                <?php elseif($status == 7): ?>
                    <span class="badge bg-success px-3 py-2 rounded-pill">Approved</span>
                <?php endif; ?>
                <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"><?= date('M d, Y') ?></div>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body pt-5 pb-3">
                <div class="stepper-wrapper">
                    <div class="stepper-item completed"><div class="step-counter"><i class="bi bi-check-lg"></i></div><div class="step-name">HR Draft</div></div>
                    <div class="stepper-item <?= $status == 2 ? 'current' : ($status > 2 ? 'completed' : '') ?>"><div class="step-counter">2</div><div class="step-name">Admin</div></div>
                    <div class="stepper-item <?= $status == 3 ? 'current' : ($status > 3 ? 'completed' : '') ?>"><div class="step-counter">3</div><div class="step-name">Budget</div></div>
                    <div class="stepper-item <?= $status == 4 ? 'current' : ($status > 4 ? 'completed' : '') ?>"><div class="step-counter">4</div><div class="step-name">Accounting</div></div>
                    <div class="stepper-item <?= $status == 5 ? 'current' : ($status > 5 ? 'completed' : '') ?>"><div class="step-counter">5</div><div class="step-name">VP Approval</div></div>
                    <div class="stepper-item <?= $status == 6 ? 'current' : ($status > 6 ? 'completed' : '') ?>"><div class="step-counter">6</div><div class="step-name">Cashier</div></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 border-top border-primary border-4 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Payroll Computation</h6>
                    </div>
                    
                    <div class="card-body px-4 pb-4 pt-1">
                        
                        <div class="mb-4 bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="ledger-label mb-0 text-dark">Rate per Day / Base</span>
                                <div class="input-group input-group-sm w-auto shadow-sm">
                                    <span class="input-group-text bg-white fw-bold">₱</span>
                                    <input type="number" id="rate_input" class="form-control text-end fw-bold text-primary" style="width: 80px;" step="0.01">
                                    <button class="btn btn-primary" type="button" onclick="saveRate()"><i class="bi bi-save"></i></button>
                                </div>
                            </div>
                            
                            <label class="ledger-label text-dark">Employee Selection</label>
                            <select id="employee_select" class="form-select form-select-lg border-2 shadow-sm">
                                <option value="">-- Choose from Roster --</option>
                                <?php foreach ($employees as $row): ?>
                                    <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                        <option value="<?= $row->employee_id ?>" data-position="<?= htmlspecialchars($row->position) ?>" data-name="<?= htmlspecialchars(trim($row->name . ' ' . $row->middle_name . ' ' . $row->last_name)) ?>">
                                            <?= htmlspecialchars($row->name . ' ' . (!empty($row->middle_name) ? strtoupper(substr($row->middle_name, 0, 1)) . '. ' : '') . $row->last_name) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" id="employee_name" class="form-control readonly-field mt-2" readonly style="display:none;">
                        </div>

                        <form id="payrollForm">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">
                            <input type="hidden" id="employee_name_hidden" name="employee_name">
                            <input type="hidden" id="position_hidden" name="position">
                            <input type="hidden" name="payroll_id" id="payroll_id">

                            <div class="section-title-custom bg-earnings">Earnings & Gross</div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <span class="ledger-label">Days Worked</span>
                                    <div class="amount-input-group">
                                        <input type="number" step="0.001" min="0" id="days_worked" name="days_worked" class="money-field text-dark">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">LWOP <span class="lwop-badge">Days</span></span>
                                    <div class="amount-input-group">
                                        <input type="number" step="0.001" min="0" id="lwop_days" name="lwop_days" class="money-field text-danger">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <span class="ledger-label">Calculated Basic Salary</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="basic_salary" name="basic_salary" class="money-field readonly-field" readonly>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <span class="ledger-label text-danger">LWOP Ded. Amount</span>
                                    <div class="amount-input-group readonly-money border-danger border-opacity-25">
                                        <span class="currency-symbol text-danger">-₱</span>
                                        <input type="text" id="lwop_amount" name="lwop_amount" class="money-field readonly-field text-danger" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">Salary after LWOP</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="salary_lwop" name="salary_lwop" class="money-field readonly-field" readonly>
                                    </div>
                                </div>

                                <div class="col-12" id="pera_container">
                                    <span class="ledger-label">PERA (Adjusted for LWOP)</span>
                                    <div class="amount-input-group readonly-money">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="pera" name="pera" class="money-field readonly-field" readonly>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <span class="ledger-label text-success">Total Gross Pay</span>
                                    <div class="amount-input-group border-success" style="border-width: 2px;">
                                        <span class="currency-symbol bg-earnings border-0">₱</span>
                                        <input type="text" id="gross_pay" name="gross_pay" class="money-field text-success fw-bolder" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                <div class="section-title-custom bg-deductions m-0">Mandatory Deductions</div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="mandatory_switch" data-bs-toggle="tooltip" title="Auto Compute">
                                    <label class="form-check-label fw-bold small text-muted">Auto</label>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6" id="gov_container">
                                    <span class="ledger-label" id="gov_label">GSIS</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="gsis" class="money-field">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">PhilHealth</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="philhealth" class="money-field">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">Pag-IBIG</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="pagibig" class="money-field">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="ledger-label">Withholding Tax</span>
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₱</span>
                                        <input type="text" id="tax" class="money-field">
                                    </div>
                                </div>
                            </div>

                            <div class="section-title-custom bg-loans mb-3 mt-4">Variations / Loans</div>
                            <div id="loan-deductions-container" class="row g-3 mb-4">
                                <div class="col-12 text-center text-muted small py-3 bg-light rounded-3 border border-dashed">
                                    Select an employee to view active loans.
                                </div>
                            </div>

                            <div class="section-title-custom bg-summary mb-3 mt-4">Final Computation</div>
                            <div class="summary-card-total shadow-sm mb-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <span class="ledger-label text-danger">Total Deductions</span>
                                        <div class="amount-input-group readonly-money border-danger border-opacity-50 mb-2">
                                            <span class="currency-symbol text-danger border-0" style="background:#fef2f2;">₱</span>
                                            <input type="text" id="total_deductions" name="total_deductions" class="money-field text-danger fw-bold readonly-field" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="net-pay-highlight shadow-sm">
                                            <span class="ledger-label text-success mb-1">Net Take Home Pay</span>
                                            <input type="text" id="net_pay" name="net_pay" class="money-field net-pay-amount text-center bg-transparent border-0" style="padding:0!important;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="btnSavePayroll" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold fs-5" <?= $status >= 2 ? 'disabled' : '' ?>>
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
                        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                            <div class="card-body py-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-uppercase opacity-75 fw-bold" style="font-size: 11px;">Period Gross Total</div>
                                    <h3 class="mb-0 fw-bold font-monospace" id="total_gross_pay">₱0.00</h3>
                                </div>
                                <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white h-100">
                            <div class="card-body py-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-uppercase opacity-75 fw-bold" style="font-size: 11px;">Period Deductions</div>
                                    <h3 class="mb-0 fw-bold font-monospace text-danger" id="total_deduction">₱0.00</h3>
                                </div>
                                <i class="bi bi-dash-circle fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="mb-0 fw-bold">Processed Members (<?= $unit ?>)</h6>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border rounded-pill px-3 dropdown-toggle shadow-sm fw-bold" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1 text-primary"></i> Operations
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                <li><a class="dropdown-item py-2" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf_dw/'.$period_id) ?>">
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
                                <li><a class="dropdown-item py-2 text-primary fw-bold submit_payroll" href="#" data-period_id="<?= $period_id ?>" data-payroll_number="<?= $payroll_number ?? '' ?>">
                                    <i class="bi bi-check-all me-2"></i>Finalize & Submit
                                </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="savedPayrollTable" style="min-width: 1200px;">
                                <thead class="sticky-top" style="z-index: 10;">
                                    <tr class="bg-light text-center border-bottom">
                                        <th colspan="1" class="border-end">Employee Info</th>
                                        <th colspan="2" class="border-end bg-earnings bg-opacity-10 text-dark">Earnings</th>
                                        <th colspan="4" class="border-end bg-deductions bg-opacity-10 text-danger">Mandatory Deductions</th>
                                        <th colspan="2" class="border-end bg-loans bg-opacity-10 text-warning">Variations</th>
                                        <th colspan="1" class="border-end bg-summary bg-opacity-10 text-success">Disbursement</th>
                                        <th colspan="1" class="bg-light text-secondary">Action</th>
                                    </tr>
                                    <tr class="bg-light text-muted text-uppercase" style="font-size: 11px;">
                                        <th class="ps-4 border-end pt-0 border-top-0">Name & Position</th>
                                        <th class="text-end pt-0 border-top-0">Basic Sal.</th>
                                        <th class="text-end border-end pt-0 border-top-0 text-success">Gross Pay</th>
                                        <th class="text-end pt-0 border-top-0" id="gov_col_label">GSIS</th>
                                        <th class="text-end pt-0 border-top-0">PhilH</th>
                                        <th class="text-end pt-0 border-top-0">PagIBIG</th>
                                        <th class="text-end border-end pt-0 border-top-0">Tax</th>
                                        <th class="text-end text-danger pt-0 border-top-0">LWOP Amt.</th>
                                        <th class="text-end border-end pt-0 border-top-0">Other Loans</th>
                                        <th class="text-end pe-3 fw-bold text-success border-end pt-0 border-top-0">Net Pay</th>
                                        <th class="text-center pe-3 pt-0 border-top-0">Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td class="text-end fw-bold ps-4 border-end" style="font-size: 12px;">TOTALS</td>
                                        <td id="totalBasic" class="text-end font-monospace text-muted fw-bold">₱0.00</td>
                                        <td id="totalGross" class="text-end font-monospace text-success fw-bold border-end">₱0.00</td>
                                        <td id="totalGSIS" class="text-end font-monospace text-muted">₱0.00</td>
                                        <td id="totalPhilhealth" class="text-end font-monospace text-muted">₱0.00</td>
                                        <td id="totalPagibig" class="text-end font-monospace text-muted">₱0.00</td>
                                        <td class="text-end font-monospace text-muted border-end"></td>
                                        <td id="totalLwop" class="text-end font-monospace text-danger">₱0.00</td>
                                        <td class="text-end border-end"></td>
                                        <td id="totalNetPay" class="text-end font-monospace text-primary fw-bolder fs-6 border-end">₱0.00</td>
                                        <td class="pe-3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
const isCOS = PAYROLL_TYPE === 'CONTRACT OF SERVICE';

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
    Swal.fire({
        icon: 'success', title: 'Rate Saved', text: "Rate updated to ₱" + rate,
        timer: 1500, showConfirmButton: false
    });
    computePayroll();
}

function formatMoney(num) {
    return parseFloat(num || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function parseNumber(val) {
    return parseFloat((val || '').toString().replace(/[₱,\s]/g, '')) || 0;
}

function parseOtherDeductions(str) {
    if (!str) return 0;
    let total = 0;
    str.split(',').forEach(item => {
        let parts = item.split(':');
        if (parts.length === 2) { total += parseFloat(parts[1]) || 0; }
    });
    return total;
}

function formatOtherDeductions(otherStr) {
    if (!otherStr) return '<span class="text-muted opacity-50">-</span>';
    return otherStr.split(',').map(item => {
        let [name, amount] = item.split(':');
        return `<div style="font-size: 10px;">${name}: <span class="font-monospace text-dark">₱${parseFloat(amount || 0).toFixed(2)}</span></div>`;
    }).join('');
}

$(document).ready(function () {
    setupPayrollUI();
    computePayroll();
    loadPayrollDW();
    
    let savedRate = getRatePerDay();
    $('#rate_input').val(savedRate);
});

function setupPayrollUI() {
    if (isCOS) {
        $('#gov_label').text('SSS');
        $('#gov_col_label').text('SSS');
        $('#mandatory_switch').prop('checked', false);
        $('#pera_container').hide();
        $('#pera').val(0);
        $('#gsis, #philhealth, #pagibig').prop('readonly', false).removeClass('readonly-money');
        toggleMandatory(false);
    } else {
        $('#gov_label').text('GSIS');
        $('#gov_col_label').text('GSIS');
        $('#mandatory_switch').prop('checked', true);
        $('#pera_container').show();
        $('#gsis, #philhealth').prop('readonly', true).addClass('readonly-money');
        $('#pagibig').prop('readonly', false).removeClass('readonly-money');
        toggleMandatory(true);
    }
}

function toggleMandatory(isEnabled) {
    if (isEnabled) {
        if(isCOS) {
            $('#gsis, #pagibig, #philhealth').prop('readonly', false).removeClass('readonly-money');
        }
    } else {
        $('#gsis, #pagibig, #philhealth').prop('readonly', true).addClass('readonly-money');
        if(!isCOS) $('#pagibig').prop('readonly', false).removeClass('readonly-money');
    }
}

$('#mandatory_switch').on('change', function () {
    toggleMandatory(this.checked);
    computePayroll();
});

function computePayroll() {
    let ratePerDay = parseNumber($('#rate_input').val()) || getRatePerDay();
    let daysWorked = parseNumber($('#days_worked').val());
    let lwopDays = parseNumber($('#lwop_days').val());

    let basicSalary = daysWorked * ratePerDay;
    let lwopDeduction = lwopDays * ratePerDay;
    let salaryAfterLWOP = basicSalary - lwopDeduction;

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

    let gov = 0, philhealth = 0, pagibig = 0;

    if ($('#mandatory_switch').is(':checked')) {
        if (isCOS) {
            gov = parseNumber($('#gsis').val());
            philhealth = parseNumber($('#philhealth').val());
            pagibig = parseNumber($('#pagibig').val());
        } else {
            let monthlyBase = ratePerDay * 22;
            gov = monthlyBase * 0.09;
            philhealth = monthlyBase * 0.025;
            pagibig = parseNumber($('#pagibig').val()); 
            if(pagibig === 0) pagibig = 200; 
        }
    } else {
        gov = parseNumber($('#gsis').val());
        philhealth = parseNumber($('#philhealth').val());
        pagibig = parseNumber($('#pagibig').val());
    }

    $('#gsis').val(formatMoney(gov));
    $('#philhealth').val(formatMoney(philhealth));
    $('#pagibig').val(formatMoney(pagibig));

    let tax = parseNumber($('#tax').val());
    let otherDeductions = 0;
    $('#loan-deductions-container .loan-input').each(function () {
        otherDeductions += parseNumber($(this).val());
    });

    let totalDeductions = gov + philhealth + pagibig + tax + otherDeductions;
    $('#total_deductions').val(formatMoney(totalDeductions));

    let netPay = grossPay - totalDeductions;
    $('#net_pay').val(formatMoney(netPay));
}

$(document).on('input', '#days_worked, #lwop_days, #tax, #rate_input', computePayroll);
$(document).on('input', '#gsis, #philhealth, #pagibig', computePayroll);
$(document).on('input', '.loan-input', computePayroll);

$(document).on('change', '#employee_select', function () {
    let emp_id = $(this).val();
    let selected = $("#employee_select option:selected");

    $('#employee_id').val(emp_id);

    if (emp_id) {
        let rawName = selected.data('name') || selected.text() || '';
        $('#employee_name_hidden').val(rawName.toString().replace(/\s+/g, ' ').trim());
        $('#position_hidden').val((selected.data('position') || '').toString().replace(/\s+/g, ' ').trim());
        loadEmployeeLoans(emp_id);
    } else {
        $('#employee_name_hidden').val('');
        $('#position_hidden').val('');
        $('#loan-deductions-container').html('<div class="col-12 text-center text-muted small py-3 bg-light rounded-3 border border-dashed">Select an employee to view active loans.</div>');
    }
});

function loadEmployeeLoans(employee_id) {
    $('#loan-deductions-container').html('<div class="col-12 text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
    $.ajax({
        url: BASE_URL + "payroll/get_employee_loans",
        type: "POST",
        data: { employee_id: employee_id, [CSRF_NAME]: CSRF_HASH },
        dataType: "json",
        success: function(res) {
            let html = "";
            if (res.length > 0) {
                res.forEach(loan => {
                    html += `
                        <div class="col-md-6 loan-item">
                            <span class="ledger-label text-truncate" title="${loan.deduction_name}">${loan.deduction_name}</span>
                            <div class="amount-input-group">
                                <span class="currency-symbol">₱</span>
                                <input type="text" class="money-field loan-input text-danger" value="${parseFloat(loan.monthly_deduction || 0).toFixed(2)}">
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `<div class="col-12 text-center text-muted small py-3 bg-light rounded-3 border border-dashed">No active loans found.</div>`;
            }
            $('#loan-deductions-container').html(html);
            computePayroll();
        }
    });
}

function savePayroll() {
    let otherDeductionsStr = '';
    $('#loan-deductions-container .loan-item').each(function () {
        let name = $(this).find('span.ledger-label').text().trim();
        let amount = parseNumber($(this).find('.loan-input').val());
        if (amount > 0) {
            otherDeductionsStr += `${name}:${amount.toFixed(2)},`;
        }
    });
    otherDeductionsStr = otherDeductionsStr.replace(/,$/, '');

    let btn = $('#btnSavePayroll');
    let ogHTML = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop('disabled', true);

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
            btn.html(ogHTML).prop('disabled', false);
            if (res.status === 'success') {
                Swal.fire({icon: 'success', title: 'Saved', showConfirmButton: false, timer: 1000});
                $('#payrollForm')[0].reset();
                $('#employee_select').val('').trigger('change');
                loadPayrollDW();
            } else {
                Swal.fire('Error', res.message || 'Saving failed.', 'error');
            }
        },
        error: function() {
            btn.html(ogHTML).prop('disabled', false);
            Swal.fire('Error', 'Network Error.', 'error');
        }
    });
}

$(document).on('click', '#btnSavePayroll', function(e){
    e.preventDefault();
    if(!$('#employee_id').val()) { Swal.fire('Wait', 'Select an employee first.', 'warning'); return; }
    savePayroll();
});

function loadPayrollDW() {
    $.ajax({
        url: BASE_URL + "payroll/fetchPayrollDW?period_id=" + PERIOD_ID,
        type: "GET",
        dataType: "json",
        success: function(res) {
            let tbody = $("#savedPayrollTable tbody");
            tbody.empty();
            let usedEmployees = res.used || [];
            
            let totalBasicPay = 0, totalGrossPay = 0, totalGSIS = 0, totalPhilhealth = 0, totalPagibig = 0, totalLwopAmt = 0, totalDeductions = 0, totalNetPay = 0;

            if (!res.data || res.data.length === 0) {
                tbody.html(`<tr><td colspan="11" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No ledger entries yet.</td></tr>`);
                $("#total_gross_pay, #total_deduction, #totalBasic, #totalGross, #totalGSIS, #totalPhilhealth, #totalPagibig, #totalLwop, #totalDeductions, #totalNetPay").text("₱0.00");
                return;
            }

            res.data.forEach(row => {
                let basic = parseFloat(row.basic_salary || 0);
                let gross = parseFloat(row.gross_pay || 0);
                let contribution = isCOS ? parseFloat(row.sss || 0) : parseFloat(row.gsis || 0);
                let philhealth = parseFloat(row.philhealth || 0);
                let pagibig = parseFloat(row.pagibig || 0);
                let other = isCOS ? 0 : parseOtherDeductions(row.other_deductions);
                let lwopAmount = parseFloat(row.lwop_amount || 0);
                let deduction = parseFloat(row.total_deductions || 0);
                let net = parseFloat(row.net_pay || 0);

                totalBasicPay += basic; totalGrossPay += gross; totalGSIS += contribution; totalPhilhealth += philhealth;
                totalPagibig += pagibig; totalLwopAmt += lwopAmount; totalDeductions += deduction; totalNetPay += net;

                tbody.append(`
                    <tr>
                        <td class="ps-4 border-end">
                            <div class="fw-bold text-dark">${row.name || ''}</div>
                            <div class="text-muted" style="font-size: 0.70rem;">${row.position || ''}</div>
                        </td>
                        <td class="text-end font-monospace text-dark">₱${basic.toFixed(2)}</td>
                        <td class="text-end font-monospace text-success fw-bold border-end">₱${gross.toFixed(2)}</td>
                        <td class="text-end font-monospace text-muted">₱${contribution.toFixed(2)}</td>
                        <td class="text-end font-monospace text-muted">₱${philhealth.toFixed(2)}</td>
                        <td class="text-end font-monospace text-muted">₱${pagibig.toFixed(2)}</td>
                        <td class="text-end font-monospace text-muted border-end">₱${parseFloat(row.tax || 0).toFixed(2)}</td>
                        <td class="text-end font-monospace text-danger">₱${lwopAmount.toFixed(2)}</td>
                        <td class="text-end font-monospace border-end">${!isCOS ? formatOtherDeductions(row.other_deductions) : '<span class="opacity-50 text-muted">-</span>'}</td>
                        <td class="text-end pe-3 font-monospace text-primary fw-bolder fs-6 border-end">₱${net.toFixed(2)}</td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-sm btn-light border text-primary edit-payroll-btn" data-id="${row.payroll_id}" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-light border text-danger delete-payroll-btn" data-id="${row.payroll_id}" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `);
            });

             $("#employee_select option").each(function () {
                let empId = $(this).val();
                if (usedEmployees.includes(String(empId))) {
                    $(this).prop('disabled', true);
                    if(!$(this).text().includes('(Added)')) {
                        $(this).text($(this).text() + " (Added)");
                    }
                }
            });

            // Re-init tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });

            // Dash Cards
            $("#total_gross_pay, #total_dw_gross_pay").text("₱" + formatMoney(totalGrossPay));
            $("#total_deduction, #total_dw_deduction").text("₱" + formatMoney(totalDeductions));
            
            // Footer
            $("#totalBasic").text("₱" + formatMoney(totalBasicPay));
            $("#totalGross").text("₱" + formatMoney(totalGrossPay));
            $("#totalGSIS").text("₱" + formatMoney(totalGSIS));
            $("#totalPhilhealth").text("₱" + formatMoney(totalPhilhealth));
            $("#totalPagibig").text("₱" + formatMoney(totalPagibig));
            $("#totalLwop").text("₱" + formatMoney(totalLwopAmt));
            $("#totalDeductions").text("₱" + formatMoney(totalDeductions));
            $("#totalNetPay").text("₱" + formatMoney(totalNetPay));
        }
    });
}

$(document).on('click', '.edit-payroll-btn', function () {
    let payroll_id = $(this).data('id');
    $.ajax({
        url: BASE_URL + "payroll/get_single_dw",
        type: "POST",
        data: { payroll_id: payroll_id, [CSRF_NAME]: CSRF_HASH },
        dataType: "json",
        success: function (res) {
            if (!res) { Swal.fire('Error', 'Record not found.', 'error'); return; }

            let opt = $(`#employee_select option[value="${res.employee_id}"]`);
            opt.prop('disabled', false);
            $('#employee_select').val(res.employee_id).trigger('change');
            
            setTimeout(() => {
                $('#payroll_id').val(res.payroll_id);
                $('#days_worked').val(res.days_worked);
                $('#lwop_days').val(res.lwop_days);
                
                if (isCOS) { $('#gsis').val(res.sss || 0); } 
                else { $('#gsis').val(res.gsis || 0); }
                
                $('#philhealth').val(res.philhealth);
                $('#pagibig').val(res.pagibig);
                $('#tax').val(res.tax);
                
                computePayroll();
                $('html, body').animate({ scrollTop: $(".card").first().offset().top - 50 }, 300);
            }, 300); 
        }
    });
});

$(document).on('click', '.delete-payroll-btn', function () {
    let payroll_id = $(this).data('id');
    Swal.fire({
        title: 'Remove Entry?', text: "This deletes the employee from the ledger.", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: BASE_URL + "payroll/delete_dw", type: "POST",
                data: { payroll_id: payroll_id, [CSRF_NAME]: CSRF_HASH }, dataType: "json",
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({icon: 'success', title: 'Deleted', showConfirmButton: false, timer: 1000});
                        location.reload(); 
                    }
                }
            });
        }
    });
});

$(document).on('click', '.submit_payroll', function () {
    let period_id = $(this).data('period_id');
    let payroll_number = $(this).data('payroll_number');
    
    Swal.fire({
        title: 'Submit to Admin?',
        text: "This locks the batch and sends it to the Admin Office.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Yes, Submit'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('payroll/submit_payroll') ?>", type: "POST",
                data: { period_id: period_id, payroll_number: payroll_number, [CSRF_NAME]: CSRF_HASH }, dataType: "json",
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({title: 'Submitted!', icon: 'success', confirmButtonColor: '#198754'}).then(() => location.reload());
                    }
                }
            });
        }
    });
});

$(document).ready(function() {
    $('#btnPrint').on('click', function(e) {
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