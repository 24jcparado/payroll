<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $period ?></title>

<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon.png') ?>">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* General Page Styles */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
#mainContent {
    padding: 30px;
}

/* Header */
.payroll-header {
    text-align: center;
    margin-bottom: 35px;
}
.payroll-header img {
    width: 90px;
    margin-bottom: 10px;
}
.payroll-header h1, .payroll-header h5, .payroll-header .fw-bold {
    margin: 0;
}
.payroll-header .unit {
    margin-top: 8px;
    font-size: 14px;
}

/* Tracker Styles */
.payroll-tracker {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 30px 0;
}
.payroll-tracker::before {
    content: '';
    position: absolute;
    top: 22px;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: #e9ecef;
    z-index: 1;
}
.tracker-step {
    position: relative;
    text-align: center;
    z-index: 2;
    flex: 1;
}
.tracker-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto 8px auto;
}
.tracker-step.active .tracker-circle { background-color: #28a745; color: #fff; }
.tracker-step.current .tracker-circle { background-color: #ffc107; color: #000; }
.sub-process { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
.sub-step { font-size: 11px; padding: 3px 8px; border-radius: 20px; background-color: #dee2e6; }
.sub-step.active { background-color: #28a745; color: #fff; }
.sub-step.current { background-color: #ffc107; color: #000; }

/* Legend */
.tracker-legend { display: flex; gap: 20px; align-items: center; margin-bottom: 20px; font-size: 14px; }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-item::before { content: ''; width: 15px; height: 15px; border-radius: 50%; display: inline-block; }
.active-legend::before { background-color: #28a745; }
.current-legend::before { background-color: #ffc107; }
.pending-legend::before { background-color: #dee2e6; }

/* Summary Cards */
.card-summary {
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.card-summary .dashboard-icon i {
    font-size: 28px;
}

/* Payroll Table */
#savedPayrollTable {
    margin-top: 25px;
}
#savedPayrollTable th, #savedPayrollTable td {
    font-size: 0.85rem;
    padding: 0.5rem;
    white-space: nowrap;
}
#savedPayrollTable tbody tr:hover {
    background-color: #f1f3f5;
}
</style>
</head>
<body>

<style>
    #savedPayrollTable th,
    #savedPayrollTable td {
        white-space: normal !important;
        word-break: break-word;
        font-size: 0.85rem;
        padding: 0.35rem 0.5rem;
    }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 10px 15px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
        background: #f8f9fa;
    }

    .section-earnings { border-left-color: #198754; }
    .section-deductions { border-left-color: #dc3545; }
    .section-loans { border-left-color: #fd7e14; }
    .section-summary { border-left-color: #0d6efd; }

    .payroll-input {
        text-align: right;
    }

    .readonly-field {
        background-color: #f1f3f5;
        font-weight: 600;
    }


    /* tracker */
    .payroll-tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 40px 0;
        flex-wrap: wrap; /* allow wrapping on small screens */
    }

    .payroll-tracker::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e9ecef;
        z-index: 1;
    }

    .tracker-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1 1 150px; /* flexible width with minimum */
        margin-bottom: 20px; /* spacing when wrapped */
    }

    .tracker-circle {
        width: 40px;
        height: 40px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #dee2e6;
        line-height: 40px;
        font-weight: bold;
    }

    .tracker-step.active .tracker-circle {
        background: #28a745;
        color: #fff;
    }

    .tracker-step.current .tracker-circle {
        background: #ffc107;
        color: #000;
    }

    /* SUB PROCESS */
    .sub-process {
        margin-top: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap; /* wrap sub-steps if needed */
    }

    .sub-step {
        font-size: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #dee2e6;
        white-space: nowrap; /* prevent breaking inside words */
    }

    .sub-step.active {
        background: #28a745;
        color: #fff;
    }

    .sub-step.current {
        background: #ffc107;
        color: #000;
    }

    /* LEGEND */
    .tracker-legend {
        display: flex;
        gap: 15px;
        align-items: center;
        font-size: 14px;
        flex-wrap: wrap; /* wrap legend items if needed */
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .legend-item::before {
        content: '';
        width: 15px;
        height: 15px;
        display: inline-block;
        border-radius: 50%;
    }

    .active-legend::before {
        background-color: #28a745;
    }

    .current-legend::before {
        background-color: #ffc107;
    }

    .pending-legend::before {
        background-color: #dee2e6;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .payroll-tracker {
            flex-direction: column; /* stack steps vertically */
            align-items: flex-start;
        }

        .tracker-step {
            flex: 1 1 100%;
            text-align: left;
        }

        .sub-process {
            justify-content: flex-start;
        }
    }
</style>
<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        <?= $unit?> ( <?= $payroll_type ?>) 
                    </h5>

                    <div>
                        <?php if($status == 1): ?>
                            <span class="badge bg-secondary">Draft Payroll</span>

                        <?php elseif($status >= 2 && $status < 7): ?>
                            <span class="badge bg-warning text-dark">
                                Pending Admin Approval
                            </span>

                        <?php elseif($status == 7): ?>
                            <span class="badge bg-success">
                                Approved
                            </span>

                        <?php elseif($status == 9): ?>
                            <span class="badge bg-primary">
                                Released
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Gross Pay
                                            </div>
                                            <h5 class="mb-0 fw-bold text-success" id="total_dw_gross_pay">
                                                ₱0.00
                                            </h5>
                                        </div>
                                        <div class="text-success dashboard-icon">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Deductions
                                            </div>
                                            <h5 class="mb-0 fw-bold text-danger" id="total_dw_deduction">
                                                ₱0.00
                                            </h5>
                                        </div>
                                        <div class="text-danger dashboard-icon">
                                            <i class="bi bi-dash-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="col-12 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body d-flex justify-content-center align-items-center">

                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-secondary btn-sm w-100 dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> Actions
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end w-100 shadow">

                                            <!-- PRINT -->
                                            <li>
                                                <a class="dropdown-item" href="#" id="btnPrint" data-url="<?= base_url('payroll/export_pdf_dw/'.$period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Download Payroll
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('payroll/export_transmittal_pdf/'. $period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Download Transmittal PDF
                                                </a>
                                            </li>

                                            <!-- SUBMIT -->
                                           <?php if($status == 1): ?>
                                                <li>
                                                    <a class="dropdown-item submit_payroll" href="#" data-period_id="<?= $period_id ?>" data-payroll_number="<?= $payroll_number ?>"> <!-- add payroll_number -->
                                                        <i class="bi bi-check-circle me-2"></i> Submit Payroll
                                                    </a>
                                                </li>
                                                <?php else: ?>
                                                <li>
                                                    <span class="dropdown-item text-muted">
                                                        <i class="bi bi-hourglass-split me-2"></i>
                                                        Payroll Submitted
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <!-- GENERATE PAYSLIPS -->
                                            <li>
                                                <a class="dropdown-item"
                                                href="#"
                                                onclick="generatePayslips(<?= $period_id ?>)">
                                                    <i class="bi bi-receipt me-2"></i> Generate Payslips
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm table-bordered" id="savedPayrollTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Days Worked</th>
                                    <th>Rate Per Day</th>
                                    <th>Gross Pay</th>
                                    <th>LWOP Remarks</th>
                                    <th>GSIS</th>
                                    <th>Pag-Ibig</th>
                                    <th>PhilHealth</th>
                                    <th>Other Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($payrolls as $row){?>
                                <?php 
                                    $total_days = 0;
                                    $total_rate = 0;
                                    $total_gross = 0;
                                    $total_lwop = 0;
                                    $total_gsis = 0;
                                    $total_pagibig = 0;
                                    $total_philhealth = 0;
                                    $total_net = 0;

                                    $days = floatval($row->days_worked);
                                    $rate = floatval($row->rate_per_day);
                                    $gross = floatval($row->gross_pay);
                                    $lwop = floatval($row->lwop_days);
                                    $gsis = floatval($row->gsis);
                                    $pagibig = floatval($row->pagibig);
                                    $philhealth = floatval($row->philhealth);
                                    $net = floatval($row->net_pay);

                                    // accumulate
                                    $total_days += $days;
                                    $total_rate += $rate; // optional (can remove if not needed)
                                    $total_gross += $gross;
                                    $total_lwop += $lwop;
                                    $total_gsis += $gsis;
                                    $total_pagibig += $pagibig;
                                    $total_philhealth += $philhealth;
                                    $total_net += $net;
                                ?>
                                 <tr>
                                    <td><?= $row->name ?></td>
                                    <td><?= $row->position ?></td>
                                    <td>₱ <?= number_format($row->days_worked,2) ?></td>
                                    <td>₱ <?= number_format($row->rate_per_day,2) ?></td>
                                    <td>₱ <?= number_format($row->gross_pay,2) ?></td>
                                    <td class="amount-accrued">₱ <?= number_format($row->lwop_days,2) ?></td>
                                    
                                    <!-- Mandatory deductions -->
                                    <td class="gsis">₱ <?= number_format($row->gsis,2) ?></td>
                                    <td class="philhealth">₱ <?= number_format($row->pagibig,2) ?></td>
                                    <td class="pagibig">₱ <?= number_format($row->philhealth,2) ?></td>
                                    
                                    <td><?= $row->other_deductions ?></td>
                                    <td class="tax-amount">₱ <?= number_format($row->net_pay,2) ?></td>
                                    <td class="netpay">₱ <?= number_format($row->net_pay,2) ?></td>
                                    <td></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="4" class="text-end">TOTAL</td>

                                    <td class="text-end"><?= number_format($total_gross,2) ?></td>
                                    <td class="text-end"><?= number_format($total_lwop,2) ?></td>

                                    <td class="text-end"><?= number_format($total_gsis,2) ?></td>
                                    <td class="text-end"><?= number_format($total_pagibig,2) ?></td>
                                    <td class="text-end"><?= number_format($total_philhealth,2) ?></td>

                                    <td></td> <!-- other deductions -->

                                    <td class="text-end"><?= number_format($total_net,2) ?></td>
                                    <td class="text-end"><?= number_format($total_net,2) ?></td>

                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>


