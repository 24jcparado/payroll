<!-- CONTENT -->
<main id="mainContent">
    <?php $this->load->view('template/admin_topbar')?>
    <!-- Example Table -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-text me-2"></i>Salary Grade</h5>
                    <div class="table-responsive">
                        <form method="post" action="<?= base_url('payroll/edit_sg') ?>">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-sm">
                                    <?= $this->session->flashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Salary Grade</th>
                                        <th>Step 1</th>
                                        <th>Step 2</th>
                                        <th>Step 3</th>
                                        <th>Step 4</th>
                                        <th>Step 5</th>
                                        <th>Step 6</th>
                                        <th>Step 7</th>
                                        <th>Step 8</th>
                                    </tr>
                                </thead>

                                <!-- YOUR CODE GOES HERE -->
                                <tbody>
                                    <?php
                                    $grouped = [];
                                    foreach ($salary_grades as $row) {
                                        $grouped[$row->salary_grade][$row->step] = $row->amount;
                                    }
                                    ?>
                                    <?php foreach ($grouped as $sg => $steps): ?>
                                    <tr>
                                        <td class="fw-bold">SG <?= $sg ?></td>

                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                            <td>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control form-control-sm text-end"
                                                    name="salary[<?= $sg ?>][<?= $i ?>]"
                                                    value="<?= isset($steps[$i]) ? $steps[$i] : '' ?>"
                                                    placeholder="-">
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main> <!-- container-fluid -->


<script>
$(document).ready(function() {
    $('#periodTable').DataTable({
        pageLength: 10, 
        lengthMenu: [5, 10, 25, 50],
        order: [[1, 'desc']],
        responsive: true
    });
});

document.querySelectorAll('input[name^="salary"]').forEach(input => {
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
});
</script>


