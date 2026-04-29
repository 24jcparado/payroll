<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $period ?? 'Payroll View' ?></title>

<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon.png') ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* General Page Styles */
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    #mainContent {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* DIGITAL DOCUMENT HEADER */
    .doc-header {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        position: relative;
        border-top: 5px solid #800000; /* EVSU Maroon */
    }
    
    /* PREMIUM TABLE STYLES */
    .table-container {
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        background: white;
        padding: 0;
        overflow: hidden;
    }
    .table-custom {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    .table-custom thead th {
        background-color: #f8f9fc;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid #e9ecef;
        vertical-align: middle;
    }
    .table-custom tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        white-space: nowrap;
    }
    .table-custom tbody tr:hover {
        background-color: #f8f9fc;
    }
    .table-custom tfoot td {
        background-color: #f8f9fc;
        font-weight: 700;
        color: #212529;
        padding: 1rem 0.75rem;
        border-top: 2px solid #e9ecef;
    }
    
    /* Column Group Highlighting */
    .col-earnings { background-color: rgba(25, 135, 84, 0.02); }
    .col-deductions { background-color: rgba(220, 53, 69, 0.02); }
    
    .hover-elevate {
        transition: all 0.2s ease-in-out;
    }
    .hover-elevate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    /* Custom Scrollbar for wide tables */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
</head>
<body>

<main id="mainContent">

    <div class="doc-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
        <div class="d-flex align-items-center gap-3 text-center text-md-start">
            <img src="<?= base_url('assets/img/favicon.png') ?>" alt="EVSU Logo" style="width: 80px;">
            <div>
                <p class="mb-0 text-muted small text-uppercase fw-bold letter-spacing-1">Republic of the Philippines</p>
                <h4 class="mb-0 fw-bolder text-dark">Eastern Visayas State University</h4>
                <p class="mb-0 text-muted small">Tacloban City</p>
            </div>
        </div>
        <div class="text-center text-md-end">
            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-4 py-2 rounded-pill mb-2 fs-6 text-uppercase">
                <?= $payroll_type ?? 'General Payroll' ?>
            </div>
            <p class="mb-0 text-secondary fw-medium"><i class="bi bi-diagram-3-fill me-2"></i>Unit: <span class="text-dark fw-bold"><?= $unit ?? 'N/A' ?></span></p>
        </div>
    </div>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i>Payroll Register Details</h6>
            <div>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 hover-elevate me-2">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <button class="btn btn-sm btn-primary rounded-pill px-3 hover-elevate" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle" id="savedPayrollTable">
                <thead>
                    <tr>
                        <th rowspan="2" class="ps-4 border-end">Employee Information</th>
                        <th colspan="4" class="text-center border-end text-success">Earnings</th>
                        <th colspan="6" class="text-center border-end text-danger">Deductions</th>
                        <th colspan="3" class="text-center pe-4 text-primary">Net Pay & Distribution</th>
                    </tr>
                    <tr>
                        <th class="text-end col-earnings">Basic</th>
                        <th class="text-end col-earnings">Salary LWOP</th>
                        <th class="text-end col-earnings">PERA LWOP</th>
                        <th class="text-end border-end col-earnings text-success">Gross Pay</th>
                        
                        <th class="text-end col-deductions">GSIS</th>
                        <th class="text-end col-deductions">PhilHealth</th>
                        <th class="text-end col-deductions">Pag-IBIG</th>
                        <th class="text-end col-deductions">W/ Tax</th>
                        <th class="text-center col-deductions">Other</th>
                        <th class="text-end border-end col-deductions text-danger">Total Ded.</th>
                        
                        <th class="text-end text-primary">Total Net</th>
                        <th class="text-end text-muted">1st Quincena</th>
                        <th class="text-end pe-4 text-muted">2nd Quincena</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($payrolls)): ?>
                        <?php foreach ($payrolls as $payroll): ?> 
                        <tr>
                            <td class="ps-4 border-end">
                                <div class="fw-bold text-dark"><?= $payroll->name ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= $payroll->position ?></div>
                            </td>
                            
                            <td class="text-end font-monospace col-earnings">₱ <?= number_format($payroll->basic_salary,2) ?></td>
                            <td class="text-end font-monospace col-earnings text-danger"><?= $payroll->salary_lwop > 0 ? '- ₱ '.number_format($payroll->salary_lwop,2) : '<span class="opacity-25">-</span>' ?></td>
                            <td class="text-end font-monospace col-earnings text-danger"><?= $payroll->pera > 0 ? '- ₱ '.number_format($payroll->pera,2) : '<span class="opacity-25">-</span>' ?></td>
                            <td class="text-end border-end font-monospace fw-semibold text-success amount-accrued col-earnings">₱ <?= number_format($payroll->gross_pay,2) ?></td>
                            
                            <td class="text-end font-monospace col-deductions gsis">₱ <?= number_format($payroll->gsis,2) ?></td>
                            <td class="text-end font-monospace col-deductions philhealth">₱ <?= number_format($payroll->philhealth,2) ?></td>
                            <td class="text-end font-monospace col-deductions pagibig">₱ <?= number_format($payroll->pagibig,2) ?></td>
                            <td class="text-end font-monospace col-deductions tax">₱ <?= number_format($payroll->tax,2) ?></td>
                            <td class="text-center col-deductions">
                                <?php if(!empty($payroll->other_deductions)): ?>
                                    <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" title="<?= htmlspecialchars($payroll->other_deductions) ?>"></i>
                                <?php else: ?>
                                    <span class="opacity-25">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end border-end font-monospace fw-semibold text-danger tax-amount col-deductions">₱ <?= number_format($payroll->total_deductions,2) ?></td>
                            
                            <td class="text-end font-monospace fw-bold text-primary netpay">₱ <?= number_format($payroll->net_pay,2) ?></td>
                            <td class="text-end font-monospace text-muted">₱ <?= number_format($payroll->net_pay_first,2) ?></td>
                            <td class="text-end pe-4 font-monospace text-muted">₱ <?= number_format($payroll->net_pay_second,2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="13" class="text-center py-5 text-muted">No payroll records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end ps-4 border-end pe-3">GRAND TOTAL</td>
                        
                        <td class="text-end font-monospace"></td>
                        <td class="text-end font-monospace"></td>
                        <td class="text-end font-monospace"></td>
                        <td class="text-end border-end font-monospace text-success" id="total_amount_accrued">₱ 0.00</td>
                        
                        <td class="text-end font-monospace" id="total_gsis">₱ 0.00</td>
                        <td class="text-end font-monospace" id="total_philhealth">₱ 0.00</td>
                        <td class="text-end font-monospace" id="total_pagibig">₱ 0.00</td>
                        <td class="text-end font-monospace" id="total_tax">₱ 0.00</td>
                        <td class="text-center"></td>
                        <td class="text-end border-end font-monospace text-danger" id="total_deductions_all">₱ 0.00</td>
                        
                        <td class="text-end font-monospace text-primary fs-6" id="total_netpay">₱ 0.00</td>
                        <td></td>
                        <td class="pe-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>

<script>
    // Initialize tooltips for the "Other Deductions" icons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Cleaned up JS Totals Calculator
    function parsePeso(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[₱,\s]/g, '')) || 0;
    }

    function formatPeso(value) {
        return '₱ ' + value.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateTotals() {
        let totalGSIS = 0,
            totalPhilhealth = 0,
            totalPagibig = 0,
            totalTax = 0,
            totalAccrued = 0,
            totalDedAll = 0,
            totalNetPay = 0;

        document.querySelectorAll('#savedPayrollTable tbody tr').forEach(row => {
            // Check if row has data (skip empty rows)
            if(!row.querySelector('.gsis')) return;

            totalGSIS += parsePeso(row.querySelector('.gsis').textContent);
            totalPhilhealth += parsePeso(row.querySelector('.philhealth').textContent);
            totalPagibig += parsePeso(row.querySelector('.pagibig').textContent);
            totalTax += parsePeso(row.querySelector('.tax').textContent);
            
            totalAccrued += parsePeso(row.querySelector('.amount-accrued').textContent);
            totalDedAll += parsePeso(row.querySelector('.tax-amount').textContent);
            totalNetPay += parsePeso(row.querySelector('.netpay').textContent);
        });

        // Apply formatted totals to the footer
        document.getElementById('total_gsis').textContent = formatPeso(totalGSIS);
        document.getElementById('total_philhealth').textContent = formatPeso(totalPhilhealth);
        document.getElementById('total_pagibig').textContent = formatPeso(totalPagibig);
        document.getElementById('total_tax').textContent = formatPeso(totalTax);
        
        document.getElementById('total_amount_accrued').textContent = formatPeso(totalAccrued);
        document.getElementById('total_deductions_all').textContent = formatPeso(totalDedAll);
        document.getElementById('total_netpay').textContent = formatPeso(totalNetPay);
    }

    // Run calculation when DOM is ready
    document.addEventListener('DOMContentLoaded', updateTotals);
</script>

</body>
</html>