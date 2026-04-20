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

/* ===== DOMPDF SAFE BASE ===== */
body {
    font-family: "Calibri", sans-serif;
    font-size: 12px;           /* MUST be small for payroll */
    color: #000;
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

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
}

th, td {
    border: 1px solid #000;
    padding: 3px;
    vertical-align: middle;
}

th {
    text-align: center;
    font-size: 10px;
    font-weight: bold;
}

td {
    font-size: 9px;
}

/* ===== ALIGNMENTS ===== */
.text-left   { text-align: left; }
.text-center { text-align: center; }
.text-right  { text-align: right; }

/* ===== SIGNATURES ===== */
.signature-table td {
    border: none;
    padding-top: 18px;
    font-size: 10px;
}
</style>

</head>

<body>

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
$grandTotal = 0;

foreach ($payroll as $row) {
    $grandTotal += $row->net_pay;
}

$totalInWords = number_to_words($grandTotal);

$rowsPerPage = 20;
$pages = array_chunk($payroll, $rowsPerPage);
$totalPages = count($pages);

// ============================
// GRAND TOTALS
// ============================
$grand = [
    'basic_salary'=>0,'pera'=>0,'gross_pay'=>0,'lwop'=>0,'tax'=>0,
    'gsis'=>0,'philhealth'=>0,'pagibig'=>0,'total_deductions'=>0,
    'net_pay'=>0,'first'=>0,'second'=>0
];

$grandOther = array_fill_keys($otherColumns, 0);
?>

<style>
table {
    border-collapse: collapse;
    width: 100%;
    font-size: 11px;
}

th, td {
    border: 1px solid #000;
    padding: 4px;
}

thead {
    display: table-header-group;
}

tr {
    page-break-inside: avoid;
}

.page-break {
    page-break-after: always;
}
</style>

<?php $pageNo = 0; foreach ($pages as $pageRows): $pageNo++; ?>

<?php
// ============================
// RESET PAGE TOTALS
// ============================
$pt = array_fill_keys(array_keys($grand), 0);
$ptOther = array_fill_keys($otherColumns, 0);

$rowNo = 1 + ($pageNo - 1) * $rowsPerPage;
?>

<table>
<thead>
<tr>
    <th rowspan="2">No.</th>
    <th rowspan="2">Name</th>
    <th rowspan="2">Position</th>
    <th rowspan="2">Basic Salary</th>
    <th rowspan="2">ACA PERA</th>
    <th rowspan="2">Gross Pay</th>

    <th colspan="<?= 5 + count($otherColumns) ?>">Deductions</th>

    <th rowspan="2">Total Deductions</th>
    <th rowspan="2">Net Pay</th>
    <th rowspan="2">First</th>
    <th rowspan="2">Last</th>
</tr>

<tr>
    <th>LWOP</th>
    <th>W/Tax</th>
    <th>GSIS</th>
    <th>PhilHealth</th>
    <th>Pag-ibig</th>

    <?php foreach($otherColumns as $col): ?>
        <th><?= htmlspecialchars($col) ?></th>
    <?php endforeach; ?>
</tr>
</thead>

<tbody>

<?php foreach ($pageRows as $row): ?>

<?php
// ============================
// ACCUMULATE PAGE + GRAND TOTALS
// ============================
$pt['basic_salary'] += $row->basic_salary;
$pt['pera'] += $row->pera;
$pt['gross_pay'] += $row->gross_pay;
$pt['lwop'] += $row->lwop_amount;
$pt['tax'] += $row->tax;
$pt['gsis'] += $row->gsis;
$pt['philhealth'] += $row->philhealth;
$pt['pagibig'] += $row->pagibig;
$pt['total_deductions'] += $row->total_deductions;
$pt['net_pay'] += $row->net_pay;
$pt['first'] += $row->net_pay_first;
$pt['second'] += $row->net_pay_second;

$grand['basic_salary'] += $row->basic_salary;
$grand['pera'] += $row->pera;
$grand['gross_pay'] += $row->gross_pay;
$grand['lwop'] += $row->lwop_amount;
$grand['tax'] += $row->tax;
$grand['gsis'] += $row->gsis;
$grand['philhealth'] += $row->philhealth;
$grand['pagibig'] += $row->pagibig;
$grand['total_deductions'] += $row->total_deductions;
$grand['net_pay'] += $row->net_pay;
$grand['first'] += $row->net_pay_first;
$grand['second'] += $row->net_pay_second;

// OTHER DEDUCTIONS
foreach($otherColumns as $col){
    $val = $row->parsed_deductions[$col] ?? 0;
    $ptOther[$col] += $val;
    $grandOther[$col] += $val;
}
?>

<tr>
    <td><?= $rowNo++ ?></td>
    <td><?= htmlspecialchars($row->name) ?></td>
    <td><?= htmlspecialchars($row->position) ?></td>

    <td>₱ <?= number_format($row->basic_salary,2) ?></td>
    <td>₱ <?= number_format($row->pera,2) ?></td>
    <td>₱ <?= number_format($row->gross_pay,2) ?></td>

    <td>₱ <?= number_format($row->lwop_amount,2) ?></td>
    <td>₱ <?= number_format($row->tax,2) ?></td>
    <td>₱ <?= number_format($row->gsis,2) ?></td>
    <td>₱ <?= number_format($row->philhealth,2) ?></td>
    <td>₱ <?= number_format($row->pagibig,2) ?></td>

    <?php foreach($otherColumns as $col): ?>
        <td>₱ <?= number_format($row->parsed_deductions[$col] ?? 0,2) ?></td>
    <?php endforeach; ?>

    <td>₱ <?= number_format($row->total_deductions,2) ?></td>
    <td>₱ <?= number_format($row->net_pay,2) ?></td>
    <td>₱ <?= number_format($row->net_pay_first,2) ?></td>
    <td>₱ <?= number_format($row->net_pay_second,2) ?></td>
</tr>

<?php endforeach; ?>

<!-- ============================
SUBTOTAL PER PAGE (ALWAYS CORRECT)
============================ -->
<tr style="font-weight:bold; background:#f2f2f2;">
    <td colspan="3">SUBTOTAL (PAGE <?= $pageNo ?>)</td>

    <td>₱ <?= number_format($pt['basic_salary'],2) ?></td>
    <td>₱ <?= number_format($pt['pera'],2) ?></td>
    <td>₱ <?= number_format($pt['gross_pay'],2) ?></td>

    <td>₱ <?= number_format($pt['lwop'],2) ?></td>
    <td>₱ <?= number_format($pt['tax'],2) ?></td>
    <td>₱ <?= number_format($pt['gsis'],2) ?></td>
    <td>₱ <?= number_format($pt['philhealth'],2) ?></td>
    <td>₱ <?= number_format($pt['pagibig'],2) ?></td>

    <?php foreach($otherColumns as $col): ?>
        <td>₱ <?= number_format($ptOther[$col],2) ?></td>
    <?php endforeach; ?>

    <td>₱ <?= number_format($pt['total_deductions'],2) ?></td>
    <td>₱ <?= number_format($pt['net_pay'],2) ?></td>
    <td>₱ <?= number_format($pt['first'],2) ?></td>
    <td>₱ <?= number_format($pt['second'],2) ?></td>
</tr>

</tbody>
</table>

<!-- PAGE BREAK (SAFE) -->
<?php if ($pageNo < $totalPages): ?>
<div class="page-break"></div>
<?php endif; ?>

<?php endforeach; ?>

<!-- ============================
GRAND TOTAL (LAST PAGE ONLY)
============================ -->
<table border="1" width="100%" cellspacing="0" cellpadding="5">
<tr style="font-weight:bold; background:#d9edf7;">
    <td colspan="3">GRAND TOTAL</td>

    <td>₱ <?= number_format($grand['basic_salary'],2) ?></td>
    <td>₱ <?= number_format($grand['pera'],2) ?></td>
    <td>₱ <?= number_format($grand['gross_pay'],2) ?></td>

    <td>₱ <?= number_format($grand['lwop'],2) ?></td>
    <td>₱ <?= number_format($grand['tax'],2) ?></td>
    <td>₱ <?= number_format($grand['gsis'],2) ?></td>
    <td>₱ <?= number_format($grand['philhealth'],2) ?></td>
    <td>₱ <?= number_format($grand['pagibig'],2) ?></td>

    <?php foreach($otherColumns as $col): ?>
        <td>₱ <?= number_format($grandOther[$col],2) ?></td>
    <?php endforeach; ?>

    <td>₱ <?= number_format($grand['total_deductions'],2) ?></td>
    <td>₱ <?= number_format($grand['net_pay'],2) ?></td>
    <td>₱ <?= number_format($grand['first'],2) ?></td>
    <td>₱ <?= number_format($grand['second'],2) ?></td>
</tr>
</table>

<br><br>

<table width="100%">
    <tr>
        <td width="33%" class="text-center">
            CERTIFIED: Services has been duly rendered as certified by the DEAN<br><br>
            ___________________________<br>
            DR. DORIS ANN S. ESPINA <br>
            CAO, Administrative Services
        </td>

        <td width="33%">

            <b>APPROVED FOR PAYMENT</b><br><br>

            <b>TOTAL AMOUNT:</b><br>
            ₱ <?= number_format($grandTotal, 2) ?><br><br>

            <b>IN WORDS:</b><br>
            <?= $totalInWords ?><br><br><br><br>

            <!-- CENTERED SIGNATORY ONLY -->
            <div style="text-align:center;">
                ___________________________<br>
                <b>DR. LYDIA M. MORANTE</b><br>
                VPAA
            </div>

        </td>

        <td width="33%" class="text-center">
            Approved by:<br><br>
            ___________________________<br>
            Head of Agency
        </td>
    </tr>
    <tr>
        <td width="33%" class="text-center">
            Prepared by:<br><br>
            ___________________________<br>
            Payroll Clerk
        </td>

        <td width="33%" class="text-center">
            Certified Correct:<br><br>
            ___________________________<br>
            Accountant
        </td>

        <td width="33%" class="text-center">
            Approved by:<br><br>
            ___________________________<br>
            Head of Agency
        </td>
    </tr>
</table>
</body>
</html>
