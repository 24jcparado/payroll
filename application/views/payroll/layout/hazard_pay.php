<!DOCTYPE html>
<html>
<head>
    <title>Hazard Pay General Payroll</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 0; }
        .container { width: 100%; padding: 10px; }
        .header { text-align: center; margin-bottom: 10px; line-height: 1.2; }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; border: 2px solid black; margin-bottom: 10px; }
        th, td { border: 1px solid black; padding: 3px 5px; font-size: 9px; }
        th { text-align: center; font-weight: bold; background-color: #f2f2f2; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        /* Certification Boxes */
        .cert-container { width: 100%; border: 1px solid black; display: table; table-layout: fixed; }
        .cert-row { display: table-row; }
        .cert-box { display: table-cell; border: 1px solid black; padding: 6px; vertical-align: top; height: 110px; width: 50%; }
        .cert-title { font-weight: bold; font-size: 10px; margin-bottom: 3px; }
        .signature-line { margin-top: 30px; border-top: 1px solid black; width: 85%; margin-left: auto; margin-right: auto; text-align: center; font-weight: bold; }
        
        .footer-table { width: 100%; margin-top: 5px; border: none; }
        .footer-table td { border: none; padding: 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <p style="margin:0;">GENERAL PAYROLL</p>
        <h2>EASTERN VISAYAS STATE UNIVERSITY</h2>
        <p style="margin:0;">Tacloban City</p>
        <p class="bold" style="margin-top: 5px;"><?= strtoupper(date('F, Y', strtotime($period->date_period))) ?></p>
    </div>

    <p style="font-size: 8px; margin-bottom: 5px;">
        HAZARD PAY (per BR #96 s. 2007 / DBM-DOH JC #1 s. 2016)<br>
        We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period covered.
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">NO.</th>
                <th style="width: 20%;">NAME</th>
                <th style="width: 15%;">POSITION / SG</th>
                <th style="width: 12%;">BASIC SALARY</th>
                <th style="width: 12%;">AMOUNT ACCRUED</th>
                <th style="width: 12%;">Total Amount Accrued</th>
                <th style="width: 12%;">Less: W/holding Tax</th>
                <th style="width: 12%;">NET DUE</th>
                <th style="width: 10%;">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            $total_accrued = 0; $total_tax = 0; $total_net = 0;
            foreach($payroll as $p): 
                $total_accrued += $p->gross_pay;
                $total_tax += $p->tax;
                $total_net += $p->net_pay;
                
                // Logic to display percentage based on SG
                // Assuming you store SG in your employee table, otherwise default to "Hazard Pay"
                $percentage_label = ($p->basic_salary > 0) ? round(($p->gross_pay / $p->basic_salary) * 100) . "% of Basic Salary" : "";
            ?>
            <tr>
                <td class="text-center"><?= $count++ ?></td>
                <td class="bold"><?= strtoupper($p->name) ?></td>
                <td class="text-center"><?= $p->position ?></td>
                <td class="text-right"><?= number_format($p->basic_salary, 2) ?></td>
                <td class="text-center">
                    <?= number_format($p->gross_pay, 2) ?><br>
                    <small style="font-size: 7px;">(<?= $percentage_label ?>)</small>
                </td>
                <td class="text-right bold"><?= number_format($p->gross_pay, 2) ?></td>
                <td class="text-right"><?= number_format($p->tax, 2) ?></td>
                <td class="text-right bold"><?= number_format($p->net_pay, 2) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            
            <tr class="bold" style="background-color: #eee;">
                <td colspan="3" class="text-right">GRAND TOTALS</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"><?= number_format($total_accrued, 2) ?></td>
                <td class="text-right"><?= number_format($total_tax, 2) ?></td>
                <td class="text-right"><?= number_format($total_net, 2) ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Certification Section -->
    <div class="cert-container">
        <div class="cert-row">
            <div class="cert-box">
                <div class="cert-title">A. CERTIFIED: Services has been duly rendered as stated</div>
                <div class="signature-line" style="margin-top: 50px;">
                    DR. DORIS ANN S. ESPINA<br>
                    <span style="font-weight: normal; font-size: 8px;">CAO, Adm. Services Division</span>
                </div>
            </div>
            <div class="cert-box">
                <div class="cert-title">B. APPROVED FOR PAYMENT:</div>
                <p class="text-center bold" style="margin-top: 15px;">
                    <?= ucwords(number_to_words($total_net)) ?> Pesos and <?= date('s') ?>/100. (₱<?= number_format($total_net, 2) ?>)
                </p>
                <div class="signature-line" style="margin-top: 25px;">
                    DR. LYDIA M. MORANTE<br>
                    <span style="font-weight: normal; font-size: 8px;">VPAdmin. and Finance</span>
                </div>
            </div>
        </div>
        <div class="cert-row">
            <div class="cert-box">
                <div class="cert-title">C. CERTIFIED: Supporting documents complete and proper and cash available in the amount of ₱</div>
                <div class="text-right bold" style="padding-right: 20px;"><?= number_format($total_net, 2) ?></div>
                <div class="signature-line" style="margin-top: 25px;">
                    RUBY N. MANCIO, CPA<br>
                    <span style="font-weight: normal; font-size: 8px;">Head Accounting Office</span>
                </div>
            </div>
            <div class="cert-box">
                <div class="cert-title">D. CERTIFIED: Each employee whose name appears above has been paid the amount indicated opposite his/her name.</div>
                <div class="signature-line" style="margin-top: 35px;">
                    LEAH S. BELEÑA<br>
                    <span style="font-weight: normal; font-size: 8px;">Head Cashiering Office</span>
                </div>
                <table class="footer-table" style="margin-top: 10px;">
                    <tr>
                        <td>OS/BUS/US No.</td>
                        <td>Date:</td>
                    </tr>
                    <tr>
                        <td>JEV No.</td>
                        <td>Date:</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 10px; width: 40%;">
        <p>Prepared by:</p>
        <p class="bold" style="margin-top: 25px; border-bottom: 1px solid black; display: inline-block;">HONEY LEE F. CADAVIS, MM</p>
        <p style="margin:0;">Head HRMDO</p>
    </div>
</div>

</body>
</html>