<style>
    /* Spreadsheet-like Table Styling */
    .table-salary {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-salary thead th {
        background-color: #f8f9fa;
        color: #6b0f1a;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6 !important;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .table-salary tbody td {
        padding: 0 !important; /* Remove padding to let input fill cell */
        border: 1px solid #edf2f7;
        transition: all 0.2s;
    }
    
    /* Sticky Salary Grade Column */
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 5;
        border-right: 2px solid #dee2e6 !important;
        padding: 10px 15px !important;
        min-width: 100px;
    }
    tr:hover .sticky-col { background-color: #fff8f8; }
    tr:hover td { background-color: #fff8f8; }

    /* Clean Inputs */
    .salary-input {
        border: none !important;
        border-radius: 0 !important;
        padding: 12px 10px;
        background: transparent;
        font-family: 'Courier New', Courier, monospace; /* Monospaced for numbers */
        font-weight: 500;
        width: 100%;
        height: 100%;
    }
    .salary-input:focus {
        background-color: #fff;
        box-shadow: inset 0 0 0 2px #6b0f1a !important;
        z-index: 5;
        position: relative;
    }
    .salary-input::placeholder { color: #cbd5e0; font-size: 0.8rem; }

    /* Action Bar */
    .action-bar {
        position: fixed;
        bottom: 20px;
        right: 40px;
        z-index: 1000;
        display: none; /* Shown via JS when input changes */
    }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark"><i class="bi bi-table me-2 text-maroon"></i>Salary Schedule (SSL)</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <form method="post" action="<?= base_url('payroll/edit_sg') ?>" id="salaryForm">
            <div class="action-bar animate__animated animate__fadeInUp" id="saveBar">
                <div class="card shadow-lg border-0 bg-dark text-white rounded-pill p-1">
                    <div class="d-flex align-items-center px-3">
                        <small class="me-3 opacity-75 d-none d-md-block">You have unsaved changes</small>
                        <button type="button" onclick="window.location.reload()" class="btn btn-sm btn-outline-light rounded-pill me-2">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-yellow rounded-pill px-4">Save All Changes</button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-muted small text-uppercase">Monthly Basic Salary Matrix</h6>
                        <span class="badge bg-maroon-subtle text-maroon rounded-pill">8 Steps / Grade</span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success m-3 rounded-3 shadow-sm border-0 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i> <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive" style="max-height: 70vh;">
                        <table class="table table-salary table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="sticky-col text-center">Grade</th>
                                    <?php for($s=1; $s<=8; $s++): ?>
                                        <th class="text-center">Step <?= $s ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grouped = [];
                                foreach ($salary_grades as $row) {
                                    $grouped[$row->salary_grade][$row->step] = $row->amount;
                                }
                                ksort($grouped); // Ensure SG is in order
                                ?>
                                
                                <?php foreach ($grouped as $sg => $steps): ?>
                                <tr>
                                    <td class="sticky-col text-center fw-800 text-maroon">
                                        SG <?= $sg ?>
                                    </td>
                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <td>
                                        <input type="number"
                                               step="0.01"
                                               class="form-control salary-input text-end"
                                               name="salary[<?= $sg ?>][<?= $i ?>]"
                                               value="<?= isset($steps[$i]) ? $steps[$i] : '' ?>"
                                               placeholder="0.00">
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
$(document).ready(function() {
    const saveBar = $('#saveBar');
    const salaryInputs = $('.salary-input');

    // Show save bar when any value changes
    salaryInputs.on('input', function() {
        saveBar.fadeIn();
    });

    // Handle Enter Key Navigation (Downwards like Excel)
    salaryInputs.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const index = salaryInputs.index(this);
            // Move focus to the input in the same column but next row
            // (There are 8 columns, so current index + 8)
            const nextInput = salaryInputs.eq(index + 8);
            
            if (nextInput.length) {
                nextInput.focus().select();
            } else {
                // If last row, submit form
                $('#salaryForm').submit();
            }
        }
    });

    // Select text on focus for easier editing
    salaryInputs.on('focus', function() {
        $(this).select();
    });
});
</script>