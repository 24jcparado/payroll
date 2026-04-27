<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mid-Year Bonus Payroll - EVSU</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-section {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            position: relative;
        }
        .header-section h2 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .header-section h3 { font-size: 13px; margin: 2px 0; }
        
        .qr-container {
            position: absolute;
            right: 0;
            top: 0;
            text-align: center;
        }
        .qr-code-img {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
        }

        .info-row { margin-bottom: 8px; width: 100%; }
        .period-box { float: left; width: 50%; }
        .notice-box { float: right; width: 45%; font-style: italic; font-size: 8px; text-align: right; }
        .clearfix { clear: both; }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 3px;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-size: 8px;
            text-transform: uppercase;
            vertical-align: middle;
            text-align: center;
        }
        td { font-size: 9px; vertical-align: middle; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        .subtotal-row { background-color: #f9f9f9; font-style: italic; font-weight: bold; }
        .grand-total-row { background-color: #eee; font-weight: bold; }

        .signature-table { border: none; margin-top: 20px; width: 100%; }
        .signature-table td { border: none; padding: 10px 5px; text-align: center; vertical-align: bottom; }
        .sig-line { border-top: 1px solid #000; width: 90%; margin: 0 auto 2px auto; font-weight: bold; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<?php
// SETTINGS
$rowsPerPage = 15; 
$chunks = array_chunk($payroll, $rowsPerPage);
$totalPages = count($chunks);

// Initialize Grand Totals
$grand = [
    'basic_salary' => 0, 
    'gross_pay' => 0, 
    'tax' => 0, 
    'total_deductions' => 0, 
    'net_pay' => 0
];
$grandOther = array_fill_keys($otherColumns, 0);
$rowNo = 1;

foreach ($chunks as $pageIdx => $pageRows):
    $pageNo = $pageIdx + 1;
    
    // Initialize Page Subtotals
    $pt = array_fill_keys(array_keys($grand), 0);
    $ptOther = array_fill_keys($otherColumns, 0);
?>

<div class="header-section">
    <div class="qr-container">
        <img src="<?=base_url($period->qr_code)?>" class="qr-code-img">
        <div style="font-size: 7px; font-family: monospace;"><?= $period->token_id ?></div>
    </div>
    <h3>Republic of the Philippines</h3>
    <h2>Eastern Visayas State University</h2>
    <div style="font-size: 10px;">Tacloban City</div>
    <h2 style="margin-top: 8px; border-top: 1px solid #eee; padding-top: 5px;">Mid-Year Bonus Payroll</h2>
</div>

<div class="info-row">
    <div class="period-box">
        Calendar Year: <span class="fw-bold"><?= date('Y', strtotime($period->date_period)) ?></span><br>
        Fund Source: <span class="fw-bold"><?= $period->payroll_type ?></span>
    </div>
    <div class="notice-box">
        "We acknowledge receipt of the Mid-Year Bonus shown opposite our names as full compensation for services rendered."
    </div>
    <div class="clearfix"></div>
</div>

<table>
    <thead>
        <tr>
            <th width="30" rowspan="2">No.</th>
            <th width="150" rowspan="2">Name</th>
            <th width="100" rowspan="2">Position</th>
            <th width="80" rowspan="2">Monthly Basic<br>Salary</th>
            <th width="80" rowspan="2">Gross Mid-Year<br>Bonus</th>
            <th colspan="<?= 1 + count($otherColumns) ?>">Deductions</th>
            <th width="80" rowspan="2">Total<br>Deductions</th>
            <th width="90" rowspan="2">Net Bonus<br>Amount</th>
        </tr>
        <tr>
            <th width="60">W/Tax</th>
            <?php foreach($otherColumns as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pageRows as $row): 
            // Add to Page Subtotal
            $pt['basic_salary'] += $row->basic_salary;
            $pt['gross_pay'] += $row->gross_pay;
            $pt['tax'] += $row->tax;
            $pt['total_deductions'] += $row->total_deductions;
            $pt['net_pay'] += $row->net_pay;

            // Add to Grand Total
            $grand['basic_salary'] += $row->basic_salary;
            $grand['gross_pay'] += $row->gross_pay;
            $grand['tax'] += $row->tax;
            $grand['total_deductions'] += $row->total_deductions;
            $grand['net_pay'] += $row->net_pay;
        ?>
        <tr>
            <td class="text-center"><?= $rowNo++ ?></td>
            <td class="fw-bold"><?= htmlspecialchars($row->name) ?></td>
            <td><?= htmlspecialchars($row->position) ?></td>
            <td class="text-right"><?= number_format($row->basic_salary, 2) ?></td>
            <td class="text-right fw-bold"><?= number_format($row->gross_pay, 2) ?></td>
            <td class="text-right"><?= number_format($row->tax, 2) ?></td>
            <?php foreach($otherColumns as $col): 
                $val = $row->parsed_deductions[$col] ?? 0;
                $ptOther[$col] += $val;
                $grandOther[$col] += $val;
            ?>
                <td class="text-right"><?= number_format($val, 2) ?></td>
            <?php endforeach; ?>
            <td class="text-right fw-bold"><?= number_format($row->total_deductions, 2) ?></td>
            <td class="text-right fw-bold" style="background-color: #fcfcfc;">₱ <?= number_format($row->net_pay, 2) ?></td>
        </tr>
        <?php endforeach; ?>

        <tr class="subtotal-row">
            <td colspan="3" class="text-center">PAGE SUBTOTAL (Page <?= $pageNo ?> of <?= $totalPages ?>)</td>
            <td class="text-right"><?= number_format($pt['basic_salary'], 2) ?></td>
            <td class="text-right"><?= number_format($pt['gross_pay'], 2) ?></td>
            <td class="text-right"><?= number_format($pt['tax'], 2) ?></td>
            <?php foreach($otherColumns as $col): ?>
                <td class="text-right"><?= number_format($ptOther[$col], 2) ?></td>
            <?php endforeach; ?>
            <td class="text-right"><?= number_format($pt['total_deductions'], 2) ?></td>
            <td class="text-right">₱ <?= number_format($pt['net_pay'], 2) ?></td>
        </tr>

        <?php if ($pageNo == $totalPages): ?>
        <tr class="grand-total-row">
            <td colspan="3" class="text-center">GRAND TOTAL</td>
            <td class="text-right"><?= number_format($grand['basic_salary'], 2) ?></td>
            <td class="text-right"><?= number_format($grand['gross_pay'], 2) ?></td>
            <td class="text-right"><?= number_format($grand['tax'], 2) ?></td>
            <?php foreach($otherColumns as $col): ?>
                <td class="text-right"><?= number_format($grandOther[$col], 2) ?></td>
            <?php endforeach; ?>
            <td class="text-right"><?= number_format($grand['total_deductions'], 2) ?></td>
            <td class="text-right">₱ <?= number_format($grand['net_pay'], 2) ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($pageNo == $totalPages): ?>
<div style="margin-top: 10px; border: 1px solid #000; padding: 8px;">
    <table style="border: none; width: 100%; margin: 0;">
        <tr style="border: none;">
            <td style="border: none; width: 60%; font-size: 10px;">
                Total Amount in Words: <br>
                <strong style="text-transform: uppercase;"><?= number_to_words($grand['net_pay']) ?> PESOS ONLY</strong>
            </td>
            <td style="border: none; width: 40%; text-align: right; font-size: 12px;">
                Total Net Amount: <span class="fw-bold">₱ <?= number_format($grand['net_pay'], 2) ?></span>
            </td>
        </tr>
    </table>
</div>

<table class="signature-table">
    <tr>
        <td>Prepared by:<br><br><br><div class="sig-line">Personnel Clerk</div>Designation</td>
        <td>Certified Correct:<br><br><br><div class="sig-line">University Accountant</div>Accountant III</td>
        <td>Certified Services Rendered:<br><br><br><div class="sig-line">DR. DORIS ANN S. ESPINA</div>CAO, Admin Services</td>
    </tr>
    <tr>
        <td>Approved for Payment:<br><br><br><div class="sig-line">DR. LYDIA M. MORANTE</div>VPAA / OIC President</td>
        <td>Approved by:<br><br><br><div class="sig-line">Head of Agency</div>Authorized Representative</td>
        <td>Verified by:<br><br><br><div class="sig-line">Internal Auditor</div>Audit Section</td>
    </tr>
</table>
<?php endif; ?>

<?php if ($pageNo < $totalPages): ?>
    <div class="page-break"></div>
<?php endif; ?>

<?php endforeach; ?>

</body>
</html>