<main id="mainContent" class="p-4" style="background-color: #f8f9fa; min-height:100vh;">
    <style>
        /* Custom UI Enhancements */
        .hover-elevate {
            transition: all 0.2s ease-in-out;
        }
        .hover-elevate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .token-input {
            font-size: 1.5rem;
            letter-spacing: 3px;
            font-weight: bold;
            background-color: #f8f9fc;
            border: 2px dashed #ced4da;
            transition: all 0.3s;
        }
        .token-input:focus {
            background-color: #fff;
            border-color: #0d6efd;
            border-style: solid;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .table-custom th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        .modal-custom-header {
            background: linear-gradient(135deg, #800000 0%, #a31a1a 100%);
            color: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .info-label {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .info-value {
            font-weight: 600;
            color: #212529;
            text-align: right;
        }
        /* Custom scrollbar for table if it gets long */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bolder text-dark mb-1"><i class="bi bi-shield-check text-success me-2"></i>Payroll Receiving Center</h4>
            <p class="text-muted mb-0 small">Verify physical documents and track incoming payroll transmittals.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-pill shadow-sm border d-flex align-items-center">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="fw-bold text-secondary" style="font-size: 0.9rem;"><?= date('l, F d, Y') ?></span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-secondary"></i>Recently Processed</h6>
                    <a href="<?= base_url('receiver/received') ?>" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium hover-elevate text-secondary">
                        View All History <i class="bi bi-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead class="table-light sticky-top" style="z-index: 1;">
                                <tr>
                                    <th rowspan="2" class="ps-4 border-bottom-0 pb-1">Payroll Details</th>
                                    <th rowspan="2" class="border-bottom-0 pb-1">Unit</th>
                                    <th colspan="6" class="text-center border-bottom-0 pb-1">Approving Authorities</th>
                                    <th rowspan="2" class="border-bottom-0 pb-1">Status</th>
                                    <th rowspan="2" class="pe-4 text-center border-bottom-0 pb-1">Action</th>
                                </tr>
                                <tr>
                                    <th class="text-center pt-0 border-top-0">HR</th>
                                    <th class="text-center pt-0 border-top-0">Admin</th>
                                    <th class="text-center pt-0 border-top-0">Budget</th>
                                    <th class="text-center pt-0 border-top-0">Acct</th>
                                    <th class="text-center pt-0 border-top-0">VP</th>
                                    <th class="text-center pt-0 border-top-0">Cash</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($recent_payrolls)): ?>
                                    <?php foreach($recent_payrolls as $payroll): ?>
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($payroll->payroll_number) ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($payroll->payroll_type) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-secondary border px-2 py-1"><?= htmlspecialchars($payroll->unit ?? 'N/A') ?></span>
                                            </td>
                                            
                                            <?php 
                                            $auths = [
                                                $payroll->date_time_forwarded_hr,
                                                $payroll->date_time_received_admin,
                                                $payroll->date_time_received_budget,
                                                $payroll->date_time_received_accounting,
                                                $payroll->date_time_received_vps,
                                                $payroll->date_time_received_cashier
                                            ];
                                            foreach($auths as $date_time): 
                                            ?>
                                            <td class="text-center">
                                                <?php if (!empty($date_time)): ?>
                                                    <i class="bi bi-check-circle-fill text-success fs-6"
                                                    data-bs-toggle="tooltip"
                                                    title="<?= date('M d, Y h:i A', strtotime($date_time)) ?>">
                                                    </i>
                                                <?php else: ?>
                                                    <i class="bi bi-dash text-black-50"></i>
                                                <?php endif; ?>
                                            </td>
                                            <?php endforeach; ?>

                                            <td>
                                                <?php if($payroll->status == 'APPROVED'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill"><i class="bi bi-check me-1"></i>Approved</span>
                                                <?php elseif($payroll->status == 'PENDING'): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill"><?= htmlspecialchars($payroll->status) ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="pe-4 text-center">
                                                <button class="btn btn-sm btn-outline-primary btn-view-payroll rounded-pill px-3 fw-bold shadow-sm" data-id="<?= $payroll->payroll_period_id ?>">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <div class="mb-3 mt-2"><i class="bi bi-folder-x fs-1 text-light"></i></div>
                                            <h6 class="fw-bold">No Recent Payrolls</h6>
                                            <p class="small mb-2">You haven't processed any documents yet.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payrollVerifyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                
                <div class="modal-header modal-custom-header border-0 pb-4 pt-4 px-4 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center w-100">
                        <img src="<?= base_url('assets/img/favicon.png') ?>" class="logo mb-2 bg-white rounded-circle p-1 shadow-sm" style="width: 60px;">
                        <h5 class="fw-bold mb-0">Payroll Overview</h5>
                        <small class="text-white-50">EVSU HR & Financial System</small>
                    </div>
                </div>
                
                <div class="modal-body p-4">
                    <div class="bg-light rounded-3 p-3 mb-4 border shadow-sm">
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Reference No.</div>
                            <div class="col-7 info-value text-break fs-6 text-dark" id="modalPayrollNumber"></div>
                        </div>
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Classification</div>
                            <div class="col-7 info-value text-primary" id="modalPayrollType"></div>
                        </div>
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Particulars</div>
                            <div class="col-7 info-value fw-normal small" id="modalParticulars"></div>
                        </div>
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Unit</div>
                            <div class="col-7 info-value fw-normal" id="modalUnit"></div>
                        </div>
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Status</div>
                            <div class="col-7 text-end" id="modalStatus"></div>
                        </div>
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-5 info-label">Generated</div>
                            <div class="col-7 info-value fw-normal small text-muted" id="modalCreatedAt"></div>
                        </div>
                        <div class="row g-2 mt-3 pt-3 border-top align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-upc-scan me-1"></i> Token ID</div>
                            <div class="col-7 info-value fw-monospace text-secondary bg-white border rounded px-2 py-1" id="modalTokenId"></div>
                        </div>
                    </div>

                    <div id="modalReceivedSection" class="mb-4"></div>

                    <h6 class="fw-bold text-muted small mb-3 text-uppercase" style="letter-spacing: 1px;">Export & Actions</h6>
                    
                    <div class="d-grid gap-2 mb-2">
                        <a href="#" id="modalViewFullLink" class="btn btn-outline-primary fw-bold" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-2"></i>View Full Payroll Data
                        </a>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="#" id="modalDownloadPDF" class="btn btn-light border w-100 text-danger hover-elevate fw-medium">
                                <i class="bi bi-file-pdf-fill me-1"></i> PDF
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" id="modalDownloadExcel" class="btn btn-light border w-100 text-success hover-elevate fw-medium">
                                <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" id="modalDownloadProofList" class="btn btn-light border w-100 text-primary hover-elevate fw-medium">
                                <i class="bi bi-list-check me-1"></i> Proof List
                            </a>
                        </div>
                        <div class="col-6">
                            <button onclick="generatePayslipsModal()" class="btn btn-light border w-100 text-secondary hover-elevate fw-medium">
                                <i class="bi bi-receipt me-1"></i> Payslips
                            </button>
                        </div>
                        
                        <div class="col-12" id="remittanceContainer" style="display: none;">
                            <a href="#" id="modalDownloadRemittances" class="btn btn-light border w-100 text-dark hover-elevate fw-medium">
                                <i class="bi bi-bank me-1"></i> Remittances
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Initialize tooltips for the workflow icons
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Define the strict workflow sequence mapping
const workflowSequence = [
    { role: 'hr', field: 'date_time_forwarded_hr', label: 'HR' },
    { role: 'admin', field: 'date_time_received_admin', label: 'Admin' },
    { role: 'budget', field: 'date_time_received_budget', label: 'Budget' },
    { role: 'accounting', field: 'date_time_received_accounting', label: 'Accounting' },
    { role: 'vps', field: 'date_time_received_vps', label: 'Vice Presidents' },
    { role: 'cashier', field: 'date_time_received_cashier', label: 'Cashier' }
];

var userRole = "<?= $this->session->userdata('receiver_role') ?>";
var field = userRole === 'hr' ? 'date_time_forwarded_hr' : `date_time_received_${userRole}`;

// Auto-format Token Input (XXX-XXX-XXX)
$('input[name="token_id"]').on('input', function (e) {
    var target = e.target;
    var val = target.value.replace(/-/g, '').toUpperCase();
    if (val.length > 3 && val.length <= 6) {
        target.value = val.slice(0, 3) + '-' + val.slice(3);
    } else if (val.length > 6) {
        target.value = val.slice(0, 3) + '-' + val.slice(3, 6) + '-' + val.slice(6, 9);
    } else {
        target.value = val;
    }
});

// FORM 1: Submit Token for Verification
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
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Token',
                    text: resp.message,
                    confirmButtonColor: '#dc3545'
                });
            } else {
                const p = resp.payroll;
                
                // Fill modal fields
                $('#modalPayrollNumber').text(p.payroll_number);
                $('#modalPayrollType').text(p.payroll_type);
                $('#modalParticulars').text(p.particulars ?? '-');
                $('#modalUnit').text(p.unit ?? '-');
                $('#modalTokenId').text(p.token_id);
                $('#modalCreatedAt').text(p.created_at);

                // Setup Status Badge
                if(p.status === 'APPROVED'){
                    $('#modalStatus').html('<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i> APPROVED</span>');
                } else if (p.status === 'PENDING') {
                    $('#modalStatus').html('<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> PENDING</span>');
                } else {
                    $('#modalStatus').html('<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">'+p.status+'</span>');
                }
                
                // --- WORKFLOW VALIDATION LOGIC ---
                let canReceive = true;
                let pendingRoleLabel = '';
                let currentStepIndex = workflowSequence.findIndex(step => step.role === userRole);

                if (currentStepIndex > 0) {
                    let previousStep = workflowSequence[currentStepIndex - 1];
                    if (!p[previousStep.field]) {
                        canReceive = false;
                        pendingRoleLabel = previousStep.label;
                    }
                }

                // --- UI RENDER LOGIC FOR RECEIVED SECTION ---
                if(p[field]){
                    $('#modalReceivedSection').html(
                        '<div class="alert alert-success d-flex align-items-center border-0 py-2 px-3 mb-0" role="alert">' +
                        '<i class="bi bi-check-circle-fill fs-5 me-2"></i>' +
                        '<div><small class="mb-0 fw-bold d-block">Already Received</small><small style="font-size: 0.75rem;">Logged on '+p[field]+'</small></div></div>'
                    );
                } else if (!canReceive) {
                    $('#modalReceivedSection').html(
                        '<div class="alert alert-warning d-flex align-items-center border-0 mb-4 py-3" role="alert">' +
                        '<i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>' +
                        '<div><h6 class="mb-0 fw-bold text-dark">Sequence Error</h6>' +
                        '<small class="text-dark">Cannot receive this document yet. It is currently pending processing from <strong>'+pendingRoleLabel+'</strong>.</small></div></div>'
                    );
                } else {
                    $('#modalReceivedSection').html(
                        '<form action="<?= base_url("receiver/mark_received_") ?>'+userRole+'/'+p.payroll_period_id+'" method="post">'+
                        '<input type="hidden" name="token_id" value="'+p.token_id+'">'+
                        '<button class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm hover-elevate">' +
                        '<i class="bi bi-check-circle me-2"></i> Confirm Document Receipt</button></form>'
                    );
                }

                // Set Action Links
                $('#modalViewFullLink').attr('href','<?= base_url("receiver/view_full/") ?>'+p.payroll_period_id);
                $('#modalDownloadPDF').attr('href','<?= base_url("receiver/download_pdf/") ?>'+p.payroll_period_id);
                $('#modalDownloadExcel').attr('href','<?= base_url("receiver/download_excel_general_payroll/") ?>'+p.payroll_period_id);
                $('#modalDownloadProofList').attr('href','<?= base_url("receiver/download_proof_list_gp/") ?>'+p.payroll_period_id);

                $('#payrollVerifyModal').modal('show');
            }
        }
    });
});

// ACTION: View Existing Payroll from Table
$('.btn-view-payroll').on('click', function () {
    const id = $(this).data('id');
    const $btn = $(this);
    const originalText = $btn.html();
    
    // Add loading spinner to button
    $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $btn.prop('disabled', true);

    $.ajax({
        url: '<?= base_url("receiver/get_payroll_details_ajax/") ?>' + id,
        method: 'GET',
        dataType: 'json',
        success: function(resp) {
            $btn.html(originalText);
            $btn.prop('disabled', false);

            if(resp.status === 'error'){
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: resp.message,
                    confirmButtonColor: '#dc3545'
                });
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

            // Sleek Status Badges
            if(p.status === 'APPROVED'){
                $('#modalStatus').html('<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i> APPROVED</span>');
            } else if (p.status === 'PENDING') {
                $('#modalStatus').html('<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> PENDING</span>');
            } else {
                $('#modalStatus').html('<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">'+p.status+'</span>');
            }

            // Received section for VIEW mode
            if(p[field]){
                $('#modalReceivedSection').html(
                    '<div class="alert alert-success d-flex align-items-center border-0 py-2 px-3 mb-0" role="alert">' +
                    '<i class="bi bi-check-circle-fill fs-5 me-2"></i>' +
                    '<div><small class="mb-0 fw-bold d-block">Received</small><small style="font-size: 0.75rem;">'+p[field]+'</small></div></div>'
                );
            } else {
                $('#modalReceivedSection').html(
                    '<div class="alert alert-light d-flex align-items-center border py-2 px-3 mb-0 text-muted" role="alert">' +
                    '<i class="bi bi-clock-history fs-5 me-2"></i>' +
                    '<div><small class="mb-0 fw-bold d-block">Pending Receipt</small><small style="font-size: 0.75rem;">Document not yet logged by your department</small></div></div>'
                );
            }
            
            $('#modalViewFullLink').attr('href', '<?= base_url("receiver/view_full/") ?>' + p.payroll_period_id);
            if (p.payroll_type === 'GENERAL PAYROLL') {
                $('#modalDownloadPDF').attr('href', '<?= base_url("receiver/download_pdf/") ?>' + p.payroll_period_id);
                $('#modalDownloadExcel').attr('href', '<?= base_url("receiver/download_excel_general_payroll/") ?>' + p.payroll_period_id);
                $('#modalDownloadProofList').attr('href', '<?= base_url("receiver/download_proof_list_gp/") ?>' + p.payroll_period_id);
                $('#modalDownloadRemittances').attr('href', '<?= base_url("receiver/download_remittances/") ?>' + p.payroll_period_id);
                $('#remittanceContainer').show();

            } else if (p.payroll_type === 'MID-YEAR BONUS') {
                
                // CHANGE THESE URLS to match your actual Mid-Year Controller functions!
                $('#modalDownloadPDF').attr('href', '<?= base_url("receiver/export_pdf_mid/") ?>' + p.payroll_period_id);
                $('#modalDownloadExcel').attr('href', '<?= base_url("receiver/download_excel_midyear_payroll/") ?>' + p.payroll_period_id);
                $('#modalDownloadProofList').attr('href', '<?= base_url("receiver/download_proof_list_mid/") ?>' + p.payroll_period_id);
                $('#remittanceContainer').hide();
            } else {
                // Fallback just in case it's empty or a different type
                $('#modalViewFullLink, #modalDownloadPDF, #modalDownloadExcel, #modalDownloadProofList').attr('href', '#');
            }

            $('#payrollVerifyModal').modal('show');
        },
        error: function() {
            $btn.html(originalText);
            $btn.prop('disabled', false);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Could not connect to the server. Please try again later.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
});

function generatePayslipsModal(){
    const id = $('#modalViewFullLink').attr('href').split('/').pop();
    window.open("<?= base_url('receiver/view_payslips/') ?>" + id, "_blank");
}
</script>