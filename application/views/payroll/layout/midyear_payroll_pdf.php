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
            padding: 2px 1px;
            word-wrap: break-word;
        }
        
        .col-no { 
            width: 20px !important; 
            max-width: 20px !important;
            padding: 2px 0px !important; 
            text-align: center !important; 
            overflow: hidden;
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

        .cert-container { 
            width: 100%; 
            border: 1px solid #000; 
            margin-top: 15px; 
            border-collapse: collapse; 
            display: table; 
            page-break-inside: avoid; /* Keeps the footer from breaking in half */
        }
        .cert-row { display: table-row; }
        .cert-box { display: table-cell; border: 1px solid #000; padding: 8px; vertical-align: top; width: 50%; height: 100px; }
        .cert-title { font-weight: bold; font-size: 8.5px; margin-bottom: 5px; }
        .signature-line { text-align: center; font-weight: bold; font-size: 10px; width: 85%; margin: 0 auto; border-top: 1px solid #000; padding-top: 2px; }
        .footer-table { width: 100%; font-size: 8px; margin-top: 5px; border: none; }
        .footer-table td { border: none !important; padding: 1px !important; }
        
        tr { page-break-inside: avoid; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<?php
// 1. Sort alphabetically
usort($payroll, function($a, $b) {
    return strcmp($a->last_name, $b->last_name);
});

// 2. STRICT CHUNKING - NO MORE HALVING/SPLITTING LOGIC
// Adjust this exact number to fit your page perfectly. If 20 leaves room, try 21 or 22.
$rowsPerPage = 20; 
$chunks = array_chunk($payroll, $rowsPerPage);

$totalPages = count($chunks);
$grand = ['basic_salary' => 0, 'gross_pay' => 0, 'tax' => 0, 'total_deductions' => 0, 'net_pay' => 0];
$grandOther = array_fill_keys($otherColumns, 0);
$rowNo = 1;

foreach ($chunks as $pageIdx => $pageRows):
    $pageNo = $pageIdx + 1;
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
        Unit: <span class="fw-bold"><?= $period->unit ?></span>
    </div>
    <div class="notice-box">"We acknowledge receipt of the Mid-Year Bonus shown opposite our names."</div>
    <div class="clearfix"></div>
</div>

<table>
    <thead>
        <tr>
            <th class="col-no" rowspan="2">No.</th>
            <th width="150" rowspan="2">Name<br>(Last, First, M.I.)</th>
            <th width="100" rowspan="2">Position</th>
            <th width="80" rowspan="2">Monthly Basic Salary</th>
            <th width="80" rowspan="2">Gross Mid-Year Bonus</th>
            <th colspan="<?= 1 + count($otherColumns) ?>">Deductions</th>
            <th width="80" rowspan="2">Total Deductions</th>
            <th width="90" rowspan="2">Net Bonus Amount</th>
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
            $mi = !empty($row->middle_name) ? substr($row->middle_name, 0, 1) . '.' : '';
            $displayName = strtoupper($row->last_name . ', ' . $row->name . ' ' . $mi);

            $current_row_other_deductions = 0;
            foreach($otherColumns as $col) {
                $val = $row->parsed_deductions[$col] ?? 0;
                $current_row_other_deductions += $val;
                $ptOther[$col] += $val;
                $grandOther[$col] += $val;
            }
            
            $calculated_total_deductions = $row->tax + $current_row_other_deductions;

            $pt['basic_salary'] += $row->basic_salary; $pt['gross_pay'] += $row->gross_pay; $pt['tax'] += $row->tax;
            $pt['total_deductions'] += $calculated_total_deductions; $pt['net_pay'] += $row->net_pay;

            $grand['basic_salary'] += $row->basic_salary; $grand['gross_pay'] += $row->gross_pay; $grand['tax'] += $row->tax;
            $grand['total_deductions'] += $calculated_total_deductions; $grand['net_pay'] += $row->net_pay;
        ?>
        <tr>
            <td class="col-no"><?= $rowNo++ ?></td>
            <td class="fw-bold"><?= $displayName ?></td>
            <td><?= htmlspecialchars($row->position) ?></td>
            <td class="text-right"><?= number_format($row->basic_salary, 2) ?></td>
            <td class="text-right fw-bold"><?= number_format($row->gross_pay, 2) ?></td>
            <td class="text-right"><?= number_format($row->tax, 2) ?></td>
            <?php foreach($otherColumns as $col): ?>
                <td class="text-right"><?= number_format($row->parsed_deductions[$col] ?? 0, 2) ?></td>
            <?php endforeach; ?>
            <td class="text-right fw-bold"><?= number_format($calculated_total_deductions, 2) ?></td>
            <td class="text-right fw-bold" style="background-color: #fcfcfc;">₱ <?= number_format($row->net_pay, 2) ?></td>
        </tr>
        <?php endforeach; ?>

        <!-- Guaranteed to render right below the rows -->
        <tr class="subtotal-row">
            <td colspan="3" class="text-center">PAGE SUBTOTAL (Page <?= $pageNo ?>)</td>
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
<div class="cert-container">
    <div class="cert-row">
        <div class="cert-box">
            <div class="cert-title">A. CERTIFIED: Services has been duly rendered as stated</div>
            <div class="signature-line" style="margin-top: 45px;">DR. DORIS ANN S. ESPINA<br><span style="font-weight: normal; font-size: 8px;">CAO, Adm. Services Division</span></div>
        </div>
        <div class="cert-box">
            <div class="cert-title">B. APPROVED FOR PAYMENT:</div>
            <p class="text-center fw-bold" style="margin-top: 10px; font-size: 8.5px; text-transform: uppercase;"><?= ucwords(number_to_words($grand['net_pay'])) ?> and <?= date('s') ?>/100 Pesos (₱<?= number_format($grand['net_pay'], 2) ?>)</p>
            <div class="signature-line" style="margin-top: 40px;">DR. LYDIA M. MORANTE<br><span style="font-weight: normal; font-size: 8px;">VP Admin. and Finance</span></div>
        </div>
    </div>
    <div class="cert-row">
        <div class="cert-box">
            <div class="cert-title">C. CERTIFIED: Supporting documents complete and proper and cash available in the amount of</div>
            <div class="text-right fw-bold" style="padding-right: 10px; font-size: 10px;"><?= number_format($grand['net_pay'], 2) ?></div>
            <div class="signature-line" style="margin-top: 40px;">RUBY N. MANCIO, CPA<br><span style="font-weight: normal; font-size: 8px;">Head Accounting Office</span></div>
        </div>
        <div class="cert-box">
            <div class="cert-title">D. CERTIFIED: Each employee whose name appears above has been paid the amount indicated opposite his/her name.</div>
            <div class="signature-line" style="margin-top: 40px;">LEAH S. BELEÑA<br><span style="font-weight: normal; font-size: 8px;">Head Cashiering Office</span></div>
            <table class="footer-table"><tr><td>OS/BUS/US No.</td><td class="text-right">Date:</td></tr><tr><td>JEV No.</td><td class="text-right">Date:</td></tr></table>
        </div>
    </div>
</div>

<div style="margin-top: 20px; width: 100%; page-break-inside: avoid;">
    <p style="font-size: 9px; margin: 0;">Prepared by:</p>
    <p class="fw-bold" style="font-size: 11px; margin-top: 15px; text-decoration: underline; display: inline-block; text-transform: uppercase;">HONEYLEE F. CADAVIS, MM</p>
    <p style="margin: 0; font-size: 9px;">SAO / HEAD, HRMDO</p>
</div>
<?php endif; ?>

<?php if ($pageNo < $totalPages): ?> <div class="page-break"></div> <?php endif; ?>
<?php endforeach; ?>

</body>
</html>