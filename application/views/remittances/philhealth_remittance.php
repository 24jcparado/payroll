<style>
    .bg-philhealth { background-color: #198754; color: white; }
    .text-philhealth { color: #198754; }
    .table-details th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-badge { font-size: 0.9rem; padding: 10px 20px; border-radius: 50px; }
</style>

<main id="mainContent" class="py-4">
    <?php $this->load->view('template/admin_topbar')?>

    <div class="container-fluid px-md-4">
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-philhealth py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-medical me-2"></i>PhilHealth Remittance Summary</h5>
            </div>
            <div class="card-body bg-white">
                <div class="row g-3">
                    <div class="col-md-3 border-end">
                        <small class="text-muted text-uppercase fw-bold x-small d-block">Payroll Number</small>
                        <span class="fw-bold text-dark fs-5"><?= $payroll->payroll_number ?></span>
                    </div>
                    <div class="col-md-3 border-end">
                        <small class="text-muted text-uppercase fw-bold x-small d-block">Unit / Department</small>
                        <span class="fw-bold text-dark fs-6"><?= $payroll->unit ?></span>
                    </div>
                    <div class="col-md-3 border-end">
                        <small class="text-muted text-uppercase fw-bold x-small d-block">Payroll Type</small>
                        <span class="fw-bold text-dark fs-6"><?= $payroll->payroll_type ?></span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase fw-bold x-small d-block">Applicable Period</small>
                        <span class="fw-bold text-philhealth fs-6"><?= $payroll->payroll_period ?? 'N/A' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>Employee Premium Breakdown</h6>
                <div class="badge bg-success-subtle text-success summary-badge border border-success-subtle">
                    Rate: 5.0% (Split 50/50)
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-details">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">Employee Name</th>
                                <th>PhilHealth PIN</th>
                                <th>Position</th>
                                <th>Basic Salary</th>
                                <th>Personal Share (PS)</th>
                                <th>Gov't Share (GS)</th>
                                <th class="pe-4">Total Premium</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total_ee = 0;
                                $total_er = 0;
                                $grand_total = 0;

                                foreach($employees as $e):
                                    $basic = (float)($e->basic_salary ?? 0);

                                    // PhilHealth 2024-2026 Logic: 
                                    // Total = 5% of Basic Salary
                                    // Floor: 10,000 | Ceiling: 100,000
                                    $clamped_salary = max(10000, min($basic, 100000));
                                    
                                    // Total Premium (5%)
                                    $total_premium = $clamped_salary * 0.05;
                                    
                                    // Split 50/50
                                    $ee = $total_premium / 2;
                                    $er = $total_premium / 2;

                                    $total_ee += $ee;
                                    $total_er += $er;
                                    $grand_total += $total_premium;
                                ?>
                            <tr>
                                <td class="ps-4 text-start">
                                    <div class="fw-bold text-dark"><?= strtoupper($e->name) ?></div>
                                    <small class="text-muted"><?= $e->unit ?></small>
                                </td>
                                <td>
                                    <code class="text-dark fw-bold"><?= $e->philhealth_no ?? '<span class="text-danger">MISSING</span>' ?></code>
                                </td>
                                <td class="small text-muted"><?= $e->position ?></td>
                                <td class="text-end">₱<?= number_format($basic, 2) ?></td>
                                <td class="text-end text-primary fw-semibold">₱<?= number_format($ee, 2) ?></td>
                                <td class="text-end text-primary fw-semibold">₱<?= number_format($er, 2) ?></td>
                                <td class="text-end pe-4 fw-bold text-dark">₱<?= number_format($total_premium, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr class="fw-bold fs-6">
                                <td colspan="4" class="text-end ps-4 py-3">GRAND TOTAL REMITTANCE</td>
                                <td class="text-end text-success">₱<?= number_format($total_ee, 2) ?></td>
                                <td class="text-end text-success">₱<?= number_format($total_er, 2) ?></td>
                                <td class="text-end pe-4 text-philhealth">₱<?= number_format($grand_total, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-end gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary px-4 rounded-pill">
                <i class="bi bi-printer me-1"></i> Print Report
            </button>
            <a href="<?= base_url('payroll/export_philhealth/'.$payroll->payroll_period_id) ?>" class="btn btn-philhealth px-4 rounded-pill shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
            </a>
        </div>
    </div>
</main>