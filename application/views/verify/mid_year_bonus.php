<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payroll Verification Result</title>
<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    body {
        background-color: #eef1f5;
        font-family: "Segoe UI", Arial, sans-serif;
        padding: 50px 15px;
    }

    .verification-card {
        max-width: 850px;
        margin: auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        padding: 40px;
        border-top: 6px solid #7b1113;
    }

    .header-section {
        text-align: center;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .logo {
        width: 85px;
        margin-bottom: 12px;
    }

    .university-name {
        font-weight: 700;
        font-size: 20px;
        color: #7b1113;
        letter-spacing: .5px;
    }

    .system-name {
        font-size: 13px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .verification-title {
        margin-top: 15px;
        font-size: 17px;
        font-weight: 600;
        color: #198754;
    }

    .info-label {
        font-weight: 600;
        width: 200px;
        color: #495057;
    }

    .info-value {
        font-weight: 500;
    }

    .status-badge {
        font-size: 13px;
        padding: 7px 14px;
        letter-spacing: .5px;
    }

    .section-divider {
        margin: 30px 0 20px 0;
        border-top: 1px solid #dee2e6;
    }

    .btn {
        font-weight: 500;
        letter-spacing: .3px;
    }

    .footer-note {
        margin-top: 35px;
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        border-top: 1px solid #dee2e6;
        padding-top: 15px;
    }

    @media print {
        body {
            background: #ffffff;
        }
        .verification-card {
            box-shadow: none;
            border-top: none;
        }
        .btn, hr {
            display: none;
        }
    }
</style>
</head>
<body>

<div class="verification-card">

    <!-- HEADER -->
    <div class="header-section">
        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo" alt="EVSU Logo">
        <div class="university-name">EASTERN VISAYAS STATE UNIVERSITY</div>
        <div class="system-name">Human Resource and Financial Management System</div>
        <div class="verification-title">Official Payroll Verification Result</div>
    </div>

    <!-- PAYROLL INFORMATION -->
    <table class="table table-borderless align-middle">
        <tr>
            <td class="info-label">Payroll Reference Number</td>
            <td class="info-value"><?= htmlspecialchars($payroll->payroll_number) ?></td>
        </tr>
        <tr>
            <td class="info-label">Payroll Classification</td>
            <td class="info-value"><strong><?= htmlspecialchars($payroll->payroll_type) ?></strong></td>
        </tr>
        <tr>
            <td class="info-label">Description / Particulars</td>
            <td class="info-value"><?= htmlspecialchars($payroll->particulars) ?></td>
        </tr>
        <tr>
            <td class="info-label">Organizational Unit</td>
            <td class="info-value"><?= htmlspecialchars($payroll->unit) ?></td>
        </tr>
        <tr>
            <td class="info-label">Approval Status</td>
            <td class="info-value">
                <?php if($payroll->status == 'APPROVED'): ?>
                    <span class="badge bg-success status-badge">APPROVED</span>
                <?php else: ?>
                    <span class="badge bg-secondary status-badge">
                        <?= htmlspecialchars($payroll->status) ?>
                    </span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="info-label">Verification Token ID</td>
            <td class="info-value"><?= htmlspecialchars($payroll->token_id) ?></td>
        </tr>
        <tr>
            <td class="info-label">Date & Time Generated</td>
            <td class="info-value">
                <?= date('F d, Y • h:i A', strtotime($payroll->created_at)) ?>
            </td>
        </tr>
    </table>

    <div class="section-divider"></div>

    <?php if (empty($payroll->date_time_received_accounting) || $payroll->date_time_received_accounting == 0): ?>
        <div class="mb-4">
            <form action="<?= base_url('verify/mark_received_accounting/'.$payroll->payroll_period_id) ?>" method="post">
            <input type="hidden" name="token_id" value="<?= $payroll->token_id ?>">    
            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                    <i class="bi bi-check-circle me-2"></i>
                    Mark as Received
                </button>
            </form>
        </div>
        <?php else: ?>

        <div class="alert alert-success text-center">
                Received on <?= date('F d, Y h:i A', strtotime($payroll->date_time_received_accounting)) ?>
            </div>
        <!-- ACTIONS -->
        <div class="action-section">

            <!-- PRIMARY ACTION -->
            <div class="mb-4">
                <label class="text-muted small fw-semibold d-block mb-2">
                    MAIN ACTION
                </label>

                <a href="<?= base_url('verify/view_full/'.$payroll->payroll_period_id) ?>"
                class="btn btn-primary w-100 py-2 fw-semibold" target="_blank">
                    <i class="bi bi-folder2-open me-2"></i>
                    View Complete Payroll Record
                </a>
            </div>

            <!-- EXPORT FILES -->
            <div class="mb-4">
                <label class="text-muted small fw-semibold d-block mb-2">
                    EXPORT FILES
                </label>

                <div class="row g-2">
                    <div class="col-md-6">
                        <a href="<?= base_url('verify/download_pdf_midyear_bonus/'.$payroll->payroll_period_id) ?>"
                        class="btn btn-outline-danger w-100" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Payroll (PDF)
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="<?= base_url('verify/download_excel_midyear_payroll/'.$payroll->payroll_period_id) ?>"
                        class="btn btn-outline-success w-100" target="_blank">
                            <i class="bi bi-file-earmark-excel me-2"></i>
                            Payroll (Excel)
                        </a>
                    </div>

                    <div class="col-md-12">
                        <a href="#" onclick="generatePayslips(<?= $payroll->payroll_period_id ?>)"
                        class="btn btn-outline-secondary w-100" target="_blank">
                            <i class="bi bi-receipt me-2"></i>
                            Individual Payslips (PDF)
                        </a>
                    </div>
                </div>
            </div>

            <!-- GOVERNMENT REMITTANCE -->
            <div>
                <label class="text-muted small fw-semibold d-block mb-2">
                    GOVERNMENT REMITTANCE FILES
                </label>

                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="<?= base_url('verify/download_philhealth/'.$payroll->payroll_period_id) ?>"
                        class="btn btn-light border w-100" target="_blank">
                            <i class="bi bi-hospital me-2"></i>
                            PhilHealth
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="<?= base_url('verify/download_gsis/'.$payroll->payroll_period_id) ?>"
                        class="btn btn-light border w-100" target="_blank">
                            <i class="bi bi-bank me-2"></i>
                            GSIS
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="<?= base_url('verify/download_pagibig/'.$payroll->payroll_period_id) ?>"
                        class="btn btn-light border w-100" target="_blank">
                            <i class="bi bi-house me-2"></i>
                            Pag-IBIG
                        </a>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

    

    <!-- FOOTER -->
    <div class="footer-note">
        This payroll record has been electronically validated through the official EVSU Payroll Verification Portal.
        <br>
        © <?= date('Y') ?> Eastern Visayas State University. All Rights Reserved.
    </div>

</div>
<script>
    function generatePayslips(period_id) {
        window.open(
            "<?= base_url('verify/payslips/') ?>" + period_id,
            "_blank"
        );
    }
</script>

</body>
</html>