<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-person-plus"></i>
                        Add / Update BP Number
                    </h6>
                </div>

                <div class="card-body">
                    <form id="bpForm">
                        
                        <input type="hidden" name="payroll_period_id" id="payroll_period_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select form-select-sm select2" required>
                                <option value="">-- Select Employee --</option>
                                <?php foreach($all_employees as $e): ?>
                                    <option value="<?= $e->employee_id ?>">
                                        <?= $e->last_name . ', ' . $e->name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">BP Number</label>
                            <input type="text" name="bp_no" class="form-control form-control-sm" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-save"></i> Save BP Number
                        </button>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm mt-3">

                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bank me-2"></i>
                        GSIS Remittance - Payroll List
                    </h5>

                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-light active" id="btnPayroll">Payroll List</button>
                        <button class="btn btn-light" id="btnEmployees">Employee GSIS</button>
                    </div>
                </div>
                

                <div class="card-body">

                    <!-- ================= PAYROLL LIST ================= -->
                    <div id="payrollView">
                        <div class="table-responsive">
                            <table id="payrollTable" class="table table-sm table-bordered">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th rowspan="2">Payroll No.</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Type</th>
                                        <th colspan="6">Routing History</th>
                                    </tr>
                                    <tr>
                                        <th>HR</th>
                                        <th>Admin</th>
                                        <th>Budget</th>
                                        <th>Accounting</th>
                                        <th>VPS</th>
                                        <th>Cashier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payroll as $p): ?>
                                    <tr class="payrollRow" data-id="<?= $p->payroll_period_id ?>">
                                        <td><?= $p->payroll_number ?></td>
                                        <td><?= $p->unit ?></td>
                                        <td><?= $p->payroll_type ?></td>
                                        <td><?= $p->date_time_forwarded_hr ?></td>
                                        <td><?= $p->date_time_received_admin ?></td>
                                        <td><?= $p->date_time_received_budget ?></td>
                                        <td><?= $p->date_time_received_accounting ?></td>
                                        <td><?= $p->date_time_received_vps ?></td>
                                        <td><?= $p->date_time_received_cashier ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= EMPLOYEE GSIS ================= -->
                    <div id="employeeView" style="display:none;">
                        <div class="table-responsive">
                            <table id="employeeTable" class="table table-sm table-bordered">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>GSIS No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($all_employees as $e): ?>
                                    <tr>
                                        <td><?= $e->last_name . ', ' . $e->name ?></td>
                                        <td><?= $e->unit ?></td>
                                        <td>
                                            <?= $e->gsis_no 
                                                ? '<span class="fw-bold">'.$e->gsis_no.'</span>' 
                                                : '<span class="text-muted">None</span>' ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editBP"
                                                data-id="<?= $e->employee_id ?>"
                                                data-gsis="<?= $e->gsis_no ?>">
                                                <i class="bi bi-pencil"></i>
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

    
</main>
<script>
$(document).ready(function(){

    $('#payrollTable').DataTable();

    $(document).on('click', '.payrollRow', function(){
        var payroll_period_id = $(this).data('id');

        window.location.href = "<?= base_url('payroll/gsis_remittance/') ?>" + payroll_period_id;
    });

});

$(document).ready(function() {

    $('#employee_id').select2({
        placeholder: "Search employee...",
        allowClear: true,
        width: '100%'
    });

});

$('#bpForm').submit(function(e){
    e.preventDefault();

    $.post("<?= base_url('payroll/save_bp_number') ?>", $(this).serialize(), function(res){

        if(res.status){
            Swal.fire('Success', 'GSIS number saved!', 'success');
            location.reload(); // or update UI dynamically
        } else {
            Swal.fire('Error', res.message, 'error');
        }

    }, 'json');
});

$(document).ready(function(){

    // DataTables
    $('#payrollTable').DataTable();
    $('#employeeTable').DataTable();

    // SWITCH VIEW
    $('#btnPayroll').click(function(){
        $('#payrollView').show();
        $('#employeeView').hide();

        $(this).addClass('active');
        $('#btnEmployees').removeClass('active');
    });

    $('#btnEmployees').click(function(){
        $('#payrollView').hide();
        $('#employeeView').show();

        $(this).addClass('active');
        $('#btnPayroll').removeClass('active');
    });

    // EDIT BP (auto-fill left form)
    $(document).on('click', '.editBP', function(){

        var id = $(this).data('id');
        var gsis = $(this).data('gsis');

        $('#employee_id').val(id).trigger('change');
        $('input[name="bp_no"]').val(gsis);

        // optional: switch focus to form
        $('html, body').animate({
            scrollTop: $("#bpForm").offset().top
        }, 300);
    });

});
</script>