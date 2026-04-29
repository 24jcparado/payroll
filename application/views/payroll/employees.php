<style>
    body { 
        background: #f4f7fe; 
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* Cards & Shadows */
    .dashboard-card, .stat-card, .filter-box, .table-card {
        border-radius: 16px;
        border: none;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .dashboard-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    /* Icon Wrappers for a Modern Look */
    .icon-shape {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 12px;
    }
    .icon-shape-primary { background: #e0e8ff; color: #4318FF; }
    .icon-shape-success { background: #e2fbd7; color: #34b53a; }
    .icon-shape-info { background: #e1f4ff; color: #00a5ff; }
    .icon-shape-warning { background: #fff5d9; color: #ffb547; }

    /* Typography */
    .section-title {
        font-weight: 700;
        color: #2b3674;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-weight: 800;
        color: #2b3674;
    }

    /* List Items */
    .list-item {
        padding: 8px 4px;
        border-bottom: 1px dashed #e2e8f0;
        transition: background 0.2s;
        border-radius: 6px;
    }
    .list-item:hover { background: #f8fafc; padding-left: 8px; }
    .list-item:last-child { border-bottom: none; }

    /* Scrollable list container */
    .scrollable-list {
        max-height: 180px; 
        overflow-y: auto;
        padding-right: 8px;
    }
    .scrollable-list::-webkit-scrollbar { width: 4px; }
    .scrollable-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .scrollable-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .scrollable-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Table Enhancements */
    .table-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        border-radius: 16px 16px 0 0;
    }
    #periodTable { font-size: 0.875rem; }
    #periodTable th, #periodTable td {
        white-space: nowrap; 
        vertical-align: middle;
        padding: 12px 16px;
    }
    #periodTable thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
    }
    #periodTable tbody tr:hover { background: #f8fafc; }

    /* Identifiers & Badges */
    .id-number {
        font-family: 'Consolas', 'Courier New', monospace;
        color: #475569;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-permanent { background: #dcfce7; color: #166534; }
    .badge-contract { background: #fee2e2; color: #991b1b; }
    .badge-tax { background: #fef3c7; color: #92400e; font-weight: 700; }
    
    .badge-missing {
        background-color: #fee2e2;
        color: #ef4444;
        font-family: inherit;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Form Controls */
    .filter-box .form-control, .filter-box .input-group-text {
        border-color: #e2e8f0;
        background-color: #f8fafc;
    }
    .filter-box .form-control:focus {
        box-shadow: none;
        border-color: #cbd5e1;
        background-color: #ffffff;
    }
    
    /* Toggle Switch customized to match missing danger color */
    .form-switch .form-check-input:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
</style>

<main id="mainContent" class="py-4">
    <div class="container-fluid px-md-4">
        
        <div class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="menuToggle"><i class="bi bi-list fs-5"></i></button>
                <h4 class="m-0 fw-bold text-dark" style="color: #2b3674;"><?= $period ?></h4>
            </div>
            <div id="runningClock" class="fw-bold text-muted small d-none d-sm-block bg-light px-3 py-2 rounded-pill"></div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card dashboard-card p-4 h-100 d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="icon-shape icon-shape-primary fs-3"><i class="bi bi-people-fill"></i></div>
                    <h6 class="text-muted fw-bold text-uppercase small mb-1">Total Employees</h6>
                    <h2 class="stat-value mb-0"><?= $total_employees ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="icon-shape icon-shape-success fs-5 m-0" style="width: 32px; height: 32px;"><i class="bi bi-person-badge"></i></div>
                        <h6 class="section-title m-0">Status</h6>
                    </div>
                    <div class="scrollable-list">
                        <?php foreach($by_status as $s): ?>
                            <div class="d-flex justify-content-between align-items-center list-item small">
                                <span class="text-secondary fw-medium"><?= $s->status ?></span>
                                <span class="badge bg-light text-dark border"><?= $s->total ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="icon-shape icon-shape-info fs-5 m-0" style="width: 32px; height: 32px;"><i class="bi bi-building"></i></div>
                        <h6 class="section-title m-0">Unit</h6>
                    </div>
                    <div class="scrollable-list">
                        <?php foreach($by_unit as $u): ?>
                            <div class="d-flex justify-content-between align-items-center list-item small">
                                <span class="text-secondary fw-medium"><?= $u->unit ?></span>
                                <span class="badge bg-light text-dark border"><?= $u->total ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="icon-shape icon-shape-warning fs-5 m-0" style="width: 32px; height: 32px;"><i class="bi bi-briefcase"></i></div>
                        <h6 class="section-title m-0">Position</h6>
                    </div>
                    <div class="scrollable-list">
                        <?php foreach($by_position as $p): ?>
                            <div class="d-flex justify-content-between align-items-center list-item small">
                                <span class="text-secondary fw-medium"><?= $p->position ?></span>
                                <span class="badge bg-light text-dark border"><?= $p->total ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="section-title mb-3"><i class="bi bi-bar-chart-fill me-2 text-primary"></i> Data Analytics</h5>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Filtered</h6>
                        <h4 id="totalEmployees" class="fw-bold text-primary mb-0">0</h4>
                    </div>
                    <i class="bi bi-funnel opacity-25 fs-1 text-primary"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Average SG</h6>
                        <h4 id="avgSG" class="fw-bold text-info mb-0">0</h4>
                    </div>
                    <i class="bi bi-graph-up opacity-25 fs-1 text-info"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Permanent</h6>
                        <h4 id="regularCount" class="fw-bold text-success mb-0">0</h4>
                    </div>
                    <i class="bi bi-check-circle opacity-25 fs-1 text-success"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Contractual</h6>
                        <h4 id="contractCount" class="fw-bold text-danger mb-0">0</h4>
                    </div>
                    <i class="bi bi-clock-history opacity-25 fs-1 text-danger"></i>
                </div>
            </div>
        </div>

        <div class="filter-box p-4 mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold text-uppercase">Campus Filter</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" id="filterCampus" class="form-control border-start-0" placeholder="e.g. Main Campus">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold text-uppercase">Status Filter</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-person-lines-fill"></i></span>
                        <input type="text" id="filterStatus" class="form-control border-start-0" placeholder="e.g. Permanent">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold text-uppercase">Salary Grade (Min)</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-sort-numeric-up"></i></span>
                        <input type="number" id="filterSG" class="form-control border-start-0" placeholder="e.g. 15">
                    </div>
                </div>
                <div class="col-md-3 pb-2">
                    <div class="form-check form-switch p-3 bg-light rounded-3 border d-flex align-items-center gap-2 m-0">
                        <input class="form-check-input m-0" type="checkbox" id="missingDataToggle" role="switch">
                        <label class="form-check-label small fw-bold text-dark m-0" style="cursor: pointer;" for="missingDataToggle">
                            Show Missing IDs Only
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2 mb-5">
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header p-4 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-table me-2 text-primary"></i>Comprehensive Masterlist</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3">
                            <table id="periodTable" class="table table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Campus</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>SG</th>
                                        <th>Step</th>
                                        <th>Tax Rate</th>
                                        <th>Account No.</th>
                                        <th>GSIS No.</th>
                                        <th>PhilHealth</th>
                                        <th>Pag-IBIG</th>
                                        <th>Status</th>
                                        <th>Assignment</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($employee as $row): ?>
                                        <tr>
                                            <td><?= $row->unit ?></td>
                                            <td><?= $row->campus ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= $row->name ?> <?= $row->last_name ?></div>
                                                <div class="small text-muted"><?= $row->middle_name ?></div>
                                            </td>
                                            <td><?= $row->position ?></td>
                                            <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2"><?= $row->sg ?></span></td>
                                            <td><?= $row->step ?></td>
                                            
                                            <td><span class="badge badge-tax"><?= $row->tax_rate ?>%</span></td>
                                            
                                            <td class="id-number">
                                                <?= !empty($row->account_no) ? $row->account_no : '<span class="badge-missing"><i class="bi bi-x-circle-fill"></i> Missing</span>' ?>
                                            </td>
                                            <td class="id-number">
                                                <?= !empty($row->gsis_no) ? $row->gsis_no : '<span class="badge-missing"><i class="bi bi-x-circle-fill"></i> Missing</span>' ?>
                                            </td>
                                            <td class="id-number">
                                                <?= !empty($row->philhealth_no) ? $row->philhealth_no : '<span class="badge-missing"><i class="bi bi-x-circle-fill"></i> Missing</span>' ?>
                                            </td>
                                            <td class="id-number">
                                                <?= !empty($row->pagibig_no) ? $row->pagibig_no : '<span class="badge-missing"><i class="bi bi-x-circle-fill"></i> Missing</span>' ?>
                                            </td>
                                            
                                            <td>
                                                <?php if(strtolower($row->status) == 'permanent'): ?>
                                                    <span class="badge-status badge-permanent">Permanent</span>
                                                <?php else: ?>
                                                    <span class="badge-status badge-contract"><?= $row->status ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small"><?= $row->assignment ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-light border action-btn me-1" title="Edit Record">
                                                    <i class="bi bi-pencil-square text-primary"></i>
                                                </button>
                                                <button class="btn btn-sm btn-light border action-btn" title="Delete Record">
                                                    <i class="bi bi-trash3 text-danger"></i>
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
</main> 

<script>
$(document).ready(function(){

    let table = $('#periodTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        scrollX: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Quick search..."
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
    });

    // --- Unified Custom Search Filter ---
    // We combine the logic for the SG filter and the Missing Data toggle into ONE function
    // so they do not override each other.
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        
        // 1. Check Minimum SG Condition
        let minSG = parseInt($('#filterSG').val()) || 0;
        let sg = parseInt(data[4]) || 0; 
        let passesSG = (sg >= minSG);

        // 2. Check Missing Data Toggle Condition
        let showMissingOnly = $('#missingDataToggle').is(':checked');
        let hasMissingData = data[7].includes('Missing') || 
                             data[8].includes('Missing') || 
                             data[9].includes('Missing') || 
                             data[10].includes('Missing');
        
        let passesMissingToggle = showMissingOnly ? hasMissingData : true;

        // Row must pass BOTH custom conditions to be shown
        return passesSG && passesMissingToggle;
    });

    // --- Standard Text Filters ---
    $('#filterCampus').keyup(function(){
        table.column(1).search(this.value).draw();
    });

    $('#filterStatus').keyup(function(){
        table.column(11).search(this.value).draw();
    });

    // --- Trigger Custom Filters on Change ---
    $('#filterSG').keyup(function(){
        table.draw();
    });

    $('#missingDataToggle').on('change', function() {
        table.draw();
    });

    // --- Analytics Recalculation ---
    function analyze(){
        // Get data from rows that survived the current filters
        let data = table.rows({search:'applied'}).data();

        let total = data.length;
        let totalSG = 0;
        let reg = 0;
        let con = 0;

        for(let i=0; i<total; i++){
            let sg = parseInt(data[i][4]) || 0; 
            let rawStatus = data[i][11].replace(/(<([^>]+)>)/gi, "").toLowerCase();

            totalSG += sg;
            if(rawStatus.includes('permanent')) reg++;
            else con++;
        }

        // Animate numbers for a modern feel (optional but looks great)
        $('#totalEmployees').fadeOut(100, function(){ $(this).text(total).fadeIn(100); });
        $('#avgSG').fadeOut(100, function(){ $(this).text(total ? (totalSG/total).toFixed(1) : 0).fadeIn(100); });
        $('#regularCount').fadeOut(100, function(){ $(this).text(reg).fadeIn(100); });
        $('#contractCount').fadeOut(100, function(){ $(this).text(con).fadeIn(100); });
    }

    // Run analysis on init and every time the table is drawn/filtered
    analyze();
    table.on('draw', analyze);

});
</script>