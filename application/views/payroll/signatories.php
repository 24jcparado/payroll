<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>

    <button type="button" class="btn btn-primary" id="addSignatoryBtn">
        <i class="bi bi-plus-lg me-1"></i> Add Signatory
    </button>

    <div class="row mt-3">
        <div class="col-8">

            <!-- ===================== EMPLOYEE RATE TABLE ===================== -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin me-2"></i>
                        Employee Rate Per Hour
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="rateTable" class="table table-sm table-striped small">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($signatories as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row->sig_name) ?></td>
                                    <td><?= htmlspecialchars($row->sig_designation) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary editRateBtn"
                                                data-signatory_id="<?= $row->signatory_id ?>">
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

        <!-- RIGHT PANEL -->
        <div class="col-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>
                        Rate Information
                    </h6>
                </div>
                <div class="card-body small text-muted">
                    <p>
                        Rate per hour is used to compute:
                    </p>
                    <ul>
                        <li>Overload Pay</li>
                        <li>Part-Time Pay</li>
                    </ul>
                    <hr>
                    <p class="mb-0">
                        Payroll Formula:
                        <br>
                        <strong>Total Pay = Hours Worked × Rate Per Hour</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ADD / EDIT RATE MODAL -->
<div class="modal fade" id="addSignatoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="signatoryForm" method="POST">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-badge me-1"></i> Add Signatory
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Signatory Name</label>
                        <input type="text" name="sig_name" class="form-control" placeholder="Enter full name" required>
                    </div>

                    <div class="mb-3">
                        <label>Designation</label>
                        <input type="text" name="sig_designation" class="form-control" placeholder="Enter designation" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Signatory</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    $('.select2').select2({
        dropdownParent: $('#addRateModal'),
        placeholder: "-- Select Employee --",
        allowClear: true,
        width: '100%'
    });

    $('#addSignatoryBtn').on('click', function(){
        $('#signatoryForm')[0].reset();
        $('#signatoryForm').attr('action', '<?= base_url("payroll/save_signatory") ?>');
        $('#addSignatoryModal').modal('show');
    });

    $('.editRateBtn').on('click', function(){
        let employeeId = $(this).data('employee_id');
        let rate = $(this).data('rate');
        let tax = $(this).data('tax');

        $('#employee_select').val(employeeId).trigger('change');
        $('input[name="rate_per_hour"]').val(rate);
        $('input[name="tax"]').val(tax);

        $('#rateForm').attr('action', '<?= base_url("payroll/edit_rate") ?>');
        $('#addRateModal').modal('show');
    });

    $('#rateForm').on('submit', function(e){
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
                alert('Rate saved successfully!');
                $('#addRateModal').modal('hide');
                location.reload();
            } else {
                alert(response.message);
            }
        });
    });

    $('#rateTable').DataTable({
        pageLength: 10,
        order: [[3,'desc']],
        responsive: true
    });
});
</script>


