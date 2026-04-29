<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandatory Password Reset | EVSU HRIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f4f7fe;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-card {
            max-width: 450px;
            width: 100%;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .reset-header {
            background-color: #800000; /* EVSU Maroon */
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .btn-maroon {
            background-color: #800000;
            color: white;
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-maroon:hover {
            background-color: #5c0000;
            color: white;
        }
        .form-control:focus {
            border-color: #800000;
            box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.25);
        }
        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container px-3">
        <div class="card reset-card mx-auto">
            <div class="reset-header">
                <i class="bi bi-shield-lock-fill display-4 mb-2"></i>
                <h4 class="fw-bold mb-0">Update Password</h4>
                <p class="text-white-50 small mt-2 mb-0">For security reasons, you must change the default password provided by the administrator.</p>
            </div>
            
            <div class="card-body p-4 p-md-5">
                
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger border-0 small">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('info')): ?>
                    <div class="alert alert-info border-0 small bg-light text-dark border-start border-4 border-info">
                        <i class="bi bi-info-circle-fill text-info me-2"></i><?= $this->session->flashdata('info') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('welcome/process_force_reset') ?>" method="POST" id="resetForm">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="At least 8 characters" required minlength="8">
                            <span class="input-group-text bg-light toggle-password" data-target="new_password">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-check-circle"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Type it again" required minlength="8">
                            <span class="input-group-text bg-light toggle-password" data-target="confirm_password">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <div id="passwordMatchText" class="small mt-2" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-maroon w-100" id="submitBtn">Save New Password</button>
                    
                    <div class="text-center mt-3">
                        <a href="<?= base_url('welcome') ?>" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle Password Visibility
            $('.toggle-password').click(function() {
                let targetId = $(this).data('target');
                let input = $('#' + targetId);
                let icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });

            // Live Password Match Checker
            $('#new_password, #confirm_password').on('keyup', function() {
                let pass = $('#new_password').val();
                let conf = $('#confirm_password').val();
                let msg = $('#passwordMatchText');

                if (conf.length > 0) {
                    msg.show();
                    if (pass === conf) {
                        msg.html('<span class="text-success"><i class="bi bi-check-lg me-1"></i>Passwords match</span>');
                        $('#submitBtn').prop('disabled', false);
                    } else {
                        msg.html('<span class="text-danger"><i class="bi bi-x-lg me-1"></i>Passwords do not match</span>');
                        $('#submitBtn').prop('disabled', true);
                    }
                } else {
                    msg.hide();
                }
            });
        });
    </script>
</body>
</html>