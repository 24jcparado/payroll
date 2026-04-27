<style>
    .bg-maroon { background-color: #6b0f1a; color: white; }
    .btn-maroon { background-color: #6b0f1a; color: white; }
    .btn-maroon:hover { background-color: #4a0a0b; color: white; }
    .x-small { font-size: 0.75rem; }
    .card-header-custom { background-color: #6b0f1a; color: white; }
    .badge-mandatory { background-color: #dc3545; color: white; font-weight: 600; }
    .badge-optional { background-color: #198754; color: white; font-weight: 600; }
    .table-hover tbody tr:hover { background-color: rgba(107, 15, 26, 0.03); cursor: pointer; }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Deduction Settings</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-maroon rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFundModal">
                <i class="bi bi-plus-lg me-1"></i> Create New Deduction
            </button>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header card-header-custom py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Institutional & Mandatory Deductions</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr class="text-muted x-small text-uppercase">
                                        <th class="ps-4">Deduction Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">System Tag</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($deductions as $row): if($row->is_mandatory == 1): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= $row->deduction_name ?></td>
                                        <td><span class="text-muted"><?= $row->deduction_type ?></span></td>
                                        <td>
                                            <?php if($row->is_active == 1): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">Active</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-light text-muted border px-3">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge badge-mandatory px-2 py-1"><i class="bi bi-lock-fill me-1"></i> MANDATORY</span>
                                        </td>
                                    </tr>
                                    <?php endif; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-collection-fill me-2 text-primary"></i>Other / Optional Deductions</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="otherTable" class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr class="text-muted x-small text-uppercase">
                                        <th class="ps-4">Deduction Name</th>
                                        <th>Type</th>
                                        <th>Payroll Category</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($deductions as $row): if($row->is_mandatory == 0): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= $row->deduction_name ?></td>
                                        <td><span class="text-muted"><?= $row->deduction_type ?></span></td>
                                        <td><small class="fw-semibold text-primary"><?= $row->applicable ?></small></td>
                                        <td>
                                            <?php if($row->is_active == 1): ?>
                                                <i class="bi bi-check-circle-fill text-success me-1"></i> Active
                                            <?php else: ?>
                                                <i class="bi bi-dash-circle text-muted me-1"></i> Disabled
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="<?= base_url('payroll/add_employee/'.$row->deduction_id) ?>" class="btn btn-sm btn-light border" title="Manage Employees"><i class="bi bi-people"></i></a>
                                                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editDeductionModal<?= $row->deduction_id ?>"><i class="bi bi-pencil"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold">Deduction Logic Guide</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <span class="badge badge-mandatory me-3 mt-1"><i class="bi bi-lock-fill"></i></span>
                            <div>
                                <h6 class="mb-0 small fw-bold">Mandatory</h6>
                                <p class="text-muted x-small mb-0">Applied to all employees automatically based on GSIS/PhilHealth tables.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <span class="badge badge-optional me-3 mt-1"><i class="bi bi-check-circle-fill"></i></span>
                            <div>
                                <h6 class="mb-0 small fw-bold">Optional / Loan</h6>
                                <p class="text-muted x-small mb-0">Only applied to specific employees who have active ledger entries.</p>
                            </div>
                        </div>
                        <hr class="dashed">
                        <div class="bg-light p-3 rounded-3">
                            <h6 class="x-small fw-bold text-uppercase text-muted mb-2">Current System Status</h6>
                            <div class="d-flex justify-content-between small">
                                <span>Total Active Deductions:</span>
                                <span class="fw-bold text-success"><?= count(array_filter($deductions, fn($d) => $d->is_active == 1)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addFundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?php echo base_url('payroll/add_deduction'); ?>" method="POST" class="w-100">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-maroon text-white border-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Configure Deduction</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label x-small fw-bold text-muted">DEDUCTION NAME</label>
                        <input type="text" name="deduction_name" class="form-control" placeholder="e.g. Landbank Loan" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted">TYPE</label>
                            <select name="deduction_type" class="form-select" required>
                                <option value="Contribution">Contribution</option>
                                <option value="Loan">Loan</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label x-small fw-bold text-muted">IS MANDATORY?</label>
                            <select name="is_mandatory" class="form-select">
                                <option value="0">No (Optional)</option>
                                <option value="1">Yes (System-wide)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label x-small fw-bold text-muted">APPLICABLE PAYROLL CATEGORY</label>
                        <select name="applicable" class="form-select" required>
                            <option value="GENERAL PAYROLL">GENERAL PAYROLL</option>
                            <option value="MID-YEAR BONUS">MID-YEAR BONUS</option>
                            <option value="YEAR-END BONUS">YEAR-END BONUS</option>
                            <option value="DAILY WAGE">DAILY WAGE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-maroon px-4 rounded-pill">Create Deduction</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#otherTable').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 25],
        order: [[0, 'asc']],
        responsive: true,
        language: { search: "" , searchPlaceholder: "Filter deductions..." }
    });
});
</script>