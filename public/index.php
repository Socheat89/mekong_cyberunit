<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mekong CyberUnit | Unified Business Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/Mekong_CyberUnit/public/css/landing.css">
    
    <style>
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
            border-bottom: 1px solid #e2e8f0;
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
            color: #64748b;
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
    </style>
    
    <!-- Icons (Phosphor Icons for a premium look) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    
    <!-- Header -->
    <header class="main-header">
        <div class="container nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="ph-bold ph-cube"></i>
                </div>
                <span>Mekong CyberUnit</span>
            </a>
            
            <nav class="nav-links">
                <a href="#systems" class="nav-item">Solutions</a>
                <a href="#features" class="nav-item">Features</a>
                <a href="#pricing" class="nav-item">Pricing</a>
            </nav>
            
            <div class="flex items-center gap-4">
                <a href="/Mekong_CyberUnit/public/login.php" class="nav-item">Sign In</a>
                <a href="/Mekong_CyberUnit/public/register.php" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">Get Started</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-pill">
                <i class="ph-fill ph-sparkle" style="margin-right: 6px;"></i> v2.0 is now live
            </div>
            <h1>
                Run your entire business on <br>
                <span class="gradient-text">One Unified Platform</span>
            </h1>
            <p>
                Stop juggling multiple disjointed subscriptions. Access POS, Inventory, HR, and Accounting in a single, seamless operating system designed for modern growth.
            </p>
            <div class="btn-group">
                <a href="/Mekong_CyberUnit/public/register.php" class="btn btn-primary">
                    Start Free Trial <i class="ph-bold ph-arrow-right" style="margin-left: 8px;"></i>
                </a>
                <a href="#systems" class="btn btn-outline">Explore Systems</a>
            </div>
            
            <!-- Dashboard Preview / Mockup would go here -->
            <div style="margin-top: 4rem; position: relative;">
                <div style="background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; overflow: hidden; max-width: 1000px; margin: 0 auto; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                    <div style="text-align: center; color: #94a3b8;">
                         <!-- Abstract UI Representation -->
                         <div style="display: grid; grid-template-columns: 200px 1fr; gap: 0; height: 100%; width: 100%; text-align: left;">
                             <div style="background: #1e293b; padding: 20px;">
                                 <div style="height: 30px; width: 30px; background: rgba(255,255,255,0.1); border-radius: 8px; margin-bottom: 30px;"></div>
                                 <div style="height: 10px; width: 60%; background: rgba(255,255,255,0.1); border-radius: 4px; margin-bottom: 15px;"></div>
                                 <div style="height: 10px; width: 80%; background: rgba(255,255,255,0.1); border-radius: 4px; margin-bottom: 15px;"></div>
                                 <div style="height: 10px; width: 70%; background: rgba(255,255,255,0.1); border-radius: 4px; margin-bottom: 15px;"></div>
                             </div>
                             <div style="background: white; padding: 30px;">
                                 <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
                                     <div style="height: 20px; width: 200px; background: #f1f5f9; border-radius: 6px;"></div>
                                     <div style="height: 30px; width: 100px; background: #2563eb; border-radius: 6px;"></div>
                                 </div>
                                 <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                                     <div style="height: 120px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;"></div>
                                     <div style="height: 120px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;"></div>
                                     <div style="height: 120px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;"></div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Systems Section -->
    <section class="systems-section" id="systems">
        <div class="container">
            <div class="section-header">
                <h2>Modular Systems for Every Need</h2>
                <p>Choose the modules you need now and add more as you grow.</p>
            </div>
            
            <div class="systems-grid">
                <!-- POS System -->
                <div class="system-card">
                    <div class="system-icon">
                        <i class="ph-duotone ph-cash-register"></i>
                    </div>
                    <h3 class="system-title">Cloud POS</h3>
                    <p class="system-desc">
                        Lightning fast point of sale with offline mode, receipt printing, and barcode scanning support.
                    </p>
                    <div class="price-tag">
                        <span class="price-amount">From $10</span>
                        <span class="price-period">/month</span>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="system-card">
                    <div class="system-icon">
                        <i class="ph-duotone ph-package"></i>
                    </div>
                    <h3 class="system-title">Smart Inventory</h3>
                    <p class="system-desc">
                        Real-time stock tracking, automated reorder alerts, and supplier management.
                    </p>
                    <div class="price-tag">
                        <span class="price-amount">$30</span>
                        <span class="price-period">/month</span>
                    </div>
                </div>

                <!-- HR -->
                <div class="system-card">
                    <div class="system-icon">
                        <i class="ph-duotone ph-users-three"></i>
                    </div>
                    <h3 class="system-title">HR & Payroll</h3>
                    <p class="system-desc">
                        Manage employee attendance, leave requests, and process payroll in one click.
                    </p>
                    <div class="price-tag">
                        <span class="price-amount">$40</span>
                        <span class="price-period">/month</span>
                    </div>
                </div>
                
                 <!-- CRM (Future) -->
                 <div class="system-card" style="opacity: 0.7; background: #f8fafc;">
                    <div class="system-icon" style="background: #e2e8f0; color: #94a3b8;">
                        <i class="ph-duotone ph-chart-line-up"></i>
                    </div>
                    <h3 class="system-title">Advanced CRM</h3>
                    <p class="system-desc">
                        Customer loyalty programs, marketing automation, and sales tracking.
                    </p>
                    <div class="price-tag">
                        <span class="price-period" style="font-weight: 600; color: var(--secondary);">Coming Soon</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cloud POS Pricing Section -->
    <section class="pricing-section" id="pricing" style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="section-header">
                <div class="hero-pill" style="margin-bottom: 1rem;">Cloud POS Plans</div>
                <h2>Simple, Transparent Pricing</h2>
                <p>Choose the right plan for your business needs.</p>
            </div>
            
            <div class="systems-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <!-- Starter Plan -->
                <div class="system-card" style="border-top: 4px solid var(--border-color);">
                    <h3 class="system-title" style="margin-bottom: 0.5rem;">Starter</h3>
                    <p class="system-desc" style="margin-bottom: 1rem; min-height: auto;">Perfect for small businesses just getting started.</p>
                    <div class="price-tag" style="margin-bottom: 2rem;">
                        <span class="price-amount">$10</span>
                        <span class="price-period">/month</span>
                    </div>
                    
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: var(--text-muted);">
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Basic POS features
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Single User
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Basic Reporting
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Email Support
                        </li>
                    </ul>
                    
                    <button onclick="openPaymentModal('starter', 10)" class="btn btn-outline" style="width: 100%;">Choose Starter</button>
                </div>

                <!-- Professional Plan -->
                <div class="system-card" style="border-top: 4px solid var(--primary); transform: scale(1.05); box-shadow: var(--shadow-xl); z-index: 1;">
                    <div style="position: absolute; top: 0; right: 0; background: var(--primary); color: white; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-bottom-left-radius: 0.5rem;">POPULAR</div>
                    <h3 class="system-title" style="margin-bottom: 0.5rem;">Professional</h3>
                    <p class="system-desc" style="margin-bottom: 1rem; min-height: auto;">Advanced features for growing businesses.</p>
                    <div class="price-tag" style="margin-bottom: 2rem;">
                        <span class="price-amount">$50</span>
                        <span class="price-period">/month</span>
                    </div>
                    
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: var(--text-muted);">
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> <strong>All Starter features</strong>
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Intermediate Features
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> 5 Staff Logins
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Inventory Management
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Priority Email Support
                        </li>
                    </ul>
                    
                    <button onclick="openPaymentModal('professional', 50)" class="btn btn-primary" style="width: 100%;">Choose Professional</button>
                </div>

                <!-- Enterprise Plan -->
                <div class="system-card" style="border-top: 4px solid var(--text-main);">
                    <h3 class="system-title" style="margin-bottom: 0.5rem;">Enterprise</h3>
                    <p class="system-desc" style="margin-bottom: 1rem; min-height: auto;">Full functionality for large operations.</p>
                    <div class="price-tag" style="margin-bottom: 2rem;">
                        <span class="price-amount">$100</span>
                        <span class="price-period">/month</span>
                    </div>
                    
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: var(--text-muted);">
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> <strong>All Pro features</strong>
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Full Function Access
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Unlimited Staff
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> Advanced Analytics
                        </li>
                        <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-check" style="color: var(--primary);"></i> 24/7 Phone Support
                        </li>
                    </ul>
                    
                    <button onclick="openPaymentModal('enterprise', 100)" class="btn btn-outline" style="width: 100%;">Choose Enterprise</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features / CTA -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <h2>Ready to transform your business?</h2>
                <p>Join hundreds of businesses using Mekong CyberUnit to streamline operations.</p>
                <div class="btn-group">
                     <a href="/Mekong_CyberUnit/public/register.php" class="btn" style="background: white; color: var(--text-main);">
                        Create Free Account
                    </a>
                    <a href="#" class="btn" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon" style="width: 28px; height: 28px;">
                            <i class="ph-bold ph-cube"></i>
                        </div>
                        <span>Mekong CyberUnit</span>
                    </div>
                    <p>Empowering businesses with enterprise-grade tools at a fraction of the cost.</p>
                </div>
                
                <div class="footer-col">
                    <h4>Product</h4>
                    <ul class="footer-links">
                        <li><a href="#">POS</a></li>
                        <li><a href="#">Inventory</a></li>
                        <li><a href="#">HR Management</a></li>
                        <li><a href="#">Pricing</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2026 Mekong CyberUnit. All rights reserved.
            </div>
        </div>
    </footer>
    <!-- Payment Modal -->
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
                <div class="payment-instruction" style="color: #64748b; margin-bottom: 1.5rem;">Scan with Bakong or any Banking App</div>
                
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
                
                <p style="font-size: 0.9rem; color: #64748b; margin-top: 1rem;">
                    Payment for <span id="planName" style="font-weight: 700; color: #0f172a;">Plan</span>
                </p>
            </div>
            <div class="modal-footer" style="padding: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; gap: 1rem; background: #f8fafc;">
                <button type="button" id="confirmBtn" class="btn btn-primary" style="flex: 2; display: none; background: #16a34a; border-color: #16a34a;" onclick="confirmStaticPayment()">
                    <i class="ph-bold ph-check-circle"></i> I Have Paid
                </button>
                <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentPlan = '';
        let currentAmount = 0;
        let pollingInterval = null;
        const modal = document.getElementById('paymentModal');
        const qrImage = document.getElementById('qrImage');
        const qrPlaceholder = document.getElementById('qrPlaceholder');

        async function openPaymentModal(plan, price) {
            currentPlan = plan;
            currentAmount = price;
            document.getElementById('modalAmount').textContent = '$' + price.toFixed(2);
            document.getElementById('planName').textContent = plan.charAt(0).toUpperCase() + plan.slice(1) + ' Plan';
            
            // Reset modal state
            const confirmBtn = document.getElementById('confirmBtn');
            const staticNotice = document.getElementById('staticNotice');
            const pollingNotice = document.getElementById('pollingNotice');
            
            qrImage.style.display = 'none';
            qrPlaceholder.style.display = 'block';
            confirmBtn.style.display = 'none';
            staticNotice.style.display = 'none';
            pollingNotice.style.display = 'block';
            
            modal.classList.add('active');

            try {
                const response = await fetch(`/Mekong_CyberUnit/public/api/bakong_qr.php?plan=${plan}&method=bakong`);
                const result = await response.json();

                if (result.success) {
                    qrImage.src = result.image;
                    qrImage.style.display = 'block';
                    qrPlaceholder.style.display = 'none';
                    
                    if (result.is_static) {
                        confirmBtn.style.display = 'flex';
                        staticNotice.style.display = 'block';
                        pollingNotice.style.display = 'none';
                    } else {
                        // Dynamic QR - Enable Manual Confirmation Fallback
                        confirmBtn.style.display = 'flex'; // Enable manual confirm
                        confirmBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
                        
                        // Keep polling active in background
                        startPolling(result.md5);
                    }
                } else {
                    alert('Error generating QR: ' + result.error);
                }
            } catch (error) {
                console.error('Payment Error:', error);
                alert('Connection failed. Please try again.');
            }
        }

        window.confirmStaticPayment = async function() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Sending Notification...';
            
            try {
                const response = await fetch('/Mekong_CyberUnit/public/api/notify_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ plan: currentPlan, amount: currentAmount })
                });
                const result = await response.json();

                if (result.success) {
                    window.location.href = `/Mekong_CyberUnit/public/register.php?plan=${currentPlan}&ref=${result.ref}`;
                } else {
                    alert('Error: ' + result.error);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
                }
            } catch (error) {
                console.error('Notification Error:', error);
                alert('Connection failed.');
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-check-circle"></i> I Have Paid';
            }
        }

        function closeModal() {
            modal.classList.remove('active');
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        function startPolling(md5) {
            if (pollingInterval) clearInterval(pollingInterval);
            
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/Mekong_CyberUnit/public/api/bakong_check.php?md5=${md5}`);
                    const result = await response.json();

                    if (result.success && result.status === 'SUCCESS') {
                        clearInterval(pollingInterval);
                        window.location.href = `/Mekong_CyberUnit/public/register.php?plan=${currentPlan}&paid=true&md5=${md5}`;
                    }
                } catch (error) {
                    console.error('Polling Error:', error);
                }
            }, 3000); // Check every 3 seconds
        }

        // Close on outside click
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>