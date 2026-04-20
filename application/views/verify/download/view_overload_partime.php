<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$period?></title>

<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

body {
    background-color: #f4f6f9;
}

/* PAGE SPACING */
#mainContent {
    padding: 30px 35px;
}

/* CARD IMPROVEMENTS */
.card {
    margin-bottom: 25px;
    border-radius: 10px;
}

.card-header {
    padding: 18px 22px;
}

.card-body {
    padding: 25px;
}

/* TABLE */
#savedPayrollTable {
    margin-top: 25px;
}

#savedPayrollTable th,
#savedPayrollTable td {
    white-space: normal !important;
    word-break: break-word;
    font-size: 0.85rem;
    padding: 0.45rem 0.6rem;
}

/* TRACKER */
.payroll-tracker {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 35px 0 55px 0;
    flex-wrap: wrap;
}

.payroll-tracker::before {
    content: '';
    position: absolute;
    top: 22px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #e9ecef;
    z-index: 1;
}

.tracker-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1 1 150px;
    margin-bottom: 20px;
}

.tracker-circle {
    width: 40px;
    height: 40px;
    margin: 0 auto 8px;
    border-radius: 50%;
    background: #dee2e6;
    line-height: 40px;
    font-weight: bold;
}

.tracker-step.active .tracker-circle {
    background: #28a745;
    color: #fff;
}

.tracker-step.current .tracker-circle {
    background: #ffc107;
    color: #000;
}

/* SUB PROCESS */
.sub-process {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.sub-step {
    font-size: 10px;
    padding: 5px 10px;
    border-radius: 20px;
    background: #dee2e6;
}

.sub-step.active {
    background: #28a745;
    color: #fff;
}

.sub-step.current {
    background: #ffc107;
    color: #000;
}

/* LEGEND */
.tracker-legend {
    display: flex;
    gap: 20px;
    align-items: center;
    font-size: 14px;
    margin-bottom: 25px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-item::before {
    content: '';
    width: 15px;
    height: 15px;
    display: inline-block;
    border-radius: 50%;
}

.active-legend::before { background-color: #28a745; }
.current-legend::before { background-color: #ffc107; }
.pending-legend::before { background-color: #dee2e6; }

/* SUMMARY CARDS */
.dashboard-icon i {
    font-size: 28px;
}

/* ACTION BUTTON */
.dropdown button {
    padding: 10px 15px;
}
.payroll-header {
    line-height: 1.4;
}

.payroll-header hr {
    border-top: 2px solid #000;
    width: 60%;
    margin-left: auto;
    margin-right: auto;
}

</style>
</head>

<body>
    <?php
        $grouped = [];

        if (!empty($payrolls)) {
            foreach ($payrolls as $row) {
                $grouped[$row->school_year][] = $row;
            }
        }
    ?>

<main id="mainContent">
<!-- OFFICIAL GOVERNMENT HEADER -->
<div class="text-center mb-4 payroll-header">

    <!-- LOGO -->
    <img src="<?= base_url('assets/img/favicon.png') ?>" 
         alt="EVSU Logo" 
         style="width:90px; margin-bottom:10px;">

    <div class="fw-semibold" style="font-size:14px;">
        Republic of the Philippines
    </div>

    <div class="fw-bold text-uppercase" style="font-size:18px; letter-spacing:0.5px;">
        Eastern Visayas State University
    </div>

    <div style="font-size:14px;">
        Tacloban City
    </div>

    <div class="mt-3 fw-bold text-uppercase" style="font-size:16px;">
        Payroll for Overload
    </div>

    <div class="mt-2" style="font-size:14px;">
        <strong>Unit:</strong> <?= $unit ?>
    </div>
</div>
<div class="row">
<div class="col-12">

<div class="card shadow">

<div class="card-header">
    <h5 class="mb-0">
        <i class="bi bi-check-circle text-success me-2"></i>
        <?= $unit?> ( <?= $payroll_type ?>)
    </h5>
</div>

<div class="card-body">

<!-- LEGEND -->
<div class="tracker-legend">
    <span class="legend-item active-legend">Completed</span>
    <span class="legend-item current-legend">Current</span>
    <span class="legend-item pending-legend">Pending</span>
</div>

<!-- TRACKER -->
<div class="payroll-tracker">
    <div class="tracker-step" id="step-1">
        <div class="tracker-circle">1</div>
        <div>HRMO</div>
        <div class="sub-process">
            <div class="sub-step" id="hrmo-draft">DRAFT PAYROLL</div>
            <div class="sub-step" id="hrmo-final">FINAL PAYROLL</div>
        </div>
    </div>

    <div class="tracker-step" id="step-2">
        <div class="tracker-circle">2</div>
        <div>Accounting</div>
        <div class="sub-process">
            <div class="sub-step" id="acc-pre-d">PRE AUDIT - (D)</div>
            <div class="sub-step" id="acc-tax">TAX COMPUTATION</div>
            <div class="sub-step" id="acc-pre-f">PRE AUDIT - (F)</div>
        </div>
    </div>

    <div class="tracker-step" id="step-3">
        <div class="tracker-circle">3</div>
        <div>Budget</div>
    </div>

    <div class="tracker-step" id="step-4">
        <div class="tracker-circle">4</div>
        <div>Approved</div>
    </div>

    <div class="tracker-step" id="step-5">
        <div class="tracker-circle">5</div>
        <div>Released</div>
    </div>
</div>

<!-- SUMMARY ROW -->
<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">
                            Total Amount Accrued
                        </div>
                        <h5 class="mb-0 fw-bold text-success" id="total_amount_accrued">
                            ₱0.00
                        </h5>
                    </div>
                    <div class="text-success dashboard-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">
                            Total Tax
                        </div>
                        <h5 class="mb-0 fw-bold text-danger" id="total_tax">
                            ₱0.00
                        </h5>
                    </div>
                    <div class="text-danger dashboard-icon">
                        <i class="bi bi-dash-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLE -->
<table class="table table-xsm table-bordered text-center" id="savedPayrollTable">
    <thead class="table-light">
        <thead>
            <tr>
                <th rowspan="2">Name</th>
                <th rowspan="2">Rate/Hour</th>
                <th rowspan="2">Particular</th>

                <!-- First 6 Months -->
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>May</th>
                <th>Jun</th>

                <th rowspan="2">No. HRS</th>
                <th rowspan="2">Amount Accrued</th>
                <th rowspan="2">Less W/Tax</th>
                <th rowspan="2">Net Due</th>
            </tr>

            <tr>
                <th>Jul</th>
                <th>Aug</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
            </tr>
        </thead>
    </thead>
    <tbody>
        <?php if(!empty($grouped)): ?>

            <?php foreach($grouped as $school_year => $records): ?>
                <tr>
                    <td colspan="16" class="text-start fw-bold bg-light">
                        SCHOOL YEAR: <?= htmlspecialchars($school_year) ?>
                    </td>
                </tr>

                <?php foreach($records as $row): ?>
                    <tr>
                        <td rowspan="2">
                            <?= htmlspecialchars(
                                $row->name . ' ' .
                                (!empty($row->middle_name)
                                    ? strtoupper(substr($row->middle_name, 0, 1)) . '. '
                                    : ''
                                ) .
                                $row->last_name
                            ) ?>
                        </td>

                        <td rowspan="2" class="text-end">
                            <?= number_format($row->rate_per_hour,2) ?>
                        </td>

                        <td rowspan="2">
                            <?= htmlspecialchars($row->particulars ?? '-') ?>
                        </td>

                        <!-- Jan–Jun -->
                       <td><?= ($row->jan ?? 0) > 0 ? '<strong>'.number_format($row->jan,2).'</strong>' : number_format($row->jan ?? 0,2); ?></td>
                        <td><?= ($row->feb ?? 0) > 0 ? '<strong>'.number_format($row->feb,2).'</strong>' : number_format($row->feb ?? 0,2); ?></td>
                        <td><?= ($row->mar ?? 0) > 0 ? '<strong>'.number_format($row->mar,2).'</strong>' : number_format($row->mar ?? 0,2); ?></td>
                        <td><?= ($row->apr ?? 0) > 0 ? '<strong>'.number_format($row->apr,2).'</strong>' : number_format($row->apr ?? 0,2); ?></td>
                        <td><?= ($row->may ?? 0) > 0 ? '<strong>'.number_format($row->may,2).'</strong>' : number_format($row->may ?? 0,2); ?></td>
                        <td><?= ($row->jun ?? 0) > 0 ? '<strong>'.number_format($row->jun,2).'</strong>' : number_format($row->jun ?? 0,2); ?></td>

                        <td rowspan="2"><?= number_format($row->total_hours,2) ?></td>
                        <td class="amount-accrued" rowspan="2"><?= number_format($row->gross_amount,2) ?></td>
                        <td class="tax-amount" rowspan="2"><?= number_format($row->tax_amount,2) ?></td>
                        <td rowspan="2"><?= number_format($row->total_net,2) ?></td>
                    </tr>

                    <tr>
                        <!-- Jul–Dec -->
                        <td><?= ($row->jul ?? 0) > 0 ? '<strong>'.number_format($row->jul,2).'</strong>' : number_format($row->jul ?? 0,2); ?></td>
                        <td><?= ($row->aug ?? 0) > 0 ? '<strong>'.number_format($row->aug,2).'</strong>' : number_format($row->aug ?? 0,2); ?></td>
                        <td><?= ($row->sept ?? 0) > 0 ? '<strong>'.number_format($row->sept,2).'</strong>' : number_format($row->sept ?? 0,2); ?></td>
                        <td><?= ($row->oct ?? 0) > 0 ? '<strong>'.number_format($row->oct,2).'</strong>' : number_format($row->oct ?? 0,2); ?></td>
                        <td><?= ($row->nov ?? 0) > 0 ? '<strong>'.number_format($row->nov,2).'</strong>' : number_format($row->nov ?? 0,2); ?></td>
                        <td><?= ($row->dece ?? 0) > 0 ? '<strong>'.number_format($row->dece,2).'</strong>' : number_format($row->dece ?? 0,2); ?></td>

                <?php endforeach; ?>

            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="16" class="text-center">
                    No payroll records found.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>
</div>
</div>
</div>

</main>
<script>
    function updateTotals() {
        let totalAccrued = 0;
        let totalTax = 0;
        document.querySelectorAll('#savedPayrollTable td.amount-accrued').forEach(td => {
            let accrued = parseFloat(td.textContent.replace(/,/g, '')) || 0;
            totalAccrued += accrued;
        });
        document.querySelectorAll('#savedPayrollTable td.tax-amount').forEach(td => {
            let tax = parseFloat(td.textContent.replace(/,/g, '')) || 0;
            totalTax += tax;
        });

        document.getElementById('total_amount_accrued').textContent =
            '₱' + totalAccrued.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        document.getElementById('total_tax').textContent =
            '₱' + totalTax.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    // Initialize totals after DOM is ready
    document.addEventListener('DOMContentLoaded', updateTotals);
</script>
</body>
</html>