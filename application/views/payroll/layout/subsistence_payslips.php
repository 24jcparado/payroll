<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVSU Subsistence Pay Slip</title>
    <style>
        @page {
            size: 8.5in 13in; /* Long Bond / Legal */
            margin: 0.5in;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .payslip {
            width: 49%;
            height: 5.8in;
            box-sizing: border-box;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 0.2in;
            position: relative;
            page-break-inside: avoid;
        }

        .payslip:nth-child(4n) {
            page-break-after: always;
        }

        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #800000; /* EVSU Maroon */
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .logo {
            width: 45px;
            height: 45px;
            margin-right: 10px;
        }

        .header-text h2 {
            margin: 0;
            font-size: 13px;
            color: #800000;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 9px;
            font-weight: bold;
        }

        .period-label {
            background: #f4f4f4;
            padding: 2px 6px;
            display: inline-block;
            margin-top: 3px;
            border-radius: 3px;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
            padding: 2px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 9px;
            color: #800000;
            margin-bottom: 3px;
            border-left: 3px solid #800000;
            padding-left: 5px;
            background: #fdf2f2;
        }

        .right { text-align: right; }
        .bold { font-weight: bold; }
        .text-maroon { color: #800000; }

        .footer-sig {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .sig-box {
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            margin-top: 20px;
            font-size: 8px;
            padding-top: 2px;
        }

        @media print {
            body { background: none; }
            .payslip { border: 1px solid #000; }
        }
    </style>
</head>
<body onload="window.print()">

<?php foreach ($payrolls as $p): ?>

<div class="payslip">
    <div class="header-container">
        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo" alt="EVSU Logo">
        <div class="header-text">
            <h2>Subsistence & Laundry Slip</h2>
            <p>EASTERN VISAYAS STATE UNIVERSITY</p>
            <span class="period-label">Period: <?= date('F d, Y', strtotime($p['date_period'])) ?></span>
        </div>
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="100%"><strong>Name:</strong> <span style="font-size: 11px; text-transform: uppercase;"><?= htmlspecialchars($p['name']) ?></span></td>
        </tr>
        <tr>
            <td width="100%"><strong>Position:</strong> <?= htmlspecialchars($p['position']) ?></td>
        </tr>
    </table>

    <div class="section-title">Earnings (Allowances)</div>
    <table>
        <tr>
            <td width="70%">Net Subsistence (<?= $p['days_present'] ?> days @ 50.00)</td>
            <td class="right" width="30%">₱ <?= number_format((float)$p['base_allowance'], 2) ?></td>
        </tr>
        <tr>
            <td width="70%">Laundry Allowance</td>
            <td class="right" width="30%">₱ <?= number_format((float)$p['laundry_allowance'], 2) ?></td>
        </tr>
        <tr class="bold" style="background-color: #fff9f9;">
            <td>Gross Amount</td>
            <td class="right text-maroon">₱ <?= number_format((float)$p['gross_pay'], 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Deductions</div>
    <table>
        <tr>
            <td width="70%">Withholding Tax</td>
            <td class="right" width="30%">₱ <?= number_format((float)$p['tax'], 2) ?></td>
        </tr>
        <?php 
        $loan_total = 0;
        if (!empty($p['less'])): 
            $adjustments = explode(',', $p['less']);
            foreach ($adjustments as $adjustment):
                $parts = explode(':', $adjustment);
                if (count($parts) == 2):
                    $adj_name = trim($parts[0]);
                    $adj_amount = (float) trim($parts[1]); 
                    $loan_total += $adj_amount;
        ?>
                    <tr>
                        <td width="70%"><?= htmlspecialchars($adj_name) ?></td>
                        <td class="right" width="30%">₱ <?= number_format($adj_amount, 2) ?></td>
                    </tr>
        <?php 
                endif;
            endforeach;
        endif; 
        ?>
        <tr class="bold" style="background-color: #fff9f9;">
            <td class="right">Total Deductions</td>
            <td class="right text-maroon">₱ <?= number_format($loan_total + (float)$p['tax'], 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Summary</div>
    <table>
        <tr class="bold" style="font-size: 12px; background-color: #f8f8f8;">
            <td width="70%">NET AMOUNT DUE</td>
            <td class="right text-maroon" width="30%">₱ <?= number_format((float)$p['net_pay'], 2) ?></td>
        </tr>
    </table>

    <div class="footer-sig">
        <div class="sig-box">Certified Correct by HR/Finance</div>
        <div class="sig-box">Received by (Signature)</div>
    </div>
</div>

<?php endforeach; ?>

</body>
</html>