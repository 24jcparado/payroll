<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>

    <button type="button" class="btn btn-primary" id="addUsersBtn">
        <i class="bi bi-plus-lg me-1"></i> Add Payroll Receiver
    </button>

    <div class="row mt-3">
        <div class="col-8">

            <!-- ===================== EMPLOYEE RATE TABLE ===================== -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Payroll Receiver
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="rateTable" class="table table-sm table-striped small">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row->last_name) ?></td>
                                    <td><?= htmlspecialchars($row->role) ?></td>
                                    <td><?= htmlspecialchars($row->username) ?></td>
                                    <td><?= htmlspecialchars($row->status) ?></td>
                                    <td><?= htmlspecialchars($row->last_login) ?></td>
                                    <td class="text-center">

                                    <!-- EDIT -->
                                    <button class="btn btn-sm btn-outline-primary editUserBtn"
                                        data-id="<?= $row->receiver_id ?>"
                                        data-first_name="<?= $row->first_name ?>"
                                        data-middle_name="<?= $row->middle_name ?>"
                                        data-last_name="<?= $row->last_name ?>"
                                        data-username="<?= $row->username ?>"
                                        data-email="<?= $row->email ?>"
                                        data-role="<?= $row->role ?>"
                                        data-status="<?= $row->status ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- DELETE -->
                                    <button class="btn btn-sm btn-outline-danger deleteUserBtn"
                                        data-id="<?= $row->receiver_id ?>">
                                        <i class="bi bi-trash"></i>
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

        <!-- RIGHT PANEL -->
        <div class="col-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-people me-2"></i>
                        Receiver Information
                    </h6>
                </div>
                <div class="card-body small text-muted">
                    <p>
                        Payroll Receivers are authorized users who can:
                    </p>
                    <ul>
                        <li>Access payroll records</li>
                        <li>Confirm receipt of payroll</li>
                        <li>Manage assigned payroll transactions</li>
                    </ul>
                    <hr>
                    <p class="mb-0">
                        <strong>Username Format:</strong><br>
                        <span class="text-dark">rcvr_lastname</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>


<div class="modal fade" id="addReceiverModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="receiverForm" method="POST" action="<?= base_url('payroll/add_py_receiver') ?>">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-1"></i> Add Payroll Receiver
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Name Fields -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" readonly required>
                        <small class="text-muted">Auto-generated (rcvr_lastname)</small>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Select Role --</option>
                            <option value="accounting">Accounting</option>
                            <option value="admin">Admin</option>
                            <option value="budget">Budget</option>
                            <option value="vps">Vice President</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    // -----------------------
    // AUTO GENERATE USERNAME
    // -----------------------
    $('#last_name').on('keyup change', function () {
        let lastName = $(this).val().toLowerCase().replace(/\s+/g, '');
        if (lastName !== '') {
            $('#username').val('rcvr_' + lastName);
        } else {
            $('#username').val('');
        }
    });

    // -----------------------
    // OPEN ADD USER MODAL
    // -----------------------
    $('#addUsersBtn').on('click', function(){
        $('#receiverForm')[0].reset();
        $('#username').val('');
        $('#receiverForm').attr('action', '<?= base_url("payroll/add_py_receiver") ?>');
        $('#addReceiverModal').modal('show');
    });

    // -----------------------
    // OPEN EDIT USER MODAL
    // -----------------------
    $('.editUserBtn').on('click', function(){

        let id          = $(this).data('id');
        let firstName   = $(this).data('first_name');
        let middleName  = $(this).data('middle_name');
        let lastName    = $(this).data('last_name');
        let username    = $(this).data('username');
        let email       = $(this).data('email');
        let role        = $(this).data('role');
        let status      = $(this).data('status');

        $('#first_name').val(firstName);
        $('input[name="middle_name"]').val(middleName);
        $('#last_name').val(lastName);
        $('#username').val(username);
        $('input[name="email"]').val(email);
        $('select[name="role"]').val(role);
        $('select[name="status"]').val(status);

        // Optional: hide password on edit
        $('input[name="password"]').val('').prop('required', false);

        $('#receiverForm').attr('action', '<?= base_url("payroll/update_py_receiver") ?>/'+id);
        $('#addReceiverModal').modal('show');
    });

    // -----------------------
    // AJAX SUBMIT
    // -----------------------
    $('#receiverForm').on('submit', function(e){
        e.preventDefault();

        let url = $(this).attr('action');
        let formData = $(this).serialize();

        $.post(url, formData, function(response){

            try {
                response = JSON.parse(response);
            } catch(err){
                console.error('Invalid JSON', response);
                alert('Server returned invalid response.');
                return;
            }

            if(response.status){
                alert('User saved successfully!');
                $('#addReceiverModal').modal('hide');
                location.reload();
            } else {
                alert(response.message);
            }
        });
    });

    // -----------------------
    // DATATABLE
    // -----------------------
    $('#receiverTable').DataTable({
        pageLength: 10,
        order: [[0,'asc']],
        responsive: true
    });

});

// -----------------------
// DELETE USER
// -----------------------
$(document).on('click', '.deleteUserBtn', function(){

    let id = $(this).data('id');

    if(!confirm('Are you sure you want to delete this user?')){
        return;
    }

    $.ajax({
        url: '<?= base_url("payroll_receiver/delete") ?>/' + id,
        type: 'POST',
        success: function(response){

            try {
                response = JSON.parse(response);
            } catch(err){
                alert('Invalid server response');
                return;
            }

            if(response.status){
                alert('User deleted successfully!');
                location.reload();
            } else {
                alert(response.message);
            }
        }
    });

});
</script>


