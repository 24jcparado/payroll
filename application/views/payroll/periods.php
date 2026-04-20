
<style>
    #savedPayrollTable tbody tr {
        transition: 0.2s ease-in-out;
    }

    #savedPayrollTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 12px;
        border-radius: 50px;
    }
</style>
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPeriodModal">
        <i class="bi bi-plus-lg me-1"></i> Add Payroll Period
    </button>
    <div class="row g-3">
        <div class="col-12 col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-calendar-event me-2"></i>
                    <h6 class="mb-0 fw-bold">Saved Payroll Periods</h6>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="savedPayrollTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width:140px;">Payroll No.</th>
                                    <th>Description</th>
                                    <th style="width:220px;">Approval Status</th>
                                    <th style="width:150px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php if(!empty($payroll)): ?>
                                <?php foreach($payroll as $row): ?>

                                    <?php
                                        $steps = [
                                            1 => 'HRMDO',
                                            2 => 'Accounting',
                                            3 => 'Budget',
                                            4 => 'Bank'
                                        ];
                                    ?>

                                    <tr>

                                        <!-- Payroll Number -->
                                        <td class="text-center fw-bold text-primary">
                                            <?= htmlspecialchars($row->payroll_number) ?>
                                        </td>

                                        <!-- Description Column -->
                                        <td>
                                            <!-- Payroll Type Emphasis -->
                                            <div class="mb-2">
                                                <?php
                                                    $type = strtolower($row->payroll_type);
                                                    $typeClass = 'bg-primary';
                                                    
                                                    if (strpos($type, 'regular') !== false) {
                                                        $typeClass = 'bg-success';
                                                    } elseif (strpos($type, 'cos') !== false) {
                                                        $typeClass = 'bg-info text-dark';
                                                    } elseif (strpos($type, 'part') !== false) {
                                                        $typeClass = 'bg-warning text-dark';
                                                    } elseif (strpos($type, 'special') !== false) {
                                                        $typeClass = 'bg-danger';
                                                    }
                                                ?>

                                                <span class="badge <?= $typeClass ?> px-3 py-2 fs-6">
                                                    <?= htmlspecialchars($row->payroll_type) ?>
                                                </span>
                                            </div>

                                            <!-- Other Metadata -->
                                            <div class="small text-muted">
                                                <div><strong>Unit:</strong> <?= htmlspecialchars($row->unit) ?></div>
                                                <div><strong>Date Period:</strong> <?= htmlspecialchars($row->date_period) ?></div>
                                            </div>
                                        </td>

                                        <!-- Approval Status -->
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">

                                                <?php foreach($steps as $key => $label): ?>

                                                    <?php
                                                        $badgeClass = 'bg-light text-muted';

                                                        if ($row->status > $key) {
                                                            $badgeClass = 'bg-success';
                                                        } elseif ($row->status == $key) {
                                                            $badgeClass = 'bg-warning text-dark';
                                                        }
                                                    ?>

                                                    <span class="badge <?= $badgeClass ?> px-3 py-2">
                                                        <?= $label ?>
                                                    </span>

                                                <?php endforeach; ?>
                                            </div>

                                            <div class="small mt-2">
                                                <?php if($row->status < 4): ?>
                                                    <span class="text-muted">
                                                        Currently at:
                                                        <strong>
                                                            <?= $steps[$row->status] ?? 'Pending' ?>
                                                        </strong>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-success fw-semibold">
                                                        Fully Approved
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Action -->
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">

                                                <?php if($row->status < 4): ?>

                                                    <!-- RUN PAYROLL (Primary Action) -->
                                                    <a href="<?= base_url('payroll/run/'.$row->payroll_period_id) ?>"
                                                    class="btn btn-success"
                                                    title="Run Payroll">
                                                        <i class="bi bi-calculator me-1"></i> Run
                                                    </a>

                                                    <!-- EDIT -->
                                                    <a href="javascript:void(0);" class="btn btn-outline-primary btn-edit-payroll" title="Edit Payroll"
                                                    data-id="<?= $row->payroll_period_id ?>"
                                                    data-number="<?= $row->payroll_number ?>"
                                                    data-type="<?= $row->payroll_type ?>"
                                                    data-unit="<?= $row->unit ?>"
                                                    data-date_period="<?= $row->date_period ?>"
                                                    data-particulars="<?= htmlspecialchars($row->particulars) ?>"
                                                    >
                                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                                    </a>

                                                    <!-- DELETE (Use POST for security) -->
                                                    <form action="<?= base_url('payroll/delete/'.$row->payroll_period_id) ?>" 
                                                        method="post" 
                                                        onsubmit="return confirm('Are you sure you want to delete this payroll record?');"
                                                        style="display:inline-block;">

                                                        <button type="submit" class="btn btn-outline-danger" title="Delete Payroll">
                                                            <i class="bi bi-trash me-1"></i> Delete
                                                        </button>
                                                    </form>

                                                <?php else: ?>

                                                    <!-- COMPLETED STATE -->
                                                    <button class="btn btn-outline-secondary" disabled>
                                                        <i class="bi bi-check-circle me-1"></i> Completed
                                                    </button>

                                                <?php endif; ?>

                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
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

        <!-- Payroll Status Legend (Right / Bottom on mobile) -->
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>
                        Payroll Status Legend
                    </h6>
                </div>
                <div class="card-body small text-muted">
                    <!-- ================= LEGEND ================= -->

                    <!-- ACTIVE -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="badge bg-success d-inline-flex align-items-center mb-1">
                                <i class="bi bi-play-circle-fill me-1"></i> Active
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Payroll period is open and ready for processing.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- PROCESSING -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="badge bg-warning text-dark d-inline-flex align-items-center mb-1">
                                <i class="bi bi-hourglass-split me-1"></i> Processing
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Payroll computation is currently in progress.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- PROCESSED -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="badge bg-primary d-inline-flex align-items-center mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Processed
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Payroll has been finalized and is ready for release.
                            </div>
                        </div>
                    </div>

                    <!-- ================= CAROUSEL SECTION ================= -->

                    <hr class="my-4">

                    <h6 class="fw-bold text-center mb-3">
                        <i class="bi bi-check2-all me-1 text-success"></i>
                        Recently Processed
                    </h6>

                    <?php 
                    $processedPayrolls = array_filter($payroll, function($row){
                        return $row->status == 4; // Fully processed
                    });
                    ?>

                    <?php if(!empty($processedPayrolls)): ?>

                    <div id="workflowCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
                        <div class="carousel-inner">

                            <?php
                            $first = true;
                            foreach ($payrolls as $row):

                                // Only show workflow (exclude fully completed)
                                if (!in_array($row->status, [1,2,3,4,5,6,7])) continue;
                            ?>

                            <div class="carousel-item <?= $first ? 'active' : '' ?>">
                                <div class="card shadow-sm">
                                    <div class="card-body">

                                        <h6 class="fw-bold mb-1">
                                            Payroll #<?= $row->payroll_no ?>
                                        </h6>

                                        <p class="small text-muted mb-3">
                                            Period: <?= $row->period ?>
                                        </p>

                                        <!-- WORKFLOW VISUAL -->
                                        <div class="workflow-steps d-flex justify-content-between text-center"
                                            data-status="<?= $row->status ?>">

                                            <div class="step" id="step-1">
                                                <div class="circle">1</div>
                                                <small>HRMDO</small>
                                            </div>

                                            <div class="step" id="step-2">
                                                <div class="circle">2</div>
                                                <small>ACCOUNTING</small>
                                            </div>

                                            <div class="step" id="step-3">
                                                <div class="circle">3</div>
                                                <small>BUDGET</small>
                                            </div>

                                            <div class="step" id="step-4">
                                                <div class="circle">4</div>
                                                <small>BANK</small>
                                            </div>

                                            <div class="step" id="step-5">
                                                <div class="circle">5</div>
                                                <small>COMPLETED</small>
                                            </div>

                                        </div>
                                        <!-- END WORKFLOW -->

                                    </div>
                                </div>
                            </div>

                            <?php
                                $first = false;
                            endforeach;
                            ?>

                        </div>

                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#workflowCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button"
                                data-bs-target="#workflowCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <?php else: ?>
                        <div class="text-center small text-muted">
                            No processed payroll available.
                        </div>
                    <?php endif; ?>
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
                                    <input type="date" class="form-control" name="date_from">
                                    <small class="text-muted">From</small>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="date_to">
                                    <small class="text-muted">To</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="currentYear">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Current Year</label>

                                <div class="input-group">
                                    <select id="current_year" name="current_year" 
                                        class="form-select payroll-input" required>
                                        <option value="">-- Select Current Year --</option>
                                    </select>

                                    <button type="button" 
                                        class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#YearModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
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

        if (type === 'OJT HONORARIUM') {

            regularFields.style.display = 'none';
            currentYear.style.display = 'block';

            dateFrom.required = false;
            dateTo.required = false;
            yearField.required = true;

        } else {

            regularFields.style.display = 'block';
            currentYear.style.display = 'none';

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
    const unit = document.querySelector('[name="unit"]').value;
    const dateFrom = document.querySelector('[name="date_from"]').value;
    const dateTo = document.querySelector('[name="date_to"]').value;
    const year = document.getElementById('current_year').value;

    let description = '';

    // Format date nicely
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    const from = formatDate(dateFrom);
    const to = formatDate(dateTo);

    // --- RULES ---
    if (type === 'GENERAL PAYROLL') {
        description = `General Payroll of ${unit || 'All Units'} for the period ${from} - ${to}`;
    } 
    else if (type === 'OVERLOAD') {
        description = `Overload Compensation of ${unit || 'Personnel'} for the period ${from} - ${to}`;
    } 
    else if (type === 'PART-TIME') {
        description = `Part-Time Salary of ${unit || 'Personnel'} for the period ${from} - ${to}`;
    } 
    else if (type === 'DAILY WAGE') {
        description = `Daily Wage Payroll of ${unit || 'Workers'} for the period ${from} - ${to}`;
    } 
    else if (type === 'CONTRACT OF SERVICE') {
        description = `Contract of Service Payroll of ${unit || 'Personnel'} for the period ${from} - ${to}`;
    } 
    else if (type === 'MID-YEAR BONUS') {
        description = `Mid-Year Bonus for ${unit || 'Personnel'} for CY ${year}`;
    } 
    else if (type === 'OJT HONORARIUM') {
        description = `OJT Honorarium for ${unit || 'Trainees'} for CY ${year}`;
    }

    // Set values
    document.getElementById('particulars_display').value = description;
    document.getElementById('particulars').value = description;
}

// Listen to changes
document.querySelectorAll('#payroll_type, [name="unit"], [name="date_from"], [name="date_to"], #current_year')
.forEach(el => {
    el.addEventListener('change', generateParticulars);
});
</script>

