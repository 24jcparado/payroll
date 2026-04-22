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

    <style>
        :root { --evsu-red: #6b0f1a; --evsu-gold: #c99700; }
        body { background-color: #f4f7f6; overflow-x: hidden; font-family: 'Inter', sans-serif; }

        /* Sidebar Persistence */
        #sidebar {
            height: 100vh; position: fixed; top: 0; left: 0; width: 260px;
            background-color: var(--evsu-red); z-index: 1050; transition: all 0.3s;
            overflow-y: auto; scrollbar-width: thin;
        }

        #mainContent { margin-left: 260px; transition: all 0.3s; min-height: 100vh; }

        /* KPI Card Enhancements */
        .kpi-card { border-left: 5px solid var(--evsu-gold); border-radius: 12px; transition: transform 0.2s; }
        .kpi-card:hover { transform: translateY(-3px); }
        .icon-shape { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }

        /* Responsive Utilities */
        @media (max-width: 992px) {
            #sidebar { left: -260px; }
            #sidebar.active { left: 0; }
            #mainContent { margin-left: 0 !important; }
            .overlay.active { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040; }
        }

        .x-small { font-size: 0.75rem; }
        .hover-lift:hover { box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; transform: translateY(-3px); transition: 0.2s; }

        /* Sidebar Link Styling */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important; /* Force off-white color */
            padding: 12px 18px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        /* Hover State */
        .sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1); /* Subtle white highlight */
            border-radius: 8px;
            margin: 0 10px; /* Gives it a "pill" look on hover */
        }

        /* Active/Selected State */
        .sidebar .nav-link.active {
            color: #ffffff !important;
            background-color: #8b1e2d !important; /* Lighter maroon for active */
            font-weight: 600;
        }

        /* Icon Color Sync */
        .sidebar .nav-link i {
            margin-right: 10px;
            color: inherit; /* Icons will now match the text color automatically */
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay"></div>

<?php $this->load->view('template/admin_nav')?>