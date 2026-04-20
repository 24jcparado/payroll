<?php
$grouped = [];

if (!empty($payrolls)) {
    foreach ($payrolls as $row) {
        $grouped[$row->school_year][] = $row;
    }
}
?>
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
        <div class="col-4 col-sm-4 col-lg-4">
            <!-- PAYROLL FORM -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payroll Computation</h5>
                </div>

                <div class="card-body">
                    <form action="<?= base_url('payroll/save_payroll_opt') ?>" method="POST" class="container-fluid" style="font-size:14px;">

                    <!-- EMPLOYEE SELECT -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Employee</label>
                        <select id="employee_select" name="employee_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            <?php 
                            // Loop through employees
                            foreach($employees as $row): 
                
                            ?>
                                <option value="<?= $row->employee_id ?>" data-rate="<?= $row->rate_per_hour ?>" data-tax="<?= $row->tax ?>">
                                    <?= htmlspecialchars($row->name.' '.(!empty($row->middle_name)? strtoupper(substr($row->middle_name,0,1)).'. ':'').$row->last_name) ?>
                                    (Rate-<?= $row->rate_per_hour ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="payroll_period_id" id="payroll_period_id" value="<?= $period_id ?>">
                    <div class="row g-4">
                        <!-- ================= RATE INFORMATION ================= -->
                        <div class="col-12">

                            <div class="section-title section-earnings">
                                Compensation Rate
                            </div>

                            <div class="row g-3">

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Rate per Hour</label>
                                    <input type="number" step="0.01" min="0"
                                        id="rate_per_hour" name="rate_per_hour"
                                        class="form-control payroll-input">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">School Year</label>

                                    <div class="input-group">
                                        <select id="school_year" name="school_year" 
                                            class="form-select payroll-input" required>
                                            <option value="">-- Select School Year --</option>
                                        </select>

                                        <button type="button" 
                                            class="btn btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#schoolYearModal">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- ================= MONTHLY HOURS ================= -->
                        <!-- ================= MONTH SELECTION ================= -->
                        <div class="col-12">

                            <div class="section-title section-summary">
                                Monthly Hours (<?= date('Y') ?>)
                            </div>

                            <div class="row g-3">

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Select Month(s)</label>
                                    <select id="month_selector" class="form-select" multiple>
                                        <option value="january">January</option>
                                        <option value="february">February</option>
                                        <option value="march">March</option>
                                        <option value="april">April</option>
                                        <option value="may">May</option>
                                        <option value="june">June</option>
                                        <option value="july">July</option>
                                        <option value="august">August</option>
                                        <option value="september">September</option>
                                        <option value="october">October</option>
                                        <option value="november">November</option>
                                        <option value="december">December</option>
                                    </select>
                                    <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple</small>
                                </div>

                            </div>

                            <div id="selected-months-container" class="row g-3 mt-3"></div>

                        </div>

                        <hr>

                        <!-- ================= ANNUAL SUMMARY ================= -->
                        <div class="col-12 mt-4">

                            <div class="section-title section-summary">
                                Annual Summary
                            </div>

                            <div class="row g-3">

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Total Annual Hours</label>
                                    <input type="text" id="total_annual_hours" name="total_hours"
                                        class="form-control readonly-field fw-bold"
                                        readonly>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-danger">Tax (%)</label>
                                    <input type="number" step="0.01" min="0"
                                        id="tax_percentage"
                                        class="form-control payroll-input"
                                        placeholder="Enter tax percentage">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-danger">Tax Amount</label>
                                    <input type="text" id="total_tax_amount" name="tax_amount" class="form-control readonly-field fw-bold text-danger" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-success">Gross Pay</label>
                                    <input type="text" id="gross_amount" name="gross_amount"
                                        class="form-control readonly-field fw-bold text-success"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-primary">Total Net Pay</label>
                                    <input type="text"
                                        id="total_net_pay"
                                        name="total_net"
                                        class="form-control readonly-field fw-bold text-primary"
                                        readonly>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    Save Payroll
                                </button>
                            </div>

                        </div>

                    </div>
                </form>


                </div>
            </div>
        </div>
        <div class="col-8 col-sm-8 col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-3">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        <?= $unit?> ( <?= $payroll_type ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="card-body">

                            <div class="tracker-legend mb-4">
                                <span class="legend-item active-legend">Completed</span>
                                <span class="legend-item current-legend">Current</span>
                                <span class="legend-item pending-legend">Pending</span>
                            </div>
                            <div class="payroll-tracker">
                                <div class="tracker-step" id="step-1">
                                    <div class="tracker-circle">1</div>
                                    <div>HRMO</div>
                                    <div class="sub-process">
                                        <div class="sub-step" id="hrmo-draft">DRAFT PAYROLL</div>
                                        <div class="sub-step" id="hrmo-final">FINAL PAYROLL</div>
                                    </div>
                                </div>
                                <div class="tracker-step" id="step-2">
                                    <div class="tracker-circle">2</div>
                                    <div>Accounting</div>
                                    <div class="sub-process">
                                        <div class="sub-step" id="acc-pre-d">PRE AUDIT - (D)</div>
                                        <div class="sub-step" id="acc-tax">TAX COMPUTATION</div>
                                        <div class="sub-step" id="acc-pre-f">PRE AUDIT - (F)</div>
                                    </div>
                                </div>

                                <div class="tracker-step" id="step-3">
                                    <div class="tracker-circle">3</div>
                                    <div>Budget</div>
                                </div>

                                <div class="tracker-step" id="step-4">
                                    <div class="tracker-circle">4</div>
                                    <div>Approved</div>
                                </div>

                                <div class="tracker-step" id="step-5">
                                    <div class="tracker-circle">5</div>
                                    <div>Released</div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Amount Accrued
                                            </div>
                                            <h5 class="mb-0 fw-bold text-success" id="total_amount_accrued">
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
                                                Total Tax
                                            </div>
                                            <h5 class="mb-0 fw-bold text-danger" id="total_tax">
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
                                                <a class="dropdown-item"
                                                href="#"
                                                id="btnPrint"
                                                data-url="<?= base_url('payroll/ov_pt_payroll_format/'.$period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Print Payroll
                                                </a>
                                            </li>

                                            <!-- SUBMIT -->
                                            <li>
                                                <a class="dropdown-item submit_payroll"
                                                href="#"
                                                data-payroll_number="<?= $payroll_number ?>" data-period_id="<?= $period_id ?>">
                                                    <i class="bi bi-check-circle me-2"></i> Submit Payroll
                                                </a>
                                            </li>

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
                    <table class="table table-xsm table-bordered text-center" id="savedPayrollTable">
                        <thead class="table-light">
                            <thead>
                                <tr>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Rate/Hour</th>
                                    <th rowspan="2">Particular</th>

                                    <!-- First 6 Months -->
                                    <th>Jan</th>
                                    <th>Feb</th>
                                    <th>Mar</th>
                                    <th>Apr</th>
                                    <th>May</th>
                                    <th>Jun</th>

                                    <th rowspan="2">No. HRS</th>
                                    <th rowspan="2">Amount Accrued</th>
                                    <th rowspan="2">Less W/Tax</th>
                                    <th rowspan="2">Net Due</th>
                                </tr>

                                <tr>
                                    <th>Jul</th>
                                    <th>Aug</th>
                                    <th>Sep</th>
                                    <th>Oct</th>
                                    <th>Nov</th>
                                    <th>Dec</th>
                                </tr>
                            </thead>
                        </thead>
                        <tbody>
                            <?php if(!empty($grouped)): ?>

                                <?php foreach($grouped as $school_year => $records): ?>
                                    <tr>
                                        <td colspan="16" class="text-start fw-bold bg-light">
                                            SCHOOL YEAR: <?= htmlspecialchars($school_year) ?>
                                        </td>
                                    </tr>

                                    <?php foreach($records as $row): ?>
                                        <tr>
                                            <td rowspan="2">
                                                <?= htmlspecialchars(
                                                    $row->name . ' ' .
                                                    (!empty($row->middle_name)
                                                        ? strtoupper(substr($row->middle_name, 0, 1)) . '. '
                                                        : ''
                                                    ) .
                                                    $row->last_name
                                                ) ?>
                                            </td>

                                            <td rowspan="2" class="text-end">
                                                <?= number_format($row->rate_per_hour,2) ?>
                                            </td>

                                            <td rowspan="2">
                                                <?= htmlspecialchars($row->particulars ?? '-') ?>
                                            </td>

                                            <!-- Jan–Jun -->
                                            <td><?= number_format($row->jan ?? 0,2) ?></td>
                                            <td><?= number_format($row->feb ?? 0,2) ?></td>
                                            <td><?= number_format($row->mar ?? 0,2) ?></td>
                                            <td><?= number_format($row->apr ?? 0,2) ?></td>
                                            <td><?= number_format($row->may ?? 0,2) ?></td>
                                            <td><?= number_format($row->jun ?? 0,2) ?></td>

                                            <td rowspan="2"><?= number_format($row->total_hours,2) ?></td>
                                            <td class="amount-accrued" rowspan="2"><?= number_format($row->gross_amount,2) ?></td>
                                            <td class="tax-amount" rowspan="2"><?= number_format($row->tax_amount,2) ?></td>
                                            <td rowspan="2"><?= number_format($row->total_net,2) ?></td>
                                        </tr>

                                        <tr>
                                            <!-- Jul–Dec -->
                                            <td><?= number_format($row->jul ?? 0,2) ?></td>
                                            <td><?= number_format($row->aug ?? 0,2) ?></td>
                                            <td><?= number_format($row->sept ?? 0,2) ?></td>
                                            <td><?= number_format($row->oct ?? 0,2) ?></td>
                                            <td><?= number_format($row->nov ?? 0,2) ?></td>
                                            <td><?= number_format($row->dece ?? 0,2) ?></td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endforeach; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="16" class="text-center">
                                        No payroll records found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="schoolYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add School Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Term</label>
                    <select id="term" class="form-select">
                        <option value="FS">First Semester (FS)</option>
                        <option value="SS">Second Semester (SS)</option>
                        <option value="SUMMER">Summer</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Academic Year</label>
                    <input type="text" 
                        id="year_range" 
                        class="form-control"
                        placeholder="2025-2026">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" 
                    class="btn btn-secondary" 
                    data-bs-dismiss="modal">Cancel</button>

                <button type="button" 
                    class="btn btn-primary" 
                    id="saveSchoolYear">Save</button>
            </div>

        </div>
    </div>
</div>

<script>
   function calculatePayroll() {
    const rate = parseFloat(document.getElementById("rate_per_hour")?.value) || 0;
    const taxPercent = parseFloat(document.getElementById("tax_percentage")?.value) || 0;
    let totalHours = 0;
    document.querySelectorAll(".monthly-hours").forEach(function(input){
        totalHours += parseFloat(input.value) || 0;
    });
    const totalGross = totalHours * rate;
    const taxAmount = totalGross * (taxPercent / 100);
    const netPay = totalGross - taxAmount;

    const hoursField = document.getElementById("total_annual_hours");
    const grossField = document.getElementById("gross_amount");
    const taxField = document.getElementById("total_tax_amount");
    const netField = document.getElementById("total_net_pay");

    if (hoursField) hoursField.value = totalHours.toFixed(2);
    if (grossField) grossField.value = totalGross.toFixed(2);
    if (taxField) taxField.value = taxAmount.toFixed(2);
    if (netField) netField.value = netPay.toFixed(2);
}
document.addEventListener("DOMContentLoaded", function() {

    // Static inputs
    document.getElementById("rate_per_hour")
        ?.addEventListener("input", calculatePayroll);

    document.getElementById("tax_percentage")
        ?.addEventListener("input", calculatePayroll);

    // Delegate for dynamically created monthly hours
    document.addEventListener("input", function(e) {
        if (e.target.classList.contains("monthly-hours")) {
            calculatePayroll();
        }
    });

});

    document.getElementById("month_selector").addEventListener("change", function () {

        const container = document.getElementById("selected-months-container");
        container.innerHTML = "";

        const selectedMonths = Array.from(this.selectedOptions).map(opt => ({
            value: opt.value,
            text: opt.text
        }));

        selectedMonths.forEach(month => {
            // Map full month to short key
            const monthMap = {
                'january':'jan','february':'feb','march':'mar','april':'apr','may':'may',
                'june':'jun','july':'jul','august':'aug','september':'sept','october':'oct',
                'november':'nov','december':'dece'
            };

            const monthKey = monthMap[month.value.toLowerCase()];

            const col = document.createElement("div");
            col.className = "col-md-12 col-lg-12";

            col.innerHTML = `
                <div class="rounded p-0">
                    <label class="fw-bold">${month.text}</label>
                    <div class="mt-2">
                        <input type="number" step="0.01" min="0" name="hours_${monthKey}" class="form-control monthly-hours payroll-input">
                    </div>
                </div>
            `;
            container.appendChild(col);
        });

    });

    $('#employee_select').on('change', function(){
        const rate = $(this).find(':selected').data('rate');
        const tax = $(this).find(':selected').data('tax');
        $('#rate_per_hour').val(rate || '');
        $('#tax_percentage').val(tax || '');
    });

    document.addEventListener("DOMContentLoaded", function () {

        const select = document.getElementById("school_year");
        const saveBtn = document.getElementById("saveSchoolYear");
        const input = document.getElementById("new_school_year");

        function loadSchoolYears() {
            select.innerHTML = '<option value="">-- Select School Year --</option>';

            let years = JSON.parse(localStorage.getItem("schoolYears")) || [];

            // Sort latest first
            years.sort().reverse();

            years.forEach(function(year){
                let option = document.createElement("option");
                option.value = year;
                option.textContent = year;
                select.appendChild(option);
            });
        }

        saveBtn.addEventListener("click", function(){

            let term = document.getElementById("term").value;
            let year = document.getElementById("year_range").value.trim();

            let pattern = /^\d{4}-\d{4}$/;

            if (!pattern.test(year)) {
                alert("Year must be in format 2025-2026");
                return;
            }

            let value = term + " " + year;

            let years = JSON.parse(localStorage.getItem("schoolYears")) || [];

            if (!years.includes(value)) {
                years.push(value);
                localStorage.setItem("schoolYears", JSON.stringify(years));
            } else {
                alert("School Year already exists.");
            }

            document.getElementById("year_range").value = "";
            loadSchoolYears();

            bootstrap.Modal.getInstance(
                document.getElementById('schoolYearModal')
            ).hide();
        });

        loadSchoolYears();
    });

    $('#btnPrint').on('click', function(e){
        e.preventDefault();
        let url = $(this).data('url');
        window.open(url, '_blank');
    });

    function updateTotals() {
        let totalAccrued = 0;
        let totalTax = 0;
        document.querySelectorAll('#savedPayrollTable td.amount-accrued').forEach(td => {
            let accrued = parseFloat(td.textContent.replace(/,/g, '')) || 0;
            totalAccrued += accrued;
        });
        document.querySelectorAll('#savedPayrollTable td.tax-amount').forEach(td => {
            let tax = parseFloat(td.textContent.replace(/,/g, '')) || 0;
            totalTax += tax;
        });

        document.getElementById('total_amount_accrued').textContent =
            '₱' + totalAccrued.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        document.getElementById('total_tax').textContent =
            '₱' + totalTax.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Initialize totals after DOM is ready
    document.addEventListener('DOMContentLoaded', updateTotals);


    $(document).on('click', '.submit_payroll', function () {
        let period_id = $(this).data('period_id');
        let payroll_number = $(this).data('payroll_number');
        if (!confirm("Are you sure you want to submit this payroll?")) {
            return;
        }
        $.ajax({
            url: "<?= base_url('payroll/submit_payroll') ?>",
            type: "POST",
            data: { period_id: period_id, payroll_number: payroll_number },
            dataType: "json",
            success: function (res) {

                if (res.status === 'success') {
                    alert('Payroll submitted successfully.');
                    location.reload();
                } else {
                    alert('Something went wrong.');
                }
            },
            error: function () {
                alert('Server error.');
            }
        });
    });
</script>


