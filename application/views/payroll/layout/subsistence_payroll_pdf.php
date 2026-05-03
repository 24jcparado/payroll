<!DOCTYPE html>
<html>
<head>
    <title>Subsistence and Laundry Allowance Payroll</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .container { width: 100%; padding: 10px; }
        .header { text-align: center; margin-bottom: 15px; line-height: 1.4; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; border: 2px solid black; }
        th, td { border: 1px solid black; padding: 4px; font-size: 10px; }
        th { text-align: center; font-weight: bold; background-color: #f2f2f2; }
        
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        /* Certification Boxes */
        .cert-container { width: 100%; margin-top: 10px; border: 1px solid black; }
        .cert-row { display: table; width: 100%; }
        .cert-box { display: table-cell; width: 50%; border: 1px solid black; padding: 8px; vertical-align: top; height: 120px; }
        .cert-title { font-weight: bold; margin-bottom: 5px; }
        .signature-line { margin-top: 35px; border-top: 1px solid black; width: 80%; margin-left: auto; margin-right: auto; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Eastern Visayas State University</h2>
        <p>Tacloban City</p>
        <p class="bold" style="margin-top: 5px;">SUBSISTENCE AND LAUNDRY ALLOWANCE</p>
        <p>For the period: <span class="bold"><?= date('F, Y', strtotime($period->date_period)) ?></span></p>
    </div>

    <p style="font-size: 9px; margin-bottom: 5px;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period covered.</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">NO.</th>
                <th style="width: 25%;">NAME</th>
                <th style="width: 15%;">POSITION</th>
                <th style="width: 10%;">SUBSISTENCE ALLOWANCE</th>
                <th style="width: 8%;">No. of Days</th>
                <th style="width: 10%;">Net Subs. Allow.</th>
                <th style="width: 10%;">LAUNDRY ALLOWANCE</th>
                <th style="width: 10%;">NET AMOUNT DUE</th>
                <th style="width: 12%;">SIGNATURE</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            $total_subs = 0; $total_net_subs = 0; $total_laundry = 0; $total_due = 0;
            foreach($payroll as $p): 
                $total_subs += 1500; // Standard monthly maximum shown in your form
                $total_net_subs += $p->base_allowance;
                $total_laundry += $p->laundry_allowance;
                $total_due += $p->net_pay;
            ?>
            <tr>
                <td class="text-center"><?= $count++ ?></td>
                <td class="text-left bold"><?= strtoupper($p->name) ?></td>
                <td class="text-left"><?= $p->position ?></td>
                <td class="text-right">1,500.00</td>
                <td class="text-center"><?= $p->days_present ?></td>
                <td class="text-right"><?= number_format($p->base_allowance, 2) ?></td>
                <td class="text-right"><?= number_format($p->laundry_allowance, 2) ?></td>
                <td class="text-right bold"><?= number_format($p->net_pay, 2) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            
            <tr class="bold bg-light">
                <td colspan="3" class="text-right">GRAND TOTALS</td>
                <td class="text-right"><?= number_format($total_subs, 2) ?></td>
                <td></td>
                <td class="text-right"><?= number_format($total_net_subs, 2) ?></td>
                <td class="text-right"><?= number_format($total_laundry, 2) ?></td>
                <td class="text-right">₱ <?= number_format($total_due, 2) ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p style="font-size: 9px; font-style: italic; margin-top: 5px;">Maximum Subsistence is only ₱1,500.00 w/c is 30 days</p>

    <!-- Bottom Certification Sections -->
    <div class="cert-container">
        <div class="cert-row">
            <div class="cert-box">
                <div class="cert-title">A. CERTIFIED:</div>
                <p>Services has been duly rendered as stated</p>
                <div class="signature-line">
                    <span class="bold">DR. DORIS ANN S. ESPINA</span><br>
                    CAO, Adm Services Division
                </div>
            </div>
            <div class="cert-box">
                <div class="cert-title">B. APPROVED FOR PAYMENT:</div>
                <p class="text-center" style="margin-top: 10px;">One Thousand Nine Hundred Fifty Pesos Only (₱<?= number_format($total_due, 2) ?>)</p>
                <div class="signature-line">
                    <span class="bold">DR. LYDIA M. MORANTE</span><br>
                    VP Admin. and Finance
                </div>
            </div>
        </div>
        <div class="cert-row">
            <div class="cert-box">
                <div class="cert-title">C. CERTIFIED:</div>
                <p>Supporting documents complete and proper and cash available in the amount of ₱<?= number_format($total_due, 2) ?></p>
                <div class="signature-line">
                    <span class="bold">RUBY N. MANCIO, CPA</span><br>
                    Head Accounting Office
                </div>
            </div>
            <div class="cert-box">
                <div class="cert-title">D. CERTIFIED:</div>
                <p>Each employee whose name appears above has been paid the amount indicated opposite his/her name.</p>
                <div class="signature-line">
                    <span class="bold">LEAH S. BELEÑA</span><br>
                    Head Cashiering Office
                </div>
                <p class="text-right" style="margin-top: 10px;">DATE: ___________</p>
            </div>
        </div>
    </div>

    <div style="margin-top: 10px; width: 40%;">
        <p>Prepared by:</p>
        <p class="bold" style="margin-top: 20px;">HONEY LEE F. CADAVIS</p>
        <p>Head HRMDO</p>
    </div>
</div>

</body>
</html>