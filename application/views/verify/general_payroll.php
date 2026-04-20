<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payroll Verification Result</title>
<link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f1f4f8;
    font-family: "Segoe UI", Arial, sans-serif;
    padding: 40px 15px;
}

/* CARD */
.verification-card {
    max-width: 900px;
    margin: auto;
    background: #fff;
    border-radius: 12px;
    padding: 35px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    border-top: 5px solid #7b1113;
}

/* HEADER */
.header-section {
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.logo {
    width: 80px;
    margin-bottom: 10px;
}

.university-name {
    font-weight: 700;
    font-size: 18px;
    color: #7b1113;
}

.system-name {
    font-size: 12px;
    color: #6c757d;
    letter-spacing: 1px;
}

.verification-title {
    margin-top: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #0d6efd;
}

/* INFO */
.info-table td {
    padding: 10px 5px;
}

.info-label {
    font-weight: 600;
    width: 220px;
    color: #495057;
}

.info-value {
    font-weight: 500;
}

/* STATUS */
.status-approved {
    background: #d1fae5;
    color: #065f46;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
}

/* RECEIVED */
.received-box {
    background: #e6f4ea;
    border-left: 4px solid #198754;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
}

/* SECTION */
.section-title {
    font-size: 12px;
    font-weight: 700;
    color: #6c757d;
    margin-bottom: 10px;
}

/* ACTION BOX */
.action-box {
    background: #fafbfc;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

/* FOOTER */
.footer-note {
    margin-top: 30px;
    font-size: 12px;
    color: #6c757d;
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 12px;
}

@media print {
    body { background: #fff; }
    .verification-card {
        box-shadow: none;
        border-top: none;
    }
    .btn { display: none; }
}
</style>
</head>

<body>

<div class="verification-card">

    <!-- HEADER -->
    <div class="header-section">
        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo">
        <div class="university-name">EASTERN VISAYAS STATE UNIVERSITY</div>
        <div class="system-name">Human Resource and Financial Management System</div>
        <div class="verification-title">Payroll Verification Result</div>
    </div>

    <!-- INFO -->
    <table class="table table-borderless info-table">
        <tr>
            <td class="info-label">Payroll Reference No.</td>
            <td class="info-value"><?= $payroll->payroll_number ?></td>
        </tr>
        <tr>
            <td class="info-label">Classification</td>
            <td class="info-value fw-bold"><?= $payroll->payroll_type ?></td>
        </tr>
        <tr>
            <td class="info-label">Particulars</td>
            <td class="info-value"><?= $payroll->particulars ?></td>
        </tr>
        <tr>
            <td class="info-label">Unit</td>
            <td class="info-value"><?= $payroll->unit ?></td>
        </tr>
        <tr>
            <td class="info-label">Approval Status</td>
            <td>
                <?php if($payroll->status=='APPROVED'): ?>
                    <span class="status-approved">APPROVED</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $payroll->status ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="info-label">Token ID</td>
            <td class="info-value"><?= $payroll->token_id ?></td>
        </tr>
        <tr>
            <td class="info-label">Generated</td>
            <td class="info-value">
                <?= date('F d, Y h:i A', strtotime($payroll->created_at)) ?>
            </td>
        </tr>
    </table>

    <hr>

    <!-- RECEIVED -->
    <?php if(empty($payroll->date_time_received_accounting)): ?>

        <form action="<?= base_url('verify/mark_received_accounting/'.$payroll->payroll_period_id) ?>" method="post">
            <input type="hidden" name="token_id" value="<?= $payroll->token_id ?>">
            <button class="btn btn-success w-100 mb-3">
                <i class="bi bi-check-circle me-2"></i> Confirm Receipt
            </button>
        </form>

    <?php else: ?>

        <div class="received-box mb-3">
            <i class="bi bi-check-circle-fill text-success"></i>
            Received on <?= date('F d, Y h:i A', strtotime($payroll->date_time_received_accounting)) ?>
        </div>

    <?php endif; ?>

    <!-- ACTIONS -->
    <div class="action-box">

        <div class="mb-3">
            <div class="section-title">PRIMARY ACTION</div>
            <a href="<?= base_url('verify/view_full/'.$payroll->payroll_period_id) ?>"
               class="btn btn-primary w-100" target="_blank">
               View Full Payroll
            </a>
        </div>

        <div class="mb-3">
            <div class="section-title">EXPORT FILES</div>
            <div class="row g-2">

                <div class="col-md-6">
                    <a href="<?= base_url('verify/download_pdf/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-outline-danger w-100">PDF</a>
                </div>

                <div class="col-md-6">
                    <a href="<?= base_url('verify/download_excel_general_payroll/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-outline-success w-100">Excel</a>
                </div>

                <div class="col-md-6">
                    <a href="<?= base_url('verify/download_proof_list_gp/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-outline-primary w-100">Proof List</a>
                </div>

                <div class="col-md-6">
                    <button onclick="generatePayslips(<?= $payroll->payroll_period_id ?>)"
                        class="btn btn-outline-secondary w-100">Payslips</button>
                </div>

            </div>
        </div>

        <div>
            <div class="section-title">REMITTANCE</div>
            <div class="row g-2">

                <div class="col-md-4">
                    <a href="<?= base_url('verify/download_philhealth/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-light border w-100">PhilHealth</a>
                </div>

                <div class="col-md-4">
                    <a href="<?= base_url('verify/download_gsis/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-light border w-100">GSIS</a>
                </div>

                <div class="col-md-4">
                    <a href="<?= base_url('verify/download_pagibig/'.$payroll->payroll_period_id) ?>"
                       class="btn btn-light border w-100">Pag-IBIG</a>
                </div>

            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer-note">
        Verified via EVSU Payroll System<br>
        © <?= date('Y') ?> Eastern Visayas State University
    </div>

</div>

<script>
function generatePayslips(id){
    window.open("<?= base_url('verify/payslips/') ?>" + id, "_blank");
}
</script>

</body>
</html>