<!DOCTYPE html>
<html>
<head>
    <title>General Payroll</title>
    <style>
        body {
            font-family: times new roman, serif;
            font-size: 15px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 4px;
        }

        .no-border td {
            border: none;
        }

        .header-title {
            font-weight: bold;
            font-size: 16px;
        }

        @media print {
            @page { size: Legal landscape; margin: 15mm; }
        }

        .print-header {
            width: 100%;
        }

        @media print {
            table, tr, td, div {
                page-break-inside: avoid !important;
                page-break-before: auto !important;
                page-break-after: auto !important;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        /* Optional styling to keep boxes uniform */
        td div {
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="print-header" style="position:relative;">
    <div class="text-center header-title">GENERAL PAYROLL</div>
    <div class="text-center">EASTERN VISAYAS STATE UNIVERSITY</div>
    <div class="text-center">Tacloban City</div>
    <div style="position:absolute; top:0; right:0; text-align:center;">
        <div style="margin-bottom:5px; font-size:12px; font-weight:bold;">
            <?= htmlspecialchars($period->token_id) ?>
        </div>
        <img src="<?= base_url($period->qr_code) ?>" alt="Payroll QR" style="width:120px; height:120px;">
    </div>

    <br><br>

    <strong>PAYROLL FOR: <?= $period->payroll_type ?></strong><br>
    <strong>COLLEGE OF EDUCATION</strong>

    <p class="fst-italic">
        We acknowledge receipt of cash shown opposite our name as full compensation
        for services rendered for the period covered.
    </p>
</div>
<?php
$grouped = [];

if (!empty($payrolls)) {
    foreach ($payrolls as $row) {
        $grouped[$row->school_year][] = $row;
    }
}
?>
<table>
    <thead>
        <tr>
            <th rowspan="2">NAME OF EMPLOYEE</th>
            <th rowspan="2">Position</th>
            <th rowspan="2">Rate/Hour</th>
            <th rowspan="2">Particular</th>

            <th>JAN</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>APR</th>
            <th>MAY</th>
            <th>JUN</th>

            <th rowspan="2">NO. HRS RENDERED</th>
            <th rowspan="2">AMOUNT ACCRUED</th>
            <th rowspan="2">LESS: W/TAX</th>
            <th rowspan="2">NET DUE</th>
        </tr>
        <tr>
            <th>JUL</th>
            <th>AUG</th>
            <th>SEP</th>
            <th>OCT</th>
            <th>NOV</th>
            <th>DEC</th>
        </tr>
    </thead>

    <tbody>

        <?php if(!empty($grouped)): ?>

        <?php
        $overall_hours = 0;
        $overall_gross = 0;
        $overall_tax   = 0;
        $overall_net   = 0;
        ?>

        <?php foreach($grouped as $school_year => $records): ?>

            <?php
            $grand_hours = 0;
            $grand_gross = 0;
            $grand_tax   = 0;
            $grand_net   = 0;
            ?>

            <!-- SCHOOL YEAR HEADER -->
            <tr>
                <td colspan="16" style="font-weight:bold; background:#f2f2f2;">
                    SCHOOL YEAR: <?= htmlspecialchars($school_year) ?>
                </td>
            </tr>

            <?php foreach($records as $row): ?>

                <?php
                $grand_hours += $row->total_hours;
                $grand_gross += $row->gross_amount;
                $grand_tax   += $row->tax_amount;
                $grand_net   += $row->total_net;

                $overall_hours += $row->total_hours;
                $overall_gross += $row->gross_amount;
                $overall_tax   += $row->tax_amount;
                $overall_net   += $row->total_net;
                ?>

                <tr>
                    <td rowspan="2">
                        <?= strtoupper($row->last_name . ', ' . $row->name) ?>
                    </td>
                    <td rowspan="2"><?= $row->position ?></td>
                    <td rowspan="2" class="text-right"><?= number_format($row->rate_per_hour,2) ?></td>
                    <td rowspan="2"><?= $row->particulars ?? '' ?></td>

                    <td><?= ($row->jan  != 0) ? number_format($row->jan, 2)  : '' ?></td>
                    <td><?= ($row->feb  != 0) ? number_format($row->feb, 2)  : '' ?></td>
                    <td><?= ($row->mar  != 0) ? number_format($row->mar, 2)  : '' ?></td>
                    <td><?= ($row->apr  != 0) ? number_format($row->apr, 2)  : '' ?></td>
                    <td><?= ($row->may  != 0) ? number_format($row->may, 2)  : '' ?></td>
                    <td><?= ($row->jun  != 0) ? number_format($row->jun, 2)  : '' ?></td>

                    <td rowspan="2" class="text-right"><?= number_format($row->total_hours,2) ?></td>
                    <td rowspan="2" class="text-right"><?= number_format($row->gross_amount,2) ?></td>
                    <td rowspan="2" class="text-right"><?= number_format($row->tax_amount,2) ?></td>
                    <td rowspan="2" class="text-right"><?= number_format($row->total_net,2) ?></td>
                </tr>

                <tr>
                    <td><?= ($row->jul  != 0) ? number_format($row->jul, 2)  : '' ?></td>
                    <td><?= ($row->aug  != 0) ? number_format($row->aug, 2)  : '' ?></td>
                    <td><?= ($row->sept != 0) ? number_format($row->sept, 2) : '' ?></td>
                    <td><?= ($row->oct  != 0) ? number_format($row->oct, 2)  : '' ?></td>
                    <td><?= ($row->nov  != 0) ? number_format($row->nov, 2)  : '' ?></td>
                    <td><?= ($row->dece != 0) ? number_format($row->dece, 2) : '' ?></td>
                </tr>

            <?php endforeach; ?>

            <!-- SCHOOL YEAR TOTAL -->
            <tr style="font-weight:bold;">
                <td colspan="10" class="text-right">TOTAL (<?= htmlspecialchars($school_year) ?>)</td>
                <td class="text-right"><?= number_format($grand_hours,2) ?></td>
                <td class="text-right"><?= number_format($grand_gross,2) ?></td>
                <td class="text-right"><?= number_format($grand_tax,2) ?></td>
                <td class="text-right"><?= number_format($grand_net,2) ?></td>
            </tr>

        <?php endforeach; ?>

        <!-- OVERALL GRAND TOTAL -->
        <tr style="font-weight:bold; background:#e6e6e6;">
            <td colspan="10" class="text-right">OVERALL GRAND TOTAL</td>
            <td class="text-right"><?= number_format($overall_hours,2) ?></td>
            <td class="text-right"><?= number_format($overall_gross,2) ?></td>
            <td class="text-right"><?= number_format($overall_tax,2) ?></td>
            <td class="text-right"><?= number_format($overall_net,2) ?></td>
        </tr>

        <?php else: ?>
        <tr>
            <td colspan="16" class="text-center">No payroll records found.</td>
        </tr>
        <?php endif; ?>

    </tbody>
</table>

<br><br>

<table width="100%" style="border-collapse:collapse; margin-top:30px;">
    <tr>
        <!-- BOX A -->
        <td width="50%" style="vertical-align:top; padding:10px;">
            <div style="border:1px solid #000; padding:15px; min-height:130px;">
                <strong>A. CERTIFIED:</strong><br>
                Services have been duly rendered as stated.
                <br><br><br>
                <strong>DR. DORIS ANN S. ESPINA, CPA, CSEE</strong><br>
                Chief Administrative Officer
            </div>
        </td>

        <!-- BOX B -->
        <td width="50%" style="vertical-align:top; padding:10px;">
            <div style="border:1px solid #000; padding:15px; min-height:130px;">
                <strong>B. CERTIFIED:</strong><br>
                Supporting documents complete and proper.
                <br><br><br>
                <strong>RUBY N. MANCIO, CPA</strong><br>
                Head, Accounting Office
            </div>
        </td>
    </tr>

    <tr>
        <!-- BOX C -->
        <td width="50%" style="vertical-align:top; padding:10px;">
            <div style="border:1px solid #000; padding:15px; min-height:130px;">
                <strong>C. APPROVED FOR PAYMENT:</strong><br><br>
                <strong>Php <?= number_format($overall_net ?? 0,2) ?></strong>
                <br><br><br>
                <strong>DR. BENEDICTO T. MILITANTE</strong><br>
                Vice-President for Academic Affairs
            </div>
        </td>

        <!-- BOX D -->
        <td width="50%" style="vertical-align:top; padding:10px;">
            <div style="border:1px solid #000; padding:15px; min-height:130px;">
                <strong>D. CERTIFIED:</strong><br>
                Each employee has been paid accordingly.
                <br><br><br>
                <strong>LEAH S. BELEÑA, MPRM</strong><br>
                Head Cashiering Office
            </div>
        </td>
    </tr>
</table>

<script>
window.onload = function() {
    window.print();
}
</script>
</body>
</html>