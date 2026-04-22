<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>

    <!-- HEADER -->
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pagibig Remittance Details</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Payroll No:</strong><br>
                    <?= $payroll->payroll_number ?>
                </div>

                <div class="col-md-3">
                    <strong>Unit:</strong><br>
                    <?= $payroll->unit ?>
                </div>

                <div class="col-md-3">
                    <strong>Type:</strong><br>
                    <?= $payroll->payroll_type ?>
                </div>

                <div class="col-md-3">
                    <strong>Period:</strong><br>
                    <?= $payroll->payroll_period ?? '' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Employee Pagibig Contributions</h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-sm table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Pagibig Number</th>
                        <th>Position</th>
                        <th>Unit</th>
                        <th>Basic Salary</th>
                        <th>PS</th>
                        <th>GS</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                        $total_ee = 0;
                        $total_er = 0;
                        $grand_total = 0;

                        foreach($employees as $e):

                            $basic = $e->basic_salary ?? 0;

                            // FIX: ensure EE is not wrong column
                            $ee = $e->pagibig ?? 0;
                            if ($ee == 0) {
                                // fallback compute if DB is empty
                                $base_ee = min($basic, 10000);

                                if ($base_ee <= 1500) {
                                    $ee = $base_ee * 0.01;
                                } else {
                                    $ee = $base_ee * 0.02;
                                }
                            }
                            $base = min($basic, 10000);
                            $er = $base * 0.02;
                            $total = $ee + $er;
                            $total_ee += $ee;
                            $total_er += $er;
                            $grand_total += $total;
                        ?>
                    <tr>
                        <td><?= $e->name ?></td>
                        <td><?= $e->pagibig_no ?></td>
                        <td><?= $e->position ?></td>
                        <td><?= $e->unit ?></td>

                        <td class="text-end"><?= number_format($basic,2) ?></td>
                        <td class="text-end"><?= number_format($e->pagibig,2) ?></td>
                        <td class="text-end"><?= number_format($er,2) ?></td>
                        <td class="text-end fw-bold"><?= number_format($total,2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">TOTAL</td>
                        <td class="text-end"><?= number_format($total_ee,2) ?></td>
                        <td class="text-end"><?= number_format($total_er,2) ?></td>
                        <td class="text-end"><?= number_format($grand_total,2) ?></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</main>