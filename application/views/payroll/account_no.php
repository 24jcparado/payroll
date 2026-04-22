<!-- CONTENT -->
    <main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark"><?= isset($period) ? $period : 'Account Management' ?></h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            Employee Account Directory
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-6">
                                <label for="unitFilter" class="form-label small fw-bold text-muted">FILTER BY DEPARTMENT/UNIT</label>
                                <select id="unitFilter" class="form-select form-select-sm border-secondary-subtle">
                                    <option value="">-- All Units --</option>
                                    <?php 
                                    $units = array_unique(array_map(fn($e) => $e->unit, $employees));
                                    foreach($units as $unit): ?>
                                        <option value="<?= htmlspecialchars($unit) ?>"><?= htmlspecialchars($unit) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                <span class="badge bg-light text-dark border small py-2 px-3">
                                    Total Records: <span class="fw-bold"><?= count($employees) ?></span>
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="accountTable" class="table table-hover align-middle small">
                                <thead class="table-light">
                                    <tr class="text-uppercase text-muted" style="font-size: 0.7rem;">
                                        <th>Full Name</th>
                                        <th>Position</th>
                                        <th>Unit</th>
                                        <th>Account No.</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($employees as $row){?>
                                    <tr>
                                        <td class="fw-bold">
                                            <?= htmlspecialchars($row->last_name . ', ' . $row->name . (!empty($row->middle_name) ? ' ' . strtoupper(substr($row->middle_name, 0, 1)) . '.' : '') . (!empty($row->ext) ? ' ' . $row->ext : '')) ?>
                                        </td>
                                        <td class="text-muted small"><?=$row->position?></td>
                                        <td><span class="badge bg-info-subtle text-info border border-info-subtle px-2"><?= $row->unit ?></span></td>
                                        <td class="account_no fw-bold text-primary"><?=$row->account_no ? $row->account_no : '<span class="text-danger small">No Data</span>'?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary editRateBtn rounded-circle shadow-sm"
                                                    data-employee_id="<?= $row->employee_id ?>"
                                                    data-name="<?= htmlspecialchars($row->name . ' ' . $row->last_name) ?>"
                                                    data-account="<?= $row->account_no ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0 fw-bold small text-uppercase">Data Integrity Status</h6>
                    </div>
                    <div class="card-body">
                        <?php 
                            $missing_accounts = count(array_filter($employees, fn($e) => empty($e->account_no)));
                            $completeness = count($employees) > 0 ? floor(((count($employees) - $missing_accounts) / count($employees)) * 100) : 0;
                        ?>
                        <div class="text-center mb-3">
                            <h2 class="fw-800 text-primary mb-0"><?= $completeness ?>%</h2>
                            <small class="text-muted text-uppercase fw-bold">Account Mapping Complete</small>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: <?= $completeness ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between x-small fw-bold">
                            <span class="text-danger"><i class="bi bi-x-circle me-1"></i> Missing: <?= $missing_accounts ?></span>
                            <span class="text-success"><i class="bi bi-check-circle me-1"></i> Verified: <?= count($employees) - $missing_accounts ?></span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark">Unit Distribution</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush small">
                            <?php 
                                $unit_counts = array_count_values(array_map(fn($e) => $e->unit, $employees));
                                arsort($unit_counts);
                                foreach($unit_counts as $unit_name => $count):
                            ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted fw-semibold"><?= $unit_name ?></span>
                                <span class="badge bg-secondary rounded-pill"><?= $count ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <button class="btn btn-sm btn-outline-dark w-100 fw-bold rounded-pill" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i> Export Account List
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="accountForm" class="w-100">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white border-0">
                        <h6 class="modal-title fw-bold" id="accountModalLabel">Update Disbursement Account</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <input type="hidden" id="employee_id" name="employee_id">

                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-muted">EMPLOYEE NAME</label>
                            <input type="text" class="form-control bg-light border-0 fw-bold" id="modal_employee_name" readonly>
                        </div>

                        <div class="mb-1">
                            <label for="account_no" class="form-label x-small fw-bold text-muted">BANK ACCOUNT NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-bank text-primary"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0 fw-bold" id="account_no" name="account_no" placeholder="Enter Valid Account No." required>
                            </div>
                        </div>
                        <p class="x-small text-muted mt-2"><i class="bi bi-info-circle me-1"></i> Ensure account numbers match Landbank/ATM records.</p>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
    .fw-800 { font-weight: 800; }
    .x-small { font-size: 0.75rem; }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    
    #accountTable th { 
        padding-top: 12px; 
        padding-bottom: 12px;
        letter-spacing: 0.5px;
    }
    
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>


<script>
$(document).ready(function(){

    $('#accountTable').DataTable({
        pageLength: 10,
        order: [[1,'desc']],
        responsive: true
    });
});

$(document).on('click', '.editRateBtn', function() {
    // Get employee data from the row and button
    var $row = $(this).closest('tr');
    var employeeId = $(this).data('employee_id');
    var name = $row.find('td:first').text().trim();
    var accountNo = $row.find('td.account_no').text().trim(); // Make sure you have a column with class 'account_no'

    // Populate the modal fields
    $('#employee_id').val(employeeId);
    $('#modal_employee_name').val(name);
    $('#account_no').val(accountNo);

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('accountModal'));
    modal.show();
});

// Handle form submission via AJAX
$('#accountForm').on('submit', function(e) {
    e.preventDefault();

    $.post("<?= base_url('payroll/updateAccountNo') ?>", $(this).serialize(), function(res) {
        if (!res.status) {
            Swal.fire('Error', res.message, 'error');
            return;
        }

        // Update the account number in the table
        var $row = $('#savedPayrollTable tbody').find('tr').filter(function() {
            return $(this).find('.editRateBtn').data('employee_id') == res.employee_id;
        });
        $row.find('td.account_no').text(res.account_no);

        // Hide modal and show success
        $('#accountModal').modal('hide');
        Swal.fire('Success', 'Account number updated!', 'success');
    }, 'json');
});
</script>


