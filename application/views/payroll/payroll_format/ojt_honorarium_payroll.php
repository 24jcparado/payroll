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
                    <?php if($status >= 2 && $status < 7): ?>
                        <div class="alert alert-warning d-flex align-items-center mb-3">
                            <i class="bi bi-hourglass-split me-2"></i>
                            <div>
                                This payroll has been <strong>submitted by HR</strong> and is currently
                                <strong>awaiting approval from the Admin Office</strong><br>
                                <strong>Token ID: <?= $token_id ?></strong>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                    <div>HR</div>
                                </div>

                                <div class="tracker-step" id="step-2">
                                    <div class="tracker-circle">2</div>
                                    <div>Admin Office</div>
                                </div>

                                <div class="tracker-step" id="step-3">
                                    <div class="tracker-circle">3</div>
                                    <div>Budget Office</div>
                                </div>

                                <div class="tracker-step" id="step-4">
                                    <div class="tracker-circle">4</div>
                                    <div>Accounting Office</div>
                                </div>

                                <div class="tracker-step" id="step-5">
                                    <div class="tracker-circle">5</div>
                                    <div>VP for Signing</div>
                                </div>

                                <div class="tracker-step" id="step-6">
                                    <div class="tracker-circle">6</div>
                                    <div>Cashier (Bank Release)</div>
                                </div>

                            </div>

                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small text-uppercase fw-semibold">
                                                Total Gross Pay
                                            </div>
                                            <h5 class="mb-0 fw-bold text-success" id="total_gross_pay">
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
                                            <h5 class="mb-0 fw-bold text-danger" id="total_deduction">
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
                                                data-url="<?= base_url('payroll/general_payroll/'.$period_id) ?>">
                                                    <i class="bi bi-printer me-2"></i> Print Payroll
                                                </a>
                                            </li>

                                            <!-- SUBMIT -->
                                           <?php if($status == 1): ?>
                                                <li>
                                                    <a class="dropdown-item submit_payroll"
                                                    href="#"
                                                    data-period_id="<?= $period_id ?>"
                                                    data-payroll_number="<?= $payroll_number ?>"> <!-- add payroll_number -->
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
                    <form method="POST" id="ojtPayrollForm" action="<?= base_url('payroll/saveOjtHonorarium') ?>">
                        <input type="hidden" id="csrf_name" value="<?= $this->security->get_csrf_token_name() ?>">
                        <input type="hidden" id="csrf_hash" value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle text-center" id="ojtPayrollTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Rate / Subject</th>
                                        <th>Total Amount Paid</th>
                                        <th>Amount Accrued</th>
                                        <th>Tax</th>
                                        <th>Net Due</th>
                                    </tr>
                                </thead>
                                <tbody id="ojtPayrollBody">
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL</th>
                                        <th id="totalAmountSum">0.00</th>
                                        <th id="amountAccruedSum">0.00</th>
                                        <th id="taxSum">0.00</th>
                                        <th id="netDueSum">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addOjtRow">
                            <i class="bi bi-plus-lg"></i> Add Row
                        </button>
                        <button type="submit" class="btn btn-sm btn-success mt-2">Save Payroll</button>
                    </form>

                    <!-- Hidden Employee Template -->
                    <select id="employee_select_template" style="display:none">
                        <option value="">-- Select Employee --</option>
                        <?php foreach ($employees as $row): ?>
                            <?php if (!in_array($row->employee_id, $paid_ids)): ?>
                                <option value="<?= $row->employee_id ?>"
                                        data-name="<?= htmlspecialchars($row->name) ?>"
                                        data-mname="<?= htmlspecialchars($row->middle_name) ?>"
                                        data-lname="<?= htmlspecialchars($row->last_name) ?>"
                                        data-ext="<?= htmlspecialchars($row->ext ?? '') ?>">
                                    <?= htmlspecialchars($row->name) . ' ' . (!empty($row->middle_name) ? strtoupper(substr($row->middle_name,0,1)) . '. ' : '') . $row->last_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ojtPayrollForm');
    const tableBody = document.getElementById('ojtPayrollBody');
    const employeeTemplate = document.getElementById('employee_select_template');

    // =========================
    // ADD ROW
    // =========================
    function addRow() {
        const newRow = document.createElement('tr');
        const period_id = <?= json_encode($period_id) ?>;

        newRow.innerHTML = `
            <td>
                <select class="form-select form-select-sm employee-dropdown" required></select>
                <input type="hidden" class="employee-id" name="employee_id[]">
                <input type="hidden" class="employee-name" name="o_name[]">
            </td>
            <td>
                <select class="form-select form-select-sm position" name="o_position[]">
                    <option value="">Select</option>
                    <option value="head">Head</option>
                    <option value="coordinator">Coordinator</option>
                </select>
            </td>
            <td>
                <input type="hidden" value="${period_id}" name="payroll_period_id[]">
                <input type="text" class="form-control form-control-sm rate" name="o_subject[]" readonly>
            </td>
            <td><input type="number" class="form-control form-control-sm total-amount" name="o_total_amount[]" min="0"></td>
            <td><input type="number" class="form-control form-control-sm amount-accrued" name="o_amount_accrued[]" readonly></td>
            <td><input type="number" class="form-control form-control-sm tax" name="o_tax[]" min="0"></td>
            <td><input type="text" class="form-control form-control-sm net-due" name="o_net[]" readonly></td>
        `;

        tableBody.appendChild(newRow);

        // Populate employee dropdown
        const dropdown = newRow.querySelector('.employee-dropdown');
        dropdown.innerHTML = employeeTemplate.innerHTML;
    }

    addRow();
    document.getElementById('addOjtRow').addEventListener('click', addRow);

    // =========================
    // COMPUTE ROW
    // =========================
    function computeRow(row) {

        const employeeSelect = row.querySelector('.employee-dropdown');
        const option = employeeSelect.options[employeeSelect.selectedIndex];

        if(option && option.value){
            const fname = option.dataset.fname || '';
            const mname = option.dataset.mname || '';
            const lname = option.dataset.lname || '';
            const ext   = option.dataset.ext || '';

            let fullName = lname + ', ' + fname;
            if(mname) fullName += ' ' + mname.charAt(0).toUpperCase() + '.';
            if(ext) fullName += ' ' + ext;

            row.querySelector('.employee-id').value = option.value;
            row.querySelector('.employee-name').value = fullName;
        }

        // Position rate
        const position = row.querySelector('.position').value;
        let rate = 0;

        if(position === 'head') rate = 0.10;
        else if(position === 'coordinator') rate = 0.60;

        row.querySelector('.rate').value = (rate * 100).toFixed(0) + '%';

        const totalAmount = parseFloat(row.querySelector('.total-amount').value) || 0;
        const accrued = totalAmount * rate;

        row.querySelector('.amount-accrued').value = accrued.toFixed(2);

        const tax = parseFloat(row.querySelector('.tax').value) || 0;
        const netDue = accrued - tax;

        row.querySelector('.net-due').value = netDue.toFixed(2);
    }

    // =========================
    // COMPUTE TOTALS
    // =========================
    function computeTotals() {
        let totalAmountSum = 0, accruedSum = 0, taxSum = 0, netDueSum = 0;

        tableBody.querySelectorAll('tr').forEach(row => {
            totalAmountSum += parseFloat(row.querySelector('.total-amount')?.value) || 0;
            accruedSum     += parseFloat(row.querySelector('.amount-accrued')?.value) || 0;
            taxSum         += parseFloat(row.querySelector('.tax')?.value) || 0;
            netDueSum      += parseFloat(row.querySelector('.net-due')?.value) || 0;
        });

        document.getElementById('totalAmountSum').textContent   = totalAmountSum.toFixed(2);
        document.getElementById('amountAccruedSum').textContent = accruedSum.toFixed(2);
        document.getElementById('taxSum').textContent           = taxSum.toFixed(2);
        document.getElementById('netDueSum').textContent        = netDueSum.toFixed(2);
    }

    // =========================
    // EVENT LISTENERS
    // =========================
    tableBody.addEventListener('input', function(e){
        const row = e.target.closest('tr');
        if(!row) return;
        computeRow(row);
        computeTotals();
    });

    tableBody.addEventListener('change', function(e){
        const row = e.target.closest('tr');
        if(!row) return;
        computeRow(row);
        computeTotals();
    });

    // =========================
    // FORM SUBMIT (AJAX)
    // =========================
    form.addEventListener('submit', function(e){
        e.preventDefault();

        const formData = new FormData(form);

        // Append CSRF
        formData.append(
            document.getElementById('csrf_name').value,
            document.getElementById('csrf_hash').value
        );

        fetch('<?= base_url("payroll/saveOjtHonorarium") ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {

            if(res.status === 'success'){

                alert('OJT Payroll saved successfully!');
                tableBody.innerHTML = '';

                res.data.forEach(row => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${row.o_name}</td>
                        <td>${row.o_position}</td>
                        <td>${parseFloat(row.o_subject).toFixed(0)}%</td>
                        <td>${parseFloat(row.o_total_amount).toFixed(2)}</td>
                        <td>${parseFloat(row.o_amount_accrued).toFixed(2)}</td>
                        <td>${parseFloat(row.o_tax).toFixed(2)}</td>
                        <td>${parseFloat(row.o_net).toFixed(2)}</td>
                    `;

                    tableBody.appendChild(tr);
                });

                computeTotals();

                // Refresh CSRF token
                if(res.csrf_hash){
                    document.getElementById('csrf_hash').value = res.csrf_hash;
                }

            } else {
                alert(res.message || 'Error saving OJT payroll.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving.');
        });
    });

    function loadSavedData() {
        const period_id = <?= json_encode($period_id) ?>;

        fetch(`<?= base_url('payroll/getOjtHonorarium/') ?>${period_id}`)
        .then(res => res.json())
        .then(data => {

            tableBody.innerHTML = '';

            if(data.length === 0){
                addRow(); // fallback if no data
                return;
            }

            data.forEach(row => {
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${row.o_name}</td>
                    <td>${row.o_position}</td>
                    <td>${parseFloat(row.o_subject).toFixed(0)}%</td>
                    <td>${parseFloat(row.o_total_amount).toFixed(2)}</td>
                    <td>${parseFloat(row.o_amount_accrued).toFixed(2)}</td>
                    <td>${parseFloat(row.o_tax).toFixed(2)}</td>
                    <td>${parseFloat(row.o_net).toFixed(2)}</td>
                `;

                tableBody.appendChild(tr);
            });

            computeTotals();
        })
        .catch(err => {
            console.error('Load error:', err);
        });
    }
    loadSavedData();

});
</script>