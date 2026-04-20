<div class="content p-4">
    <h4 class="mb-3">
        <i class="bi bi-eye me-2"></i>
        Payroll Summary (<?= $period->period_start ?> – <?= $period->period_end ?>)
    </h4>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $row): ?>
                    <tr>
                        <td><?= $row->name ?></td>
                        <td>₱<?= number_format($row->gross_pay, 2) ?></td>
                        <td>₱<?= number_format($row->total_deductions, 2) ?></td>
                        <td><strong>₱<?= number_format($row->net_pay, 2) ?></strong></td>
                        <td>
                            <span class="badge bg-success">Processed</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
