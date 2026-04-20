<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <div class="row mt-3">
        <div class="col-8">

            <!-- ===================== EMPLOYEE RATE TABLE ===================== -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin me-2"></i>
                        Employee Tax Rate
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mb-3">
                            <label for="unitFilter" class="form-label fw-bold">Filter by Unit</label>
                            <select id="unitFilter" class="form-select form-select-sm">
                                <option value="">-- All Units --</option>
                                <?php 
                                // Get distinct units
                                $units = array_unique(array_map(fn($e) => $e->unit, $employees));
                                foreach($units as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit) ?>"><?= htmlspecialchars($unit) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <table id="rateTable" class="table table-sm table-striped small">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Unit</th>
                                    <th>Tax Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($employees as $row){?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars(
                                            $row->last_name . ', ' . 
                                            $row->name . 
                                            (!empty($row->middle_name) ? ' ' . strtoupper(substr($row->middle_name, 0, 1)) . '.' : '') . 
                                            (!empty($row->ext) ? ' ' . $row->ext : '')
                                        ) ?>
                                    </td>
                                    <td>
                                        <?=$row->position?>
                                    </td>
                                    <td>
                                        <?=$row->unit?>
                                    </td>
                                    <td>
                                        <?=$row->tax_rate?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary editRateBtn"
                                                data-employee_id="<?= $row->employee_id ?>">
                                            <i class="bi bi-pencil"></i>
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

        <!-- RIGHT PANEL -->
        <div class="col-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>
                        Tax Rate Legend
                    </h6>
                </div>
                <div class="card-body small text-muted">
                    <p class="mb-2">
                        Philippine Income Tax Rates (for compensation income)
                    </p>
                    <table class="table table-sm table-bordered small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Range of Annual Income (₱)</th>
                                <th>Tax Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0 – 250,000</td>
                                <td>0%</td>
                            </tr>
                            <tr>
                                <td>250,001 – 400,000</td>
                                <td>20% of excess over 250,000</td>
                            </tr>
                            <tr>
                                <td>400,001 – 800,000</td>
                                <td>30,000 + 25% of excess over 400,000</td>
                            </tr>
                            <tr>
                                <td>800,001 – 2,000,000</td>
                                <td>130,000 + 30% of excess over 800,000</td>
                            </tr>
                            <tr>
                                <td>2,000,001 – 8,000,000</td>
                                <td>490,000 + 32% of excess over 2,000,000</td>
                            </tr>
                            <tr>
                                <td>8,000,001 & above</td>
                                <td>2,410,000 + 35% of excess over 8,000,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taxRateModal" tabindex="-1" aria-labelledby="taxRateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="taxRateForm">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="taxRateModalLabel">Update Tax Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <input type="hidden" id="modal_employee_id" name="employee_id">

                <div class="mb-3">
                    <label for="modal_employee_name" class="form-label fw-bold">Employee</label>
                    <input type="text" class="form-control" id="modal_employee_name" readonly>
                </div>

                <div class="mb-3">
                    <label for="modal_tax_rate" class="form-label fw-bold">Tax Rate (%)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="modal_tax_rate" name="tax_rate" required>
                </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Tax Rate</button>
                </div>
            </div>
            </form>
        </div>
        </div>
</main>


<script>
$(document).ready(function(){

    $('#rateTable').DataTable({
        pageLength: 10,
        order: [[1,'desc']],
        responsive: true
    });
});

$(document).ready(function() {
    $('#unitFilter').on('change', function() {
        var selectedUnit = $(this).val().trim();

        $('#rateTable tbody tr').each(function() {
            var rowUnit = $(this).find('td').eq(3).text().trim();

            if (selectedUnit === "" || rowUnit === selectedUnit) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

$(document).on('click', '.editRateBtn', function() {
    var $row = $(this).closest('tr');
    var employeeId = $(this).data('employee_id');
    var name = $row.find('td:first').text().trim();
    var taxRate = $row.find('td:nth-child(4)').text().trim(); // Tax Rate column

    $('#modal_employee_id').val(employeeId);
    $('#modal_employee_name').val(name);
    $('#modal_tax_rate').val(taxRate);

    var modal = new bootstrap.Modal(document.getElementById('taxRateModal'));
    modal.show();
});

$('#taxRateForm').on('submit', function(e) {
    e.preventDefault();

    $.post("<?= base_url('payroll/updateTaxRate') ?>", $(this).serialize(), function(res) {
        if (!res.status) {
            Swal.fire('Error', res.message, 'error');
            return;
        }
        var $row = $('#rateTable tbody').find('tr').filter(function() {
            return $(this).find('.editRateBtn').data('employee_id') == res.employee_id;
        });
        $row.find('td:nth-child(3)').text(parseFloat(res.tax_rate).toFixed(2));
        $('#taxRateModal').modal('hide'); 
        Swal.fire('Success', 'Tax rate updated!', 'success');
    }, 'json');
});
</script>


