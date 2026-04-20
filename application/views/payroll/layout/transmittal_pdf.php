<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Transmittal Form</title>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    margin: 40px;
}

/* HEADER */
.header {
    width: 100%;
    border-bottom: 2px solid black;
    margin-bottom: 15px;
}

.header td {
    vertical-align: middle;
}

.logo {
    width: 80px;
}

.title {
    text-align: center;
    font-weight: bold;
    font-size: 16px;
}

/* PAYROLL INFO */
.info {
    width: 100%;
    margin-top: 10px;
    margin-bottom: 15px;
}

.info td {
    padding: 4px;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid black;
    padding: 6px;
    text-align: center;
}

.table th {
    font-weight: bold;
}

/* COLUMN WIDTHS */
.col-file { width: 15%; }
.col-no { width: 5%; }
.col-name { width: 25%; }
.col-remarks { width: 20%; }
.col-signature { width: 20%; }
.col-date { width: 15%; }

</style>
</head>

<body>

<!-- HEADER -->
 <div style="text-align:center; margin-bottom:15px;">
    <div style="margin-bottom:5px;">
        <img src="<?= base_url('assets/img/favicon.png') ?>" style="height:70px;">
    </div>
    <div style="font-size:12px;">
        Republic of the Philippines<br>
        EASTERN VISAYAS STATE UNIVERSITY<br>
        TAacloban City
    </div>
</div>
<table class="header">
    <tr>
        <td width="15%">
            <img src="<?=base_url($qr_code->qr_code)?>" class="logo">
            <?=$qr_code->token_id?>
        </td>
        <td class="title">
            TRANSMITTAL FORM
        </td>
        <td width="25%" style="text-align:right;">
            <strong>Payroll No:</strong> __________<br>
            <strong>Payroll Type:</strong> __________<br>
            <strong>Unit:</strong> __________
            
        </td>
    </tr>
</table>

<!-- TABLE -->
<table class="table">
    <thead>
        <tr>
            <th class="col-no">No.</th>
            <th class="col-name">Name</th>
            <th class="col-remarks">Remarks</th>
            <th class="col-signature">Signature</th>
            <th class="col-date">Date</th>
        </tr>
    </thead>
    <tbody>

       <?php for ($i = 1; $i <= 20; $i++): ?>
        <tr>
            <td><?= $i ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
      <?php endfor; ?>

    </tbody>
</table>

</body>
</html>