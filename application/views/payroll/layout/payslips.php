<!DOCTYPE html>
<html>
<head>
    <title>Payslips</title>
    <style>
        @page {
            size: 8.5in 13in; /* Long bond / Legal */
            margin: 12mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .payslip {
            width: 48%;          /* 2 columns */
            height: 6.25in;      /* half page (2 rows) */
            box-sizing: border-box;
            border: 1px solid #000;
            margin-bottom: 10px;
            padding: 6px;
            page-break-inside: avoid;
        }

        /* Page break after every 4 payslips (2x2 grid) */
        .payslip:nth-child(4n) {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #000;
            padding: 4px;
        }

        .no-border td {
            border: none;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>


</head>
<body onload="window.print()">

<?php foreach ($payrolls as $p): ?>

<div class="payslip">

    <div class="header">
        <h2>PAYROLL PAYMENT SLIP</h2>
        <p>EASTERN VISAYAS STATE UNIVERSITY <br> Tacloban City</p>
        <h4>Payroll Period: <?= $p->date_period ?></h4>
    </div>

    <table class="no-border">
        <tr>
            <td><strong>Name:</strong> <?= $p->name ?></td>
            <td><strong>Position:</strong> <?= $p->position ?></td>
        </tr>
    </table>

    <h4>Earnings</h4>
    <table>
        <tr>
            <td>Basic Salary</td>
            <td class="right">₱ <?= number_format($p->basic_salary, 2) ?></td>
        </tr>
        <tr>
            <td>Salary LWOP</td>
            <td class="right">₱ <?= number_format($p->salary_lwop, 2) ?></td>
        </tr>
        <tr>
            <td>PERA LWOP</td>
            <td class="right">₱ <?= number_format($p->pera, 2) ?></td>
        </tr>
        <tr>
            <td>LWOP</td>
            <td class="right">(₱ <?= number_format($p->lwop_amount, 2) ?>)</td>
        </tr>
        <tr class="bold">
            <td>Gross Pay</td>
            <td class="right">₱ <?= number_format($p->gross_pay, 2) ?></td>
        </tr>
    </table>

    <h4>Deductions</h4>
    <table>
        <tr>
            <td colspan="4" class="bold">Mandatory Deductions</td>
        </tr>
        <tr>
            <td>GSIS</td>
            <td class="right">₱ <?= number_format($p->gsis, 2) ?></td>
            <td>PhilHealth</td>
            <td class="right">₱ <?= number_format($p->philhealth, 2) ?></td>
        </tr>
        <tr>
            <td>Pag-IBIG</td>
            <td class="right">₱ <?= number_format($p->pagibig, 2) ?></td>
            <td></td>
            <td></td>
        </tr>

        <?php if ($p->other_deductions): ?>
            <tr>
                <td colspan="4" class="bold">Other Deductions</td>
            </tr>

            <?php
            $others = [];
            foreach (explode(',', $p->other_deductions) as $d) {
                [$name, $amt] = explode(':', $d);
                $others[] = [trim($name), (float)$amt];
            }

            $half = ceil(count($others) / 2);
            $left = array_slice($others, 0, $half);
            $right = array_slice($others, $half);
            ?>

            <?php for ($i = 0; $i < $half; $i++): ?>
            <tr>
                <td><?= $left[$i][0] ?? '' ?></td>
                <td class="right">
                    <?= isset($left[$i]) ? '₱ ' . number_format($left[$i][1], 2) : '' ?>
                </td>
                <td><?= $right[$i][0] ?? '' ?></td>
                <td class="right">
                    <?= isset($right[$i]) ? '₱ ' . number_format($right[$i][1], 2) : '' ?>
                </td>
            </tr>
            <?php endfor; ?>
        <?php endif; ?>

        <tr class="bold">
            <td colspan="3">Total Deductions</td>
            <td class="right">₱ <?= number_format($p->total_deductions, 2) ?></td>
        </tr>
    </table>


    <h4>Net Pay</h4>
    <table>
        <tr class="bold">
            <td>Total Net Pay</td>
            <td class="right">₱ <?= number_format($p->net_pay, 2) ?></td>
        </tr>
        <tr>
            <td>1st Quincena</td>
            <td class="right">₱ <?= number_format($p->net_pay_first, 2) ?></td>
        </tr>
        <tr>
            <td>2nd Quincena</td>
            <td class="right">₱ <?= number_format($p->net_pay_second, 2) ?></td>
        </tr>
    </table>

</div>

<?php endforeach; ?>

</body>
</html>
