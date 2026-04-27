<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVSU Payslips</title>
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
            width: 49%; /* Allowing a small gap for center margin */
            height: 5.8in; /* Adjusted to fit exactly 2 rows on long bond */
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
            margin-bottom: 8px;
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
            padding: 4px 6px;
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
            margin-top: 15px;
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
            .payslip { border: 1px solid #000; } /* Darken border for printer */
        }
    </style>
</head>
<body onload="window.print()">

<?php foreach ($payrolls as $p): ?>

<div class="payslip">
    <div class="header-container">
        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo" alt="EVSU Logo">
        <div class="header-text">
            <h2>Payroll Payment Slip</h2>
            <p>EASTERN VISAYAS STATE UNIVERSITY</p>
            <span class="period-label">Period: <?= $p->date_period ?></span>
        </div>
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="60%"><strong>Name:</strong> <span style="font-size: 12px;"><?= $p->name ?></span></td>
            <td width="40%"><strong>Position:</strong> <?= $p->position ?></td>
        </tr>
    </table>

    <div class="section-title">Earnings</div>
    <table>
        <tr>
            <td>Basic Salary</td>
            <td class="right">₱ <?= number_format($p->basic_salary, 2) ?></td>
            <td>Salary LWOP</td>
            <td class="right">₱ <?= number_format($p->salary_lwop, 2) ?></td>
        </tr>
        <tr>
            <td>PERA LWOP</td>
            <td class="right">₱ <?= number_format($p->pera, 2) ?></td>
            <td>LWOP Deduction</td>
            <td class="right text-maroon">(₱ <?= number_format($p->lwop_amount, 2) ?>)</td>
        </tr>
        <tr class="bold" style="background-color: #fff9f9;">
            <td colspan="3">Gross Pay</td>
            <td class="right text-maroon">₱ <?= number_format($p->gross_pay, 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Deductions</div>
    <table>
        <thead>
            <tr>
                <th colspan="2">Mandatory</th>
                <th colspan="2">Other Loans/Adjustments</th>
            </tr>
        </thead>
        <tr>
            <td width="25%">GSIS</td>
            <td class="right" width="25%">₱ <?= number_format($p->gsis, 2) ?></td>
            
            <?php
            $others = [];
            if ($p->other_deductions) {
                foreach (explode(',', $p->other_deductions) as $d) {
                    [$name, $amt] = explode(':', $d);
                    $others[] = ['n' => trim($name), 'a' => (float)$amt];
                }
            }
            ?>
            
            <td><?= $others[0]['n'] ?? '' ?></td>
            <td class="right"><?= isset($others[0]) ? '₱ '.number_format($others[0]['a'], 2) : '' ?></td>
        </tr>
        <tr>
            <td>PhilHealth</td>
            <td class="right">₱ <?= number_format($p->philhealth, 2) ?></td>
            <td><?= $others[1]['n'] ?? '' ?></td>
            <td class="right"><?= isset($others[1]) ? '₱ '.number_format($others[1]['a'], 2) : '' ?></td>
        </tr>
        <tr>
            <td>Pag-IBIG</td>
            <td class="right">₱ <?= number_format($p->pagibig, 2) ?></td>
            <td><?= $others[2]['n'] ?? '' ?></td>
            <td class="right"><?= isset($others[2]) ? '₱ '.number_format($others[2]['a'], 2) : '' ?></td>
        </tr>
        
        <?php if (count($others) > 3): ?>
            <?php for ($i = 3; $i < max(count($others), 3); $i++): ?>
                <tr>
                    <td colspan="2"></td>
                    <td><?= $others[$i]['n'] ?? '' ?></td>
                    <td class="right">₱ <?= number_format($others[$i]['a'], 2) ?></td>
                </tr>
            <?php endfor; ?>
        <?php endif; ?>

        <tr class="bold">
            <td colspan="3" class="right">Total Deductions</td>
            <td class="right text-maroon">₱ <?= number_format($p->total_deductions, 2) ?></td>
        </tr>
    </table>

    <div class="section-title">Net Take-Home Pay</div>
    <table>
        <tr class="bold" style="font-size: 12px; background-color: #f8f8f8;">
            <td width="50%">TOTAL NET PAY</td>
            <td class="right text-maroon">₱ <?= number_format($p->net_pay, 2) ?></td>
        </tr>
        <tr>
            <td>1st Quincena (1-15)</td>
            <td class="right">₱ <?= number_format($p->net_pay_first, 2) ?></td>
        </tr>
        <tr>
            <td>2nd Quincena (16-31)</td>
            <td class="right">₱ <?= number_format($p->net_pay_second, 2) ?></td>
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