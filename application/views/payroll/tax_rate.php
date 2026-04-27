<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Employee Tax Configuration</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-primary shadow-sm px-4" id="addTaxBtn">
                <i class="bi bi-plus-lg me-2"></i>Set Tax Rate
            </button>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-6">
                                <label for="unitFilter" class="form-label fw-bold small text-uppercase text-muted">Filter by Unit</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="bi bi-funnel"></i></span>
                                    <select id="unitFilter" class="form-select border-start-0 ps-0">
                                        <option value="">All Units</option>
                                        <?php 
                                        $units = array_unique(array_map(fn($e) => $e->unit, $employees));
                                        foreach($units as $unit): ?>
                                            <option value="<?= htmlspecialchars($unit) ?>"><?= htmlspecialchars($unit) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="rateTable" class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted">Name</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Position</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Unit</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-center">Tax Rate</th>
                                        <th class="pe-4 py-3 text-center text-uppercase fs-xs fw-bold text-muted">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($employees as $row): ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">
                                            <?= htmlspecialchars($row->last_name . ', ' . $row->name) ?>
                                        </td>
                                        <td><span class="badge bg-soft-primary text-primary rounded-pill px-2"><?= $row->position ?></span></td>
                                        <td class="text-muted small"><?= $row->unit ?></td>
                                        <td class="text-center">
                                            <span class="fw-bold <?= ($row->tax_rate > 0) ? 'text-danger' : 'text-muted' ?>">
                                                <?= number_format($row->tax_rate, 2) ?>%
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <button class="btn btn-icon btn-light btn-sm border editRateBtn"
                                                    data-employee_id="<?= $row->employee_id ?>"
                                                    data-name="<?= htmlspecialchars($row->last_name . ', ' . $row->name) ?>"
                                                    data-tax="<?= $row->tax_rate ?>">
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

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2 text-success"></i>BIR Tax Table 2024</h6>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm small mb-0 opacity-75">
                                <thead>
                                    <tr class="border-secondary">
                                        <th>Annual Income</th>
                                        <th class="text-end">Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Below ₱250k</td><td class="text-end">0%</td></tr>
                                    <tr><td>₱250k - ₱400k</td><td class="text-end">20%*</td></tr>
                                    <tr><td>₱400k - ₱800k</td><td class="text-end">₱30k + 25%*</td></tr>
                                    <tr><td>₱800k - ₱2M</td><td class="text-end">₱130k + 30%*</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taxRateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="taxRateForm" class="w-100">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" id="modalTitle">Update Tax Rate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <input type="hidden" id="modal_employee_id" name="employee_id">
                            
                            <div class="mb-4" id="employeeSelectContainer" style="display: none;">
                                <label class="form-label small fw-bold text-uppercase">Select Employee</label>
                                <select id="modal_select_employee" class="form-select select2-modal">
                                    <option value="">-- Choose Employee --</option>
                                    <?php foreach($employees as $e): ?>
                                        <option value="<?= $e->employee_id ?>" data-name="<?= htmlspecialchars($e->last_name . ', ' . $e->name) ?>" data-tax="<?= $e->tax_rate ?>">
                                            <?= htmlspecialchars($e->last_name . ', ' . $e->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4" id="employeeReadOnlyContainer">
                                <label class="form-label small fw-bold text-uppercase">Employee</label>
                                <input type="text" class="form-control bg-light border-0 fw-bold" id="modal_employee_name" readonly>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label small fw-bold text-uppercase">Tax Rate (%)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-percent text-primary"></i></span>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control bg-light border-start-0" id="modal_tax_rate" name="tax_rate" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <div>
</main>

<style>
    .bg-soft-primary { background-color: #eef2ff !important; }
    .text-primary { color: #4f46e5 !important; }
    .fs-xs { font-size: 0.7rem; letter-spacing: 0.05em; }
    .btn-icon { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; }
</style>

<script>
$(document).ready(function(){
    var table = $('#rateTable').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center p-0"f>rt<"d-flex justify-content-between align-items-center p-3"ip>',
    });

    // Custom Filter Logic
    $('#unitFilter').on('change', function() {
        table.column(2).search($(this).val()).draw();
    });

    // OPEN MODAL FOR ADD (NEW ENTRY)
    $('#addTaxBtn').on('click', function() {
        $('#modalTitle').text('Set Employee Tax Rate');
        $('#taxRateForm')[0].reset();
        
        // Show selection, hide readonly
        $('#employeeSelectContainer').show();
        $('#employeeReadOnlyContainer').hide();
        
        $('#taxRateModal').modal('show');
    });

    // SYNC SELECT TO HIDDEN ID ON ADD
    $('#modal_select_employee').on('change', function() {
        var selected = $(this).find(':selected');
        $('#modal_employee_id').val($(this).val());
        $('#modal_tax_rate').val(selected.data('tax'));
    });

    // OPEN MODAL FOR EDIT (ROW CLICK)
    $(document).on('click', '.editRateBtn', function() {
        $('#modalTitle').text('Update Tax Rate');
        var d = $(this).data();

        // Show readonly, hide selection
        $('#employeeSelectContainer').hide();
        $('#employeeReadOnlyContainer').show();

        $('#modal_employee_id').val(d.employee_id);
        $('#modal_employee_name').val(d.name);
        $('#modal_tax_rate').val(d.tax);

        $('#taxRateModal').modal('show');
    });

    // AJAX SUBMIT
    $('#taxRateForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Processing...');

        $.post("<?= base_url('payroll/updateTaxRate') ?>", $(this).serialize(), function(res) {
            if (res.status) {
                Swal.fire('Success', 'Tax rate saved!', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
                btn.prop('disabled', false).text('Save Changes');
            }
        }, 'json');
    });
});
</script>