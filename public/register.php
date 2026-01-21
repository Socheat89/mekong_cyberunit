<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mekong CyberUnit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/Mekong_CyberUnit/public/css/landing.css">
    
    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
            background: #f8fafc;
        }

        .auth-card {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 600px;
            border: 1px solid var(--border-color);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            text-decoration: none;
            color: var(--text-main);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        
        .form-group { margin-bottom: 1.25rem; }
        
        .form-group.full-width { grid-column: span 2; }
        
        .form-group label { 
            display: block; 
            margin-bottom: 0.5rem; 
            font-weight: 500; 
            font-size: 0.9rem;
            color: var(--text-main);
        }
        
        .form-group input { 
            width: 100%; 
            padding: 0.75rem 1rem; 
            border: 1px solid var(--border-color);
            border-radius: 0.5rem; 
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-helper {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            display: block;
        }

        .system-selection {
            margin-top: 2rem;
            margin-bottom: 2rem;
            border-top: 1px solid var(--border-color);
            padding-top: 1.5rem;
        }
        
        .system-selection h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .checkbox-group {
            display: grid;
            gap: 0.75rem;
        }
        
        .checkbox-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .checkbox-card:hover {
            background: #f8fafc;
            border-color: var(--primary);
        }

        .checkbox-card input {
            margin-right: 1rem;
            width: 1.2rem;
            height: 1.2rem;
            accent-color: var(--primary);
        }
        
        .checkbox-card span { font-weight: 500; }
        .checkbox-price { margin-left: auto; color: var(--text-muted); font-size: 0.9rem; }
        
        .btn-full { width: 100%; }
        
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        
        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }
        
        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 0;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 90%;
            max-width: 450px;
            animation: modalFadeIn 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
            line-height: 1;
        }

        .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .qr-code-container {
            margin-bottom: 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            display: inline-block;
            background: white;
        }

        .qr-code-container img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .payment-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .payment-instruction {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            background: #f8fafc;
        }

        .total-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border-color);
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <a href="/Mekong_CyberUnit/index.php" class="auth-logo">
                <div class="logo-icon">
                    <i class="ph-bold ph-cube"></i>
                </div>
                <span>Mekong CyberUnit</span>
            </a>
            <h3>Create Account</h3>
            <p>Complete payment via Bakong to setup your workspace</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="ph-bold ph-warning-circle" style="vertical-align: text-bottom;"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register_process.php" id="registerForm">
            <!-- Plan Selection (Visible First) -->
            <div class="system-selection" id="plan_section">
                <h3>1. Select Plan to Pay</h3>
                <div class="checkbox-group">
                    <label class="checkbox-card" onclick="selectPlan(1, 50, 'starter')">
                        <input type="radio" name="plan_select" value="1" class="plan-radio">
                        <div style="flex:1;">
                            <span>Starter POS</span>
                            <div style="font-size:0.8rem; color:#64748b;">Product Management, Single User, Basic Reports</div>
                        </div>
                        <div class="checkbox-price">$10/mo</div>
                    </label>
                    <label class="checkbox-card" onclick="selectPlan(2, 50, 'professional')">
                        <input type="radio" name="plan_select" value="2" class="plan-radio">
                        <div style="flex:1;">
                            <span>Professional</span>
                            <div style="font-size:0.8rem; color:#64748b;">Inventory, 5 Users</div>
                        </div>
                        <div class="checkbox-price">$50/mo</div>
                    </label>
                    <label class="checkbox-card" onclick="selectPlan(3, 100, 'enterprise')">
                        <input type="radio" name="plan_select" value="3" class="plan-radio">
                        <div style="flex:1;">
                            <span>Enterprise</span>
                            <div style="font-size:0.8rem; color:#64748b;">Unlimited, All Features</div>
                        </div>
                        <div class="checkbox-price">$100/mo</div>
                    </label>
                </div>
                
                <div class="total-display" id="payment_cta" style="display:none; flex-direction:column; gap:10px; border:none; margin-top: 2rem;">
                    <button type="button" class="btn btn-primary btn-full" onclick="showModal()" style="background: #E31E26; border-color: #E31E26;">
                        <i class="ph-bold ph-qr-code"></i> <span id="pay_btn_text">Pay via Bakong</span>
                    </button>
                    <p style="text-align:center; font-size:0.85rem; color:#64748b;">Unlock account creation after payment</p>
                </div>
            </div>

            <!-- Payment Method Selection (Visible for $50 and $100) -->
            <div class="system-selection" id="payment_method_section" style="display: none; border-top: 1px solid var(--border-color); padding-top: 1.5rem; margin-top: 1rem;">
                <h3>2. Select Payment Method</h3>
                <div class="checkbox-group">
                    <label class="checkbox-card method-card" onclick="selectPaymentMethod('bakong')">
                        <input type="radio" name="payment_method" value="bakong" class="method-radio">
                        <div style="flex:1;">
                            <span>Bakong QR</span>
                            <div style="font-size:0.8rem; color:#64748b;">Scan with Bakong or Banking App</div>
                        </div>
                        <div class="checkbox-price">Dynamic</div>
                    </label>
                    <label class="checkbox-card method-card" onclick="selectPaymentMethod('acleda')">
                        <input type="radio" name="payment_method" value="acleda" class="method-radio">
                        <div style="flex:1;">
                            <span>ACLEDA Bank</span>
                            <div style="font-size:0.8rem; color:#64748b;">Scan with ACLEDA Mobile</div>
                        </div>
                        <div class="checkbox-price">Static</div>
                    </label>
                </div>
            </div>

            <!-- Account Details (Hidden until Paid/Approved) -->
            <div id="account_details_section" style="display: none; opacity: 0; transition: opacity 0.5s;">
                <h3 style="margin: 2rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;"><span id="account_step_num">2</span>. Account Details</h3>
                
                <!-- Approval Waiting Status -->
                <div id="approval_waiting" style="display: none; background: #ffffff; border: 1px solid #e2e8f0; padding: 3rem 2rem; border-radius: 1.5rem; text-align: center; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
                    <!-- Background Accent -->
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #0284c7, #38bdf8);"></div>
                    
                    <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 2rem;">
                        <!-- SVG Progress Ring -->
                        <svg width="120" height="120" viewBox="0 0 120 120" style="transform: rotate(-90deg);">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#f1f5f9" stroke-width="8" />
                            <circle id="progress_bar" cx="60" cy="60" r="54" fill="none" stroke="#0284c7" stroke-width="8" 
                                    stroke-dasharray="339.29" stroke-dashoffset="0" stroke-linecap="round" 
                                    style="transition: stroke-dashoffset 1s linear, stroke 0.3s ease;" />
                        </svg>
                        <!-- Center Icon/Text -->
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <div id="timer_sec" style="font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1;">120</div>
                            <div style="font-size: 0.7rem; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">Sec</div>
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 0.75rem; color: #0f172a; font-size: 1.5rem; letter-spacing: -0.025em;">Verifying Payment</h4>
                    <p style="font-size: 1rem; color: #475569; margin-bottom: 2rem; line-height: 1.5;">
                        We've notified the Admin. Please stay on this page.<br>
                        <span style="font-size: 0.85rem; color: #94a3b8;">Automatic verification in progress...</span>
                    </p>
                    
                    <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.9rem; color: #334155;">
                        <span style="width: 8px; height: 8px; background: #0284c7; border-radius: 50%; display: inline-block; animation: pulse 2s infinite;"></span>
                        Ref: <span id="display_ref" style="font-weight: 700; font-family: monospace;"></span>
                    </div>
                </div>

                <style>
                    @keyframes pulse {
                        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7); }
                        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(2, 132, 199, 0); }
                        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(2, 132, 199, 0); }
                    }
                    @keyframes slideDown {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                </style>

                <!-- Success Confirmation Message (Hidden) -->
                <div id="approval_success" style="display: none; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 2rem; border-radius: 1rem; text-align: center; margin-bottom: 2.5rem; animation: slideDown 0.5s ease-out;">
                    <div style="font-size: 3rem; color: #059669; margin-bottom: 1rem;">
                        <i class="ph-bold ph-check-circle"></i>
                    </div>
                    <h4 style="color: #064e3b; margin-bottom: 0.5rem;">Payment Approved!</h4>
                    <p style="color: #065f46; font-size: 0.9rem;">Thank you for your payment. You can now complete your registration below.</p>
                </div>

                <div id="registration_fields" style="display: none;">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" required placeholder="e.g. Acme Corp">
                        </div>

                        <div class="form-group full-width">
                            <label for="subdomain">Workspace URL</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="text" id="subdomain" name="subdomain" required pattern="[a-zA-Z0-9]+" title="Only letters and numbers allowed" placeholder="acme">
                                <span style="color: var(--text-muted); font-size: 0.9rem; white-space: nowrap;">.mekongcyber.com</span>
                            </div>
                            <span class="form-helper">This will be your unique address.</span>
                        </div>

                        <div class="form-group full-width">
                            <label for="admin_email">Work Email</label>
                            <input type="email" id="admin_email" name="admin_email" required placeholder="admin@example.com">
                        </div>

                        <div class="form-group">
                            <label for="admin_username">Username</label>
                            <input type="text" id="admin_username" name="admin_username" required placeholder="jdoe">
                        </div>
                    
                        <div class="form-group">
                            <label for="admin_password">Password</label>
                            <input type="password" id="admin_password" name="admin_password" required minlength="8" placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••">
                        </div>
                        
                        <input type="hidden" name="payment_status" id="payment_status" value="pending">
                        <input type="hidden" name="payment_ref" id="payment_ref" value="">
                        <!-- Hidden inputs for backend compatibility -->
                        <div id="hidden_systems"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full" style="margin-top: 2rem;">Create Account</button>
                </div>
            </div>

            <!-- Removed old system selection -->

            <!-- Submit moved inside gated section -->
        </form>
        
        <div class="auth-footer">
            Already have an account? <a href="/Mekong_CyberUnit/public/login.php">Sign in</a>
        </div>
    </div>

    <!-- Payment Modal (Bakong Branded) -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header" style="background: #E31E26; color: white;">
                <h3 style="font-weight: 600;">
                    <div style="background: white; border-radius: 4px; padding: 2px;">
                        <i class="ph-bold ph-qr-code" style="color: #E31E26;"></i>
                    </div>
                    Scan to Pay (Bakong)
                </h3>
                <button type="button" class="modal-close" onclick="closeModal()" style="color: rgba(255,255,255,0.8);">&times;</button>
            </div>
            <div class="modal-body">
                <div class="payment-amount" id="modalAmount">$0.00</div>
                <div class="payment-instruction">Scan with Bakong or any Banking App</div>
                
                <div class="qr-code-container" style="border-color: #E31E26; min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <div id="qrPlaceholder" style="display: none;">
                         <i class="ph-bold ph-spinner ph-spin" style="font-size: 2rem; color: #E31E26;"></i>
                    </div>
                    <img id="qrImage" src="" alt="KHQR Payment" style="display: none;">
                </div>
                
                <div id="staticNotice" style="margin-top: 1rem; padding: 1rem; background: #ecfdf5; border: 1px solid #d1fae5; border-radius: 0.5rem; text-align: left; display: none;">
                    <p style="font-size: 0.85rem; color: #065f46; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-seal-check"></i>
                        Please click 'I Have Paid' after scanning.
                    </p>
                </div>
                
                <div id="pollingNotice" style="margin-top: 1rem; padding: 1rem; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 0.5rem; text-align: left;">
                    <p style="font-size: 0.85rem; color: #92400e; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-info"></i>
                        Waiting for payment confirmation...
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="padding: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; gap: 1rem; background: #f8fafc;">
                <button type="button" id="confirmBtn" class="btn btn-primary" style="flex: 2; display: none; background: #16a34a; border-color: #16a34a;" onclick="simulateSuccess()">
                    <i class="ph-bold ph-check-circle"></i> I Have Paid
                </button>
                <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // State
        const form = document.getElementById('registerForm');
        const paymentModal = document.getElementById('paymentModal');
        const planSection = document.getElementById('plan_section');
        const accountSection = document.getElementById('account_details_section');
        const hiddenSystems = document.getElementById('hidden_systems');
        const paymentMethodSection = document.getElementById('payment_method_section');
        const payBtnText = document.getElementById('pay_btn_text');
        const accountStepNum = document.getElementById('account_step_num');
        
        let selectedPlan = null;
        let selectedPrice = 0;
        let selectedMethod = 'bakong'; // Default
        let paymentConfirmed = false;

        // Plan Selection
        window.selectPlan = function(planId, price, planCode) {
            // Visual feedback
            const cards = document.querySelectorAll('.plan-radio');
            cards.forEach(input => input.closest('.checkbox-card').style.borderColor = 'var(--border-color)');
            
            const input = document.querySelector(`input[name="plan_select"][value="${planId}"]`);
            if (input) {
                input.checked = true;
                input.closest('.checkbox-card').style.borderColor = '#E31E26';
            }

            selectedPrice = price;
            selectedPlan = planCode;
            
            // Logic for $10 vs $50/$100
            if (price === 10) {
                // $10 Plan: No payment method selection
                paymentMethodSection.style.display = 'none';
                paymentCta.style.display = 'flex';
                selectedMethod = 'bakong'; // Force Bakong for $10
                accountStepNum.textContent = '2';
                payBtnText.textContent = 'Pay via Bakong';
            } else {
                // $50 or $100 Plan: Show payment method selection
                paymentMethodSection.style.display = 'block';
                paymentCta.style.display = 'none'; // Hide until method selected
                accountStepNum.textContent = '3';
                
                // Auto-select Bakong
                selectPaymentMethod('bakong');
            }
        };

        // Payment Method Selection
        window.selectPaymentMethod = function(method) {
            const methodCards = document.querySelectorAll('.method-radio');
            methodCards.forEach(input => input.closest('.checkbox-card').style.borderColor = 'var(--border-color)');
            
            const input = document.querySelector(`input[name="payment_method"][value="${method}"]`);
            if (input) {
                input.checked = true;
                input.closest('.checkbox-card').style.borderColor = '#E31E26';
            }

            selectedMethod = method;
            paymentCta.style.display = 'flex';
            payBtnText.textContent = 'Pay via ' + (method === 'bakong' ? 'Bakong' : 'ACLEDA');
        };

        let currentMd5 = null;
        let isStatic = false;

        window.showModal = async function() {
            if (!selectedPlan || !selectedMethod) return;
            document.getElementById('modalAmount').textContent = '$' + selectedPrice.toFixed(2);
            
            // Update Modal Header branding
            const modalHeader = document.querySelector('.modal-header');
            const modalTitle = modalHeader.querySelector('h3');
            if (selectedMethod === 'acleda') {
                modalHeader.style.background = '#005494'; // ACLEDA Blue
                modalTitle.innerHTML = '<div style="background: white; border-radius: 4px; padding: 2px;"><i class="ph-bold ph-qr-code" style="color: #005494;"></i></div> Scan to Pay (ACLEDA)';
            } else {
                modalHeader.style.background = '#E31E26'; // Bakong Red
                modalTitle.innerHTML = '<div style="background: white; border-radius: 4px; padding: 2px;"><i class="ph-bold ph-qr-code" style="color: #E31E26;"></i></div> Scan to Pay (Bakong)';
            }

            // Reset modal state
            const qrImage = document.getElementById('qrImage');
            const qrPlaceholder = document.getElementById('qrPlaceholder');
            const confirmBtn = document.getElementById('confirmBtn');
            const staticNotice = document.getElementById('staticNotice');
            const pollingNotice = document.getElementById('pollingNotice');

            qrImage.style.display = 'none';
            qrPlaceholder.style.display = 'block';
            confirmBtn.style.display = 'none';
            staticNotice.style.display = 'none';
            pollingNotice.style.display = 'block';
            
            paymentModal.classList.add('active');

            try {
                const response = await fetch(`/Mekong_CyberUnit/public/api/bakong_qr.php?plan=${selectedPlan}&method=${selectedMethod}`);
                const result = await response.json();

                if (result.success) {
                    qrImage.src = result.image;
                    qrImage.style.display = 'block';
                    qrPlaceholder.style.display = 'none';
                    
                    isStatic = result.is_static;
                    
                    if (result.is_static) {
                        confirmBtn.style.display = 'flex';
                        confirmBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
                        confirmBtn.onclick = simulateSuccess;
                        staticNotice.style.display = 'block';
                        pollingNotice.style.display = 'none';
                    } else {
                        // Dynamic (Bakong)
                        currentMd5 = result.md5;
                        
                        // Enable "I Have Paid" button for Dynamic QR as a fallback
                        // This allows users to trigger manual verification (Telegram) if auto-detection is slow
                        confirmBtn.style.display = 'flex';
                        confirmBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
                        confirmBtn.onclick = simulateSuccess;
                        
                        pollingNotice.style.display = 'block';
                        // Start polling for dynamic QR in background
                        startPolling(result.md5);
                    }
                } else {
                    alert('Error generating QR: ' + result.error);
                    closeModal();
                }
            } catch (error) {
                console.error('Payment Error:', error);
                alert('Connection failed. Please try again.');
                closeModal();
            }
        };

        window.checkStatusManual = async function(md5) {
            const btn = document.getElementById('confirmBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Checking...';

            try {
                const response = await fetch(`/Mekong_CyberUnit/public/api/bakong_check.php?md5=${md5}`);
                const result = await response.json();

                if (result.success && result.status === 'SUCCESS') {
                     btn.innerHTML = '<i class="ph-bold ph-check"></i> Paid!';
                     btn.style.background = '#10b981';
                     btn.style.borderColor = '#10b981';
                     clearInterval(pollingInterval);
                     setTimeout(() => {
                         closeModal();
                         unlockForm();
                     }, 500);
                } else {
                    alert('Payment status: ' + (result.status || 'PENDING') + '. Please try again in a moment.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Manual Check Error:', error);
                alert('Connection failed.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        };

        window.simulateSuccess = async function() {
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Sending to Telegram...';
            
            try {
                const response = await fetch('/Mekong_CyberUnit/public/api/notify_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ plan: selectedPlan, amount: selectedPrice })
                });
                const result = await response.json();

                if (result.success) {
                    closeModal();
                    waitForApproval(result.ref);
                } else {
                    alert('Error: ' + result.error);
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
                }
            } catch (error) {
                console.error('Notification Error:', error);
                alert('Connection failed.');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
            }
        };

        function waitForApproval(ref) {
            paymentConfirmed = false;
            document.getElementById('display_ref').textContent = ref;
            document.getElementById('payment_ref').value = ref;
            
            // Show Account Section and Waiting UI
            accountSection.style.display = 'block';
            accountSection.style.opacity = '1';
            document.getElementById('approval_waiting').style.display = 'block';
            document.getElementById('registration_fields').style.display = 'none';
            document.getElementById('approval_success').style.display = 'none';
            planSection.style.opacity = '0.5';
            planSection.style.pointerEvents = 'none';

            let timer = 120;
            const totalTime = 120;
            const timerDisplay = document.getElementById('timer_sec');
            const progressBar = document.getElementById('progress_bar');
            const circumference = 339.29; // 2 * PI * 54

            // Timer Tick
            const timerInterval = setInterval(() => {
                timer--;
                
                // Update text
                timerDisplay.textContent = timer;
                
                // Update Circular Progress
                const offset = circumference - (timer / totalTime) * circumference;
                progressBar.style.strokeDashoffset = offset;

                // Change color based on time left
                if (timer <= 30) {
                    progressBar.style.stroke = "#ef4444";
                    timerDisplay.style.color = "#ef4444";
                } else if (timer <= 60) {
                    progressBar.style.stroke = "#f59e0b";
                    timerDisplay.style.color = "#f59e0b";
                }

                if (timer <= 0) {
                    clearInterval(timerInterval);
                    alert('Time expired. Please contact support if you have already paid.');
                    location.reload();
                }
            }, 1000);

            // Poll for approval every 3 seconds
            const pollInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/Mekong_CyberUnit/public/api/check_approval.php?ref=${ref}`);
                    const result = await response.json();

                    if (result.success) {
                        if (result.status === 'approved') {
                            clearInterval(timerInterval);
                            clearInterval(pollInterval);
                            
                            // Visual Success Feedback
                            progressBar.style.stroke = "#10b981";
                            progressBar.style.strokeDashoffset = 0;
                            timerDisplay.innerHTML = '<i class="ph-bold ph-check" style="font-size: 2rem; color: #10b981;"></i>';
                            
                            setTimeout(() => {
                                document.getElementById('approval_waiting').style.transition = 'all 0.5s ease';
                                document.getElementById('approval_waiting').style.opacity = '0';
                                document.getElementById('approval_waiting').style.transform = 'scale(0.9)';
                                
                                setTimeout(() => {
                                    document.getElementById('approval_waiting').style.display = 'none';
                                    document.getElementById('approval_success').style.display = 'block';
                                    unlockForm();
                                }, 500);
                            }, 1000);
                        } else if (result.status === 'rejected') {
                            clearInterval(timerInterval);
                            clearInterval(pollInterval);
                            alert('Your payment was rejected. Please contact support.');
                            location.reload();
                        }
                    }
                } catch (error) {
                    console.error('Poll Error:', error);
                }
            }, 3000);

            accountSection.scrollIntoView({ behavior: 'smooth' });
        }

        async function syncTelegram() {
            try {
                const response = await fetch('/Mekong_CyberUnit/public/api/sync_telegram.php');
                const text = await response.text();
                console.log('Sync Result:', text);
                // The polling interval will handle the redirect if successful
            } catch (error) {
                console.error('Sync Error:', error);
            }
        }

        window.closeModal = function() {
            paymentModal.classList.remove('active');
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        };

        let pollingInterval = null;
        function startPolling(md5) {
            if (pollingInterval) clearInterval(pollingInterval);
            
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/Mekong_CyberUnit/public/api/bakong_check.php?md5=${md5}`);
                    const result = await response.json();

                    if (result.success && result.status === 'SUCCESS') {
                        clearInterval(pollingInterval);
                        setTimeout(() => {
                            closeModal();
                            unlockForm();
                        }, 800);
                    }
                } catch (error) {
                    console.error('Polling Error:', error);
                }
            }, 3000); // Check every 3 seconds
        }
        
        function unlockForm() {
            paymentConfirmed = true;
            document.getElementById('payment_status').value = 'paid';
            
            // Hide waiting UI, show fields
            document.getElementById('approval_waiting').style.display = 'none';
            document.getElementById('registration_fields').style.display = 'block';

            // Hide Plan Section or Mark as done
            planSection.style.opacity = '0.5';
            planSection.style.pointerEvents = 'none';
            
            // Show Account Section
            accountSection.style.display = 'block';
            accountSection.style.opacity = '1';
            
            // Populate hidden systems based on plan
            populateHiddenSystems(selectedPlan);
            
            // Scroll to form
            document.getElementById('registration_fields').scrollIntoView({ behavior: 'smooth' });
        }

        function populateHiddenSystems(plan) {
            hiddenSystems.innerHTML = '';
            let sys = [];
            if (plan === 'starter') sys = [1];
            if (plan === 'professional') sys = [1, 2];
            if (plan === 'enterprise') sys = [1, 2, 3];
            
            sys.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'systems[]';
                input.value = id;
                hiddenSystems.appendChild(input);
            });
        }

        // Initialize
        function init() {
            const urlParams = new URLSearchParams(window.location.search);
            const plan = urlParams.get('plan');
            const paid = urlParams.get('paid');
            const ref = urlParams.get('ref');
            
            if (plan && ref) {
                // Process with approval request
                let pid = 1;
                let price = 10;
                if (plan === 'professional') { pid=2; price=50; }
                if (plan === 'enterprise') { pid=3; price=100; }
                
                selectPlan(pid, price, plan);
                waitForApproval(ref);
            } else if (plan && paid === 'true') {
                // Just select
                let pid = 1;
                let price = 10;
                if (plan === 'professional') { pid=2; price=50; }
                if (plan === 'enterprise') { pid=3; price=100; }
                selectPlan(pid, price, plan);
                
                // Unlock form immediately if paid
                document.getElementById('payment_cta').style.display = 'none';
                document.getElementById('payment_method_section').style.display = 'none';
                unlockForm();
        }
        
        // Form Submit
        form.addEventListener('submit', function(e) {
            // Basic validation
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('confirm_password').value;
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
        
        init();
    </script>
</body>
</html>