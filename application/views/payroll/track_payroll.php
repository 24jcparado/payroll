<style>
.payroll-tracker {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 40px 0;
    flex-wrap: wrap; /* allow wrapping on small screens */
}

.payroll-tracker::before {
    content: '';
    position: absolute;
    top: 22px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #e9ecef;
    z-index: 1;
}

.tracker-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1 1 150px; /* flexible width with minimum */
    margin-bottom: 20px; /* spacing when wrapped */
}

.tracker-circle {
    width: 40px;
    height: 40px;
    margin: 0 auto 8px;
    border-radius: 50%;
    background: #dee2e6;
    line-height: 40px;
    font-weight: bold;
}

.tracker-step.active .tracker-circle {
    background: #28a745;
    color: #fff;
}

.tracker-step.current .tracker-circle {
    background: #ffc107;
    color: #000;
}

/* SUB PROCESS */
.sub-process {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap; /* wrap sub-steps if needed */
}

.sub-step {
    font-size: 10px;
    padding: 5px 10px;
    border-radius: 20px;
    background: #dee2e6;
    white-space: nowrap; /* prevent breaking inside words */
}

.sub-step.active {
    background: #28a745;
    color: #fff;
}

.sub-step.current {
    background: #ffc107;
    color: #000;
}

/* LEGEND */
.tracker-legend {
    display: flex;
    gap: 15px;
    align-items: center;
    font-size: 14px;
    flex-wrap: wrap; /* wrap legend items if needed */
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.legend-item::before {
    content: '';
    width: 15px;
    height: 15px;
    display: inline-block;
    border-radius: 50%;
}

.active-legend::before {
    background-color: #28a745;
}

.current-legend::before {
    background-color: #ffc107;
}

.pending-legend::before {
    background-color: #dee2e6;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .payroll-tracker {
        flex-direction: column; /* stack steps vertically */
        align-items: flex-start;
    }

    .tracker-step {
        flex: 1 1 100%;
        text-align: left;
    }

    .sub-process {
        justify-content: flex-start;
    }
}

</style>


<div class="content">
    <div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Payroll Management</h3>
    </div>

    <div class="row mb-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-4">Payroll Status Tracking</h5>
                <div class="tracker-legend mb-4">
                    <span class="legend-item active-legend">Completed</span>
                    <span class="legend-item current-legend">Current</span>
                    <span class="legend-item pending-legend">Pending</span>
                </div>
                <div class="payroll-tracker">

                    <div class="tracker-step" id="step-1">
                        <div class="tracker-circle">1</div>
                        <div>HRMO</div>
                        <div class="sub-process">
                            <div class="sub-step" id="hrmo-draft">DRAFT PAYROLL</div>
                            <div class="sub-step" id="hrmo-final">FINAL PAYROLL</div>
                        </div>
                    </div>

                    <div class="tracker-step" id="step-2">
                        <div class="tracker-circle">2</div>
                        <div>Accounting</div>
                        <div class="sub-process">
                            <div class="sub-step" id="acc-pre-d">PRE AUDIT - (D)</div>
                            <div class="sub-step" id="acc-tax">TAX COMPUTATION</div>
                            <div class="sub-step" id="acc-pre-f">PRE AUDIT - (F)</div>
                        </div>
                    </div>

                    <div class="tracker-step" id="step-3">
                        <div class="tracker-circle">3</div>
                        <div>Budget</div>
                    </div>

                    <div class="tracker-step" id="step-4">
                        <div class="tracker-circle">4</div>
                        <div>Approved</div>
                    </div>

                    <div class="tracker-step" id="step-5">
                        <div class="tracker-circle">5</div>
                        <div>Released</div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- PAYROLL LIST -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <h5 class="mb-4">Payroll Records</h5>
            <table id="payrollTable" class="table table-bordered table-hover" style="font-size: 14px">
                <thead>
                    <tr>
                        <th>Payroll #</th>
                        <th>Period</th>
                        <th>Unit</th>
                        <th>Payroll Type</th>
                        <th>Gross Amount</th>
                        <th>Tax</th>
                        <th>Other Deduction</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(!empty($payroll)): ?>
                        <?php $no = 1; foreach($payroll as $row): ?>
                        <tr>
                            <td><?= $row->payroll_number ?></td>
                            <td><?= $row->date_period ?></td>
                            <td><?= $row->unit ?></td>
                            <td><?= $row->payroll_type ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                
                                    <button 
                                        class="btn btn-sm btn-primary track-btn"
                                        data-status="<?= $row->status ?>">
                                        Track
                                    </button>
                                

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <?php endif; ?>
                </tbody>


            </table>

        </div>
    </div>

</div>

</div>

<script>
$(document).ready(function() {
    $('#payrollTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        dom: 'Bfrtip',
        buttons: [
            'copy',
            'excel',
            'pdf',
            'print'
        ]
    });
});

const steps = [
    { step: '#step-1', sub: '#hrmo-draft' }, // status 1
    { step: '#step-1', sub: '#hrmo-final' },  // status 4
    { step: '#step-2', sub: '#acc-pre-d' },   // status 2
    { step: '#step-2', sub: '#acc-tax' },     // status 3
    { step: '#step-2', sub: '#acc-pre-f' },   // status 5
    { step: '#step-3', sub: null },           // status 6
    { step: '#step-4', sub: null },           // status 7
    { step: '#step-5', sub: null }            // status 9
];

$(document).on('click', '.track-btn', function() {
    var status = parseInt($(this).data('status'));

    // Reset tracker
    $('.tracker-step').removeClass('active current');
    $('.sub-step').removeClass('active current');

    // Loop through steps
    let foundCurrent = false;
    for(let i = 0; i < steps.length; i++) {
        let s = steps[i];

        if(!foundCurrent) {
            if(s.sub) {
                // Sub-step exists
                if(i === statusIndex(status)) {
                    $(s.sub).addClass('current');      // current
                    $(s.step).addClass('current');     // highlight circle
                    foundCurrent = true;
                } else {
                    $(s.sub).addClass('active');       // previous sub-step green
                    $(s.step).addClass('active');      // step circle green
                }
            } else {
                // Step without sub-step
                if(i === statusIndex(status)) {
                    $(s.step).addClass('current');
                    foundCurrent = true;
                } else {
                    $(s.step).addClass('active');
                }
            }
        }
    }

    // Function to get array index based on status
    function statusIndex(status) {
        switch(status) {
            case 1: return 0;
            case 4: return 1;
            case 2: return 2;
            case 3: return 3;
            case 5: return 4;
            case 6: return 5;
            case 7: return 6;
            case 9: return 7;
            default: return 0;
        }
    }
});

</script>

