<main id="mainContent" class="p-4" style="background-color: #f8f9fa; min-height:100vh;">
    <style>
        /* Custom UI Enhancements */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important;
        }
        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
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
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Payroll Receiver Dashboard</h4>
            <small class="text-muted">Overview and Verification</small>
        </div>
        <div class="bg-white px-3 py-2 rounded-pill shadow-sm border">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="fw-medium text-secondary"><?= date('l, F d, Y') ?></span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 card-hover h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.8rem;">Total Payrolls</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?= $total_payrolls ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-file-earmark-text fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 card-hover h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.8rem;">Verified Payrolls</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?= $verified_payrolls ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 card-hover h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.8rem;">Pending Verification</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?= $pending_payrolls ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 card-hover h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.8rem;">Payrolls Received</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?= $received_payrolls ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="bi bi-box-arrow-down fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-qr-code-scan me-2"></i>Verify Token
                    </h6>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <form id="verifyPayrollForm">
                        <p class="text-muted small mb-4">Enter the 11-character Token ID found on the physical transmittal form to verify and receive the document.</p>
                        
                        <div class="mb-4">
                            <input type="text" 
                                name="token_id" 
                                class="form-control token-input text-center rounded-3 py-3" 
                                placeholder="XXX-XXX-XXX" 
                                maxlength="11" 
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                            Verify & Mark as Received <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-secondary"></i>Recent Payrolls</h6>
                    <a href="<?= base_url('receiver/received') ?>" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium">
                        View All <i class="bi bi-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="ps-4">Payroll Details</th>
                                    <th rowspan="2">Unit</th>
                                    <th colspan="6" class="text-center border-bottom-0 pb-1">Approving Authorities</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2" class="pe-4">Generated</th>
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
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($payroll->payroll_number) ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($payroll->payroll_type) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-secondary border"><?= htmlspecialchars($payroll->unit ?? 'N/A') ?></span>
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
                                                    <i class="bi bi-dash text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                            <?php endforeach; ?>

                                            <td>
                                                <?php if($payroll->status == 'APPROVED'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Approved</span>
                                                <?php elseif($payroll->status == 'PENDING'): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill"><?= htmlspecialchars($payroll->status) ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="pe-4 text-muted small">
                                                <?= date('M d, Y', strtotime($payroll->created_at)) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <div class="mb-2"><i class="bi bi-inbox fs-1 text-light"></i></div>
                                            No recent payrolls found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
                            <h5 class="fw-bold mb-0">Payroll Verified</h5>
                            <small class="text-white-50">EVSU HR & Financial System</small>
                        </div>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="bg-light rounded-3 p-3 mb-4 border">
                            <div class="row g-2 mb-2">
                                <div class="col-5 text-muted small">Reference No.</div>
                                <div class="col-7 fw-bold text-end text-break" id="modalPayrollNumber"></div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-5 text-muted small">Classification</div>
                                <div class="col-7 fw-bold text-end text-primary" id="modalPayrollType"></div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-5 text-muted small">Particulars</div>
                                <div class="col-7 text-end small" id="modalParticulars"></div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-5 text-muted small">Unit</div>
                                <div class="col-7 text-end small" id="modalUnit"></div>
                            </div>
                            <div class="row g-2">
                                <div class="col-5 text-muted small">Token ID</div>
                                <div class="col-7 text-end fw-monospace small text-secondary" id="modalTokenId"></div>
                            </div>
                        </div>

                        <div id="modalReceivedSection" class="mb-4"></div>

                        <h6 class="fw-bold text-muted small mb-3 text-uppercase letter-spacing-1">Export & Actions</h6>
                        <div class="d-grid gap-2 mb-2">
                            <a href="#" id="modalViewFullLink" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-box-arrow-up-right me-2"></i>View Full Payroll
                            </a>
                        </div>
                        <!-- <div class="row g-2">
                            <div class="col-6">
                                <a href="#" id="modalDownloadPDF" class="btn btn-light border w-100 text-danger hover-elevate">
                                    <i class="bi bi-file-pdf-fill me-1"></i> PDF
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" id="modalDownloadExcel" class="btn btn-light border w-100 text-success hover-elevate">
                                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" id="modalDownloadProofList" class="btn btn-light border w-100 text-primary hover-elevate">
                                    <i class="bi bi-list-check me-1"></i> Proof List
                                </a>
                            </div>
                            <div class="col-6">
                                <button onclick="generatePayslipsModal()" class="btn btn-light border w-100 text-secondary hover-elevate">
                                    <i class="bi bi-receipt me-1"></i> Payslips
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Make sure tooltips are initialized for the new table icons
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

var userRole = "<?= $this->session->userdata('receiver_role') ?>";
var field = `date_time_received_${userRole}`;

// Auto-format the Token input to include dashes (XXX-XXX-XXX)
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

// Define the strict workflow sequence and map it to your database columns
const workflowSequence = [
    { role: 'hr', field: 'date_time_forwarded_hr', label: 'HR' },
    { role: 'admin', field: 'date_time_received_admin', label: 'Admin' },
    { role: 'budget', field: 'date_time_received_budget', label: 'Budget' },
    { role: 'accounting', field: 'date_time_received_accounting', label: 'Accounting' },
    { role: 'vps', field: 'date_time_received_vps', label: 'Vice Presidents' },
    { role: 'cashier', field: 'date_time_received_cashier', label: 'Cashier' }
];

var userRole = "<?= $this->session->userdata('receiver_role') ?>";
// Fallback logic in case HR role is named differently in session vs column
var field = userRole === 'hr' ? 'date_time_forwarded_hr' : `date_time_received_${userRole}`;

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
                // Replaced standard alert with SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Token',
                    text: resp.message,
                    confirmButtonColor: '#dc3545'
                });
            } else {
                $('#modalPayrollNumber').text(resp.payroll.payroll_number);
                $('#modalPayrollType').text(resp.payroll.payroll_type);
                $('#modalParticulars').text(resp.payroll.particulars);
                $('#modalUnit').text(resp.payroll.unit);
                $('#modalTokenId').text(resp.payroll.token_id);
                
                // --- WORKFLOW VALIDATION LOGIC ---
                let canReceive = true;
                let pendingRoleLabel = '';
                
                // Find where the current user sits in the workflow sequence
                let currentStepIndex = workflowSequence.findIndex(step => step.role === userRole);

                // If they are not the first step (HR), check the previous step
                if (currentStepIndex > 0) {
                    let previousStep = workflowSequence[currentStepIndex - 1];
                    
                    // If the previous step's date is empty/null, they cannot proceed
                    if (!resp.payroll[previousStep.field]) {
                        canReceive = false;
                        pendingRoleLabel = previousStep.label;
                    }
                }

                // --- UI RENDER LOGIC ---
                if(resp.payroll[field]){
                    // SCENARIO 1: The current user already received it
                    $('#modalReceivedSection').html(
                        '<div class="alert alert-success d-flex align-items-center border-0" role="alert">' +
                        '<i class="bi bi-check-circle-fill fs-4 me-3"></i>' +
                        '<div><h6 class="mb-0 fw-bold">Already Received</h6><small>Logged on '+resp.payroll[field]+'</small></div></div>'
                    );
                } else if (!canReceive) {
                    // SCENARIO 2: Previous department hasn't processed it yet
                    $('#modalReceivedSection').html(
                        '<div class="alert alert-warning d-flex align-items-center border-0 mb-4" role="alert">' +
                        '<i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>' +
                        '<div><h6 class="mb-0 fw-bold text-dark">Sequence Error</h6>' +
                        '<small class="text-dark">Cannot receive this document yet. It is currently pending processing from <strong>'+pendingRoleLabel+'</strong>.</small></div></div>'
                    );
                } else {
                    // SCENARIO 3: Clear to receive!
                    $('#modalReceivedSection').html(
                        '<form action="<?= base_url("receiver/mark_received_") ?>'+userRole+'/'+resp.payroll.payroll_period_id+'" method="post">'+
                        '<input type="hidden" name="token_id" value="'+resp.payroll.token_id+'">'+
                        '<button class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm hover-elevate">' +
                        '<i class="bi bi-check-circle me-2"></i> Confirm Document Receipt</button></form>'
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