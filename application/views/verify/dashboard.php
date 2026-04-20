<!-- CONTENT -->
<main id="mainContent" class="p-4" style="background-color: #f4f6f9; min-height:100vh;">
    <?php $this->load->view('template/admin_topbar') ?>

    <!-- DASHBOARD HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-secondary">Payroll Receiver Dashboard</h4>
        <small class="text-muted"><?= date('l, F d, Y') ?></small>
    </div>

    <!-- DASHBOARD CARDS -->
    <div class="row g-4">
        <!-- Total Payrolls Assigned -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase text-muted mb-1">Total Payrolls</h6>
                            <h3 class="fw-bold"><?= $total_payrolls ?? 0 ?></h3>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Payrolls -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase text-muted mb-1">Verified Payrolls</h6>
                            <h3 class="fw-bold"><?= $verified_payrolls ?? 0 ?></h3>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-circle fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase text-muted mb-1">Pending Verification</h6>
                            <h3 class="fw-bold"><?= $pending_payrolls ?? 0 ?></h3>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payrolls Received -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase text-muted mb-1">Payrolls Received</h6>
                            <h3 class="fw-bold"><?= $received_payrolls ?? 0 ?></h3>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-box-arrow-down fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYROLL VERIFICATION BY TOKEN -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 fw-bold">
                    Verify Payroll by Token ID
                </div>
                <div class="card-body">
                    <form id="verifyPayrollForm">
                        <div class="mb-3">
                            <label class="form-label">Enter Payroll Token ID</label>
                            <input type="text" 
                                name="token_id" 
                                class="form-control text-uppercase text-center" 
                                placeholder="XXX-XXX-XXX" 
                                maxlength="11" 
                                required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i> Verify & Mark as Received
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Recent Payrolls</h6>
                    <a href="<?= base_url('receiver/received') ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-xsm table-hover mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Payroll Number</th>
                                    <th rowspan="2">Payroll Type</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">HR</th>
                                    <th colspan="5" class="text-center">Approving Authorities</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Date Generated</th>
                                    <!-- <th rowspan="2">Action</th> -->
                                </tr>

                                <tr>
                                    <th>Admin</th>
                                    <th>Budget</th>
                                    <th>Accounting</th>
                                    <th>VP's</th>
                                    <th>Cashier</th>
                                </tr>

                            </thead>

                            <tbody>
                                <?php if(!empty($recent_payrolls)): ?>
                                    <?php foreach($recent_payrolls as $payroll): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($payroll->payroll_number) ?></td>
                                            <td><?= htmlspecialchars($payroll->payroll_type) ?></td>

                                            <td><?= htmlspecialchars($payroll->unit ?? '-') ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_forwarded_hr)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_forwarded_hr)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>

                                            <!-- GROUPED COLUMNS -->
                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_received_admin)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_received_admin)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_received_budget)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_received_budget)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_received_accounting)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_received_accounting)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_received_vps)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_received_vps)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if (!empty($payroll->date_time_received_cashier)): ?>
                                                    <i class="bi bi-check-circle-fill text-success"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($payroll->date_time_received_cashier)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>
    

                                            <!-- STATUS -->
                                            <td>
                                                <?php if($payroll->status == 'APPROVED'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php elseif($payroll->status == 'PENDING'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($payroll->status) ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- DATE -->
                                            <td><?= date('M d, Y', strtotime($payroll->created_at)) ?></td>

                                            <!-- ACTION -->
                                            <!-- <td>
                                                <a href="<?= base_url('receiver/view/'.$payroll->payroll_period_id) ?>" 
                                                class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            </td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No payrolls found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAYROLL VERIFICATION MODAL -->
        <div class="modal fade" id="payrollVerifyModal" tabindex="-1" aria-labelledby="payrollVerifyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- Verification Card -->
                    <div class="verification-card p-4">
                    <div class="header-section text-center">
                        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo mb-2" style="width: 80px;
    margin-bottom: 10px;">
                        <div class="university-name">EASTERN VISAYAS STATE UNIVERSITY</div>
                        <div class="system-name">Human Resource and Financial Management System</div>
                        <div class="verification-title">Payroll Verification Result</div>
                    </div>

                    <!-- Info Table -->
                    <table class="table table-borderless info-table">
                        <tr>
                        <td class="info-label">Payroll Reference No.</td>
                        <td class="info-value" id="modalPayrollNumber"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Classification</td>
                        <td class="info-value fw-bold" id="modalPayrollType"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Particulars</td>
                        <td class="info-value" id="modalParticulars"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Unit</td>
                        <td class="info-value" id="modalUnit"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Approval Status</td>
                        <td id="modalStatus"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Token ID</td>
                        <td class="info-value" id="modalTokenId"></td>
                        </tr>
                        <tr>
                        <td class="info-label">Generated</td>
                        <td class="info-value" id="modalCreatedAt"></td>
                        </tr>
                    </table>

                    <hr>

                    <!-- Received -->
                    <div id="modalReceivedSection" class="mb-3 text-center"></div>

                    <!-- Actions -->
                    <div class="action-box">
                        <div class="mb-3">
                        <div class="section-title">PRIMARY ACTION</div>
                        <a href="#" id="modalViewFullLink" class="btn btn-primary w-100" target="_blank">
                            View Full Payroll
                        </a>
                        </div>
                        <div class="mb-3">
                        <div class="section-title">EXPORT FILES</div>
                        <div class="row g-2">
                            <div class="col-md-6">
                            <a href="#" id="modalDownloadPDF" class="btn btn-outline-danger w-100">PDF</a>
                            </div>
                            <div class="col-md-6">
                            <a href="#" id="modalDownloadExcel" class="btn btn-outline-success w-100">Excel</a>
                            </div>
                            <div class="col-md-6">
                            <a href="#" id="modalDownloadProofList" class="btn btn-outline-primary w-100">Proof List</a>
                            </div>
                            <div class="col-md-6">
                            <button onclick="generatePayslipsModal()" class="btn btn-outline-secondary w-100">Payslips</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <!-- <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <h6 class="fw-bold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('receiver/payslips') ?>" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text me-2"></i> Individual Payslips
                    </a>
                    <a href="<?= base_url('receiver/proof_list') ?>" class="btn btn-info text-white">
                        <i class="bi bi-clipboard-data me-2"></i> Proof List
                    </a>
                    <a href="<?= base_url('receiver/received') ?>" class="btn btn-secondary">
                        <i class="bi bi-folder2-open me-2"></i> View Received Payrolls
                    </a>
                </div>
            </div>
        </div> -->
    </div>
</main>

<script>
var userRole = "<?= $this->session->userdata('receiver_role') ?>";
var field = `date_time_received_${userRole}`;
$('#verifyPayrollForm').on('submit', function(e){
    e.preventDefault();
    const token_id = $(this).find('input[name="token_id"]').val().toUpperCase();

    $.ajax({
        url: '<?= base_url("receiver/verify_token_ajax") ?>',
        method: 'POST',
        data: { token_id: token_id },
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'error'){
                alert(resp.message);
            } else {
                // Fill modal with data
                $('#modalPayrollNumber').text(resp.payroll.payroll_number);
                $('#modalPayrollType').text(resp.payroll.payroll_type);
                $('#modalParticulars').text(resp.payroll.particulars);
                $('#modalUnit').text(resp.payroll.unit);
                $('#modalTokenId').text(resp.payroll.token_id);
                $('#modalCreatedAt').text(resp.payroll.created_at);
                
                if(resp.payroll.status === 'APPROVED'){
                    $('#modalStatus').html('<span class="status-approved">APPROVED</span>');
                } else {
                    $('#modalStatus').html('<span class="badge bg-secondary">'+resp.payroll.status+'</span>');
                }

                if(resp.payroll[field]){
                    $('#modalReceivedSection').html(
                        '<div class="received-box mb-3"><i class="bi bi-check-circle-fill text-success"></i> Received on '+resp.payroll.date_time_received_accounting+'</div>'
                    );
                } else {
                    $('#modalReceivedSection').html(
                        '<form action="<?= base_url("receiver/mark_received_") ?>'+userRole+'/'+resp.payroll.payroll_period_id+'" method="post">'+
                        '<input type="hidden" name="token_id" value="'+resp.payroll.token_id+'">'+
                        '<button class="btn btn-success w-100 mb-3"><i class="bi bi-check-circle me-2"></i> Confirm Receipt</button></form>'
                    );
                }

                // Set action links
                $('#modalViewFullLink').attr('href','<?= base_url("receiver/view_full/") ?>'+resp.payroll.payroll_period_id);
                $('#modalDownloadPDF').attr('href','<?= base_url("receiver/download_pdf/") ?>'+resp.payroll.payroll_period_id);
                $('#modalDownloadExcel').attr('href','<?= base_url("receiver/download_excel_general_payroll/") ?>'+resp.payroll.payroll_period_id);
                $('#modalDownloadProofList').attr('href','<?= base_url("receiver/download_proof_list_gp/") ?>'+resp.payroll.payroll_period_id);

                $('#payrollVerifyModal').modal('show');
            }
        }
    });
});

function generatePayslipsModal(){
    const id = $('#modalViewFullLink').attr('href').split('/').pop();
    window.open("<?= base_url('receiver/payslips/') ?>" + id, "_blank");
}
</script>