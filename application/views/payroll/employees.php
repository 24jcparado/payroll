<style>
body { background:#f5f7fb; }

/* Cards */
.dashboard-card {
    border-radius:14px;
    border:none;
    background:#fff;
    transition:.2s;
}
.dashboard-card:hover { transform:translateY(-3px); }

.stat-card {
    border-radius:14px;
    background:linear-gradient(135deg,#fff,#eef2ff);
    border:none;
}

/* Section */
.section-title {
    font-weight:600;
    color:#2c3e50;
}

/* Filters */
.filter-box {
    background:#fff;
    border-radius:12px;
    padding:15px;
    border:1px solid #e5e7eb;
}

/* Table */
#periodTable thead {
    background:#2c3e50;
    color:#fff;
}
#periodTable tbody tr:hover {
    background:#f1f5ff;
}

/* Badges */
.badge-permanent {
    background:#d1fae5;
    color:#065f46;
    padding:5px 10px;
    border-radius:50px;
}
.badge-contract {
    background:#fee2e2;
    color:#991b1b;
    padding:5px 10px;
    border-radius:50px;
}

/* Buttons */
.action-btn {
    border-radius:8px;
    padding:4px 8px;
}
</style>
<!-- CONTENT -->
    <main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card dashboard-card text-center p-3">
                    <i class="bi bi-people fs-2 text-primary"></i>
                    <h6>Total Employees</h6>
                    <h3><?= $total_employees ?></h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-3">
                    <h6 class="section-title"><i class="bi bi-person-check"></i> Status</h6>
                    <?php foreach($by_status as $s): ?>
                        <div class="d-flex justify-content-between small">
                            <span><?= $s->status ?></span>
                            <b><?= $s->total ?></b>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-3">
                    <h6 class="section-title"><i class="bi bi-building"></i> Unit</h6>
                    <?php foreach($by_unit as $u): ?>
                        <div class="d-flex justify-content-between small">
                            <span><?= $u->unit ?></span>
                            <b><?= $u->total ?></b>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-3">
                    <h6 class="section-title"><i class="bi bi-briefcase"></i> Position</h6>
                    <?php foreach($by_position as $p): ?>
                        <div class="d-flex justify-content-between small">
                            <span><?= $p->position ?></span>
                            <b><?= $p->total ?></b>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <h5 class="section-title mb-3"><i class="bi bi-bar-chart-line"></i> Analytics</h5>

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card stat-card text-center p-3">
                    <h6>Total Filtered</h6>
                    <h3 id="totalEmployees">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card text-center p-3">
                    <h6>Average SG</h6>
                    <h3 id="avgSG">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card text-center p-3">
                    <h6>Permanent</h6>
                    <h3 id="regularCount">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card text-center p-3">
                    <h6>Others</h6>
                    <h3 id="contractCount">0</h3>
                </div>
            </div>

        </div>

        <div class="filter-box mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="small">Campus</label>
                    <input type="text" id="filterCampus" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="small">Status</label>
                    <input type="text" id="filterStatus" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="small">Min SG</label>
                    <input type="number" id="filterSG" class="form-control form-control-sm">
                </div>

            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text me-2"></i>List of Employees</h5>
                        <div class="table-responsive">
                            <!-- TABLE -->
                            <div class="card shadow-sm border-0">
                                <div class="card-body">

                                    <table id="periodTable" class="table table-hover table-striped table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>UNIT</th>
                                                <th>CAMPUS</th>
                                                <th>NAME</th>
                                                <th>POSITION</th>
                                                <th>SG</th>
                                                <th>STEP</th>
                                                <th>STATUS</th>
                                                <th>ASSIGNMENT</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php foreach($employee as $row): ?>
                                            <tr>
                                                <td><?= $row->unit ?></td>
                                                <td><?= $row->campus ?></td>
                                                <td><strong><?= $row->name ?> <?= $row->middle_name ?> <?= $row->last_name ?></strong></td>
                                                <td><?= $row->position ?></td>
                                                <td><span class="badge bg-primary"><?= $row->sg ?></span></td>
                                                <td><?= $row->step ?></td>
                                                <td>
                                                    <?php if(strtolower($row->status) == 'permanent'): ?>
                                                        <span class="badge-status badge-permanent">Permanent</span>
                                                    <?php else: ?>
                                                        <span class="badge-status badge-contract"><?= $row->status ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $row->assignment ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary action-btn">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger action-btn">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> 
<script>
$(document).ready(function(){

let table = $('#periodTable').DataTable({
    pageLength:10,
    lengthMenu:[10,25,50,100]
});

function analyze(){
    let data = table.rows({search:'applied'}).data();

    let total = data.length;
    let totalSG = 0;
    let reg = 0;
    let con = 0;

    for(let i=0;i<total;i++){
        let sg = parseInt(data[i][4])||0;
        let status = data[i][6].toLowerCase();

        totalSG += sg;
        if(status.includes('permanent')) reg++;
        else con++;
    }

    $('#totalEmployees').text(total);
    $('#avgSG').text(total ? (totalSG/total).toFixed(2) : 0);
    $('#regularCount').text(reg);
    $('#contractCount').text(con);
}

analyze();
table.on('draw', analyze);

/* FILTERS */
$('#filterCampus').keyup(function(){
    table.column(1).search(this.value).draw();
});

$('#filterStatus').keyup(function(){
    table.column(6).search(this.value).draw();
});

$('#filterSG').keyup(function(){
    let min = parseInt(this.value)||0;

    $.fn.dataTable.ext.search = [];
    $.fn.dataTable.ext.search.push(function(settings,data){
        let sg = parseInt(data[4])||0;
        return sg >= min;
    });

    table.draw();
});

});
</script>


