<?php
$chunks = array_chunk($records, 30); // 30 per page
$total_all = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .text-left {
            text-align: left;
        }

        .signature {
            margin-top: 30px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: center;
        }
    </style>
</head>
<body>

<?php foreach ($chunks as $pageIndex => $chunk): ?>
<div class="page">

    <h3>PROOF LIST</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Employee Name</th>
                <th>Account No.</th>
                <th>Basic Salary</th>
                <th>Gross Pay</th>
                <th>Deductions</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>

        <?php 
        $page_total = 0;
        foreach ($chunk as $i => $row): 
            $page_total += $row->net_pay;
            $total_all += $row->net_pay;
        ?>
            <tr>
                <td><?= ($pageIndex * 30) + $i + 1 ?></td>
                <td class="text-left">
                    <?= strtoupper($row->last_name . ', ' . $row->first_name) ?>
                </td>
                <td><?= $row->account_number ?></td>
                <td><?= number_format($row->basic_salary, 2) ?></td>
                <td><?= number_format($row->gross_pay, 2) ?></td>
                <td><?= number_format($row->total_deductions, 2) ?></td>
                <td><strong><?= number_format($row->net_pay, 2) ?></strong></td>
            </tr>
        <?php endforeach; ?>

        </tbody>

        <tfoot>
            <tr>
                <td colspan="6"><strong>Page Total</strong></td>
                <td><strong><?= number_format($page_total, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- LAST PAGE ONLY -->
    <?php if ($pageIndex == count($chunks) - 1): ?>

        <table>
            <tr>
                <td colspan="6"><strong>GRAND TOTAL</strong></td>
                <td><strong><?= number_format($total_all, 2) ?></strong></td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td>
                    ___________________________<br>
                    Prepared by
                </td>
                <td>
                    ___________________________<br>
                    Certified Correct
                </td>
                <td>
                    ___________________________<br>
                    Approved by
                </td>
            </tr>
        </table>

    <?php endif; ?>

</div>
<?php endforeach; ?>

</body>
</html>