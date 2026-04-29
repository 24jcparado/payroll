<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVSU Hazard Pay Slip</title>
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

        /* Forces a clear page break every 4 payslips */
        .payslip:nth-child(4n) {
            page-break-after: always;
        }

        /* Header Section */
        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #800000; /* EVSU Maroon */
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .header-text {
            flex-grow: 1;
        }

        .header-text h2 {
            margin: 0;
            font-size: 14px;
            color: #800000;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 10px;
            font-weight: bold;
        }

        .period-label {
            background: #f4f4f4;
            padding: 3px 8px;
            display: inline-block;
            margin-top: 5px;
            border-radius: 3px;
            font-size: 9px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th {
            background-color: #f9f9f9;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #666;
            padding: 4px;
            border: 1px solid #ddd;
        }

        td {
            border: 1px solid #ddd;
            padding: 6px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
            padding: 2px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 10px;
            color: #800000;
            margin-bottom: 4px;
            border-left: 3px solid #800000;
            padding-left: 5px;
        }

        .right { text-align: right; }
        .bold { font-weight: bold; }
        .text-maroon { color: #800000; }

        .footer-sig {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .sig-box {
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            margin-top: 25px;
            font-size: 9px;
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
            <h2>Hazard Pay Slip</h2>
            <p>EASTERN VISAYAS STATE UNIVERSITY</p>
            <span class="period-label">Period: <?= isset($p['date_period']) ? $p['date_period'] : 'Hazard Pay Period' ?></span>
        </div>
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="60%"><strong>Name:</strong> <span style="font-size: 12px;"><?= htmlspecialchars($p['name']) ?></span></td>
            <td width="40%"><strong>Position:</strong> <?= htmlspecialchars($p['position']) ?></td>
        </tr>
    </table>

    <div class="section-title">Earnings</div>
    <table>
        <tr>
            <td width="70%">Basic Salary (Basis)</td>
            <td class="right" width="30%">₱ <?= number_format((float)$p['basic_salary'], 2) ?></td>
        </tr>
        <tr class="bold" style="background-color: #fff9f9;">
            <td>Gross Hazard Pay</td>
            <td class="right text-maroon">₱ <?= number_format((float)$p['gross_pay'], 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Deductions</div>
    <table>
        <?php 
        $calculated_total = 0;
        $calculated_total += (float)$p['tax'];
        if (!empty($p['less'])): 
            $adjustments = explode(',', $p['less']);
            
            foreach ($adjustments as $adjustment):
                $parts = explode(':', $adjustment);
                
                if (count($parts) == 2):
                    $adj_name = trim($parts[0]);
                    $adj_amount = (float) trim($parts[1]); 
                    
                    $calculated_total += $adj_amount;
        ?>
                    <tr>
                        <td width="70%"><?= htmlspecialchars($adj_name) ?></td>
                        <td class="right" width="30%">₱ <?= number_format($adj_amount, 2) ?></td>
                    </tr>
        <?php 
                endif;
            endforeach;
        else: 
        ?>
            <tr>
                <td width="70%">Less (Other Adjustments)</td>
                <td class="right" width="30%">₱ 0.00</td>
            </tr>
        <?php endif; ?>

        <tr>
            <td>Tax Withheld</td>
            <td class="right">₱ <?= number_format((float)$p['tax'], 2) ?></td>
        </tr>
        
        <tr class="bold" style="background-color: #fff9f9;">
            <td class="right">Total Deductions</td>
            <td class="right text-maroon">₱ <?= number_format($calculated_total, 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Net Take-Home Pay</div>
    <table>
        <tr class="bold" style="font-size: 13px; background-color: #f8f8f8;">
            <td width="70%">TOTAL NET PAY</td>
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