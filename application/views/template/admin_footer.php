<script>
    // Sidebar Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Running Clock
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
        document.getElementById('runningClock').textContent = now.toLocaleDateString(undefined, options) + ' | ' + now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

<?php if($this->session->flashdata('success')): ?>
    <script>Swal.fire({ icon: 'success', title: 'Success', text: '<?= $this->session->flashdata('success') ?>', timer: 2500, showConfirmButton: false });</script>
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
    <script>Swal.fire({ icon: 'error', title: 'Error', html: '<?= nl2br($this->session->flashdata('error')) ?>' });</script>
<?php endif; ?>

</body>
</html>