<?php
$deductionTotals = [];

if (!empty($deduction)) {
    foreach ($deduction as $row) {
        $name = $row->deduction_name;

        if (!isset($deductionTotals[$name])) {
            $deductionTotals[$name] = [
                'total_amount' => 0,
                'total_monthly' => 0
            ];
        }

        $deductionTotals[$name]['total_amount'] += (float) $row->amount;
        $deductionTotals[$name]['total_monthly'] += (float) $row->monthly_deduction;
    }
}
?>
<!-- CONTENT -->
    <main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Add Payroll Period Button -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFundModal">
                <i class="bi bi-plus-lg me-1"></i> Add Employee
            </button>
        </div>
        
        <div class="row mt-2">
            <div class="col-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text me-2"></i>Deductions</h5>
                        <div class="table-responsive">
                            <table id="periodTable" class="text-sm table table-sm text-nowrap small">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-wallet2 me-1 text-primary"></i> Employees</th>
                                        <th><i class="bi bi-cash-stack me-1 text-success"></i> Deduction</th>
                                        <th><i class="bi bi-cash-stack me-1 text-success"></i> Type</th>
                                        <th><i class="bi bi-cash-stack me-1 text-success"></i> Amount</th>
                                        <th><i class="bi bi-calendar-event me-1 text-secondary"></i> Monthly Deduction</th>
                                        <th><i class="bi bi-calendar-event me-1 text-secondary"></i> Start Date</th>
                                        <th><i class="bi bi-calendar-event me-1 text-secondary"></i> End Date</th>
                                        <th><i class="bi bi-calendar-event me-1 text-secondary"></i> Status</th>
                                        <th class="text-center"><i class="bi bi-gear me-1"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($deduction)): ?>
                                        <?php $no = 1; foreach($deduction as $row): ?>
                                            <tr>
                                                <td>
                                                    <?= htmlspecialchars(
                                                        $row->last_name . ', ' . 
                                                        $row->name . 
                                                        (!empty($row->middle_name) ? ' ' . strtoupper(substr($row->middle_name, 0, 1)) . '.' : '') . 
                                                        (!empty($row->ext) ? ' ' . $row->ext : '')
                                                    ) ?>
                                                </td>
                                                <td><?= $row->deduction_name?></td>
                                                <td><?= $row->deduction_type?></td>
                                                <td><?= $row->amount?></td>
                                                <td><?= $row->monthly_deduction?></td>
                                                <td><?= $row->start_period?></td>
                                                <td><?= $row->end_period?></td>
                                                <td>
                                                    
                                                </td>
                                                <td>
                                                    <a class="dropdown-item" href="<?= base_url('payroll/view/'.$row->employee_loan_id) ?>"> <i class="bi bi-trash me-2 text-danger"></i> Delete </a>     
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Legend</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($deductionTotals)): ?>
                            <div class="row mb-3 g-2">
                                <?php foreach ($deductionTotals as $name => $totals): ?>
                                    <div class="col-md-12 col-lg-12">
                                        <div class="border rounded p-3 bg-light h-100">
                                            <div class="fw-bold text-primary mb-1">
                                                <i class="bi bi-cash-stack me-1"></i> <?= htmlspecialchars($name) ?>
                                            </div>

                                            <div class="small text-muted">Total Loan Amount</div>
                                            <div class="fw-bold text-danger">
                                                ₱ <?= number_format($totals['total_amount'], 2) ?>
                                            </div>

                                            <div class="small text-muted mt-2">Total Monthly Deduction</div>
                                            <div class="fw-bold text-success">
                                                ₱ <?= number_format($totals['total_monthly'], 2) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </main>

<!-- ADD EMPLOYEE LOAN MODAL -->
<div class="modal fade" id="addFundModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('payroll/add_employee_loan') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addEmployeeModalLabel">
                        <i class="bi bi-plus-lg me-1"></i> Add Employee Deduction
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Employee -->
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            <?php
                                usort($employees, function($a, $b) {
                                    $nameA = strtolower($a->last_name . ' ' . $a->name);
                                    $nameB = strtolower($b->last_name . ' ' . $b->name);
                                    return strcmp($nameA, $nameB);
                                });
                            ?>
                            <?php foreach($employees as $emp): ?>
                                <option value="<?= $emp->employee_id ?>">
                                    <?= htmlspecialchars(
                                        $emp->last_name . ', ' . 
                                        $emp->name . 
                                        (!empty($emp->middle_name) ? ' ' . strtoupper(substr($emp->middle_name, 0, 1)) . '.' : '') . 
                                        (!empty($emp->ext) ? ' ' . $emp->ext : '')
                                    ) ?>
                                    </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Deduction -->
                   

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                        <input type="hidden" name="deduction_id" value="<?=$deduction_id?>" class="form-control" step="0.01" required>
                    </div>

                    <!-- Monthly Deduction -->
                    <div class="mb-3">
                        <label for="monthly_deduction" class="form-label">Monthly Deduction</label>
                        <input type="number" name="monthly_deduction" id="monthly_deduction" class="form-control" step="0.01" required>
                    </div>

                    <!-- Start Period -->
                    <div class="mb-3">
                        <label for="start_period" class="form-label">Start Date</label>
                        <input type="date" name="start_period" id="start_period" class="form-control" required>
                    </div>

                    <!-- End Period -->
                    <div class="mb-3">
                        <label for="end_period" class="form-label">End Date</label>
                        <input type="date" name="end_period" id="end_period" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#periodTable').DataTable({
        pageLength: 10, 
        lengthMenu: [5, 10, 25, 50],
        order: [[1, 'desc']],
        responsive: true
    });
});

$(document).ready(function() {
    $('#employee_id').select2({
        placeholder: "-- Select Employee --",
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addFundModal')
    });
});
</script>


