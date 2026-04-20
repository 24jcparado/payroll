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

    <!-- Recent Payrolls Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Recent Payrolls</h6>
                    <a href="<?= base_url('receiver/received') ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">

                                <!-- TOP HEADER (GROUPING) -->
                                <tr>
                                    <th rowspan="2">Payroll Number</th>
                                    <th rowspan="2">Payroll Type</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">HR</th>
                                    <th colspan="5" class="text-center">Approving Authorities</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Date Generated</th>
                                    <th rowspan="2">Action</th>
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
                                            <td>
                                                <button 
                                                    class="btn btn-sm btn-outline-primary btn-view-payroll"
                                                    data-id="<?= $payroll->payroll_period_id ?>">
                                                    View
                                                </button>
                                            </td>
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
    </div>
</main>

<script>
var userRole = "<?= $this->session->userdata('receiver_role') ?>";
$('#verifyPayrollForm').on('submit', function(e){
    e.preventDefault();
    const token_id = $(this).find('input[name="token_id"]').val().toUpperCase();

    $.ajax({
        url: '<?= base_url("verify/verify_token_ajax") ?>',
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

                if(resp.payroll.date_time_received_accounting){
                    $('#modalReceivedSection').html(
                        '<div class="received-box mb-3"><i class="bi bi-check-circle-fill text-success"></i> Received on '+resp.payroll.date_time_received_accounting+'</div>'
                    );
                } else {
                    $('#modalReceivedSection').html(
                        '<form action="<?= base_url("verify/mark_received_") ?>'+userRole+'/'+resp.payroll.payroll_period_id+'" method="post">'+
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

$('.btn-view-payroll').on('click', function () {

    const id = $(this).data('id');

    $.ajax({
        url: '<?= base_url("receiver/get_payroll_details_ajax/") ?>' + id,
        method: 'GET',
        dataType: 'json',
        success: function(resp) {

            if(resp.status === 'error'){
                alert(resp.message);
                return;
            }

            const p = resp.payroll;

            // Fill modal fields
            $('#modalPayrollNumber').text(p.payroll_number);
            $('#modalPayrollType').text(p.payroll_type);
            $('#modalParticulars').text(p.particulars ?? '-');
            $('#modalUnit').text(p.unit ?? '-');
            $('#modalTokenId').text(p.token_id);
            $('#modalCreatedAt').text(p.created_at);

            // Status
            if(p.status === 'APPROVED'){
                $('#modalStatus').html('<span class="status-approved">APPROVED</span>');
            } else {
                $('#modalStatus').html('<span class="badge bg-secondary">'+p.status+'</span>');
            }

            // Received section
            if(p.date_time_received_accounting){
                $('#modalReceivedSection').html(
                    '<div class="received-box mb-3">'+
                    '<i class="bi bi-check-circle-fill text-success"></i> Received on '+
                    p.date_time_received_accounting+
                    '</div>'
                );
            } else {
                $('#modalReceivedSection').html(
                    '<div class="text-muted">Not yet received</div>'
                );
            }

            // Links
            $('#modalViewFullLink').attr('href','<?= base_url("receiver/view_full/") ?>'+p.payroll_period_id);
            $('#modalDownloadPDF').attr('href','<?= base_url("receiver/download_pdf/") ?>'+p.payroll_period_id);
            $('#modalDownloadExcel').attr('href','<?= base_url("receiver/download_excel_general_payroll/") ?>'+p.payroll_period_id);
            $('#modalDownloadProofList').attr('href','<?= base_url("receiver/download_proof_list_gp/") ?>'+p.payroll_period_id);

            $('#payrollVerifyModal').modal('show');
        }
    });
});
</script>