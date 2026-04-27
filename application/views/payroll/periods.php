
<style>
    /* Progress Stepper Styling */
.workflow-vertical {
    position: relative;
    padding-left: 1.5rem;
}
.workflow-vertical::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 5px;
    bottom: 5px;
    width: 2px;
    background: #e9ecef;
}
.step-item {
    position: relative;
    padding-bottom: 1.5rem;
}
.step-dot {
    position: absolute;
    left: -21px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #ced4da;
    z-index: 2;
}
.step-item.active .step-dot { border-color: #0d6efd; background: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2); }
.step-item.completed .step-dot { border-color: #198754; background: #198754; }
.step-item.completed .step-text { color: #198754; font-weight: 600; }

/* Table Hover Effects */
#savedPayrollTable tbody tr:hover {
    background-color: rgba(123, 17, 19, 0.03) !important;
    cursor: pointer;
}

/* Particulars Preview Box */
#particulars_display {
    background-color: #f8f9fa;
    border: 1px dashed #ced4da;
    color: #495057;
    font-style: italic;
    font-size: 0.9rem;
}
/* Professional Tabs Styling */
    .nav-pills-custom .nav-link {
        color: #6c757d;
        font-weight: 600;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    .nav-pills-custom .nav-link.active {
        background-color: var(--evsu-red) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(107, 15, 26, 0.2);
    }
    .nav-pills-custom .nav-link:not(.active):hover {
        background-color: #eee;
    }
    
    .x-small { font-size: 0.75rem; }
    .btn-maroon { background-color: #6b0f1a; color: white; transition: 0.3s; }
    .btn-maroon:hover { background-color: #4a0a0b; color: white; transform: translateY(-2px); }
</style>
<?php 
// Updated Workflow Steps based on your requirements
$steps = [
    1 => 'HR',
    2 => 'Admin',
    3 => 'Budget',
    4 => 'Accounting',
    5 => 'VP Signature',
    6 => 'Cashier'
];

$current_month = date('m');
// ... rest of your code
?>
<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark"><?= $period ?></h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-maroon rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPeriodModal">
                <i class="bi bi-plus-lg me-1"></i> New Payroll Entry
            </button>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-3">
                        <ul class="nav nav-pills nav-pills-custom" id="payrollTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active btn-sm px-4" id="active-tab" data-bs-toggle="pill" data-bs-target="#activePayrolls" type="button">
                                    Active Queue 
                                    <span class="badge bg-danger ms-1">
                                        <?= count(array_filter($payroll, fn($r) => $r->status < 4)) ?>
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item ms-2">
                                <button class="nav-link btn-sm px-4" id="history-tab" data-bs-toggle="pill" data-bs-target="#historyPayrolls" type="button">
                                    Disbursement History
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="activePayrolls">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="text-muted x-small text-uppercase">
                                                <th class="ps-4">Reference</th>
                                                <th>Payroll Details</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $activeItems = array_filter($payroll, fn($r) => $r->status < 4);
                                            if(!empty($activeItems)): foreach($activeItems as $row): 
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="text-dark fw-bold"><?= $row->payroll_number ?></span><br>
                                                    <small class="text-muted"><?= date('M d, Y', strtotime($row->created_at)) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1"><?= strtoupper($row->payroll_type) ?></span>
                                                    <div class="small fw-semibold"><?= $row->unit ?></div>
                                                    <div class="x-small text-muted"><?= $row->date_period ?></div>
                                                </td>
                                                <td>
                                                    <?php if($row->status > 6): ?>
                                                        <span class="text-success small fw-bold">
                                                            <i class="bi bi-check-all"></i> Fully Released by Cashier
                                                        </span>
                                                    <?php else: ?>
                                                        <div class="d-flex align-items-center">
                                                            <div class="spinner-grow spinner-grow-sm text-warning me-2" role="status"></div>
                                                            <span class="small fw-bold text-dark">
                                                                <?= isset($steps[$row->status]) ? $steps[$row->status] : 'Pending HR' ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu shadow border-0">
                                                            <li><a class="dropdown-item" href="<?= base_url('payroll/run/'.$row->payroll_period_id) ?>"><i class="bi bi-calculator me-2"></i> Run Process</a></li>
                                                            <li><a class="dropdown-item btn-edit-payroll" href="javascript:void(0);" data-id="<?= $row->payroll_period_id ?>" data-number="<?= $row->payroll_number ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(<?= $row->payroll_period_id ?>)"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                                <tr><td colspan="4" class="text-center py-5 text-muted">No active payroll processing found.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="historyPayrolls">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="text-muted x-small text-uppercase">
                                                <th class="ps-4">Reference</th>
                                                <th>Payroll Details</th>
                                                <th>Completion Date</th>
                                                <th class="text-end pe-4">View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $historyItems = array_filter($payroll, fn($r) => $r->status >= 4);
                                            if(!empty($historyItems)): foreach($historyItems as $row): 
                                            ?>
                                            <tr class="opacity-75">
                                                <td class="ps-4">
                                                    <span class="text-muted fw-bold"><?= $row->payroll_number ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border mb-1"><?= strtoupper($row->payroll_type) ?></span>
                                                    <div class="small fw-semibold text-muted"><?= $row->unit ?></div>
                                                </td>
                                                <td><span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Processed</span></td>
                                                <td class="text-end pe-4">
                                                    <a href="<?= base_url('payroll/view/'.$row->payroll_period_id) ?>" class="btn btn-sm btn-light rounded-pill px-3">View Details</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                                <tr><td colspan="4" class="text-center py-5 text-muted">No completed records in history.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">Process Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="workflow-vertical">
                            <?php foreach($steps as $num => $label): ?>
                                <?php 
                                    // Logic to determine if a step in the legend is 'completed' or 'active'
                                    // This assumes you are looking at a specific $row or a general overview
                                    $is_completed = false; // Set based on your logic if needed
                                ?>
                                <div class="step-item">
                                    <div class="step-dot"></div>
                                    <div class="step-text small fw-bold"><?= $label ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="alert alert-info border-0 rounded-3 mt-3 py-2 x-small">
                            <i class="bi bi-info-circle-fill me-1"></i> Completed payrolls are moved to the <strong>History</strong> tab automatically.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ADD/EDIT PAYROLL ENTRY MODAL -->
<div class="modal fade" id="addPeriodModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('payroll/add_entry') ?>" id="payrollForm" method="POST"> <!-- change to your controller -->
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPayrollModalLabel"><i class="bi bi-plus-lg me-1"></i> Add Payroll Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payroll_number" class="form-label">Payroll Number</label>
                        <input type="text" class="form-control" id="payroll_number" name="payroll_number" value="<?= $payroll_number ?>" readonly>
                        <input type="hidden" name="payroll_period_id" id="payroll_period_id">
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="payroll_type" name="payroll_type" required>
                            <option value="">Select type of Payroll</option>
                            <option value="OVERLOAD">OVERLOAD</option>
                            <option value="PART-TIME">PART-TIME</option>
                            <option value="DAILY WAGE">DAILY WAGE</option>
                            <option value="CONTRACT OF SERVICE">CONTRACT OF SERVICE</option>
                            <option value="GENERAL PAYROLL">GENERAL PAYROLL</option>
                            <option value="">--Special Payroll--</option>
                            <option value="MID-YEAR BONUS">MID-YEAR BONUS</option>
                            <option value="OJT HONORARIUM">OJT HONORARIUM</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <select class="form-control" name="unit">
                            <option value="">Select Unit</option>
                            <?php $units = array_unique(array_column($employee, 'assignment'));
                                foreach ($units as $unit): ?>
                                <option value="<?= $unit ?>"><?= $unit ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- OTHER PAYROLL FIELDS -->
                    <div id="regularFields">
                        <div class="mb-3">
                            <label class="form-label">Payroll Period</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="date_from" id="date_from" required>
                                    <small class="text-muted">From</small>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="date_to" id="date_to" required>
                                    <small class="text-muted">To</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="currentYear" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Year</label>
                            <div class="input-group">
                                <span class="input-group-text" id="yearLabel">Year:</span>
                                <select id="current_year" name="current_year" class="form-select">
                                    <option value="">-- Select Year --</option>
                                    <?php for($y=date('Y'); $y>=2020; $y--): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#YearModal">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Particulars / Description</label>
                        <textarea id="particulars_display" class="form-control" rows="3" readonly></textarea>
                        <input type="hidden" name="particulars" id="particulars">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="YearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Current Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="modal-body">

                    <!-- Add Year -->
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="text" id="year_input" class="form-control" placeholder="C.Y. 2026">
                        <input type="hidden" id="edit_index">
                    </div>

                    <!-- Year List -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="yearTableBody"></tbody>
                    </table>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveYearBtn" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const payrollType = document.getElementById('payroll_type');
    const regularFields = document.getElementById('regularFields');
    const currentYear = document.getElementById('currentYear');

    if (payrollType && regularFields && currentYear) {

        function toggleFields() {
            const value = payrollType.value;

            // 🔸 Hide all first (clean state)
            regularFields.style.display = 'none';
            currentYear.style.display = 'none';

            if (value === 'OVERLOAD') {
                // Only OVERLOAD → hide everything
                regularFields.style.display = 'none';

            } else if (value === 'OJT HONORARIUM') {
                // Show Current Year only
                currentYear.style.display = 'block';

            } else if (value !== '') {
                // Other payroll types → show regular fields
                regularFields.style.display = 'block';
            }
        }

        payrollType.addEventListener('change', toggleFields);

        // 🔸 Initial load (important for edit mode / page refresh)
        toggleFields();
    }

    // ===== 2. Confirm Delete =====
    const deleteButtons = document.querySelectorAll('.btn-delete-payroll');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This payroll entry will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#800000',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= base_url('payroll/delete/') ?>" + id;
                    }
                });
            });
        });
    }

    // ===== 3. Date Restrictions =====
    const dateFrom = document.getElementById('date_from');
    const dateTo   = document.getElementById('date_to');
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function () {
            dateTo.min = this.value;
        });
    }

    // ===== 4. Workflow Steps =====
    const workflowContainers = document.querySelectorAll(".workflow-steps");
    if (workflowContainers.length > 0) {
        const flow = [
            { status: 1, step: '#step-1', sub: '#hrmo-draft' },
            { status: 4, step: '#step-1', sub: '#hrmo-final' },
            { status: 2, step: '#step-2', sub: '#acc-pre-d' },
            { status: 3, step: '#step-2', sub: '#acc-tax' },
            { status: 5, step: '#step-2', sub: '#acc-pre-f' },
            { status: 6, step: '#step-3', sub: null },
            { status: 7, step: '#step-4', sub: null },
            { status: 9, step: '#step-5', sub: null }
        ];

        workflowContainers.forEach(container => {
            const currentStatus = parseInt(container.dataset.status);
            const steps = container.querySelectorAll(".step");

            const currentFlow = flow.find(f => f.status === currentStatus);
            if (!currentFlow) return;

            steps.forEach(step => {
                const stepNumber = parseInt(step.id.replace('step-', ''));
                if (step.id === currentFlow.step.replace('#','')) {
                    step.classList.add("active");
                }
                if (stepNumber < parseInt(currentFlow.step.replace('#step-',''))) {
                    step.classList.add("completed");
                }
            });
        });
    }

});

$(document).ready(function () {

    const STORAGE_KEY = 'payroll_years';

    function getYears() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    }

    function saveYears(years) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(years));
    }
    function loadYearsToSelect() {
        const years = getYears();
        const select = $('#current_year');

        select.empty().append('<option value="">-- Select Current Year --</option>');

        years.forEach(year => {
            select.append(`<option value="${year}">${year}</option>`);
        });
    }

    function loadYearsToTable() {
        const years = getYears();
        const tbody = $('#yearTableBody');

        tbody.empty();

        years.forEach((year, index) => {
            tbody.append(`
                <tr>
                    <td>${year}</td>
                    <td>
                        <button class="btn btn-sm btn-warning editYear" data-index="${index}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteYear" data-index="${index}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }
    $('#saveYearBtn').click(function () {
        const year = $('#year_input').val().trim();
        const editIndex = $('#edit_index').val();

        if (!year) {
            alert('Please enter a year.');
            return;
        }

        let years = getYears();

        if (editIndex === '') {
            if (years.includes(year)) {
                alert('Year already exists.');
                return;
            }
            years.push(year);
        } else {
            years[editIndex] = year;
            $('#edit_index').val('');
        }

        years.sort((a, b) => b - a);

        saveYears(years);
        loadYearsToSelect();
        loadYearsToTable();

        $('#year_input').val('');
    });

    $(document).on('click', '.editYear', function () {
        const index = $(this).data('index');
        const years = getYears();

        $('#year_input').val(years[index]);
        $('#edit_index').val(index);
    });

    $(document).on('click', '.deleteYear', function () {
        if (!confirm('Are you sure you want to delete this year?')) return;

        const index = $(this).data('index');
        let years = getYears();

        years.splice(index, 1);

        saveYears(years);
        loadYearsToSelect();
        loadYearsToTable();
    });
    $('#YearModal').on('shown.bs.modal', function () {
        loadYearsToTable();
    });
    loadYearsToSelect();

});



document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('addPeriodModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('payrollForm');

    const payrollType = document.getElementById('payroll_type');
    const regularFields = document.getElementById('regularFields');
    const currentYear = document.getElementById('currentYear');

    const dateFrom = document.querySelector('[name="date_from"]');
    const dateTo = document.querySelector('[name="date_to"]');
    const yearField = document.getElementById('current_year');

    const title = document.getElementById('addPayrollModalLabel');
    const submitBtn = form.querySelector('button[type="submit"]');

    // =============================
    // FIELD TOGGLING (CORE LOGIC)
    // =============================
    function togglePayrollFields(type) {
    // Check for both special types that use the Year instead of Date Range
    if (type === 'OJT HONORARIUM' || type === 'MID-YEAR BONUS') {

        regularFields.style.display = 'none';
        currentYear.style.display = 'block';

        // Disable date requirements so hidden fields don't block submission
        dateFrom.required = false;
        dateTo.required = false;
        yearField.required = true;

    } else {

        regularFields.style.display = 'block';
        currentYear.style.display = 'none';

        // Enable date requirements for standard payrolls
        dateFrom.required = true;
        dateTo.required = true;
        yearField.required = false;
    }
}

    // =============================
    // DROPDOWN CHANGE EVENT
    // =============================
    payrollType.addEventListener('change', function () {
        togglePayrollFields(this.value);
    });

    // =============================
    // EDIT BUTTON CLICK
    // =============================
    document.querySelectorAll('.btn-edit-payroll').forEach(button => {
        button.addEventListener('click', function () {
            form.action = "<?= base_url('payroll/update') ?>";
            title.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Edit Payroll Entry';
            submitBtn.textContent = 'Update Entry';
            document.getElementById('payroll_period_id').value = this.dataset.id;
            document.getElementById('payroll_number').value = this.dataset.number;
            payrollType.value = this.dataset.type;
            document.querySelector('[name="unit"]').value = this.dataset.unit;
            dateFrom.value = this.dataset.date_from;
            dateTo.value = this.dataset.date_to;
            yearField.value = this.dataset.year;
            document.querySelector('[name="particulars"]').value = this.dataset.particulars;
            togglePayrollFields(this.dataset.type);
            modal.show();
        });
    });

    // =============================
    // RESET MODAL (ADD MODE)
    // =============================
    modalEl.addEventListener('hidden.bs.modal', function () {

        form.reset();
        form.action = "<?= base_url('payroll/add_entry') ?>";
        title.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Payroll Entry';
        submitBtn.textContent = 'Add Entry';
        document.getElementById('payroll_period_id').value = '';
        togglePayrollFields('');
    });

});

$(document).ready(function() {

    // Function to generate payroll number based on type
    function generatePayrollNumber(payrollType) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // 01-12

        let prefix = '';

        switch(payrollType.toUpperCase()) {
            case 'GENERAL PAYROLL':
                prefix = 'GP';
                break;
            case 'OVERLOAD':
                prefix = 'OLP';
                break;
            case 'PART-TIME':
                prefix = 'PT';
                break;
            case 'DAILY WAGE':
                prefix = 'DW';
                break;
            case 'CONTRACT OF SERVICE':
                prefix = 'COS';
                break;
            case 'MID-YEAR BONUS':
                prefix = 'MYB';
                break;
            case 'OJT HONORARIUM':
                prefix = 'OJTH';
                break;
            default:
                prefix = 'PY';
        }

        // Here you can fetch last sequence from backend if needed
        // For demo, we just start with 00001
        const sequence = '00001';

        return `${prefix}-${year}-${month}-${sequence}`;
    }

    // On payroll type change
    $('#payroll_type').on('change', function() {
        const type = $(this).val();
        if(type) {
            const payrollNumber = generatePayrollNumber(type);
            $('#payroll_number').val(payrollNumber);
        } else {
            $('#payroll_number').val('');
        }
    });

    // Optional: populate the current year dropdown automatically
    const currentYear = new Date().getFullYear();
    const $yearSelect = $('#current_year');
    for(let y = currentYear; y >= currentYear - 10; y--) {
        $yearSelect.append(`<option value="${y}">${y}</option>`);
    }
});

function generateParticulars() {
    const type = document.getElementById('payroll_type').value;
    const unit = document.querySelector('[name="unit"]').value || 'Specified Units';
    const dateFrom = document.querySelector('[name="date_from"]').value;
    const dateTo = document.querySelector('[name="date_to"]').value;
    const year = document.getElementById('current_year').value || 'Current Year';

    if (!type) {
        document.getElementById('particulars_display').value = "Please select a payroll type...";
        return;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '...';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    const from = formatDate(dateFrom);
    const to = formatDate(dateTo);
    let description = "";

    switch(type) {
        case 'MID-YEAR BONUS':
        case 'OJT HONORARIUM':
            description = `${type} for ${unit} for CY ${year}`;
            break;
        default:
            description = `${type} of ${unit} for the period ${from} - ${to}`;
    }

    document.getElementById('particulars_display').value = description;
    document.getElementById('particulars').value = description;
}

// Listen to changes
document.querySelectorAll('#payroll_type, [name="unit"], [name="date_from"], [name="date_to"], #current_year')
.forEach(el => {
    el.addEventListener('change', generateParticulars);
});
</script>

