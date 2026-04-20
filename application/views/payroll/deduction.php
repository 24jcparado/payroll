<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFundModal">
        <i class="bi bi-plus-lg me-1"></i> Add Deduction
    </button>

    <!-- Example Table -->
    <div class="row mt-2">
        <div class="col-8">

            <!-- ===================== MANDATORY DEDUCTIONS ===================== -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex align-items-center"">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Mandatory Deductions
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mandatoryTable" class="table table-sm table-striped text-nowrap small">
                            <thead class="table-light">
                                <tr>
                                    <th>Deduction</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>GSIS</td>
                                    <td>Contribution</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>PhilHealth</td>
                                    <td>Contribution</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Pagibig</td>
                                    <td>Contribution</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td></td>
                                </tr>
                                <?php foreach($deductions as $row): ?>
                                    </tr>
                                    <!-- <?php if($row->is_mandatory == 1): ?>
                                        <tr>
                                            <td><?= $row->deduction_name ?></td>
                                            <td><?= $row->deduction_type ?></td>
                                            <td>
                                                <?php if($row->is_active == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Not Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row->created_at ?></td>
                                            <td class="text-center">
                                                
                                            </td>
                                        </tr>
                                    <?php endif; ?> -->
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- ===================== OTHER DEDUCTIONS ===================== -->
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Other Deductions
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="otherTable" class="table table-sm table-striped text-nowrap small">
                            <thead class="table-light">
                                <tr>
                                    <th>Deduction</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($deductions as $row): ?>
                                        <tr>
                                            <td><?= $row->deduction_name ?></td>
                                            <td><?= $row->deduction_type ?></td>
                                            <td>
                                                <?php if($row->is_active == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Not Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row->created_at ?></td>
                                            <td class="text-center">
                                                <!-- VIEW BUTTON -->
                                                <a href="<?= base_url('payroll/add_employee/'.$row->deduction_id) ?>" 
                                                class="btn btn-sm btn-primary me-1" 
                                                title="Add Employee">
                                                    <i class="bi bi-eye"></i> View
                                                </a>

                                                <!-- EDIT BUTTON -->
                                                <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editDeductionModal<?= $row->deduction_id ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                <?php endforeach; ?>
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
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="badge bg-danger d-inline-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Mandatory
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Required deduction applied to all employees.
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="badge bg-success d-inline-flex align-items-center mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Not Mandatory
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Optional deduction applied only when assigned.
                            </div>
                        </div>
                    </div>
                    <hr>
                        <div class="row">
                        <div class="col-md-6">
                            <span class="badge bg-success d-inline-flex align-items-center mb-1">
                                <i class="bi bi-toggle-on me-1"></i> Active
                            </span>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Deduction is enabled and included in payroll.
                            </div>
                        </div>
                    </div>
                    <hr>    
                    <div class="row">
                        <div class="col-md-6">
                            <span class="badge bg-secondary d-inline-flex align-items-center mb-1">
                                <i class="bi bi-toggle-off me-1"></i> Not Active
                            </span>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Deduction is disabled and excluded from payroll.
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ADD/EDIT PAYROLL ENTRY MODAL -->
<div class="modal fade" id="addFundModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('payroll/add_deduction') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addFundModalLabel"><i class="bi bi-plus-lg me-1"></i> Add Deduction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                     <div class="mb-3">
                        <label>Deduction Name</label>
                        <input type="text" name="deduction_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Deduction Type</label>
                        <select name="deduction_type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="Loan">Loan</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Mandatory?</label>
                        <select name="is_mandatory" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Applicable for Payroll?</label>
                        <select name="applicable" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="MID-YEAR BONUS">MID-YEAR BONUS</option>
                            <option value="YEAR-END BONUS">YEAR-END BONUS</option>
                            <option value="GENERAL PAYROLL">GENERAL PAYROLL</option>
                            <option value="DAILY WAGE">DAILY WAGE</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Active?</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
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
</script>


