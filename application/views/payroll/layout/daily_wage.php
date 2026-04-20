<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>General Payroll</title>

<style>
@page {
    size: A4 landscape;
    margin: 10mm;
}

body {
    font-family: "Calibri", sans-serif;
    font-size: 11px;
    color: #000;
}

.header {
    text-align: center;
    margin-bottom: 10px;
}

.payroll-header {
    text-align: left;
    line-height: 1.1;
}

.payroll-header h2,
.payroll-header h3 {
    margin: 0;
}

.payroll-header h2 {
    font-size: 14px;
    font-weight: bold;
}

.payroll-header h3 {
    font-size: 12px;
}

.period {
    margin-top: 4px;
    font-weight: bold;
    font-size: 11px;
}

.note {
    margin-top: 4px;
    font-style: italic;
    font-size: 10px;
}

.header h2 {
    margin: 0;
    font-size: 14px;
}

.header h3 {
    margin: 0;
    font-size: 12px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #000;
    padding: 4px;
    font-size: 9px;
}

th {
    text-align: center;
    background: #f2f2f2;
}

.text-right { text-align: right; }
.text-center { text-align: center; }

.page-break {
    page-break-after: always;
}
</style>

</head>

<body>

<?php
// ================= HELPER =================
function getTotalOtherDeductions($str) {
    if (!$str) return 0;

    $total = 0;
    $items = explode(',', $str);

    foreach ($items as $item) {
        $parts = explode(':', $item);
        if (count($parts) === 2) {
            $total += floatval($parts[1]);
        }
    }

    return $total;
}

function formatOtherDeductions($str) {
    if (!$str) return '-';

    $output = '';
    $items = explode(',', $str);

    foreach ($items as $item) {
        $parts = explode(':', $item);
        if (count($parts) === 2) {
            $output .= $parts[0] . ': ₱' . number_format($parts[1], 2) . "<br>";
        }
    }

    return $output ?: '-';
}
?>

<div class="payroll-header">
    <h2>GENERAL PAYROLL</h2>
    <h3>EASTERN VISAYAS STATE UNIVERSITY</h3>
    <div>Tacloban City</div>
</div>

<div class="period">
    Payroll Period: <strong><?=$period->date_period?></strong><br>
    <?=$period->payroll_type?>
</div>

<div class="note">
    We acknowledge receipt of the sum shown opposite our names as full compensation
    for services rendered for the period stated.
</div>

<?php
$rowsPerPage = 25;
$pages = array_chunk($payroll, $rowsPerPage);
$totalPages = count($pages);

// GRAND TOTALS
$grand = [
    'days_worked'=>0,
    'basic_salary'=>0,
    'pera'=>0,
    'gross_pay'=>0,
    'gsis'=>0,
    'pagibig'=>0,
    'philhealth'=>0,
    'other'=>0,
    'net_pay'=>0
];
?>

<?php foreach ($pages as $pageIndex => $rows): ?>

<?php
$page = array_fill_keys(array_keys($grand), 0);
$rowNo = 1 + ($pageIndex * $rowsPerPage);
?>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Name</th>
    <th>Position</th>
    <th>Days Worked</th>
    <th>Rate/Day</th>
    <th>Basic Salary</th>
    <th>PERA</th>
    <th>Gross Pay</th>
    <th>GSIS</th>
    <th>Pag-IBIG</th>
    <th>PhilHealth</th>
    <th>Other Deductions</th>
    <th>Net Pay</th>
</tr>
</thead>

<tbody>

<?php foreach ($rows as $row): ?>

<?php
$otherVal = getTotalOtherDeductions($row->other_deductions);

// PAGE TOTALS
$page['days_worked'] += $row->days_worked;
$page['basic_salary'] += $row->basic_salary;
$page['pera'] += $row->pera;
$page['gross_pay'] += $row->gross_pay;
$page['gsis'] += $row->gsis;
$page['pagibig'] += $row->pagibig;
$page['philhealth'] += $row->philhealth;
$page['other'] += $otherVal;
$page['net_pay'] += $row->net_pay;

// GRAND TOTALS
$grand['days_worked'] += $row->days_worked;
$grand['basic_salary'] += $row->basic_salary;
$grand['pera'] += $row->pera;
$grand['gross_pay'] += $row->gross_pay;
$grand['gsis'] += $row->gsis;
$grand['pagibig'] += $row->pagibig;
$grand['philhealth'] += $row->philhealth;
$grand['other'] += $otherVal;
$grand['net_pay'] += $row->net_pay;
?>

<tr>
    <td class="text-center"><?= $rowNo++ ?></td>
    <td><?= htmlspecialchars($row->name) ?></td>
    <td><?= htmlspecialchars($row->position) ?></td>

    <td class="text-right"><?= number_format($row->days_worked,2) ?></td>
    <td class="text-right"><?= number_format($row->rate_per_day,2) ?></td>
    <td class="text-right"><?= number_format($row->basic_salary,2) ?></td>
    <td class="text-right"><?= number_format($row->pera,2) ?></td>
    <td class="text-right"><?= number_format($row->gross_pay,2) ?></td>

    <td class="text-right"><?= number_format($row->gsis,2) ?></td>
    <td class="text-right"><?= number_format($row->pagibig,2) ?></td>
    <td class="text-right"><?= number_format($row->philhealth,2) ?></td>

    <td class="text-right"><?= formatOtherDeductions($row->other_deductions) ?></td>

    <td class="text-right"><?= number_format($row->net_pay,2) ?></td>
</tr>

<?php endforeach; ?>

<!-- SUBTOTAL -->
<tr style="font-weight:bold; background:#f2f2f2;">
    <td colspan="3">SUBTOTAL</td>

    <td class="text-right">-</td>
    <td class="text-right">-</td>
    <td class="text-right"><?= number_format($page['basic_salary'],2) ?></td>
    <td class="text-right"><?= number_format($page['pera'],2) ?></td>
    <td class="text-right"><?= number_format($page['gross_pay'],2) ?></td>

    <td class="text-right"><?= number_format($page['gsis'],2) ?></td>
    <td class="text-right"><?= number_format($page['pagibig'],2) ?></td>
    <td class="text-right"><?= number_format($page['philhealth'],2) ?></td>

    <td class="text-right"><?= number_format($page['other'],2) ?></td>
    <td class="text-right"><?= number_format($page['net_pay'],2) ?></td>
</tr>

</tbody>
</table>

<?php if ($pageIndex < $totalPages - 1): ?>
<div class="page-break"></div>
<?php endif; ?>

<?php endforeach; ?>

<!-- GRAND TOTAL -->
<br>

<table>
<tr style="font-weight:bold; background:#d9edf7;">
    <td colspan="5">GRAND TOTAL</td>
    <td class="text-right"><?= number_format($grand['basic_salary'],2) ?></td>
    <td class="text-right"><?= number_format($grand['pera'],2) ?></td>
    <td class="text-right"><?= number_format($grand['gross_pay'],2) ?></td>
    <td class="text-right"><?= number_format($grand['gsis'],2) ?></td>
    <td class="text-right"><?= number_format($grand['pagibig'],2) ?></td>
    <td class="text-right"><?= number_format($grand['philhealth'],2) ?></td>
    <td class="text-right"><?= number_format($grand['other'],2) ?></td>
    <td class="text-right"><?= number_format($grand['net_pay'],2) ?></td>
</tr>
</table>

</body>
</html>