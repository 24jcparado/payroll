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
                        <!-- Added ID for live update -->
                        <h3 class="fw-bold text-primary mb-0" id="liveNextPayout">--</h3>
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
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-primary me-2"></i>Active Payroll Periods
                    <!-- Added a pulsing indicator so users know it's a live view -->
                    <span class="badge bg-danger ms-2 rounded-pill shadow-sm" style="animation: pulse 2s infinite;">LIVE</span>
                </h6>
                <button class="btn btn-sm btn-dark rounded-pill px-3">View All</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 700px;">
                        <thead class="bg-light">
                            <tr class="x-small text-uppercase text-muted">
                                <th class="ps-4">Period Date</th>
                                <th>Payout Date</th>
                                <th>Total Net Pay</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <!-- Added ID here to target with jQuery -->
                        <tbody id="livePayrollTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Loading live data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- CSS for the Live Pulse Indicator -->
<style>
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>

<!-- AJAX Script to fetch live data -->
<script>
$(document).ready(function() {

    // ==========================================
    // 1. LIVE DASHBOARD TABLE POLLING
    // ==========================================
    function fetchLiveDashboard() {
        $.ajax({
            // Note: Adjust 'dashboard' below to match your actual CodeIgniter controller name
            url: '<?= base_url("payroll/fetch_live_dashboard_data") ?>', 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    
                    // Update the Next Payout KPI Card
                    $('#liveNextPayout').text(response.next_payout);

                    let rowsHtml = '';
                    
                    if(response.periods && response.periods.length > 0) {
                        $.each(response.periods, function(index, period) {
                            
                            // Map numeric status (1-6) to Text and Badge Colors
                            let statusNum = parseInt(period.status);
                            let statusText = 'Unknown';
                            let badgeClass = 'bg-light text-dark'; 

                            switch (statusNum) {
                                case 1:
                                    statusText = 'HR Draft';
                                    badgeClass = 'bg-secondary-subtle text-secondary';
                                    break;
                                case 2:
                                    statusText = 'Admin';
                                    badgeClass = 'bg-info-subtle text-info';
                                    break;
                                case 3:
                                    statusText = 'Budget';
                                    badgeClass = 'bg-warning-subtle text-warning';
                                    break;
                                case 4:
                                    statusText = 'Accounting';
                                    badgeClass = 'bg-primary-subtle text-primary';
                                    break;
                                case 5:
                                    statusText = 'VP Approval';
                                    badgeClass = 'bg-dark-subtle text-dark';
                                    break;
                                case 6:
                                    statusText = 'Cashier';
                                    badgeClass = 'bg-success-subtle text-success';
                                    break;
                            }

                            // USING YOUR EXACT SCHEMA COLUMNS NOW
                            let periodStr = period.date_period || 'Unspecified';
                            let particulars = period.particulars || 'Regular Payroll';
                            
                            // Format currency using 'net_amount'
                            let rawNetPay = parseFloat(period.net_amount || 0);
                            let netPay = rawNetPay.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });

                            // Build the HTML row using 'payroll_period_id' for the view link
                            rowsHtml += `
                                <tr>
                                    <td class="ps-4 fw-bold small">${periodStr}</td>
                                    <td class="small text-muted">${particulars}</td>
                                    <td class="fw-bold text-dark small">${netPay}</td>
                                    <td><span class="badge ${badgeClass} rounded-pill px-3" style="font-size:0.65rem;">${statusText}</span></td>
                                    <td class="text-end pe-4">
                                        <a href="<?= base_url('payroll/run/') ?>${period.payroll_period_id}" class="btn btn-sm btn-light border shadow-sm"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        // Display if table is empty
                        rowsHtml = `<tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-4 d-block mb-2"></i>No active payroll periods found.</td></tr>`;
                    }

                    // Inject the compiled rows into the table body
                    $('#livePayrollTableBody').html(rowsHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error("Dashboard Sync Error:", error);
                // Only show error message if the table is empty to prevent flashing on temporary network drops
                if ($('#livePayrollTableBody tr').length <= 1) {
                    $('#livePayrollTableBody').html('<tr><td colspan="5" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Data sync error. Retrying...</td></tr>');
                }
            }
        });
    }

    // Run immediately when the page loads
    fetchLiveDashboard();

    // Set auto-refresh interval (15000ms = 15 seconds)
    setInterval(fetchLiveDashboard, 15000);


    // ==========================================
    // 2. DYNAMIC WORKFLOW STEPPER CONTROLLER
    // ==========================================
    // Call this function and pass a number (1-6) to update the UI
    window.updateStepperUI = function(currentStatus) {
        
        // Remove active/completed classes from all steps first to reset
        $('.stepper-item').removeClass('active completed'); 

        // Loop through each step in the HTML
        $('.stepper-item').each(function() {
            // Extract the number from the ID (e.g., "step-3" becomes 3)
            let stepNum = parseInt($(this).attr('id').split('-')[1]);
            
            if (stepNum < currentStatus) {
                // If the step is lower than current status, mark as done
                $(this).addClass('completed'); 
            } else if (stepNum === currentStatus) {
                // If it matches exactly, mark as active
                $(this).addClass('active'); 
            }
        });
    };

    // If your stepper is on the same page and you want to initialize it on load with a specific value:
    // let activeWorkflowStatus = 3; 
    // updateStepperUI(activeWorkflowStatus);

});
</script>