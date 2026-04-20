<!-- Sidebar for Payroll Receiver -->
<nav class="sidebar" id="sidebar">
    <div class="d-flex flex-column text-white py-3 px-3 border-bottom">
        <div class="d-flex align-items-center">
            <img src="<?= base_url('assets/img/favicon.png') ?>" alt="Logo" style="height:45px;" class="me-3">
            <div style="width:1px; background-color:#fff; height:45px;" class="me-3"></div>
            <div class="d-flex flex-column">
                <h5 class="mb-0 fw-semibold">Payroll Receiver</h5>
            </div>
        </div>
        <hr>
        <small class="text-white-50 fst-italic">
            Account role: <?= $this->session->userdata('receiver_role') ?? 'Guest'; ?>
        </small>
    </div>

    <ul class="nav flex-column mt-3">

        <!-- CORE -->
        <li class="nav-item px-3 text-uppercase text-white-50 small mt-2">Core</li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('receiver/dashboard')?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('receiver/verified_payroll')?>"><i class="bi bi-clipboard-check"></i> Verify Payroll</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('receiver/received')?>"><i class="bi bi-check-circle"></i> Received Payrolls</a></li>

        <!-- DOWNLOADS -->
        <li class="nav-item px-3 text-uppercase text-white-50 small mt-4">Downloads</li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('receiver/payslips')?>"><i class="bi bi-file-earmark-text"></i> Individual Payslips</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('receiver/proof_list')?>"><i class="bi bi-clipboard-data"></i> Proof List (PDF)</a></li>

        <!-- SYSTEM -->
        <li class="nav-item px-3 text-uppercase text-white-50 small mt-4">System</li>
        <li class="nav-item"><a class="nav-link" href="<?=base_url('welcome/logout')?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
</nav>