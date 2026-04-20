<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payroll Admin Dashboard</title>
<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    body {
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Sidebar */

    .sidebar .nav-link {
        color: #fff;
        padding: 12px 18px;
        font-size: 15px;
    }
    .sidebar .nav-link:hover {
        background-color: #8b1e2d;
    }
    .sidebar .nav-link i {
        margin-right: 8px;
    }

    /* Sidebar scroll */
    #sidebar {
        height: 100vh;           /* Full viewport height */
        overflow-y: auto;        /* Enable vertical scrolling */
        position: fixed;         /* Keeps sidebar fixed while content scrolls */
        top: 0;
        left: 0;
        width: 250px;            /* Adjust width as needed */
        background-color: #6b0f1a; /* Sidebar background */
        padding-bottom: 1rem;    /* Extra padding for scroll */
        transition: left 0.3s;
            z-index: 1050;
            color: #fff;
    }

    /* Optional: Smooth scrolling */
    #sidebar {
        scroll-behavior: smooth;
    }

    /* Overlay for mobile */
    .overlay {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        top: 0;
        left: 0;
        z-index: 1040;
    }

    /* Topbar */
    .topbar {
        background-color: #ffffff;
        border-bottom: 3px solid #c99700;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    /* KPI Cards */
    .kpi-card {
        border-left: 5px solid #c99700;
    }
    .net-pay {
        font-size: 20px;
        font-weight: bold;
        color: #6b0f1a;
    }

    /* Buttons */
    .btn-maroon {
        background-color: #6b0f1a;
        color: #fff;
    }
    .btn-maroon:hover {
        background-color: #8b1e2d;
        color: #fff;
    }
    .btn-yellow {
        background-color: #c99700;
        color: #000;
    }
    .btn-yellow:hover {
        background-color: #e0ad00;
        color: #000;
    }

    /* Main content */
    #mainContent {
        margin-left: 250px;
        padding: 20px;
        transition: margin-left 0.3s;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
        }
        .sidebar.active {
            left: 0;
        }
        .overlay.active {
            display: block;
        }
        #mainContent {
            margin-left: 0 !important;
        }
    }

    .birthday-banner {
        background: linear-gradient(135deg, #551c1c, #a52a2a);
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1);
    }


.dashboard-card {
        height: 260px;          
        overflow-y: auto;        
    }

    .dashboard-card::-webkit-scrollbar {
        width: 6px;
    }

    .dashboard-card::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.2);
        border-radius: 4px;
    }
    .select2-container .select2-selection--single {
        height: 31px;
        padding: 2px 6px;
        font-size: 0.875rem;
    }

    .select2-selection__arrow {
        height: 31px;
    }

    .payroll-vertical {
    display: flex;
    flex-direction: column;
    gap: 2px;
    position: relative;
}

.v-step {
    display: flex;
    align-items: flex-start;
    position: relative;
}

.v-indicator {
    position: relative;
    width: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.v-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #ced4da;
    background: #fff;
    color: #6c757d;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.v-line {
    width: 2px;
    height: 30px;
    background: #ced4da;
    margin-top: 2px;
}

.v-content {
    padding-top: 3px;
}

.v-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .3px;
}

/* Completed */
.v-step.completed .v-circle {
    background: #198754;
    border-color: #198754;
    color: #fff;
}

.v-step.completed .v-line {
    background: #198754;
}

/* Current */
.v-step.current .v-circle {
    border-color: #0d6efd;
    color: #0d6efd;
    font-weight: 700;
}
</style>
</head>
<body>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>
<?php $this->load->view('template/receiver_nav')?>
