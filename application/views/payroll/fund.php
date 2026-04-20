<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFundModal">
            <i class="bi bi-plus-lg me-1"></i> Add Fund
        </button>

    <!-- Example Table -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-text me-2"></i>Funds</h5>
                    <div class="table-responsive">
                        <table id="periodTable" class="text-sm table table-sm text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-wallet2 me-1 text-primary"></i> Fund</th>
                                    <th><i class="bi bi-cash-stack me-1 text-success"></i> Allocation</th>
                                    <th><i class="bi bi-arrow-up-right-circle me-1 text-warning"></i> Disbursement</th>
                                    <th><i class="bi bi-arrow-up-right-circle me-1 text-warning"></i> Allocable</th>
                                    <th><i class="bi bi-percentage me-1 text-warning"></i> % Used </th>
                                    <th><i class="bi bi-diagram-3 me-1 text-info"></i> Units Under</th>
                                    <th><i class="bi bi-calendar-event me-1 text-secondary"></i> Created At</th>
                                    <th class="text-center"><i class="bi bi-gear me-1"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($fund)): ?>
                                    <?php $no = 1; foreach($fund as $row): 
                                            $allocation = (float) str_replace(['₱', ','], '', $row->allocation);
                                            $disbursement = (float) str_replace(['₱', ','], '', $row->disbursement);
                                            $allocable = $allocation - $disbursement;
                                            if ($allocation > 0) {
                                                $percentage = ($disbursement / $allocation) * 100;
                                            } else {
                                                $percentage = 0;
                                            }
                                            $badgeClass = 'bg-success';
                                            if ($percentage >= 80) $badgeClass = 'bg-warning';
                                            if ($percentage >= 100) $badgeClass = 'bg-danger';
                                        ?>
                                        <tr>
                                            <td><?= $row->fund ?></td>
                                            <td><?= $row->allocation?></td>
                                            <td><?= $row->disbursement?></td>
                                            <td>₱<?= number_format($allocable, 2) ?></td>
                                            <td>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= number_format($percentage, 2) ?>%
                                                </span>
                                            </td>
                                            <td>
                                                    <?php 
                                                    $units = explode(',', $row->units);
                                                    foreach ($units as $unit):
                                                ?>
                                                    <span class="badge bg-info text-dark mb-1 d-block">
                                                        <?= htmlspecialchars(trim($unit)) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </td>
                                            <td></td>
                                            <td>
                                            <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <li>
                                                            <a class="dropdown-item"
                                                            href="<?= base_url('payroll/view/'.$row->funds_id) ?>">
                                                                <i class="bi bi-eye me-2 text-info"></i> View Payroll
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item"
                                                            href="<?= base_url('payroll/run/'.$row->funds_id) ?>">
                                                                <i class="bi bi-calculator me-2 text-success"></i> Make Payroll
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <a class="dropdown-item"
                                                            href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPayrollModal">
                                                                <i class="bi bi-pencil-square me-2 text-primary"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                            href="#"
                                                            onclick="confirmDelete(<?= $row->funds_id ?>)">
                                                                <i class="bi bi-trash me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> 

<!-- ADD/EDIT PAYROLL ENTRY MODAL -->
<div class="modal fade" id="addFundModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('payroll/add_fund') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addFundModalLabel"><i class="bi bi-plus-lg me-1"></i> Add Fund</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="unit" class="form-label">Name of Fund</label>
                        <input type="text" class="form-control" id="fund" name="fund" required>
                    </div>

                    <div class="mb-3">
                        <label for="units" class="form-label">Units Under</label>
                        <select class="form-select" id="units" name="units[]" multiple="multiple" required>
                            <option value=""></option>
                             <?php $units = array_unique(array_column($employee, 'unit'));
                                 foreach ($units as $unit):
                                ?>
                                    <option value="<?= $unit ?>"><?= $unit ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">You can select multiple units.</small>
                    </div>

                    <div class="mb-3">
                        <label for="allocation" class="form-label">Allocation</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" name="allocation" id="allocation" min="0" step="0.01" placeholder="Enter amount" required>
                        </div>
                        <small class="text-muted">Enter the allocation amount in pesos.</small>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#periodTable').DataTable({
        pageLength: 10, 
        lengthMenu: [5, 10, 25, 50],
        order: [[1, 'desc']],
        responsive: true
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This payroll entry will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('payroll/delete/') ?>" + id;
        }
    });
}

</script>
