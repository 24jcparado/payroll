<!-- Scripts -->
<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString(undefined, options);
        const timeStr = now.toLocaleTimeString();
        document.getElementById('runningClock').textContent = dateStr + ' | ' + timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock(); // initial call
</script>
 <?php if($this->session->flashdata('success')): ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $this->session->flashdata('success') ?>',
            timer: 2500,
            showConfirmButton: false
        });
        </script>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '<?= nl2br($this->session->flashdata('error')) ?>',
        });
        </script>3
    <?php endif; ?>
    
</body>
</html>