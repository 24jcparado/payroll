<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Rate Per Hour</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-primary shadow-sm px-4" id="addRateBtn">
                <i class="bi bi-plus-lg me-2"></i>Set Rate Per Hour
            </button>
        </div>

        <div class="row g-4">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="rateTable" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted">Employee</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Position</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-end">Rate/Hr</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-center">Tax</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Last Updated</th>
                                        <th class="pe-4 py-3 text-center text-uppercase fs-xs fw-bold text-muted">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($rates as $row): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; background: #eef2ff;">
                                                    <?= strtoupper(substr($row->name, 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-semibold text-dark"><?= htmlspecialchars($row->name . ' ' . $row->last_name) ?></span>
                                                    <small class="text-muted">ID: #<?= $row->employee_id ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info border-0 rounded-pill px-3">
                                                <?= htmlspecialchars($row->position) ?>
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ₱ <?= number_format($row->rate_per_hour, 2) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= ($row->tax > 0) ? 'bg-soft-danger text-danger' : 'bg-soft-secondary text-secondary' ?> rounded-pill">
                                                <?= number_format($row->tax ?? 0, 2) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted small">
                                                <i class="bi bi-clock-history me-1"></i>
                                                <?= date('M d, Y', strtotime($row->updated_at ?? $row->created_at)) ?>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <button class="btn btn-icon btn-light btn-sm border editRateBtn"
                                                    data-employee_id="<?= $row->employee_id ?>"
                                                    data-rate="<?= $row->rate_per_hour ?>"
                                                    data-tax="<?= $row->tax ?? 0 ?>"
                                                    data-bs-toggle="tooltip" title="Edit Rate">
                                                <i class="bi bi-pencil-square text-primary"></i>
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

            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden mb-4">
                    <div class="card-body p-4 position-relative">
                        <i class="bi bi-calculator position-absolute end-0 bottom-0 mb-n3 me-n2 display-1 opacity-25"></i>
                        <h5 class="fw-bold mb-3">Formula Guide</h5>
                        <p class="small opacity-75">All calculations are based on the standard HR policy for Overload and Part-Time duties.</p>
                        <div class="bg-white bg-opacity-25 rounded-3 p-3 border border-white border-opacity-25">
                            <small class="d-block mb-1 opacity-75">Gross Computation:</small>
                            <div class="h6 mb-0 fw-bold">Hours × Rate</div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-3">Pro Tips</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Ensure tax percentages match BIR 2024 tables.</li>
                            <li class="mb-0"><i class="bi bi-check2-circle text-success me-2"></i> Rates are applied immediately to the next payout.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addRateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="rateForm" method="POST" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Set Rate Per Hour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase">Employee Name</label>
                        <select id="employee_select" name="employee_id" class="form-select select2" required>
                            <option value="">-- Search Employee --</option>
                            <?php foreach($employees as $row): ?>
                                <option value="<?= $row->employee_id ?>">
                                    <?= htmlspecialchars($row->name . ' ' . $row->last_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-uppercase">Rate / Hr (PHP)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">₱</span>
                                <input type="number" step="0.01" name="rate_per_hour" class="form-control bg-light border-start-0" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-uppercase">Tax (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="tax" class="form-control bg-light border-end-0" placeholder="0.00" required>
                                <span class="input-group-text bg-light border-start-0">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Confirm & Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling to match yesterday's modern theme */
    body { background-color: #f8f9fa; }
    .bg-soft-primary { background-color: #eef2ff !important; }
    .bg-soft-info { background-color: #e0f2fe !important; }
    .bg-soft-danger { background-color: #fee2e2 !important; }
    .bg-soft-secondary { background-color: #f3f4f6 !important; }
    .text-info { color: #0ea5e9 !important; }
    .text-danger { color: #ef4444 !important; }
    .fs-xs { font-size: 0.75rem; letter-spacing: 0.05em; }
    .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    .select2-container--bootstrap-5 .select2-selection { border-radius: 8px; border: 1px solid #dee2e6; height: 45px; display: flex; align-items: center; }
</style>

<script>
$(document).ready(function(){
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Select2 with improved styling
    $('.select2').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#addRateModal'),
        width: '100%'
    });

    // Reset for Add
    $('#addRateBtn').on('click', function(){
        $('#modalTitle').text('Set New Employee Rate');
        $('#rateForm')[0].reset();
        $('#employee_select').val(null).trigger('change').prop('disabled', false);
        $('#rateForm').attr('action', '<?= base_url("payroll/save_rate") ?>');
        $('#addRateModal').modal('show');
    });

    // Fill for Edit
    $('.editRateBtn').on('click', function(){
        $('#modalTitle').text('Update Rate Information');
        let employeeId = $(this).data('employee_id');
        let rate = $(this).data('rate');
        let tax = $(this).data('tax');

        $('#employee_select').val(employeeId).trigger('change').prop('disabled', true); // Often better to lock ID on edit
        $('input[name="rate_per_hour"]').val(rate);
        $('input[name="tax"]').val(tax);

        $('#rateForm').attr('action', '<?= base_url("payroll/edit_rate") ?>');
        $('#addRateModal').modal('show');
    });

    // Submit with SweetAlert (optional but recommended over alert())
    $('#rateForm').on('submit', function(e){
        e.preventDefault();
        let url = $(this).attr('action');
        let formData = $(this).serialize();

        $.post(url, formData, function(response){
            try {
                response = typeof response === 'string' ? JSON.parse(response) : response;
                if(response.status){
                    location.reload();
                } else {
                    alert(response.message);
                }
            } catch(err){
                console.error(response);
            }
        });
    });

    $('#rateTable').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center p-3"f>rt<"d-flex justify-content-between align-items-center p-3"ip>'
    });
});
</script>