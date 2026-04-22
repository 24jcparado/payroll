<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        
        <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-lg-none me-2" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5 class="m-0 fw-bold text-dark">Administrative Dashboard</h5>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block"></div>
        </div>

        <div id="birthdayCarousel" class="carousel slide mb-4 shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $current_month = date('m');
                $birthdays = $this->Get_model->getBirthdaysByMonth($current_month);
                if(!empty($birthdays)):
                    $active = 'active';
                    foreach($birthdays as $b):
                        $full_name = strtoupper(trim($b['name'] . ' ' . (!empty($b['middle_name']) ? $b['middle_name'] . ' ' : '') . $b['last_name']));
                ?>
                <div class="carousel-item <?= $active ?>" style="background: linear-gradient(135deg, #7b1113 0%, #4a0a0b 100%); min-height: 130px;">
                    <div class="d-flex align-items-center justify-content-between p-4">
                        <div class="text-start">
                            <span class="badge bg-white text-danger mb-2 small">🎉 MONTHLY CELEBRANT</span>
                            <h4 class="text-white fw-bold mb-0"><?= htmlspecialchars($full_name) ?></h4>
                            <p class="text-white-50 mb-0 small"><?= htmlspecialchars($b['unit']) ?> • <span class="text-white"><?= date('M d', strtotime($b['date_of_birth'])) ?></span></p>
                        </div>
                        <i class="bi bi-gift text-white opacity-25 display-4 d-none d-md-block"></i>
                    </div>
                </div>
                <?php $active = ''; endforeach; 
                else: ?>
                <div class="carousel-item active bg-light text-center p-4">
                    <p class="text-muted m-0">No organizational birthdays for <?= date('F') ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100 kpi-card">
                    <div class="card-body p-3">
                        <small class="text-uppercase opacity-75 fw-bold x-small">Total Staff</small>
                        <h3 class="fw-bold mb-0">1,240</h3>
                        <i class="bi bi-people position-absolute end-0 bottom-0 me-2 mb-1 opacity-25 fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body p-3">
                        <small class="text-uppercase text-muted fw-bold x-small">Gross Pay</small>
                        <h3 class="fw-bold text-dark mb-0">₱4.2M</h3>
                        <div class="text-success x-small mt-1"><i class="bi bi-arrow-up"></i> 2.4%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body p-3">
                        <small class="text-uppercase text-muted fw-bold x-small">Deductions</small>
                        <h3 class="fw-bold text-danger mb-0">₱520k</h3>
                        <div class="text-muted x-small">MDR: 12.4%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body p-3">
                        <small class="text-uppercase text-muted fw-bold x-small">Next Payout</small>
                        <h3 class="fw-bold text-primary mb-0">Apr 30</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="icon-shape bg-primary-subtle text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Payroll Summary</h6>
                            <a href="<?= base_url('payroll/payslips') ?>" class="x-small text-decoration-none">Manage Payslips</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="icon-shape bg-success-subtle text-success rounded-circle p-2 me-3">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Employees</h6>
                            <a href="<?= base_url('payroll/employees') ?>" class="x-small text-decoration-none">Update Profiles</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="icon-shape bg-warning-subtle text-warning rounded-circle p-2 me-3">
                            <i class="bi bi-calendar-event fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Pay Periods</h6>
                            <a href="<?= base_url('payroll/periods') ?>" class="x-small text-decoration-none">Set Schedule</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Recent Logs</h6>
                <button class="btn btn-sm btn-dark rounded-pill px-3">View All</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 700px;">
                        <thead class="bg-light">
                            <tr class="x-small text-uppercase text-muted">
                                <th class="ps-4">Employee</th>
                                <th>Period</th>
                                <th>Net Pay</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info text-white rounded-circle me-2 text-center" style="width:30px; height:30px; line-height:30px; font-size:10px;">JD</div>
                                        <div class="small fw-bold">Juan Dela Cruz</div>
                                    </div>
                                </td>
                                <td class="small">April 1-15</td>
                                <td class="fw-bold text-dark small">₱45,000.00</td>
                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3" style="font-size:0.65rem;">Approved</span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light border"><i class="bi bi-printer"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>