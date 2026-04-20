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
<?php
$deduction_names = [];

foreach ($payrolls as $payroll) {
    if (!empty($payroll['less'])) {
        $items = explode(',', $payroll['less']);
        foreach ($items as $item) {
            $parts = explode(':', $item);
            $name = trim($parts[0]);
            if (!in_array($name, $deduction_names)) {
                $deduction_names[] = $name;
            }
        }
    }
}
?>
<body>
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
        <?=$payroll_type?>
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
                <!-- TABLE -->
                <?php

                $total_basic = 0;
                $total_bonus = 0;
                $total_tax = 0;
                $total_net = 0;
                $total_deductions = 0;

                $deduction_totals = array_fill_keys($deduction_names, 0);

                foreach ($payrolls as $payroll) {

                    $total_basic += $payroll['basic_salary'];
                    $total_bonus += $payroll['gross_pay'];
                    $total_tax += $payroll['tax'];
                    $total_net += $payroll['net_pay'];
                    $total_deductions += $payroll['total_deductions'];

                    if (!empty($payroll['less'])) {
                        $items = explode(',', $payroll['less']);

                        foreach ($items as $item) {
                            $parts = explode(':', $item);
                            $name = trim($parts[0]);
                            $amount = floatval($parts[1] ?? 0);

                            if(isset($deduction_totals[$name])){
                                $deduction_totals[$name] += $amount;
                            }
                        }
                    }
                }

                ?>
                <table class="table table-xsm table-bordered" id=" savedPayrollTable">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">NAME</th>
                            <th rowspan="2">POSITION</th>
                            <th rowspan="2">BASIC PAY</th>
                            <th rowspan="2">MIDYEAR BONUS</th>
                            <th colspan="<?=count($deduction_names)?>">LESS</th>
                            <th rowspan="2">TAX</th>
                            <th rowspan="2">TOTAL DEDUCTION</th>
                            <th rowspan="2">NET PAY</th>
                        </tr>
                        <tr>
                        <?php foreach ($deduction_names as $d): ?>
                            <th><?=$d?></th>
                        <?php endforeach ?>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($payrolls as $payroll): ?>

                        <?php
                        $less_values = [];
                        if (!empty($payroll['less'])) {
                            $items = explode(',', $payroll['less']);
                            foreach ($items as $item) {
                                $parts = explode(':', $item);
                                $less_values[trim($parts[0])] = $parts[1] ?? 0;
                            }
                        }
                        ?>

                    <tr>
                        <td><?=$payroll['name']?></td>
                        <td><?=$payroll['position']?></td>
                        <td class="text-end">₱ <?=number_format($payroll['basic_salary'],2)?></td>
                        <td class="text-end amount-accrued">₱ <?=number_format($payroll['gross_pay'],2)?></td>

                        <?php foreach ($deduction_names as $d): ?>
                        <td class="text-end">₱ 
                            <?=isset($less_values[$d]) ? number_format($less_values[$d],2) : ''?>
                        </td>
                        <?php endforeach ?>
                        <td class="text-end">₱ <?=number_format($payroll['tax'],2)?></td>
                        <td class="text-end total-deduction">₱ <?=number_format($payroll['total_deductions'],2)?></td>
                        <td class="text-end">₱ <?=number_format($payroll['net_pay'],2)?></td>

                    </tr>

                    <?php endforeach ?>

                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-end">₱ <?=number_format($total_basic,2)?></td>
                        <td class="text-end">₱ <?=number_format($total_bonus,2)?></td>
                        <?php foreach ($deduction_names as $d): ?>
                        <td class="text-end">
                            <?=number_format($deduction_totals[$d],2)?>
                        </td>
                        <?php endforeach ?>
                        <td class="text-end">₱ <?=number_format($total_tax,2)?></td>
                        <td class="text-end">₱ <?=number_format($total_deductions,2)?></td>
                        <td class="text-end">₱ <?=number_format($total_net,2)?></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

</main>

</body>
</html>