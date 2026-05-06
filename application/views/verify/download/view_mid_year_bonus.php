<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $period ?? 'Payroll View' ?></title>

<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* General Page Styles */
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    #mainContent {
        padding: 2rem;
        max-width: 100%; /* Full width to allow horizontal scrolling for dynamic columns */
        margin: 0 auto;
    }

    /* DIGITAL DOCUMENT HEADER */
    .doc-header {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        position: relative;
        border-top: 5px solid #800000;
    }

    /* MODERN TRACKER */
    .tracker-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .payroll-tracker {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0 1rem 0;
    }
    .payroll-tracker::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 5%;
        width: 90%;
        height: 3px;
        background: #e9ecef;
        z-index: 1;
    }
    .tracker-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .tracker-circle {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e9ecef;
        color: #adb5bd;
        line-height: 42px;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .tracker-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .tracker-step.completed .tracker-circle {
        background: #198754;
        border-color: #198754;
        color: white;
    }
    .tracker-step.completed .tracker-label { color: #198754; }
    .tracker-step.current .tracker-circle {
        background: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    }
    .tracker-step.current .tracker-label { color: #0d6efd; }
    
    /* PREMIUM TABLE STYLES */
    .table-container {
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        background: white;
        padding: 0;
        overflow: hidden;
    }
    .table-custom {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    .table-custom thead th {
        background-color: #f8f9fc;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid #e9ecef;
        vertical-align: middle;
    }
    .table-custom tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        white-space: nowrap;
    }
    .table-custom .font-monospace {
        font-size: 1.05rem !important;
        letter-spacing: 0.5px;
    }
    
    .table-custom tfoot .font-monospace {
        font-size: 1.15rem !important;
    }
    .table-custom tbody tr:hover {
        background-color: #f8f9fc;
    }
    .table-custom tfoot td {
        background-color: #f8f9fc;
        font-weight: 700;
        color: #212529;
        padding: 1rem 0.75rem;
        border-top: 2px solid #e9ecef;
    }
    
    /* Column Group Highlighting */
    .col-earnings { background-color: rgba(25, 135, 84, 0.015); }
    .col-deductions { background-color: rgba(220, 53, 69, 0.015); }
    
    /* Action Buttons */
    .hover-elevate {
        transition: all 0.2s ease-in-out;
    }
    .hover-elevate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .btn-edit {
        padding: 0.35rem 0.6rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    .btn-edit:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
    }
    
    /* Custom Scrollbar for wide tables */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .dropdown-menu {
        z-index: 1050 !important; /* Forces the menu to sit above everything else */
    }
</style>
</head>
<body>

<main id="mainContent">

    <div class="doc-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
        <div class="d-flex align-items-center gap-3 text-center text-md-start">
            <img src="<?= base_url('assets/img/favicon.png') ?>" alt="EVSU Logo" style="width: 80px;">
            <div>
                <p class="mb-0 text-muted small text-uppercase fw-bold" style="letter-spacing: 1px;">Republic of the Philippines</p>
                <h4 class="mb-0 fw-bolder text-dark">Eastern Visayas State University</h4>
                <p class="mb-0 text-muted small">Tacloban City</p>
            </div>
        </div>
        <div class="text-center text-md-end">
            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-4 py-2 rounded-pill mb-2 fs-6 text-uppercase">
                <?= $payroll_type ?? 'General Payroll' ?>
            </div>
            <p class="mb-0 text-secondary fw-medium"><i class="bi bi-diagram-3-fill me-2"></i>Unit: <span class="text-dark fw-bold"><?= $unit ?? 'N/A' ?></span></p>
        </div>
    </div>

    <div class="tracker-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Document Tracking Status</h6>
            <div class="d-flex gap-3 small fw-medium">
                <span class="text-success"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Completed</span>
                <span class="text-primary"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Current</span>
                <span class="text-secondary"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Pending</span>
            </div>
        </div>

        <div class="payroll-tracker">
            <div class="tracker-step completed" id="step-1">
                <div class="tracker-circle"><i class="bi bi-check-lg"></i></div>
                <div class="tracker-label">HRMO</div>
            </div>
            <div class="tracker-step current" id="step-2">
                <div class="tracker-circle">2</div>
                <div class="tracker-label">Accounting</div>
            </div>
            <div class="tracker-step" id="step-3">
                <div class="tracker-circle">3</div>
                <div class="tracker-label">Budget</div>
            </div>
            <div class="tracker-step" id="step-4">
                <div class="tracker-circle">4</div>
                <div class="tracker-label">Approved</div>
            </div>
            <div class="tracker-step" id="step-5">
                <div class="tracker-circle">5</div>
                <div class="tracker-label">Released</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i><?=$payroll_type ?? 'Payroll'?> Register</h6>
            
            <div>
                <!-- Export Dropdown Menu -->
                <div class="dropdown d-inline-block me-2">
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 hover-elevate dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download me-1"></i> Export Options
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" aria-labelledby="exportDropdown">
                        <li>
                            <a class="dropdown-item py-2 fw-semibold" href="<?= base_url('receiver/download_pdf/'.$period_id) ?>" target="_blank">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Payroll (PDF)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 fw-semibold" href="<?= base_url('verify/download_excel_midyear_payroll/'.$period_id) ?>" target="_blank">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i> Payroll (Excel)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <a class="dropdown-item py-2 fw-semibold" href="#" onclick="generatePayslips(<?= $period_id ?>)">
                                <i class="bi bi-receipt text-secondary me-2"></i> Individual Payslips (PDF)
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Primary Download Button -->
                <a class="btn btn-sm btn-primary rounded-pill px-3 hover-elevate" href="#" id="btnPrint" data-url="<?= base_url('receiver/export_pdf_mid/'.$period_id) ?>">
                    <i class="bi bi-file-pdf me-2"></i>Download Payroll
                </a>
            </div>
        </div>

        <?php
        $deduction_names = [];
        if (!empty($payrolls)) {
            foreach ($payrolls as $payroll) {
                $p = (object) $payroll; 
                if (!empty($p->less)) {
                    $items = explode(',', $p->less);
                    foreach ($items as $item) {
                        $parts = explode(':', $item);
                        if (count($parts) == 2) {
                            $name = trim($parts[0]);
                            if (!in_array($name, $deduction_names)) {
                                $deduction_names[] = $name;
                            }
                        }
                    }
                }
            }
        }
        // Sort alphabetically so the columns look organized
        sort($deduction_names);

        // 2. INITIALIZE GRAND TOTALS FOR FOOTER
        $total_basic = 0; 
        $total_gross = 0; 
        $total_tax = 0; 
        $total_deductions_all = 0; 
        $total_net = 0;
        // Create an array to track totals for each dynamic deduction column
        $deduction_totals = array_fill_keys($deduction_names, 0);
        ?>

        <div class="table-responsive">
            <table class="table table-custom align-middle" id="savedPayrollTable">
                <thead class="sticky-top">
                    <tr>
                        <th rowspan="2" class="ps-4 border-end">Employee Information</th>
                        <th colspan="2" class="text-center border-end text-success col-earnings">Earnings</th>
                        <!-- Calculate Colspan: dynamic loans + 2 (Tax & Total Deductions) -->
                        <th colspan="<?= count($deduction_names) + 2 ?>" class="text-center border-end text-danger col-deductions">Deductions</th>
                        <th rowspan="2" class="text-center text-primary">Net Pay</th>
                        <th rowspan="2" class="text-center pe-4">Action</th>
                    </tr>
                    <tr>
                        <th class="text-end col-earnings">Basic Salary</th>
                        <th class="text-end border-end col-earnings text-success">Gross Pay</th>
                        
                        <!-- DYNAMIC DEDUCTION HEADERS -->
                        <?php foreach ($deduction_names as $d): ?>
                            <th class="text-end col-deductions opacity-75" style="font-size: 0.70rem;"><?= htmlspecialchars($d) ?></th>
                        <?php endforeach ?>
                        
                        <th class="text-end col-deductions">W/ Tax</th>
                        <th class="text-end border-end col-deductions text-danger">Total Ded.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($payrolls)): ?>
                        <?php foreach ($payrolls as $payroll): ?> 
                        <?php 
                            $p = (object) $payroll; 
                            
                            $less_values = [];
                            if (!empty($p->less)) {
                                $items = explode(',', $p->less);
                                foreach ($items as $item) {
                                    $parts = explode(':', $item);
                                    if (count($parts) == 2) {
                                        $less_values[trim($parts[0])] = (float) trim($parts[1]);
                                    }
                                }
                            }

                            $row_tax = (float)($p->tax ?? 0);
                            $row_total_deduction = $row_tax; 

                            $total_basic += (float)($p->basic_salary ?? 0);
                            $total_gross += (float)($p->gross_pay ?? 0);
                            $total_tax += $row_tax;
                            $total_net += (float)($p->net_pay ?? 0);

                            // Check if remarks exist for this row
                            $has_remarks = !empty(trim($p->midyear_remarks ?? ''));
                        ?>
                        <tr class="<?= $has_remarks ? 'table-danger' : '' ?>">
                            <td class="ps-4 border-end">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($p->name ?? '') ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($p->position ?? '') ?></div>
                                <!-- NEW: Small badge under the name if remarks exist -->
                                <?php if($has_remarks): ?>
                                    <div class="text-info mt-1" style="font-size: 0.7rem;">
                                        <i class="bi bi-chat-dots-fill me-1"></i>Has Remarks
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end font-monospace col-earnings basic">₱ <?= number_format((float)($p->basic_salary ?? 0), 2) ?></td>
                            <td class="text-end border-end font-monospace fw-semibold text-success gross col-earnings">₱ <?= number_format((float)($p->gross_pay ?? 0), 2) ?></td>
                            
                            <!-- DYNAMIC DEDUCTION ROWS -->
                            <?php foreach ($deduction_names as $d): ?>
                                <?php 
                                    $amount = isset($less_values[$d]) ? $less_values[$d] : 0;
                                    $row_total_deduction += $amount;
                                    $deduction_totals[$d] += $amount;
                                ?>
                                <td class="text-end font-monospace col-deductions text-muted dynamic-deduction-cell" data-key="<?= htmlspecialchars($d) ?>"> 
                                    <?= $amount > 0 ? '₱ '.number_format($amount, 2) : '<span class="opacity-25">0.00</span>' ?>
                                </td>
                            <?php endforeach ?>
                            
                            <?php $total_deductions_all += $row_total_deduction; ?>

                            <td class="text-end font-monospace col-deductions tax">₱ <?= number_format($row_tax, 2) ?></td>
                            <td class="text-end border-end font-monospace fw-semibold text-danger total-ded col-deductions">₱ <?= number_format($row_total_deduction, 2) ?></td>
                            <td class="text-center font-monospace fw-bold text-primary netpay" style="font-size: 1.15rem;">₱ <?= number_format((float)($p->net_pay ?? 0), 2) ?></td>

                            <td class="text-center pe-4 text-nowrap">
                                <?php if ($this->session->userdata('receiver_role') === 'accounting'): ?>
                                    
                                    <button type="button" 
                                            class="btn btn-light border btn-edit text-primary me-1" 
                                            data-id="<?= htmlspecialchars($p->midyear_id ?? '') ?>" 
                                            data-name="<?= htmlspecialchars($p->name ?? '') ?>"
                                            data-basic="<?= htmlspecialchars($p->basic_salary ?? 0) ?>"
                                            data-gross="<?= htmlspecialchars($p->gross_pay ?? 0) ?>"
                                            data-tax="<?= htmlspecialchars($p->tax ?? 0) ?>"
                                            data-deductions='<?= htmlspecialchars(json_encode($less_values), ENT_QUOTES, 'UTF-8') ?>'
                                            onclick="openEditModal(this)"
                                            data-bs-toggle="tooltip" 
                                            title="Edit Row">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- UPDATED: Button turns solid blue with a filled icon if there are remarks -->
                                    <button type="button" 
                                            class="btn border btn-remark <?= $has_remarks ? 'btn-info text-white shadow-sm' : 'btn-light text-info' ?>" 
                                            data-id="<?= htmlspecialchars($p->midyear_id ?? '') ?>" 
                                            data-name="<?= htmlspecialchars($p->name ?? '') ?>"
                                            data-remarks="<?= htmlspecialchars($p->midyear_remarks ?? '') ?>" 
                                            onclick="openRemarksModal(this)"
                                            data-bs-toggle="tooltip" 
                                            title="<?= $has_remarks ? 'View/Edit Remarks' : 'Add Remarks' ?>">
                                        <i class="bi <?= $has_remarks ? 'bi-chat-text-fill' : 'bi-chat-text' ?>"></i>
                                    </button>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($deduction_names) + 7 ?>" class="text-center py-5 text-muted">No payroll records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end ps-4 border-end pe-3 fw-bold">GRAND TOTAL</td>
                        
                        <td class="text-end font-monospace text-muted">₱ <?= number_format($total_basic, 2) ?></td>
                        <td class="text-end border-end font-monospace fw-bold text-success">₱ <?= number_format($total_gross, 2) ?></td>
                        
                        <!-- DYNAMIC DEDUCTION TOTALS -->
                        <?php foreach ($deduction_names as $d): ?>
                            <td class="text-end font-monospace text-muted">₱ <?= number_format($deduction_totals[$d], 2) ?></td>
                        <?php endforeach ?>
                        
                        <td class="text-end font-monospace text-muted">₱ <?= number_format($total_tax, 2) ?></td>
                        <td class="text-end border-end font-monospace fw-bold text-danger">₱ <?= number_format($total_deductions_all, 2) ?></td>
                        
                        <td class="text-center font-monospace text-primary fs-5 fw-bold">₱ <?= number_format($total_net, 2) ?></td>
                        <td class="pe-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="editRowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Payroll Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPayrollForm">
                    <input type="hidden" id="editRowId" name="id">
                    <!-- Hidden field to store the unedited loans for accurate math -->
                    <input type="hidden" id="editStaticDeductions" value="0">
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Employee Name</label>
                        <input type="text" class="form-control bg-light fw-bold" id="editEmpName" readonly>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Basic Salary</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₱</span>
                                <input type="number" step="0.01" class="form-control calc-trigger" name="basic_salary" id="editBasicPay">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Gross Pay</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₱</span>
                                <input type="number" step="0.01" class="form-control calc-trigger text-success fw-bold" name="gross_pay" id="editGrossPay">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold text-uppercase">W/ Tax</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₱</span>
                                <input type="number" step="0.01" class="form-control calc-trigger text-danger" name="tax" id="editTax">
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Box -->
                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between mb-2 small text-muted fw-bold">
                            <span>Total Deductions (Tax + Existing Loans):</span>
                            <span id="previewTotalDed" class="text-danger">₱ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between fs-5 fw-bold text-primary border-top pt-2">
                            <span>Net Pay Preview:</span>
                            <span id="previewNet">₱ 0.00</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="saveRowChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="remarksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-chat-text text-info me-2"></i>Add / Edit Remarks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="remarksForm">
                    <input type="hidden" id="remarkRowId" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Employee Name</label>
                        <input type="text" class="form-control bg-light fw-bold" id="remarkEmpName" readonly>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Remarks / Notes</label>
                        <textarea class="form-control" id="remarkText" rows="4" placeholder="Type remarks here..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white rounded-pill px-4 fw-semibold" onclick="saveRemarks()">Save Remarks</button>
            </div>
        </div>
    </div>
</div>


<script>
    // 1. Initialize tooltips and Accessibility fixes
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $('#editRowModal').on('hide.bs.modal', function () {
        if (document.activeElement) document.activeElement.blur();
    });

    // 2. Formatting Helpers
    function parsePeso(value) {
        if (!value) return 0;
        return parseFloat(value.toString().replace(/[₱,\s]/g, '')) || 0;
    }

    function formatPeso(value) {
        return '₱ ' + Number(value).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // 3. Attach Event Listeners on Page Load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotals();
        document.querySelectorAll('.calc-trigger').forEach(input => {
            input.addEventListener('input', calculateNetPreview);
        });
    });

    // 4. Modal Live Auto-Compute
    function calculateNetPreview() {
        const gross = parseFloat(document.getElementById('editGrossPay').value) || 0;
        const tax = parseFloat(document.getElementById('editTax').value) || 0;
        const staticDeductions = parseFloat(document.getElementById('editStaticDeductions').value) || 0;
        
        // Total Deductions = Editable Tax + Non-Editable Existing Loans
        const totalDed = staticDeductions + tax;
        const net = gross - totalDed;
        
        document.getElementById('previewTotalDed').textContent = formatPeso(totalDed);
        document.getElementById('previewNet').textContent = formatPeso(net);
    }

    // 5. Open Edit Modal
    function openEditModal(button) {
        const id = $(button).data('id');
        const name = $(button).data('name');
        const basic = parseFloat($(button).data('basic')) || 0;
        const gross = parseFloat($(button).data('gross')) || 0;
        const tax = parseFloat($(button).data('tax')) || 0;
        
        // Calculate the sum of all uneditable loans from the JSON data
        let deds = {};
        let staticDedsTotal = 0;
        try {
            deds = $(button).data('deductions');
            if (typeof deds === 'string') deds = JSON.parse(deds);
            for (let key in deds) {
                staticDedsTotal += parseFloat(deds[key]) || 0;
            }
        } catch(e) { staticDedsTotal = 0; }

        $('#editRowId').val(id);
        $('#editEmpName').val(name);
        $('#editBasicPay').val(basic.toFixed(2));
        $('#editGrossPay').val(gross.toFixed(2));
        $('#editTax').val(tax.toFixed(2));
        $('#editStaticDeductions').val(staticDedsTotal); // Store the uneditable loans
        
        calculateNetPreview();
        
        const editModal = new bootstrap.Modal(document.getElementById('editRowModal'));
        editModal.show();
    }

    // 6. Save Changes
    function saveRowChanges() {
        if (document.activeElement) document.activeElement.blur(); // A11y fix

        const id = $('#editRowId').val();
        const basic = parseFloat($('#editBasicPay').val()) || 0;
        const gross = parseFloat($('#editGrossPay').val()) || 0;
        const tax = parseFloat($('#editTax').val()) || 0;
        const staticDeductions = parseFloat($('#editStaticDeductions').val()) || 0;
        
        const totalDed = staticDeductions + tax;
        const netPay = gross - totalDed;

        // AJAX Request to CodeIgniter
        // Make sure you adjust this URL to match your controller exactly!
        let payload = {
            id: id,
            basic_salary: basic,
            gross_pay: gross,
            tax: tax
            // We do NOT send 'less' because we didn't edit the other deductions
        };

        // Add CSRF Token if needed by your CodeIgniter setup:
        // payload['<?= $this->security->get_csrf_token_name() ?>'] = $('input[name="<?= $this->security->get_csrf_token_name() ?>"]').val();

        $.ajax({
            url: '<?= base_url("receiver/update_payrollmidyear_row") ?>',
            type: 'POST',
            data: payload,
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    const button = $(`button[data-id='${id}']`);
                    const row = button.closest('tr');
                    
                    // Update table UI
                    row.find('.basic').text(formatPeso(basic));
                    row.find('.gross').text(formatPeso(gross));
                    row.find('.tax').text(formatPeso(tax));
                    row.find('.total-ded').text(formatPeso(totalDed));
                    row.find('.netpay').text(formatPeso(netPay));

                    // Update button data
                    button.data('basic', basic);
                    button.data('gross', gross);
                    button.data('tax', tax);

                    updateTotals();
                    $('#editRowModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire('Error', response.message || 'Update failed', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server failed to process the request.', 'error');
            }
        });
    }

    // 7. Dynamic Totals Calculator
    // 7. Dynamic Totals Calculator
    function updateTotals() {
        let totalBasic = 0, totalGross = 0, totalTax = 0, totalDedAll = 0, totalNetPay = 0;
        let dynamicTotals = {};

        document.querySelectorAll('#savedPayrollTable tbody tr').forEach(row => {
            if(!row.querySelector('.basic')) return; 

            totalBasic += parsePeso(row.querySelector('.basic').textContent);
            totalGross += parsePeso(row.querySelector('.gross').textContent);
            totalTax += parsePeso(row.querySelector('.tax').textContent);
            totalDedAll += parsePeso(row.querySelector('.total-ded').textContent);
            totalNetPay += parsePeso(row.querySelector('.netpay').textContent);

            row.querySelectorAll('.dynamic-deduction-cell').forEach(cell => {
                const key = cell.getAttribute('data-key');
                const val = parsePeso(cell.textContent);
                dynamicTotals[key] = (dynamicTotals[key] || 0) + val;
            });
        });

        // FIXED: Select the <tr> inside the <tfoot>, not just the <tfoot> tag itself
        const tfootRow = document.querySelector('#savedPayrollTable tfoot tr');
        
        if(tfootRow && tfootRow.cells.length > 2) {
            tfootRow.cells[1].textContent = formatPeso(totalBasic);
            tfootRow.cells[2].textContent = formatPeso(totalGross);
            
            let colIndex = 3;
            <?php foreach ($deduction_names as $d): ?>
                // Make sure dynamicTotals has a value to prevent NaN
                tfootRow.cells[colIndex].textContent = formatPeso(dynamicTotals["<?= htmlspecialchars($d) ?>"] || 0);
                colIndex++;
            <?php endforeach; ?>
            
            tfootRow.cells[colIndex].textContent = formatPeso(totalTax);
            tfootRow.cells[colIndex+1].textContent = formatPeso(totalDedAll);
            tfootRow.cells[colIndex+2].textContent = formatPeso(totalNetPay);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Find all input fields that have the "calc-trigger" class
    const calculationInputs = document.querySelectorAll('.calc-trigger');

    // 2. Tell the browser to listen for any typing ('input') in those fields
    calculationInputs.forEach(function(inputField) {
        inputField.addEventListener('input', function() {
            
            // --- THE MATH ---
            
            // Get the numbers from the inputs (default to 0 if empty)
            let gross = parseFloat(document.getElementById('editGrossPay').value) || 0;
            let tax = parseFloat(document.getElementById('editTax').value) || 0;
            let staticDeductions = parseFloat(document.getElementById('editStaticDeductions').value) || 0;
            
            // Calculate Deductions
            let totalDed = staticDeductions + tax;
            
            // Calculate Net Pay
            let net = gross - totalDed;
            
            // Update the screen instantly
            // (Assuming you have a formatting function like formatPeso)
            document.getElementById('previewTotalDed').textContent = '₱ ' + totalDed.toFixed(2);
            document.getElementById('previewNet').textContent = '₱ ' + net.toFixed(2);
            
        });
    });

});

// Accessibility fix for the remarks modal
    $('#remarksModal').on('hide.bs.modal', function () {
        if (document.activeElement) document.activeElement.blur();
    });

    // 1. Open the Remarks Modal
    function openRemarksModal(button) {
        const id = $(button).data('id');
        const name = $(button).data('name');
        const remarks = $(button).data('remarks') || ""; 

        // Populate the modal fields
        $('#remarkRowId').val(id);
        $('#remarkEmpName').val(name);
        $('#remarkText').val(remarks);
        
        // Show modal
        const remarksModal = new bootstrap.Modal(document.getElementById('remarksModal'));
        remarksModal.show();
    }

    // 2. Save Remarks via AJAX
    function saveRemarks() {
        if (document.activeElement) document.activeElement.blur();

        const id = $('#remarkRowId').val();
        const remarks = $('#remarkText').val();

        // Prepare data
        let payload = {
            id: id,
            remarks: remarks
        };


        $.ajax({
            url: '<?= base_url("receiver/update_remarks_midyear") ?>',
            type: 'POST',
            data: payload,
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    const button = $(`button.btn-remark[data-id='${id}']`);
                    button.data('remarks', remarks);
                    if(remarks.trim() !== "") {
                        button.removeClass('btn-light').addClass('btn-info text-white');
                    } else {
                        button.removeClass('btn-info text-white').addClass('btn-light text-info');
                    }

                    $('#remarksModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Remarks Saved',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire('Error', response.message || 'Failed to save remarks', 'error');
                }
            },
            error: function(xhr, status, error) {
                // 1. Log the full error to your browser's console
                console.error("Raw Error Response:", xhr.responseText);

                // 2. Show a more detailed alert
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error: ' + xhr.status, // e.g., 403, 404, or 500
                    text: 'The detailed error has been logged to the console. Press F12 to view it.',
                });
                
                // Optional: If you want to force the error to show on your screen immediately 
                // (Warning: this will replace your current page with the error screen)
                // document.write(xhr.responseText); 
            }
        });
    }

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

                // 1. Call the backend to process the data (expects JSON)
                $.post("<?= base_url('payroll/process_payslips/') ?>" + period_id, function(res) {
                    if(res.status === 'success') {
                        
                        // 2. Show success message
                        Swal.fire('Success!', res.message, 'success').then(() => {
                            // 3. AFTER they click OK, open the layout in a new tab
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
</body>
</html>