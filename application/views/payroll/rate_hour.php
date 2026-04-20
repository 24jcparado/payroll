<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>

    <button type="button" class="btn btn-primary" id="addRateBtn">
        <i class="bi bi-plus-lg me-1"></i> Set Rate Per Hour
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
                                    <th>Employee</th>
                                    <th>Position</th>
                                    <th>Rate Per Hour</th>
                                    <th>Tax (%)</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($rates as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row->name . ' ' . $row->last_name) ?></td>
                                    <td><?= htmlspecialchars($row->position) ?></td>
                                    <td>₱ <?= number_format($row->rate_per_hour,2) ?></td>
                                    <td><?= number_format($row->tax ?? 0,2) ?></td>
                                    <td><?= $row->updated_at ?? $row->created_at ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary editRateBtn"
                                                data-employee_id="<?= $row->employee_id ?>"
                                                data-rate="<?= $row->rate_per_hour ?>"
                                                data-tax="<?= $row->tax ?? 0 ?>">
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
<div class="modal fade" id="addRateModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rateForm" method="POST">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-cash-coin me-1"></i> Set Rate Per Hour
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Select Employee</label>
                        <select id="employee_select" name="employee_id" class="form-select select2" required>
                            <option value="">-- Select Employee --</option>
                            <?php foreach($employees as $row): ?>
                                <option value="<?= $row->employee_id ?>">
                                    <?= htmlspecialchars($row->name . ' ' . (!empty($row->middle_name) ? strtoupper(substr($row->middle_name,0,1)) . '. ' : '') . $row->last_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Rate Per Hour</label>
                        <input type="number" step="0.01" name="rate_per_hour" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tax (%)</label>
                        <input type="number" step="0.01" name="tax" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Rate</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    // Initialize Select2 in modal
    $('.select2').select2({
        dropdownParent: $('#addRateModal'),
        placeholder: "-- Select Employee --",
        allowClear: true,
        width: '100%'
    });

    // -----------------------
    // OPEN ADD RATE MODAL
    // -----------------------
    $('#addRateBtn').on('click', function(){
        $('#rateForm')[0].reset();
        $('#employee_select').val(null).trigger('change');
        $('#rateForm').attr('action', '<?= base_url("payroll/save_rate") ?>');
        $('#addRateModal').modal('show');
    });

    // -----------------------
    // OPEN EDIT RATE MODAL
    // -----------------------
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

    // -----------------------
    // AJAX SUBMIT
    // -----------------------
    $('#rateForm').on('submit', function(e){
        e.preventDefault();

        let url = $(this).attr('action');
        let formData = $(this).serialize();

        $.post(url, formData, function(response){
            // Ensure valid JSON
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
                location.reload(); // reload table
            } else {
                alert(response.message);
            }
        });
    });

    // Initialize DataTable
    $('#rateTable').DataTable({
        pageLength: 10,
        order: [[3,'desc']],
        responsive: true
    });
});
</script>


