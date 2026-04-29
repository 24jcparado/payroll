<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $period ?? 'Hazard Payroll View' ?></title>

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
        max-width: 1600px; 
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
        border-top: 5px solid #800000; /* EVSU Maroon */
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
        font-size: 1.1rem !important; 
        letter-spacing: 0.5px; 
    }
    
    .table-custom tfoot .font-monospace {
        font-size: 1.25rem !important;
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
    
    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
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
                <?= $payroll_type ?? 'Hazard Pay' ?>
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
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i><?= $payroll_type ?? 'Hazard Pay' ?> Register</h6>
            <div>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 hover-elevate me-2">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <button class="btn btn-sm btn-primary rounded-pill px-3 hover-elevate" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

        <?php
        // SAFELY GATHER DEDUCTION NAMES
        $deduction_names = [];
        if (!empty($payrolls)) {
            foreach ($payrolls as $payroll) {
                // Support both arrays and objects
                $p = is_array($payroll) ? (object) $payroll : $payroll; 
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
        ?>

        <div class="table-responsive">
            <?php
            // INITIALIZE SAFE TOTALS
            $total_basic = 0; $total_bonus = 0; $total_tax = 0; $total_net = 0; $total_deductions = 0;
            $deduction_totals = array_fill_keys($deduction_names, 0);

            if (!empty($payrolls)) {
                foreach ($payrolls as $payroll) {
                    $p = is_array($payroll) ? (object) $payroll : $payroll;

                    $total_basic += (float) ($p->basic_salary ?? 0);
                    $total_bonus += (float) ($p->gross_pay ?? 0);
                    $total_tax += (float) ($p->tax ?? 0);
                    $total_net += (float) ($p->net_pay ?? 0);
                    $total_deductions += (float) ($p->total_deductions ?? 0);

                    if (!empty($p->less)) {
                        $items = explode(',', $p->less);
                        foreach ($items as $item) {
                            $parts = explode(':', $item);
                            if (count($parts) == 2) {
                                $name = trim($parts[0]);
                                $amount = (float) trim($parts[1]);
                                if(isset($deduction_totals[$name])){
                                    $deduction_totals[$name] += $amount;
                                }
                            }
                        }
                    }
                }
            }
            ?>

            <table class="table table-custom align-middle" id="savedPayrollTable">
                <thead class="sticky-top">
                    <tr>
                        <th rowspan="2" class="ps-4 border-end">Employee Information</th>
                        <th colspan="2" class="text-center border-end text-success">Earnings</th>
                        <th colspan="<?= count($deduction_names) + 2 ?>" class="text-center border-end text-danger">Deductions</th>
                        <th rowspan="2" class="text-center border-end text-primary">Total Net Pay</th>
                        <th rowspan="2" class="text-center pe-4">Action</th>
                    </tr>
                    <tr>
                        <th class="text-end col-earnings">Basic Salary</th>
                        <th class="text-end border-end col-earnings text-success">Gross Hazard Pay</th>
                        
                        <?php foreach ($deduction_names as $d): ?>
                            <th class="text-end col-deductions opacity-75" style="font-size: 0.7rem;"><?= htmlspecialchars($d) ?></th>
                        <?php endforeach ?>
                        
                        <th class="text-end col-deductions">W/ Tax</th>
                        <th class="text-end border-end col-deductions text-danger">Total Ded.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($payrolls)): ?>
                        <?php foreach ($payrolls as $payroll): ?> 
                        <?php 
                            $p = is_array($payroll) ? (object) $payroll : $payroll; 
                            
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
                        ?>
                        <tr>
                            <td class="ps-4 border-end">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($p->name ?? '') ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($p->position ?? '') ?></div>
                            </td>
                            
                            <td class="text-end font-monospace col-earnings">₱ <?= number_format((float)($p->basic_salary ?? 0), 2) ?></td>
                            <td class="text-end border-end font-monospace fw-semibold text-success amount-accrued col-earnings">
                                ₱ <?= number_format((float)($p->gross_pay ?? 0), 2) ?>
                                <br>
                                <small class="text-muted fw-normal" style="font-family: system-ui, -apple-system, sans-serif; font-size: 0.7rem; letter-spacing: normal;">
                                    <?= htmlspecialchars($p->remarks ?? '') ?>
                                </small>
                            </td>
                            
                            <?php foreach ($deduction_names as $d): ?>
                                <td class="text-end font-monospace col-deductions text-muted"> 
                                    <?= isset($less_values[$d]) && $less_values[$d] > 0 ? '₱ '.number_format($less_values[$d], 2) : '<span class="opacity-25">0.00</span>' ?>
                                </td>
                            <?php endforeach ?>
                            
                            <td class="text-end font-monospace col-deductions tax">₱ <?= number_format((float)($p->tax ?? 0), 2) ?></td>
                            <td class="text-end border-end font-monospace fw-semibold text-danger tax-amount col-deductions">₱ <?= number_format((float)($p->total_deductions ?? 0), 2) ?></td>
                            
                            <td class="text-end border-end font-monospace fw-bold text-primary netpay" style="font-size: 1.25rem;">₱ <?= number_format((float)($p->net_pay ?? 0), 2) ?></td>

                            <td class="text-center pe-4">
                                <button type="button" 
                                        class="btn btn-light border btn-edit text-primary" 
                                        data-id="<?= htmlspecialchars($p->id ?? '') ?>" 
                                        data-name="<?= htmlspecialchars($p->name ?? '') ?>"
                                        data-net="<?= htmlspecialchars($p->net_pay ?? 0) ?>"
                                        onclick="openEditModal(this)"
                                        data-bs-toggle="tooltip" 
                                        title="Edit Row">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= 6 + count($deduction_names) ?>" class="text-center py-5 text-muted">No payroll records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end ps-4 border-end pe-3 fw-bold">GRAND TOTAL</td>
                        
                        <td class="text-end font-monospace text-muted">₱ <?= number_format($total_basic, 2) ?></td>
                        <td class="text-end border-end font-monospace fw-bold text-success" id="total_amount_accrued">₱ <?= number_format($total_bonus, 2) ?></td>
                        
                        <?php foreach ($deduction_names as $d): ?>
                            <td class="text-end font-monospace text-muted">₱ <?= number_format($deduction_totals[$d], 2) ?></td>
                        <?php endforeach ?>
                        
                        <td class="text-end font-monospace" id="total_tax">₱ <?= number_format($total_tax, 2) ?></td>
                        <td class="text-end border-end font-monospace fw-bold text-danger" id="total_deductions_all">₱ <?= number_format($total_deductions, 2) ?></td>
                        
                        <td class="text-end border-end font-monospace text-primary fs-5 fw-bold" id="total_netpay">₱ <?= number_format($total_net, 2) ?></td>
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
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Employee Name</label>
                        <input type="text" class="form-control bg-light" id="editEmpName" readonly>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Basic Pay</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₱</span>
                                <input type="number" step="0.01" class="form-control" name="basic_salary" id="editBasicPay">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Tax</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₱</span>
                                <input type="number" step="0.01" class="form-control" name="tax" id="editTax">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0 small border-0">
                        <i class="bi bi-info-circle-fill me-1"></i> Saving changes will automatically recalculate Net Pay.
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

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Formatting Helpers
    function parsePeso(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[₱,\s]/g, '')) || 0;
    }

    function formatPeso(value) {
        return '₱ ' + value.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Dynamic Totals Calculator (Re-written for Hazard Pay columns)
    function updateTotals() {
        let totalTax = 0,
            totalAccrued = 0,
            totalDedAll = 0,
            totalNetPay = 0;

        document.querySelectorAll('#savedPayrollTable tbody tr').forEach(row => {
            if(!row.querySelector('.tax')) return; // Skip if empty row

            totalTax += parsePeso(row.querySelector('.tax').textContent);
            totalAccrued += parsePeso(row.querySelector('.amount-accrued').textContent);
            totalDedAll += parsePeso(row.querySelector('.tax-amount').textContent);
            totalNetPay += parsePeso(row.querySelector('.netpay').textContent);
        });

        // Apply formatted totals to the footer
        document.getElementById('total_tax').textContent = formatPeso(totalTax);
        document.getElementById('total_amount_accrued').textContent = formatPeso(totalAccrued);
        document.getElementById('total_deductions_all').textContent = formatPeso(totalDedAll);
        document.getElementById('total_netpay').textContent = formatPeso(totalNetPay);
    }

    // Run calculation when DOM is ready
    document.addEventListener('DOMContentLoaded', updateTotals);

    // --- MODAL FUNCTIONS ---
    function openEditModal(button) {
        const id = $(button).data('id');
        const name = $(button).data('name');
        
        $('#editRowId').val(id);
        $('#editEmpName').val(name);
        
        const editModal = new bootstrap.Modal(document.getElementById('editRowModal'));
        editModal.show();
    }

    function saveRowChanges() {
        $('#editRowModal').modal('hide');
        
        Swal.fire({
            icon: 'success',
            title: 'Updated Successfully',
            text: 'The payroll entry has been updated.',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>

</body>
</html>