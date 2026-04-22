<style>
    .x-small { font-size: 0.7rem; }
    .routing-cell { font-size: 0.65rem; color: #6c757d; line-height: 1.2; vertical-align: middle !important; }
    .routing-cell.completed { color: #198754; font-weight: bold; }
    .routing-cell.pending { color: #f59e0b; font-style: italic; }
    .payrollRow:hover { background-color: rgba(107, 15, 26, 0.03) !important; cursor: pointer; }
    .btn-toggle-active { background-color: #6b0f1a !important; color: white !important; border-color: #6b0f1a !important; }
    /* Select2 Fix for Bootstrap 5 */
    .select2-container--default .select2-selection--single { height: 38px; border: 1px solid #dee2e6; border-radius: 0.375rem; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; padding-left: 12px; }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Pag-IBIG Remittance & Membership Registry</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white py-3 border-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Update Membership No.</h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="bpForm"> <input type="hidden" name="payroll_period_id" id="payroll_period_id">
                            
                            <div class="mb-3">
                                <label class="form-label x-small fw-bold text-muted text-uppercase">Employee Name</label>
                                <select name="employee_id" id="employee_id" class="form-select select2" required>
                                    <option value="">-- Search Name --</option>
                                    <?php foreach($all_employees as $e): ?>
                                        <option value="<?= $e->employee_id ?>">
                                            <?= strtoupper($e->last_name . ', ' . $e->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label x-small fw-bold text-muted text-uppercase">Pag-IBIG MID Number</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-house-heart"></i></span>
                                    <input type="text" name="pagibig_no" class="form-control border-start-0 ps-0 fw-bold" placeholder="12-digit MID Number" required>
                                </div>
                                <small class="text-muted x-small mt-2 d-block">Required for HDMF short-term loans and contributions.</small>
                            </div>

                            <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm fw-bold py-2">
                                <i class="bi bi-cloud-upload me-1"></i> Sync Pag-IBIG Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Data Overview</h6>
                        <div class="btn-group btn-group-sm p-1 bg-light rounded-pill">
                            <button class="btn rounded-pill px-3 btn-toggle-active" id="btnPayroll">Payrolls</button>
                            <button class="btn rounded-pill px-3" id="btnEmployees">Employee MID</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="payrollView">
                            <div class="table-responsive">
                                <table id="payrollTable" class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="text-muted x-small text-uppercase">
                                            <th class="ps-4">Reference</th>
                                            <th>Type / Unit</th>
                                            <th class="text-center" colspan="6">Approval Routing</th>
                                        </tr>
                                        <tr class="bg-light-subtle x-small text-center text-muted" style="font-size: 0.6rem;">
                                            <th colspan="2"></th>
                                            <th>HR</th>
                                            <th>ADM</th>
                                            <th>BDG</th>
                                            <th>ACC</th>
                                            <th>VPS</th>
                                            <th>CSH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($payroll)): foreach($payroll as $p): ?>
                                        <tr class="payrollRow" data-id="<?= $p->payroll_period_id ?>">
                                            <td class="ps-4">
                                                <div class="fw-bold text-primary"><?= $p->payroll_number ?></div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold"><?= $p->payroll_type ?></div>
                                                <div class="x-small text-muted"><?= $p->unit ?></div>
                                            </td>
                                            <?php 
                                                $stages = ['date_time_forwarded_hr', 'date_time_received_admin', 'date_time_received_budget', 'date_time_received_accounting', 'date_time_received_vps', 'date_time_received_cashier'];
                                                foreach($stages as $stage):
                                                    $val = $p->$stage;
                                                    $is_done = !empty($val) && $val != '0000-00-00 00:00:00';
                                            ?>
                                                <td class="text-center routing-cell <?= $is_done ? 'completed' : 'pending' ?>">
                                                    <?php if($is_done): ?>
                                                        <i class="bi bi-check-circle-fill text-success d-block mb-1"></i>
                                                        <?= date('m/d', strtotime($val)) ?>
                                                    <?php else: ?>
                                                        <i class="bi bi-dash-circle text-light d-block mb-1"></i>
                                                        --
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="employeeView" style="display:none;">
                            <div class="table-responsive">
                                <table id="employeeTable" class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="text-muted x-small text-uppercase">
                                            <th class="ps-4">Employee Name</th>
                                            <th>Unit</th>
                                            <th>Pag-IBIG MID No</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($all_employees as $e): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= strtoupper($e->last_name . ', ' . $e->name) ?></td>
                                            <td><span class="badge bg-light text-dark border"><?= $e->unit ?></span></td>
                                            <td>
                                                <?php if (isset($e->pagibig_no) && !empty($e->pagibig_no)): ?>
                                                    <code class="fw-bold fs-6 text-dark"><?= $e->pagibig_no ?></code>
                                                <?php else: ?>
                                                    <span class="text-danger x-small fw-bold">
                                                        <i class="bi bi-exclamation-circle me-1"></i>MISSING
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-primary editBP rounded-circle"
                                                        data-id="<?= $e->employee_id ?>"
                                                        data-pagibig="<?= $e->pagibig_no ?>">
                                                    <i class="bi bi-pencil-square"></i>
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
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function(){
    // Init DataTables
    $('#payrollTable').DataTable({ ordering: false, responsive: true });
    $('#employeeTable').DataTable({ responsive: true });

    // Init Select2
    $('#employee_id').select2({
        placeholder: "Find employee...",
        width: '100%'
    });

    // Navigation Switches
    $('#btnPayroll').click(function(){
        $('#payrollView').fadeIn(200);
        $('#employeeView').hide();
        $(this).addClass('btn-toggle-active').siblings().removeClass('btn-toggle-active');
    });

    $('#btnEmployees').click(function(){
        $('#payrollView').hide();
        $('#employeeView').fadeIn(200);
        $(this).addClass('btn-toggle-active').siblings().removeClass('btn-toggle-active');
    });

    // Row Redirection
    $(document).on('click', '.payrollRow', function(){
        window.location.href = "<?= base_url('payroll/pagibig_remittance/') ?>" + $(this).data('id');
    });

    // Auto-fill form for editing
    $(document).on('click', '.editBP', function(){
        const id = $(this).data('id');
        const pagibig = $(this).data('pagibig');

        $('#employee_id').val(id).trigger('change');
        $('input[name="pagibig_no"]').val(pagibig).focus();

        $('html, body').animate({ scrollTop: 0 }, 300);
    });

    // AJAX Form Submit
    $('#bpForm').submit(function(e){
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Syncing...');

        $.post("<?= base_url('payroll/save_pagibig_number') ?>", $(this).serialize(), function(res){
            if(res.status){
                Swal.fire({ icon: 'success', title: 'Registry Updated', text: 'Pag-IBIG Number Saved', timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
            } else {
                Swal.fire('Error', res.message || 'System failed to save', 'error');
                btn.prop('disabled', false).html('<i class="bi bi-save"></i> Save Pagibig Number');
            }
        }, 'json');
    });
});
</script>