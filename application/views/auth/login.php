<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVSU Payroll Portal | Secure Access</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --evsu-red: #7b1113;
            --evsu-accent: #ff4d4d;
            --glass: rgba(255, 255, 255, 0.9);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #4a0a0b;
            /* Modern Animated Mesh Gradient */
            background-image: 
                radial-gradient(at 0% 0%, rgba(123, 17, 19, 0.8) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(255, 77, 77, 0.3) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(255, 217, 61, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(0, 0, 0, 0.4) 0px, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }

        /* Ambient Floating Bubbles */
        .bubble {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            z-index: -1;
            opacity: 0.3;
            animation: float 20s infinite alternate ease-in-out;
        }
        .b1 { width: 400px; height: 400px; background: var(--evsu-accent); top: -10%; left: -5%; }
        .b2 { width: 300px; height: 300px; background: #ffd93d; bottom: -5%; right: -5%; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 100px) scale(1.2); }
        }

        /* Main Glass Card */
        .main-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 90%;
            max-width: 440px;
            border-radius: 35px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            position: relative;
            z-index: 10;
        }

        .brand-logo {
            width: 80px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
            transition: 0.5s transform cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .main-card:hover .brand-logo { transform: scale(1.1) rotate(5deg); }

        .brand-name {
            font-weight: 800;
            color: var(--evsu-red);
            font-size: 1.8rem;
            letter-spacing: -1px;
        }

        /* Form Styling */
        .form-label {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #555;
            margin-left: 5px;
        }

        .form-control {
            height: 55px;
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 0 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--evsu-red);
            box-shadow: 0 10px 20px rgba(123, 17, 19, 0.1);
            transform: translateY(-2px);
        }

        /* Password Strength Meter */
        .strength-meter {
            height: 4px;
            width: 0%;
            background: #ddd;
            border-radius: 2px;
            margin-top: -12px;
            margin-bottom: 20px;
            transition: 0.3s all;
        }

        /* Buttons */
        .btn-evsu {
            background: var(--evsu-red);
            color: white;
            height: 55px;
            border-radius: 16px;
            font-weight: 700;
            border: none;
            width: 100%;
            position: relative;
            overflow: hidden;
            transition: 0.3s all;
        }

        .btn-evsu:hover {
            background: #5e0d0f;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(123, 17, 19, 0.3);
            color: white;
        }

        /* Action Grid */
        .action-tile {
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 15px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s all;
            text-decoration: none;
            color: #333;
        }

        .action-tile:hover {
            background: #fff;
            border-color: var(--evsu-red);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .back-btn {
            background: none;
            border: none;
            color: #888;
            font-weight: 700;
            font-size: 0.85rem;
            margin-top: 20px;
            width: 100%;
        }

        /* Utilities */
        .section-animate { animation: slideUp 0.5s ease-out forwards; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .caps-warning {
            display: none;
            color: var(--evsu-red);
            font-size: 0.7rem;
            font-weight: 800;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="bubble b1"></div>
    <div class="bubble b2"></div>

    <main class="main-card">
        <header class="text-center mb-4">
            <img src="<?= base_url('assets/img/favicon.png') ?>" class="brand-logo mb-2" alt="Logo">
            <h1 class="brand-name">EVSU PAYROLL</h1>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <span class="badge bg-dark rounded-pill">Secure Portal</span>
                <span class="badge bg-success rounded-pill"><i class="bi bi-shield-check"></i> Encrypted</span>
            </div>
        </header>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 py-3 small mb-4 shadow-sm section-animate" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2"></i> <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div id="loginSection" class="section-animate">
            <form id="mainLoginForm" method="post" action="<?= base_url('welcome/login') ?>">
                <input type="hidden" name="reg_token" value="<?= $reg_token ?>">
                
                <div class="mb-3">
                    <label class="form-label">Organizational Email</label>
                    <input type="email" name="email" class="form-control" placeholder="name@evsu.edu.ph" required>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label">Secure Password</label>
                    <input type="password" name="password" id="passInput" class="form-control" placeholder="••••••••" required>
                    <div id="strengthMeter" class="strength-meter"></div>
                    <div id="capsLockNotice" class="caps-warning"><i class="bi bi-capslock-fill"></i> CAPS LOCK ACTIVE</div>
                </div>

                <button type="submit" class="btn btn-evsu" id="signInBtn">
                    <span class="btn-text">Sign In to Dashboard</span>
                </button>
            </form>

            <div class="row g-3 mt-3">
                <div class="col-6">
                    <div class="action-tile" onclick="showReceiverLogin()">
                        <i class="bi bi-person-badge fs-4 text-danger"></i>
                        <div class="small fw-bold mt-1">Receiver</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="action-tile" onclick="showVerifySection()">
                        <i class="bi bi-qr-code-scan fs-4 text-primary"></i>
                        <div class="small fw-bold mt-1">Verify</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="receiverSection" class="section-animate" style="display:none;">
            <div class="text-center mb-4">
                <h5 class="fw-800 text-danger">Receiver Access</h5>
                <p class="text-muted small">Authorization required for disbursement access.</p>
            </div>
            <form method="post" action="<?= base_url('welcome/receiver_login') ?>">
                <div class="mb-3">
                    <label class="form-label">Receiver ID</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Access Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-evsu">Verify Credentials</button>
            </form>
            <button class="back-btn" onclick="showLoginSection()"><i class="bi bi-arrow-left"></i> Back to Primary Login</button>
        </div>

        <div id="verifySection" class="section-animate" style="display:none;">
            <h5 class="text-center fw-800 mb-4">Payroll Verification</h5>
            <div class="d-grid gap-3">
                <button class="btn btn-outline-dark border-2 py-3 rounded-4 fw-bold" onclick="showTokenInput()">
                    <i class="bi bi-keyboard me-2"></i> Manual Token Entry
                </button>
                <button class="btn btn-success border-0 py-3 rounded-4 fw-bold shadow-sm" onclick="showScanner()">
                    <i class="bi bi-qr-code-scan me-2"></i> Open QR Scanner
                </button>
            </div>

            <div id="tokenForm" class="mt-4 section-animate" style="display:none;">
                <form method="post" action="<?= base_url('verify/verify_token') ?>">
                    <input type="text" id="token_id" name="token_id" class="form-control text-center text-uppercase fs-5" placeholder="XXX-XXX-XXX" maxlength="11" required>
                    <button class="btn btn-evsu mt-3">Validate Record</button>
                </form>
            </div>

            <div id="qrScanner" class="mt-4 section-animate" style="display:none;">
                <div id="reader" style="width:100%; border-radius: 16px; overflow:hidden;"></div>
            </div>

            <button class="back-btn" onclick="showLoginSection()"><i class="bi bi-arrow-left"></i> Back to Primary Login</button>
        </div>

        <footer class="text-center mt-5">
            <p class="small text-muted mb-0">&copy; <?= date('Y') ?> EVSU Management System</p>
            <div class="opacity-50 small mt-1">
                <i class="bi bi-cpu"></i> v3.0 | <i class="bi bi-shield-lock"></i> AES-256
            </div>
        </footer>
    </main>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        // Section Navigation
        function toggleSections(id) {
            ['loginSection', 'verifySection', 'receiverSection'].forEach(sec => {
                document.getElementById(sec).style.display = (sec === id) ? 'block' : 'none';
            });
        }
        function showVerifySection() { toggleSections('verifySection'); }
        function showLoginSection() { toggleSections('loginSection'); }
        function showReceiverLogin() { toggleSections('receiverSection'); }
        function showTokenInput() { 
            document.getElementById('tokenForm').style.display = 'block';
            document.getElementById('qrScanner').style.display = 'none';
        }

        // Security Feature: Password Strength & Caps Lock
        const passInput = document.getElementById('passInput');
        const meter = document.getElementById('strengthMeter');
        const capsNotice = document.getElementById('capsLockNotice');

        passInput.addEventListener('input', function() {
            let val = this.value;
            let score = 0;
            if (val.length > 6) score += 30;
            if (val.match(/[A-Z]/)) score += 35;
            if (val.match(/[0-9]/)) score += 35;
            
            meter.style.width = score + "%";
            meter.style.background = score < 50 ? "#ff4d4d" : (score < 100 ? "#ffd93d" : "#2ecc71");
        });

        passInput.addEventListener('keyup', function(e) {
            capsNotice.style.display = e.getModifierState('CapsLock') ? 'block' : 'none';
        });

        // UI Feature: Token Auto-Formatting (XXX-XXX-XXX)
        document.getElementById('token_id').addEventListener('input', function(e) {
            let val = e.target.value.replace(/[^A-Z0-9]/gi, '').toUpperCase();
            if (val.length > 3 && val.length <= 6) val = val.slice(0,3) + '-' + val.slice(3);
            if (val.length > 6) val = val.slice(0,3) + '-' + val.slice(3,6) + '-' + val.slice(6,9);
            e.target.value = val;
        });

        // UI Feature: Submit Loading State
        document.getElementById('mainLoginForm').addEventListener('submit', function() {
            const btn = document.getElementById('signInBtn');
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Encrypting Session...`;
            btn.style.opacity = "0.7";
            btn.style.pointerEvents = "none";
        });

        // QR Scanner Logic
        function showScanner() {
            document.getElementById('tokenForm').style.display = 'none';
            document.getElementById('qrScanner').style.display = 'block';
            const html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                msg => { window.location.href = "<?= base_url('payroll/verify_token/') ?>" + msg; }
            ).catch(err => { console.error(err); });
        }
    </script>
</body>
</html>