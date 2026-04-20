<!DOCTYPE html>
<html>
<head>
    <title>Payroll Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">

            <h4 class="mb-4">Payroll Verification</h4>

            <?php if($status === 'valid'): ?>

                <div class="alert alert-success">
                    ✅ Payroll Record Verified
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th>Payroll Number</th>
                        <td><?= htmlspecialchars($period->payroll_number) ?></td>
                    </tr>
                    <tr>
                        <th>Payroll Type</th>
                        <td><?= htmlspecialchars($period->payroll_type) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= htmlspecialchars($period->status) ?></td>
                    </tr>
                    <tr>
                        <th>Date Submitted</th>
                        <td><?= htmlspecialchars($period->submitted_at ?? '-') ?></td>
                    </tr>
                </table>

            <?php else: ?>

                <div class="alert alert-danger">
                    ❌ Invalid or Tampered Payroll QR Code
                </div>

                <p><?= $message ?></p>

            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>