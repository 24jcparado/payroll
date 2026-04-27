<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Access Control</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-primary shadow-sm px-4 rounded-3" id="addUsersBtn">
                <i class="bi bi-person-plus me-2"></i>Add Payroll Receiver
            </button>
        </div>

        <div class="row g-4">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="rateTable" class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted">User Identity</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Assigned Role</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-center">Status</th>
                                        <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Last Activity</th>
                                        <th class="pe-4 py-3 text-center text-uppercase fs-xs fw-bold text-muted">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $row): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                                    <?= strtoupper(substr($row->last_name, 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($row->first_name . ' ' . $row->last_name) ?></span>
                                                    <small class="text-muted"><i class="bi bi-at me-1"></i><?= htmlspecialchars($row->username) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info rounded-pill px-3 py-2 small fw-medium">
                                                <?= strtoupper(htmlspecialchars($row->role)) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if(strtolower($row->status) == 'active'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                <i class="bi bi-clock-history me-1"></i>
                                                <?= $row->last_login ? date('M d, H:i', strtotime($row->last_login)) : 'Never' ?>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-icon btn-light border editUserBtn shadow-sm"
                                                    data-id="<?= $row->receiver_id ?>"
                                                    data-first_name="<?= $row->first_name ?>"
                                                    data-middle_name="<?= $row->middle_name ?>"
                                                    data-last_name="<?= $row->last_name ?>"
                                                    data-username="<?= $row->username ?>"
                                                    data-email="<?= $row->email ?>"
                                                    data-role="<?= $row->role ?>"
                                                    data-status="<?= $row->status ?>"
                                                    data-bs-toggle="tooltip" title="Edit User">
                                                    <i class="bi bi-pencil-square text-primary"></i>
                                                </button>
                                                <button class="btn btn-icon btn-light border deleteUserBtn shadow-sm"
                                                    data-id="<?= $row->receiver_id ?>"
                                                    data-bs-toggle="tooltip" title="Remove User">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </div>
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
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4 overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <i class="bi bi-shield-lock position-absolute end-0 bottom-0 mb-n3 me-n2 display-1 opacity-25"></i>
                        <h5 class="fw-bold mb-3">Privileges</h5>
                        <ul class="list-unstyled small mb-0 opacity-90">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Audit payroll history</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Signature authorization</li>
                            <li class="mb-0"><i class="bi bi-check-circle-fill me-2"></i> Export financial reports</li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-3">Policy Reminder</h6>
                        <p class="text-muted small">All receiver accounts are logged. Sharing credentials violates the internal data privacy agreement.</p>
                        <div class="alert alert-warning border-0 small mb-0 rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Usernames are auto-locked to <strong>rcvr_</strong> prefix.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addReceiverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="receiverForm" method="POST" action="<?= base_url('payroll/add_py_receiver') ?>">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Register New Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control bg-light rounded-3" placeholder="John" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control bg-light rounded-3" placeholder="Optional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control bg-light rounded-3" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Auto-Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white border-0"><i class="bi bi-shield-shaded"></i></span>
                                <input type="text" name="username" id="username" class="form-control bg-white fw-bold" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light rounded-3" placeholder="john.doe@company.com" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••">
                            </div>
                            <small class="text-muted" id="passHelp">Leave blank to keep current password if editing.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Assign Role</label>
                            <select name="role" class="form-select bg-light" required>
                                <option value="accounting">Accounting</option>
                                <option value="admin">Admin</option>
                                <option value="budget">Budget</option>
                                <option value="vps">Vice President</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">System Status</label>
                            <select name="status" class="form-select bg-light" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">Save Receiver Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .bg-soft-primary { background-color: #eef2ff !important; }
    .bg-soft-info { background-color: #e0f2fe !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-secondary-subtle { background-color: #f1f5f9 !important; }
    .text-info { color: #0ea5e9 !important; }
    .fs-xs { font-size: 0.75rem; letter-spacing: 0.05em; }
    .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); }
</style>

<script>
$(document).ready(function(){
    // Tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(t => new bootstrap.Tooltip(t));

    // Username Auto-gen
    $('#last_name').on('keyup change', function () {
        let lastName = $(this).val().toLowerCase().replace(/\s+/g, '');
        $('#username').val(lastName !== '' ? 'rcvr_' + lastName : '');
    });

    // Add Modal Logic
    $('#addUsersBtn').on('click', function(){
        $('#modalTitle').text('Register New Receiver');
        $('#receiverForm')[0].reset();
        $('#username').val('');
        $('#passHelp').hide();
        $('input[name="password"]').prop('required', true);
        $('#receiverForm').attr('action', '<?= base_url("payroll/add_py_receiver") ?>');
        $('#addReceiverModal').modal('show');
    });

    // Edit Modal Logic
    $('.editUserBtn').on('click', function(){
        $('#modalTitle').text('Edit Receiver Profile');
        let d = $(this).data();
        
        $('#first_name').val(d.first_name);
        $('input[name="middle_name"]').val(d.middle_name);
        $('#last_name').val(d.last_name);
        $('#username').val(d.username);
        $('input[name="email"]').val(d.email);
        $('select[name="role"]').val(d.role);
        $('select[name="status"]').val(d.status);

        $('#passHelp').show();
        $('input[name="password"]').val('').prop('required', false);

        $('#receiverForm').attr('action', '<?= base_url("payroll/update_py_receiver") ?>/'+d.id);
        $('#addReceiverModal').modal('show');
    });

    // Form Submit
    $('#receiverForm').on('submit', function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(res){
            try {
                res = typeof res === 'string' ? JSON.parse(res) : res;
                if(res.status){
                    Swal.fire('Success', 'Profile updated successfully!', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            } catch(e) { console.error(res); }
        });
    });

    $('#rateTable').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center p-3"f>rt<"d-flex justify-content-between align-items-center p-3"ip>'
    });
});

// Delete Logic
$(document).on('click', '.deleteUserBtn', function(){
    let id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will lose all system access!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("payroll_receiver/delete") ?>/' + id, function(res){
                location.reload();
            });
        }
    });
});
</script>