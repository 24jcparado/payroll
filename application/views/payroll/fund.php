<!-- CONTENT -->
    <style>
    .x-small { font-size: 0.7rem; }
    .btn-maroon { background-color: #6b0f1a; color: white; transition: 0.3s; }
    .btn-maroon:hover { background-color: #4a0a0b; color: white; transform: translateY(-2px); }
    .progress-bar { transition: width 1s ease-in-out; }
    .stat-card { border-radius: 15px; border: none; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    #periodTable tbody tr:hover { background-color: rgba(107, 15, 26, 0.02); }
    .badge-unit { font-size: 10px; font-weight: 600; padding: 4px 8px; }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Fund Resource Management</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="row g-3 mb-4">
            <?php 
                $totalAlloc = 0; $totalDisb = 0;
                if(!empty($fund)) {
                    foreach($fund as $f) {
                        $totalAlloc += (float) str_replace(['₱', ','], '', $f->allocation);
                        $totalDisb += (float) str_replace(['₱', ','], '', $f->disbursement);
                    }
                }
                $remainingBalance = $totalAlloc - $totalDisb;
            ?>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <small class="text-uppercase opacity-75 fw-bold x-small">Total Allocation</small>
                        <h3 class="fw-bold mb-0">₱<?= number_format($totalAlloc, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white">
                    <div class="card-body border-start border-danger border-4 rounded-end">
                        <small class="text-uppercase text-muted fw-bold x-small">Total Disbursed</small>
                        <h3 class="fw-bold text-danger mb-0">₱<?= number_format($totalDisb, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white">
                    <div class="card-body border-start border-success border-4 rounded-end">
                        <small class="text-uppercase text-muted fw-bold x-small">Remaining Balance</small>
                        <h3 class="fw-bold text-success mb-0">₱<?= number_format($remainingBalance, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-wallet2 me-2 text-maroon"></i>Funding Sources</h6>
            <button type="button" class="btn btn-maroon rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFundModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Fund
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="periodTable" class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted x-small text-uppercase">
                                <th class="ps-4">Fund Name</th>             <th>Allocation & Balance</th>       <th style="width: 15%;">Usage</th>  <th>Units Covered</th>              <th>Date Created</th>               <th class="text-end pe-4">Actions</th>      </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($fund)): foreach($fund as $row): 
                                $alloc = (float) str_replace(['₱', ','], '', $row->allocation);
                                $disb = (float) str_replace(['₱', ','], '', $row->disbursement);
                                $bal = $alloc - $disb;
                                $perc = ($alloc > 0) ? ($disb / $alloc) * 100 : 0;
                                
                                $pColor = 'bg-success';
                                if ($perc >= 80) $pColor = 'bg-warning';
                                if ($perc >= 98) $pColor = 'bg-danger';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($row->fund) ?></div>
                                    <small class="text-muted x-small">REF: #FND-00<?= $row->funds_id ?></small>
                                </td>

                                <td>
                                    <div class="small">
                                        <div><span class="text-muted">Alloc:</span> <strong>₱<?= number_format($alloc, 2) ?></strong></div>
                                        <div><span class="text-muted">Rem:</span> <strong class="text-success">₱<?= number_format($bal, 2) ?></strong></div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 rounded-pill me-2" style="height: 6px;">
                                            <div class="progress-bar <?= $pColor ?>" style="width: <?= $perc ?>%"></div>
                                        </div>
                                        <span class="x-small fw-bold"><?= number_format($perc, 0) ?>%</span>
                                    </div>
                                </td>

                                <td>
                                    <?php 
                                        $units = explode(',', $row->units);
                                        foreach (array_slice($units, 0, 2) as $u): 
                                    ?>
                                        <span class="badge bg-light text-dark border badge-unit mb-1"><?= trim($u) ?></span>
                                    <?php endforeach; ?>
                                    <?php if(count($units) > 2): ?>
                                        <span class="text-muted x-small ms-1">+<?= count($units)-2 ?> more</span>
                                    <?php endif; ?>
                                </td>

                                <td class="small text-muted">
                                    <?= date('M d, Y', strtotime($row->created_at ?? date('Y-m-d'))) ?>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-pill border shadow-sm" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i> Manage
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item" href="<?= base_url('payroll/run/'.$row->funds_id) ?>"><i class="bi bi-play-circle me-2 text-success"></i> Make Payroll</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('payroll/view/'.$row->funds_id) ?>"><i class="bi bi-eye me-2 text-info"></i> View Records</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editFundModal<?= $row->funds_id ?>"><i class="bi bi-pencil-square me-2"></i> Edit Details</a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?= $row->funds_id ?>)"><i class="bi bi-trash3 me-2"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">No funding records found in the system.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
