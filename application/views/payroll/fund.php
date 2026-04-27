<style>
    .x-small { font-size: 0.75rem; }
    .btn-maroon { background-color: #6b0f1a; color: white; transition: 0.3s; border: none; }
    .btn-maroon:hover { background-color: #4a0a0b; color: white; transform: translateY(-2px); shadow: 0 4px 8px rgba(0,0,0,0.2); }
    .stat-card { border-radius: 15px; border: 0; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); }
    .progress { background-color: #f0f0f0; overflow: visible; }
    .badge-unit { font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 6px; }
    /* Select2 High-End Styling */
    .select2-container--bootstrap-5 .select2-selection { border-radius: 10px; padding: 0.5rem; border: 1px solid #dee2e6; }
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-maroon rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFundModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Fund Source
            </button>
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
                <div class="card stat-card shadow-sm bg-dark text-white">
                    <div class="card-body p-4">
                        <small class="text-uppercase opacity-50 fw-bold x-small">Total Budget Pool</small>
                        <h2 class="fw-bold mb-0">₱<?= number_format($totalAlloc, 2) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white border-0">
                    <div class="card-body p-4 border-start border-danger border-5 rounded-end">
                        <small class="text-uppercase text-muted fw-bold x-small">Utilized Funds</small>
                        <h2 class="fw-bold text-danger mb-0">₱<?= number_format($totalDisb, 2) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white border-0">
                    <div class="card-body p-4 border-start border-success border-5 rounded-end">
                        <small class="text-uppercase text-muted fw-bold x-small">Remaining Liquidity</small>
                        <h2 class="fw-bold text-success mb-0">₱<?= number_format($remainingBalance, 2) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="periodTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="bg-light">
                            <tr class="text-muted x-small text-uppercase">
                                <th class="ps-4 py-3">Fund Details</th>
                                <th>Financial Status</th>
                                <th style="width: 200px;">Usage Progress</th>
                                <th>Covered Units</th>
                                <th>Created At</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($fund)): foreach($fund as $row): 
                                $alloc = (float) str_replace(['₱', ','], '', $row->allocation);
                                $disb = (float) str_replace(['₱', ','], '', $row->disbursement);
                                $bal = $alloc - $disb;
                                $perc = ($alloc > 0) ? ($disb / $alloc) * 100 : 0;
                                
                                $pColor = 'bg-success';
                                if ($perc >= 80) $pColor = 'bg-warning';
                                if ($perc >= 95) $pColor = 'bg-danger';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-soft-maroon rounded-circle d-flex align-items-center justify-content-center" style="width:35px; height:35px; background: #fff5f5;">
                                            <i class="bi bi-bank text-maroon"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($row->fund) ?></div>
                                            <small class="text-muted x-small">ID: FND-<?= str_pad($row->funds_id, 4, '0', STR_PAD_LEFT) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="mb-1"><span class="text-muted">Alloc:</span> <span class="fw-semibold">₱<?= number_format($alloc, 2) ?></span></div>
                                        <div><span class="text-muted">Balance:</span> <span class="text-success fw-bold">₱<?= number_format($bal, 2) ?></span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 rounded-pill me-2" style="height: 8px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated <?= $pColor ?>" role="progressbar" style="width: <?= $perc ?>%"></div>
                                        </div>
                                        <span class="x-small fw-bold text-dark"><?= number_format($perc, 1) ?>%</span>
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
                                        <span class="text-muted x-small d-block">+<?= count($units)-2 ?> more units</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('M d, Y', strtotime($row->created_at ?? date('Y-m-d'))) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-xs" data-bs-toggle="dropdown">
                                            Manage <i class="bi bi-chevron-down ms-1 x-small"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                            <li><a class="dropdown-item py-2" href="<?= base_url('payroll/run/'.$row->funds_id) ?>"><i class="bi bi-play-fill me-2 text-success"></i> Execute Payroll</a></li>
                                            <li><a class="dropdown-item py-2" href="<?= base_url('payroll/view/'.$row->funds_id) ?>"><i class="bi bi-journal-text me-2 text-primary"></i> Financial Logs</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="confirmDelete(<?= $row->funds_id ?>)"><i class="bi bi-trash3 me-2"></i> Terminate Fund</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addFundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('payroll/add_fund') ?>" method="POST" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 bg-maroon text-white p-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Create Fund Source</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Fund Label</label>
                        <input type="text" class="form-control form-control-lg rounded-3" name="fund" placeholder="e.g. GAA 2024 - Faculty Overload" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Responsible Units</label>
                        <select class="form-select select2-multiple" name="units[]" multiple="multiple" required style="width: 100%">
                             <?php $distinctUnits = array_unique(array_column($employee, 'unit'));
                                 foreach ($distinctUnits as $unit): ?>
                                    <option value="<?= $unit ?>"><?= $unit ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text mt-2">These units will be eligible to draw from this fund.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase">Initial Allocation</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">₱</span>
                            <input type="number" class="form-control" name="allocation" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4 shadow">Confirm Allocation</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTables
    var table = $('#periodTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50],
        order: [[4, 'desc']], // Changed to sort by Date Created (Index 4)
        responsive: true,
        autoWidth: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search funds..."
        }
    });

    // Initialize Select2 for Multiple Select
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        placeholder: "Select Units",
        dropdownParent: $('#addFundModal')
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Terminate Fund Source?',
        text: 'This action is irreversible. All allocation data for this source will be cleared.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b0f1a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Terminate',
        customClass: {
            confirmButton: 'rounded-pill px-4',
            cancelButton: 'rounded-pill px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('payroll/delete/') ?>" + id;
        }
    });
}
</script>