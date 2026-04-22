<style>
    .x-small { font-size: 0.75rem; }
    .bg-maroon { background-color: #6b0f1a; color: white; }
    .btn-maroon { background-color: #6b0f1a; color: white; }
    .btn-maroon:hover { background-color: #4a0a0b; color: white; }
    .stat-box { transition: transform 0.2s; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .stat-box:hover { transform: translateY(-3px); }
    /* Select2 Bootstrap 5 Compatibility */
    .select2-container--default .select2-selection--single { height: 38px; border: 1px solid #dee2e6; border-radius: 0.375rem; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; padding-left: 12px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
</style>

<?php
$deductionTotals = [];
if (!empty($deduction)) {
    foreach ($deduction as $row) {
        $name = $row->deduction_name;
        if (!isset($deductionTotals[$name])) {
            $deductionTotals[$name] = ['total_amount' => 0, 'total_monthly' => 0, 'count' => 0];
        }
        $deductionTotals[$name]['total_amount'] += (float) $row->amount;
        $deductionTotals[$name]['total_monthly'] += (float) $row->monthly_deduction;
        $deductionTotals[$name]['count']++;
    }
}
?>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Employee Specific Deductions</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-muted mb-0">RECORD MANAGEMENT</h6>
            <button type="button" class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addFundModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add Entry
            </button>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="periodTable" class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr class="text-muted x-small text-uppercase">
                                        <th class="ps-4">Employee Name</th>
                                        <th>Deduction Info</th>
                                        <th>Financials</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($deduction)): foreach($deduction as $row): 
                                        $today = date('Y-m-d');
                                        $statusLabel = '<span class="badge bg-success">Active</span>';
                                        if ($today > $row->end_period) $statusLabel = '<span class="badge bg-secondary">Completed</span>';
                                        if ($today < $row->start_period) $statusLabel = '<span class="badge bg-info">Upcoming</span>';
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">
                                                <?= htmlspecialchars($row->last_name . ', ' . $row->name) ?>
                                            </div>
                                            <small class="text-muted">ID: <?= $row->employee_id ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-primary"><?= $row->deduction_name ?></div>
                                            <div class="x-small text-muted"><?= $row->deduction_type ?></div>
                                        </td>
                                        <td>
                                            <div class="x-small">Total: <strong>₱<?= number_format($row->amount, 2) ?></strong></div>
                                            <div class="x-small text-success">Monthly: <strong>₱<?= number_format($row->monthly_deduction, 2) ?></strong></div>
                                        </td>
                                        <td>
                                            <div class="x-small text-muted"><i class="bi bi-calendar-check me-1"></i><?= date('M Y', strtotime($row->start_period)) ?></div>
                                            <div class="x-small text-muted"><i class="bi bi-calendar-x me-1"></i><?= date('M Y', strtotime($row->end_period)) ?></div>
                                        </td>
                                        <td><?= $statusLabel ?></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light border rounded-circle shadow-sm text-danger" 
                                                    onclick="confirmDelete(<?= $row->employee_loan_id ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Deduction Portfolio</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($deductionTotals)): ?>
                            <?php foreach ($deductionTotals as $name => $totals): ?>
                                <div class="stat-box border rounded-3 p-3 bg-light mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-dark small"><?= htmlspecialchars($name) ?></span>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill"><?= $totals['count'] ?> Pax</span>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <div class="x-small text-muted text-uppercase">Total Volume</div>
                                            <div class="fw-bold text-danger">₱<?= number_format($totals['total_amount'], 0) ?></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="x-small text-muted text-uppercase">Monthly Flow</div>
                                            <div class="fw-bold text-success">₱<?= number_format($totals['total_monthly'], 0) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-folder2-open display-4 text-muted opacity-25"></i>
                                <p class="text-muted small mt-2">No active financial summaries.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addFundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('payroll/add_employee_loan') ?>" method="POST" class="w-100">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-maroon text-white">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>New Deduction Entry</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label x-small fw-bold text-muted text-uppercase">1. Select Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">-- Search Employee Name --</option>
                            <?php 
                                usort($employees, fn($a, $b) => strcmp($a->last_name, $b->last_name));
                                foreach($employees as $emp): 
                            ?>
                                <option value="<?= $emp->employee_id ?>">
                                    <?= strtoupper($emp->last_name . ', ' . $emp->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">2. Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">₱</span>
                                <input type="number" name="amount" class="form-control" step="0.01" required placeholder="0.00">
                                <input type="hidden" name="deduction_id" value="<?=$deduction_id?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">3. Monthly Rate</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">₱</span>
                                <input type="number" name="monthly_deduction" class="form-control" step="0.01" required placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">4. Start Period</label>
                            <input type="date" name="start_period" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">5. End Period</label>
                            <input type="date" name="end_period" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-maroon px-4 rounded-pill shadow-sm">Save Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // DataTable
    $('#periodTable').DataTable({
        pageLength: 10, 
        order: [[0, 'asc']],
        responsive: true,
        language: { search: "", searchPlaceholder: "Search records..." }
    });

    // Select2
    $('#employee_id').select2({
        dropdownParent: $('#addFundModal'),
        width: '100%',
        placeholder: "-- Search Employee Name --"
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Remove this entry?',
        text: "This deduction will no longer be applied to the employee's payroll.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b0f1a',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('payroll/delete_loan/') ?>" + id;
        }
    });
}
</script>