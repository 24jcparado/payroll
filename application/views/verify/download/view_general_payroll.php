<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $period ?></title>

<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon.png') ?>">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* General Page Styles */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
#mainContent {
    padding: 30px;
}

/* Header */
.payroll-header {
    text-align: center;
    margin-bottom: 35px;
}
.payroll-header img {
    width: 90px;
    margin-bottom: 10px;
}
.payroll-header h1, .payroll-header h5, .payroll-header .fw-bold {
    margin: 0;
}
.payroll-header .unit {
    margin-top: 8px;
    font-size: 14px;
}

/* Tracker Styles */
.payroll-tracker {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 30px 0;
}
.payroll-tracker::before {
    content: '';
    position: absolute;
    top: 22px;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: #e9ecef;
    z-index: 1;
}
.tracker-step {
    position: relative;
    text-align: center;
    z-index: 2;
    flex: 1;
}
.tracker-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto 8px auto;
}
.tracker-step.active .tracker-circle { background-color: #28a745; color: #fff; }
.tracker-step.current .tracker-circle { background-color: #ffc107; color: #000; }
.sub-process { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
.sub-step { font-size: 11px; padding: 3px 8px; border-radius: 20px; background-color: #dee2e6; }
.sub-step.active { background-color: #28a745; color: #fff; }
.sub-step.current { background-color: #ffc107; color: #000; }

/* Legend */
.tracker-legend { display: flex; gap: 20px; align-items: center; margin-bottom: 20px; font-size: 14px; }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-item::before { content: ''; width: 15px; height: 15px; border-radius: 50%; display: inline-block; }
.active-legend::before { background-color: #28a745; }
.current-legend::before { background-color: #ffc107; }
.pending-legend::before { background-color: #dee2e6; }

/* Summary Cards */
.card-summary {
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.card-summary .dashboard-icon i {
    font-size: 28px;
}

/* Payroll Table */
#savedPayrollTable {
    margin-top: 25px;
}
#savedPayrollTable th, #savedPayrollTable td {
    font-size: 0.85rem;
    padding: 0.5rem;
    white-space: nowrap;
}
#savedPayrollTable tbody tr:hover {
    background-color: #f1f3f5;
}
</style>
</head>
<body>

<main id="mainContent">

<!-- HEADER -->
<div class="payroll-header">
    <img src="<?= base_url('assets/img/favicon.png') ?>" alt="EVSU Logo">
    <div class="fw-semibold text-uppercase">Republic of the Philippines</div>
    <div class="fw-bold text-uppercase" style="font-size:18px;">Eastern Visayas State University</div>
    <div>Tacloban City</div>
    <div class="mt-3 fw-bold text-uppercase"><?= $payroll_type ?></div>
    <div class="unit"><strong>Unit:</strong> <?= $unit ?></div>
</div>

<!-- PAYROLL TABLE -->
<div class="card shadow-sm mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered small" id="savedPayrollTable">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2">Name</th>
                        <th rowspan="2">Position</th>
                        <th rowspan="2">Basic</th>
                        <th rowspan="2">Salary LWOP</th>
                        <th rowspan="2">PERA LWOP</th>
                        <th rowspan="2">Gross Pay</th>
                        
                        <!-- Mandatory deductions grouped -->
                        <th colspan="3" class="text-center">Mandatory Deductions</th>
                        
                        <th rowspan="2">W/ Tax</th>
                        <th rowspan="2">Other Deduction</th>
                        <th rowspan="2">Total Deductions</th>
                        <th rowspan="2">Net Pay</th>
                        <th rowspan="2">1st Quincena</th>
                        <th rowspan="2">2nd Quincena</th>
                    </tr>
                    <tr>
                        <th>GSIS</th>
                        <th>PhilHealth</th>
                        <th>Pag-IBIG</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payrolls as $payroll): ?> 
                    <tr>
                        <td><?= $payroll->name ?></td>
                        <td><?= $payroll->position ?></td>
                        <td>₱ <?= number_format($payroll->basic_salary,2) ?></td>
                        <td>₱ <?= number_format($payroll->salary_lwop,2) ?></td>
                        <td>₱ <?= number_format($payroll->pera,2) ?></td>
                        <td class="amount-accrued">₱ <?= number_format($payroll->gross_pay,2) ?></td>
                        
                        <!-- Mandatory deductions -->
                        <td class="gsis">₱ <?= number_format($payroll->gsis,2) ?></td>
                        <td class="philhealth">₱ <?= number_format($payroll->philhealth,2) ?></td>
                        <td class="pagibig">₱ <?= number_format($payroll->pagibig,2) ?></td>
                        
                        <td class="tax">₱ <?= number_format($payroll->tax,2) ?></td>
                        <td><?= $payroll->other_deductions ?></td>
                        <td class="tax-amount">₱ <?= number_format($payroll->total_deductions,2) ?></td>
                        <td class="netpay">₱ <?= number_format($payroll->net_pay,2) ?></td>
                        <td>₱ <?= number_format($payroll->net_pay_first,2) ?></td>
                        <td>₱ <?= number_format($payroll->net_pay_second,2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="6" class="text-center">TOTAL</td>

                        <td id="total_gsis">₱ 0.00</td>
                        <td id="total_philhealth">₱ 0.00</td>
                        <td id="total_pagibig">₱ 0.00</td>

                        <td id="total_tax">₱ 0.00</td>
                        <td></td>
                        <td></td>
                        <td id="total_netpay">₱ 0.00</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

</main>

<script>
function updateTotals() {
    let totalAccrued = 0, totalTax = 0;
    document.querySelectorAll('#savedPayrollTable td.amount-accrued').forEach(td => {
        totalAccrued += parseFloat(td.textContent.replace(/,/g, '')) || 0;
    });
    document.querySelectorAll('#savedPayrollTable td.tax-amount').forEach(td => {
        totalTax += parseFloat(td.textContent.replace(/,/g, '')) || 0;
    });

    document.getElementById('total_amount_accrued').textContent = 
        '₱' + totalAccrued.toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
    document.getElementById('total_tax').textContent =
        '₱' + totalTax.toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
}

document.addEventListener('DOMContentLoaded', updateTotals);

function parsePeso(value) {
    return parseFloat(value.replace(/[₱,]/g, '')) || 0;
}

function formatPeso(value) {
    return '₱ ' + value.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updateTotals() {
    let totalGSIS = 0,
        totalPhilhealth = 0,
        totalPagibig = 0,
        totalTax = 0,
        totalNetPay = 0;

    document.querySelectorAll('#savedPayrollTable tbody tr').forEach(row => {
        totalGSIS += parsePeso(row.querySelector('.gsis').textContent);
        totalPhilhealth += parsePeso(row.querySelector('.philhealth').textContent);
        totalPagibig += parsePeso(row.querySelector('.pagibig').textContent);
        totalTax += parsePeso(row.querySelector('.tax').textContent);
        totalNetPay += parsePeso(row.querySelector('.netpay').textContent);
    });

    document.getElementById('total_gsis').textContent = formatPeso(totalGSIS);
    document.getElementById('total_philhealth').textContent = formatPeso(totalPhilhealth);
    document.getElementById('total_pagibig').textContent = formatPeso(totalPagibig);
    document.getElementById('total_tax').textContent = formatPeso(totalTax);
    document.getElementById('total_netpay').textContent = formatPeso(totalNetPay);
}

document.addEventListener('DOMContentLoaded', updateTotals);
</script>

</body>
</html>