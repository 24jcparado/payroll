<style>
@page { size: A4 landscape; margin: 10mm; }

.ledger-table {
    width: 100%;
    border-collapse: collapse;
}

.ledger-table th,
.ledger-table td {
    border: 1px solid #000;
    padding: 4px;
    font-size: 11px;
}

.ledger-table th {
    text-align: center;
    vertical-align: middle;
    background: #e9ecef;
}

.text-end { text-align: right; }
.text-center { text-align: center; }

.header-section td {
    border: none;
    padding: 3px;
}
</style>

<div class="content">
    <div class="container-fluid mt-3">
        <br>
            <h4 class="text-center">INDEX OF PAYMENTS</h4>
        <br>
        <!-- LEDGER TABLE -->
        <div class="card shadow-sm" id="ledgerContent">
            <div class="card-body">

             <!-- PAGE HEADER -->
                <div class="row">

                    <div class="col-md-6">
                        <label class="text-muted small">ENTITY NAME:</label>
                        <div class="fw-bold">
                            EASTERN VISAYAS STATE UNIVERSITY
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">FUND CLUSTER: </label>
                        <div class="fw-bold">
                            ___________
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="text-muted small">CREDITOR:</label> <span><?= $employee->name?></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">ADDRESS:</label><span>___________</span>
                    </div>
                    

                    <div class="col-md-4">
                        <label class="text-muted small">EMPLOYEE NO: </label><span><?= $employee->position ?></span>
                    </div>

                    
                    <div class="col-md-4">
                        <label class="text-muted small">POSITION: </label><span><?= $employee->position ?></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">BANK ACCOUNT: </label><span>______________</span>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted small">TIN: </label><span>______________</span>
                    </div>

                </div>

                
                <table class="ledger-table">

                    <!-- HEADER ROW 1 -->
                    <tr>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">Reference / DV / Payroll No.</th>
                        <th rowspan="2">Particulars</th>

                        <th colspan="4">EARNINGS</th>
                        <th colspan="5">DEDUCTIONS</th>

                        <th rowspan="2">Total Deduction</th>
                        <th rowspan="2">Net Amount</th>
                        <th rowspan="2">1st Quincena</th>
                        <th rowspan="2">2nd Quincena</th>
                    </tr>

                    <!-- HEADER ROW 2 -->
                    <tr>
                        <!-- Earnings -->
                        <th>Basic Pay</th>
                        <th>Salary (LWOP)</th>
                        <th>PERA (LWOP)</th>
                        <th>Gross Amount</th>

                        <!-- Deductions -->
                        <th>W/Tax</th>
                        <th>GSIS</th>
                        <th>PhilHealth</th>
                        <th>Pag-IBIG</th>
                        <th>Other Deductions</th>
                    </tr>

                    <!-- SAMPLE DATA ROW -->
                    <tr>
                        <td class="text-center">01/15/2026</td>
                        <td>Payroll #001</td>
                        <td>Regular Salary – 1st Quincena</td>

                        <!-- Earnings -->
                        <td class="text-end">25,000.00</td>
                        <td class="text-end">0.00</td>
                        <td class="text-end">0.00</td>
                        <td class="text-end">25,000.00</td>

                        <!-- Deductions -->
                        <td class="text-end">1,200.00</td>
                        <td class="text-end">900.00</td>
                        <td class="text-end">500.00</td>
                        <td class="text-end">100.00</td>
                        <td class="text-end">0.00</td>

                        <!-- Totals -->
                        <td class="text-end">2,700.00</td>
                        <td class="text-end">22,300.00</td>
                        <td class="text-end">11,150.00</td>
                        <td class="text-end">11,150.00</td>
                    </tr>

                </table>


            </div>
        </div>


    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf; // <- this works with the UMD build

    const ledger = document.getElementById('ledgerContent');

    html2canvas(ledger, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');

        // Create PDF in portrait mode
        const pdf = new jsPDF('p', 'pt', 'a4'); 

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('Payroll_Ledger.pdf');
    });
}
</script>




<div class="content">
    <div class="container-fluid">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Track Payroll</h4>
            <button class="btn btn-primary">
                <i class="fa fa-file-export"></i> Export
            </button>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <h6 class="text-muted">Total Payroll</h6>
                        <h4>₱ 0.00</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-left-success">
                    <div class="card-body">
                        <h6 class="text-muted">Released</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-left-warning">
                    <div class="card-body">
                        <h6 class="text-muted">Processing</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-left-secondary">
                    <div class="card-body">
                        <h6 class="text-muted">Draft</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>

        </div>

        <!-- FILTER SECTION -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3">
                        <label>Employee</label>
                        <select class="form-control">
                            <option>All Employees</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>From</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label>To</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Status</label>
                        <select class="form-control">
                            <option>All</option>
                            <option>Draft</option>
                            <option>Processing</option>
                            <option>Released</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary btn-block">
                            Filter
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- PAYROLL TABLE -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Employee</th>
                            <th>Payroll Period</th>
                            <th>Gross Salary</th>
                            <th>Total Deduction</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Date Processed</th>
                            <th>Date Released</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>Jan 01 - Jan 15, 2026</td>
                            <td>₱ 25,000.00</td>
                            <td>₱ 5,000.00</td>
                            <td><strong>₱ 20,000.00</strong></td>
                            <td>
                                <span class="badge badge-success">
                                    Released
                                </span>
                            </td>
                            <td>Jan 16, 2026</td>
                            <td>Jan 17, 2026</td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    View
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>

    </div>

</div>