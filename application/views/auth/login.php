<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVSU Payroll System Portal</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --evsu-red: #7b1113;
            --evsu-dark: #5e0d0f;
            --text-main: #1a1a1a;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .portal-wrapper {
            display: flex;
            width: 100%;
            max-width: 1100px;
            height: 85vh;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            margin: 20px;
        }

        /* --- ENHANCED BRANDING SIDE --- */
        .branding-side {
            flex: 1.2;
            position: relative;
            background: var(--evsu-red);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
            overflow: hidden;
        }

        /* Subtle Background Pattern */
        .branding-side::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80') center/cover;
            opacity: 0.15;
            mix-blend-mode: overlay;
            z-index: 1;
        }

        /* --- THE ENHANCED BLOBS --- */
        .moving-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.5;
            z-index: 2;
            mix-blend-mode: screen;
            pointer-events: none;
        }

        .blob-1 {
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0) 70%);
            top: -15%; left: -10%;
            animation: organic1 15s infinite alternate ease-in-out;
        }

        .blob-2 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, #ff8e8e 0%, rgba(255,142,142,0) 70%);
            bottom: -10%; right: -5%;
            animation: organic2 12s infinite alternate ease-in-out;
        }

        .blob-3 {
            width: 250px; height: 250px;
            background: radial-gradient(circle, #ffd93d 0%, rgba(255,217,61,0) 70%);
            top: 20%; right: 10%;
            animation: organic3 10s infinite alternate ease-in-out;
        }

        @keyframes organic1 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(15%, 10%) scale(1.1); }
            100% { transform: translate(-5%, 20%) scale(0.9); }
        }

        @keyframes organic2 {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-20%, -15%) rotate(120deg) scale(1.2); }
        }

        @keyframes organic3 {
            0% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.4); opacity: 0.6; }
            100% { transform: scale(1); opacity: 0.3; }
        }

        /* Glass Text Container */
        .brand-content {
            position: relative;
            z-index: 10;
            backdrop-filter: blur(4px);
            background: rgba(255, 255, 255, 0.03);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-logo {
            width: 90px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
            margin-bottom: 25px;
        }

        .brand-content h1 {
            font-weight: 800;
            font-size: 3.5rem;
            letter-spacing: -2px;
            margin-bottom: 10px;
        }

        /* --- FORM SIDE --- */
        .form-side {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            z-index: 3;
        }

        .section-title { font-weight: 800; color: var(--evsu-red); font-size: 1.75rem; margin-bottom: 8px; }
        .section-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 35px; }

        .form-control {
            height: 52px;
            background-color: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px 18px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--evsu-red);
            box-shadow: 0 10px 20px -10px rgba(123, 17, 19, 0.2);
        }

        .btn-evsu {
            background: var(--evsu-red);
            color: white;
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(123, 17, 19, 0.2);
        }

        .btn-evsu:hover {
            background: var(--evsu-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 17, 19, 0.3);
            color: white;
        }

        .switch-link {
            color: var(--evsu-red);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .switch-link:hover { background: rgba(123, 17, 19, 0.05); }

        @media (max-width: 991px) {
            .portal-wrapper { flex-direction: column; height: auto; }
            .branding-side { padding: 50px 30px; }
            .form-side { padding: 40px 30px; }
        }

        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="portal-wrapper">
    <div class="branding-side">
        <div class="moving-blob blob-1"></div>
        <div class="moving-blob blob-2"></div>
        <div class="moving-blob blob-3"></div>
        
        <div class="brand-content">
            <img src="<?= base_url('assets/img/favicon.png') ?>" class="brand-logo" alt="EVSU Logo">
            <h1>EVSU</h1>
            <p class="lead fw-normal opacity-75">Human Resource and Financial Management System</p>
        </div>
    </div>

    <div class="form-side">
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div id="loginSection" class="fade-in">
            <h2 class="section-title">Personnel Portal</h2>
            <p class="section-subtitle">Sign in to your authorized account</p>

            <form method="post" action="<?= base_url('welcome/login') ?>">
                <input type="hidden" name="reg_token" value="<?= $reg_token ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Institutional Email</label>
                    <input type="email" name="email" class="form-control" placeholder="name@evsu.edu.ph" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-evsu w-100 mb-4">Sign In</button>
            </form>

            <div class="text-center">
                <button class="switch-link d-block w-100 mb-2 border-0 bg-transparent" onclick="showReceiverLogin()">
                    <i class="bi bi-person-badge me-1"></i> Login as Payroll Receiver
                </button>
                <button class="switch-link d-block w-100 border-0 bg-transparent" onclick="showVerifySection()">
                    <i class="bi bi-shield-check me-1"></i> Payroll Verification Portal
                </button>
            </div>
        </div>

        <div id="verifySection" class="fade-in" style="display:none;">
            <h2 class="section-title">Verify Payroll</h2>
            <p class="section-subtitle">Authenticate records via Token or QR</p>
            <div class="d-grid gap-2 mb-4">
                <button class="btn btn-outline-dark border-2 py-2 fw-bold" onclick="showTokenInput()">
                    <i class="bi bi-keyboard me-2"></i> Manual Token ID
                </button>
                <button class="btn btn-success border-0 py-2 fw-bold" onclick="showScanner()">
                    <i class="bi bi-qr-code-scan me-2"></i> Scan QR Code
                </button>
            </div>
            <div id="tokenForm" style="display:none;" class="fade-in">
                <form method="post" action="<?= base_url('verify/verify_token') ?>">
                    <input type="text" name="token_id" class="form-control text-center text-uppercase mb-3" placeholder="XXX-XXX-XXX" maxlength="11" required>
                    <button class="btn btn-evsu w-100">Verify Now</button>
                </form>
            </div>
            <div id="qrScanner" style="display:none;" class="fade-in">
                <div id="reader" style="width:100%; border-radius: 12px; overflow: hidden;"></div>
            </div>
            <div class="text-center mt-4">
                <button class="switch-link border-0 bg-transparent" onclick="showLoginSection()">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </button>
            </div>
        </div>

        <div id="receiverSection" class="fade-in" style="display:none;">
            <h2 class="section-title">Receiver Access</h2>
            <p class="section-subtitle">Secure login for payroll distribution</p>
            <form method="post" action="<?= base_url('welcome/receiver_login') ?>">
                <input type="hidden" name="receiver_token" value="<?= $receiver_token ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-evsu w-100 mb-3">Login as Receiver</button>
            </form>
            <div class="text-center mt-3">
                <button class="switch-link border-0 bg-transparent" onclick="showLoginSection()">
                    <i class="bi bi-arrow-left me-1"></i> Return to Main Portal
                </button>
            </div>
        </div>

        <div class="mt-auto pt-4 text-center">
            <small class="text-muted opacity-50">&copy; <?= date('Y') ?> Eastern Visayas State University</small>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // Navigation Logic
    function showVerifySection() { toggleSections('verifySection'); }
    function showLoginSection() { toggleSections('loginSection'); }
    function showReceiverLogin() { toggleSections('receiverSection'); }

    function toggleSections(id) {
        ['loginSection', 'verifySection', 'receiverSection'].forEach(sec => {
            document.getElementById(sec).style.display = (sec === id) ? 'block' : 'none';
        });
    }

    function showTokenInput() {
        document.getElementById('tokenForm').style.display = 'block';
        document.getElementById('qrScanner').style.display = 'none';
    }

    function showScanner() {
        document.getElementById('tokenForm').style.display = 'none';
        document.getElementById('qrScanner').style.display = 'block';
        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            qrCodeMessage => { window.location.href = "<?= base_url('payroll/verify_token/') ?>" + qrCodeMessage; },
            errorMessage => {}
        ).catch(err => { alert("Camera access denied."); });
    }
</script>

</body>
</html>