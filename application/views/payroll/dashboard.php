<!-- CONTENT -->
    <main id="mainContent">
        <?php $this->load->view('template/admin_topbar')?>
        <!-- Birthday Carousel -->
        <div id="birthdayCarousel" class="carousel slide mt-3 mb-3" data-bs-ride="carousel">
            <div class="carousel-inner">

                <?php 
                $current_month = date('m');
                $birthdays = $this->Get_model->getBirthdaysByMonth($current_month);

                if(!empty($birthdays)):
                    $active = 'active';
                    foreach($birthdays as $b):

                        $full_name = strtoupper(
                            trim(
                                $b['name'] . ' ' .
                                (!empty($b['middle_name']) ? $b['middle_name'] . ' ' : '') .
                                $b['last_name'] . ' ' .
                                (!empty($b['ext']) ? $b['ext'] : '')
                            )
                        );
                ?>

                <div class="carousel-item <?= $active ?>">
                    <div class="birthday-banner text-center p-4 rounded-4 shadow-sm">

                        <div class="mb-2">
                            <span class="badge bg-light text-dark fw-semibold px-3 py-2">
                                🎉 Happy Birthday
                            </span>
                        </div>

                        <h5 class="fw-bold text-white mb-1">
                            <?= htmlspecialchars($full_name) ?>
                        </h5>

                        <div class="text-white-50 small mb-2">
                            <?= htmlspecialchars($b['unit']) ?>
                        </div>

                        <div class="fs-6 text-white fw-semibold">
                            <?= date('F j', strtotime($b['date_of_birth'])) ?>
                        </div>

                    </div>
                </div>

                <?php 
                    $active = '';
                    endforeach;
                else: ?>
                    <div class="carousel-item active">
                        <div class="birthday-banner text-center p-4 rounded-4 shadow-sm">
                            <h6 class="text-white-50 m-0">No birthdays this month</h6>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#birthdayCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#birthdayCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <div class="row g-4">
            <!-- Payroll Summary Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cash-stack me-2"></i>Payroll Summary</h5>
                        <p class="card-text">Total payroll processed this month.</p>
                        <a href="<?= base_url('payroll/payslips') ?>" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>

            <!-- Employees Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-people me-2"></i>Employees</h5>
                        <p class="card-text">Total number of employees in the system.</p>
                        <a href="<?= base_url('payroll/employees') ?>" class="btn btn-sm btn-primary">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Payroll Periods Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-calendar-event me-2"></i>Payroll Periods</h5>
                        <p class="card-text">Manage open and processed payroll periods.</p>
                        <a href="<?= base_url('payroll/periods') ?>" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Example Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text me-2"></i>Recent Payrolls</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>Period</th>
                                        <th>Gross Pay</th>
                                        <th>Deductions</th>
                                        <th>Net Pay</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example row -->
                                    <tr>
                                        <td>1</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>Jan 1 - Jan 15, 2026</td>
                                        <td>₱50,000.00</td>
                                        <td>₱5,000.00</td>
                                        <td>₱45,000.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Maria Santos</td>
                                        <td>Jan 1 - Jan 15, 2026</td>
                                        <td>₱40,000.00</td>
                                        <td>₱4,000.00</td>
                                        <td>₱36,000.00</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>