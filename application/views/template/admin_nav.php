<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="d-flex flex-column text-white py-3 px-3 border-bottom">
        <div class="d-flex align-items-center">
            <img src="<?= base_url('assets/img/favicon.png') ?>" alt="Logo" style="height:45px;" class="me-3">
            <div style="width:1px; background-color:#fff; height:45px;" class="me-3"></div>
            <div class="d-flex flex-column">
                <h5 class="mb-0 fw-semibold">Payroll Admin</h5>
                <small class="text-white-50 fst-italic">
                    Account Name: <?= $this->session->userdata('name') ?? 'Guest'; ?>
                </small>
            </div>
        </div>
        <hr>
        <small class="text-white-50 fst-italic">
            Email: <?= $this->session->userdata('username') ?? 'Guest'; ?>
        </small>
    </div>
    <ul class="nav flex-column mt-3">
    <!-- CORE -->
    <li class="nav-item px-3 text-uppercase text-white-50 small mt-2">Core</li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll')?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/employees')?>"><i class="bi bi-people-fill"></i> Employees</a></li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/period')?>"><i class="bi bi-calendar-event"></i> Payroll Periods</a></li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/fund')?>"><i class="bi bi-wallet2"></i> Fund Management</a></li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/salary_grade')?>"><i class="bi bi-wallet"></i> Salary Management</a></li>

    <!-- PROCESSING -->
    <li class="nav-item px-3 text-uppercase text-white-50 small mt-4">Processing</li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/deductions')?>"><i class="bi bi-dash-circle"></i> Deductions</a></li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('payroll/account_no')?>"><i class="bi bi-bank"></i> Account No.</a></li>

    <!-- REPORTS -->
    <li class="nav-item px-3 text-uppercase text-white-50 small mt-4">Reports</li>
    <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-file-earmark-text"></i> Reports</a></li>

    <!-- ADMIN -->
    <li class="nav-item px-3 text-uppercase text-white-50 small mt-4">System</li>
    <li class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center"
        data-bs-toggle="collapse"
        href="#settingsMenu"
        role="button"
        aria-expanded="false"
        aria-controls="settingsMenu">

            <span><i class="bi bi-gear-fill"></i> Settings</span>
            <i class="bi bi-chevron-down small"></i>
        </a>

        <div class="collapse" id="settingsMenu">
            <ul class="nav flex-column ms-3 mt-1">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('payroll/rate_per_hour') ?>">
                        Rate Per Hour
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('payroll/tax_rate') ?>">
                        Tax Rate
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('payroll/signatories') ?>">
                        Signatories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('payroll/users') ?>">
                        Users
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item"><a class="nav-link" href="<?=base_url('welcome/logout')?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>

</ul>
</nav>