<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="/Mekong_CyberUnit/public/js/bakong-khqr.js?v=<?php echo time(); ?>"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --pos-primary: #6366f1;
            --pos-primary-light: #eef2ff;
            --pos-secondary: #8b5cf6;
            --pos-accent: #0ea5e9;
            --pos-bg: #f1f5f9;
            --pos-card-bg: #ffffff;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --pos-border: #e2e8f0;
            --pos-radius: 16px;
            --pos-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --pos-terminal-sidebar: 400px;
        }

        body.pos-app {
            background-color: var(--pos-bg);
            font-family: 'Inter', sans-serif;
            color: var(--pos-text);
            margin: 0;
            overflow: hidden;
        }

        h1, h2, h3, .pos-prod-card__name, .pos-cart-header h2 {
            font-family: 'Outfit', sans-serif;
        }

        /* Layout */
        .pos-terminal {
            display: flex;
            height: calc(100vh - 72px);
            overflow: hidden;
            background: #f8fafc;
        }

        .pos-terminal__products {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            position: relative;
        }

        .pos-terminal__cart {
            width: var(--pos-terminal-sidebar);
            background: white;
            border-left: 1px solid var(--pos-border);
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 50px rgba(0,0,0,0.03);
            z-index: 10;
        }

        /* Search Bar & Categories */
        .pos-search-section {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
        }

        .pos-search-bar {
            flex: 1;
            background: white;
            border: 1px solid var(--pos-border);
            border-radius: 20px;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .pos-search-bar:focus-within {
            border-color: var(--pos-primary);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .pos-search-bar input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            color: var(--pos-text);
            background: transparent;
        }

        .pos-category-select {
            background: white;
            border: 1px solid var(--pos-border);
            border-radius: 20px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            outline: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            min-width: 180px;
        }

        /* Products Grid */
        .pos-prod-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 20px;
            padding-bottom: 24px;
        }

        .pos-prod-card {
            background: white;
            border-radius: 24px;
            padding: 12px;
            border: 1px solid var(--pos-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .pos-prod-card:hover {
            transform: translateY(-8px);
            border-color: var(--pos-primary);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.1), 0 10px 10px -5px rgba(99, 102, 241, 0.04);
        }

        .pos-prod-card__img {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 18px;
            background: #f1f5f9;
            display: grid;
            place-items: center;
            overflow: hidden;
            position: relative;
        }

        .pos-prod-card__img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .pos-prod-card:hover .pos-prod-card__img img {
            transform: scale(1.1);
        }

        .pos-prod-card__info {
            padding: 4px 8px 8px;
        }

        .pos-prod-card__name {
            font-weight: 700;
            font-size: 15px;
            color: var(--pos-text);
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 40px;
            line-height: 1.3;
        }

        .pos-prod-card__price {
            font-weight: 800;
            font-size: 18px;
            border-radius: 12px;
            color: var(--pos-primary);
        }

        .pos-prod-card__stock {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            color: var(--pos-text-muted);
            border: 1px solid var(--pos-border);
            z-index: 2;
        }

        /* Cart Section */
        .pos-cart-header {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
            border-bottom: 1px solid var(--pos-border);
        }

        .pos-cart-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--pos-text);
            margin: 0;
        }

        .cart-count {
            background: var(--pos-primary-light);
            color: var(--pos-primary);
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
        }

        .pos-cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .pos-cart-item {
            display: flex;
            gap: 16px;
            align-items: center;
            padding: 12px;
            background: white;
            border-radius: 18px;
            border: 1px solid var(--pos-border);
            transition: all 0.2s;
            animation: slideInRight 0.3s ease-out;
        }

        .pos-cart-item:hover {
            border-color: var(--pos-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .pos-cart-item__img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #f1f5f9;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pos-cart-item__info {
            flex: 1;
            min-width: 0;
        }

        .pos-cart-item__name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pos-cart-item__price {
            font-size: 13px;
            font-weight: 600;
            color: var(--pos-primary);
        }

        .pos-cart-qty {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            padding: 4px;
            border-radius: 12px;
            border: 1px solid var(--pos-border);
        }

        .pos-cart-qty button {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: none;
            background: white;
            color: var(--pos-text);
            cursor: pointer;
            font-weight: 700;
            display: grid;
            place-items: center;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .pos-cart-qty button:hover {
            background: var(--pos-primary);
            color: white;
        }

        .pos-cart-qty span {
            font-weight: 800;
            font-size: 14px;
            min-width: 24px;
            text-align: center;
        }

        /* Cart Footer */
        .pos-cart-footer {
            padding: 24px;
            background: white;
            border-top: 1px solid var(--pos-border);
            box-shadow: 0 -10px 30px rgba(0,0,0,0.02);
        }

        .totals-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .pos-cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--pos-text-muted);
        }

        .pos-cart-total-row.grand {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed var(--pos-border);
            font-size: 22px;
            font-weight: 800;
            color: var(--pos-text);
        }

        .pos-cart-total-row.grand span:last-child {
            color: var(--pos-primary);
        }

        .pos-btn-pay {
            width: 100%;
            background: linear-gradient(135deg, var(--pos-primary) 0%, var(--pos-secondary) 100%);
            border: none;
            color: white;
            padding: 18px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .pos-btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
            filter: brightness(1.1);
        }

        .pos-btn-pay:active {
            transform: translateY(0);
        }

        /* Modal Enhancements */
        .pos-modal-overlay {
            background: rgba(15, 23, 42, 0.8) !important;
            backdrop-filter: blur(12px) !important;
        }

        .pos-modal {
            background: white !important;
            border-radius: 32px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
            border: none !important;
            overflow: visible !important;
        }

        .pos-modal__header {
            padding: 30px 30px 20px !important;
            border: none !important;
        }

        .pos-modal__icon {
            width: 56px !important;
            height: 56px !important;
            border-radius: 18px !important;
            background: var(--pos-primary-light) !important;
            color: var(--pos-primary) !important;
        }

        .pos-modal__title h3 {
            font-size: 24px !important;
            font-weight: 800 !important;
        }

        .pos-modal__body {
            padding: 0 30px 30px !important;
        }

        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .payment-method-item {
            border: 2px solid var(--pos-border);
            border-radius: 20px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .payment-method-item.active {
            border-color: var(--pos-primary);
            background: var(--pos-primary-light);
            color: var(--pos-primary);
        }

        .payment-method-item i {
            display: block;
            font-size: 24px;
            margin-bottom: 8px;
        }

        .payment-method-item span {
            font-weight: 700;
            font-size: 14px;
        }

        @keyframes slideInRight {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 1024px) {
            .pos-terminal { flex-direction: column; height: auto; overflow: visible; }
            .pos-terminal__cart { width: 100%; border-left: none; position: fixed; bottom: 0; max-height: 80vh; transform: translateY(calc(100% - 140px)); transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1); }
            .pos-terminal__cart.expanded { transform: translateY(0); }
            .pos-terminal__products { padding-bottom: 160px; }
        }
    </style>

</head>
<body class="pos-app">
    <?php $activeNav = 'pos'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="pos-terminal">
        <div class="pos-terminal__products">
            <div class="pos-search-section">
                <div class="pos-search-bar">
                    <i class="fas fa-search" style="color: var(--pos-primary);"></i>
                    <input type="text" id="search" placeholder="Search products, SKU or scan..." autocomplete="off">
                    <kbd style="background: #f1f5f9; padding: 2px 6px; border-radius: 6px; font-size: 10px; font-weight: 700; color: var(--pos-text-muted); border: 1px solid var(--pos-border);">ENTER</kbd>
                </div>
                <select id="category" class="pos-category-select">
                    <option value="">All Categories</option>
                </select>
            </div>

            <?php if (isset($resumeOrder) && $resumeOrder): ?>
                <div style="background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 16px; padding: 12px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: #6366f1; color: white; display: grid; place-items: center;">
                        <i class="fas fa-history" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: #4338ca; text-transform: uppercase;">Resuming Order</div>
                        <div style="font-size: 14px; font-weight: 800; color: #1e1b4b;">#<?php echo (int)$resumeOrder['id']; ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="products" class="pos-prod-grid"></div>
        </div>

        <div class="pos-terminal__cart">
            <div class="pos-cart-header">
                <h2>Cart</h2>
                <div class="cart-count" id="cartCount">0 Items</div>
            </div>

            <div id="cart" class="pos-cart-items">
                <!-- Cart items added here via JS -->
                 <div style="height: 100%; display: grid; place-items: center; text-align: center; color: var(--pos-text-muted); opacity: 0.5;">
                    <div>
                        <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px;">
                            <i class="fas fa-shopping-basket" style="font-size: 32px;"></i>
                        </div>
                        <p style="font-weight: 700; font-size: 16px;">Your cart is empty</p>
                        <p style="font-size: 14px;">Add some products to start a sale</p>
                    </div>
                 </div>
            </div>

            <div class="pos-cart-footer">
                <form id="checkoutForm" method="POST" action="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/create">
                    <?php if (isset($resumeOrder) && $resumeOrder): ?>
                        <input type="hidden" name="resume_order_id" value="<?php echo (int)$resumeOrder['id']; ?>">
                    <?php endif; ?>
                    <input type="hidden" id="itemsPayload" value="">
                    <input type="hidden" name="order_status" id="order_status" value="completed">
                    <input type="hidden" name="payment_method" id="payment_method" value="cash">
                    <input type="hidden" name="cash_given" id="cash_given" value="">

                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Customer</label>
                            <div style="position: relative;">
                                <i class="fas fa-user" style="position: absolute; left: 12px; top: 12px; font-size: 12px; color: var(--pos-text-muted);"></i>
                                <select name="customer_id" id="customer" class="pos-input" style="padding: 10px 10px 10px 32px; border-radius: 12px; font-size: 13px; width: 100%; border: 1px solid var(--pos-border); background: #f8fafc; font-weight: 600;">
                                    <option value="">Walk-in Customer</option>
                                    <?php foreach ($customers as $customer1): ?>
                                        <option value="<?php echo (int)$customer1['id']; ?>"><?php echo htmlspecialchars($customer1['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Order Type</label>
                            <select id="terminal_order_status" class="pos-input" style="padding: 10px; border-radius: 12px; font-size: 13px; width: 100%; border: 1px solid var(--pos-border); background: #f8fafc; font-weight: 600;">
                                <option value="completed">Immediate Sale</option>
                                <option value="pending">Keep on Hold</option>
                            </select>
                        </div>
                    </div>

                    <div class="totals-card">
                        <div class="pos-cart-total-row">
                            <span>Subtotal</span>
                            <span id="subtotal_pre">$0.00</span>
                        </div>
                        <div class="pos-cart-total-row">
                            <span>Tax (0%)</span>
                            <span>$0.00</span>
                        </div>
                        <div class="pos-cart-total-row grand">
                            <span>Total</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button class="pos-icon-btn" type="button" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 20px; color: #ef4444; border-color: #fee2e2; background: #fef2f2;" onclick="clearCart()" title="Clear Cart">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="pos-btn-pay" type="button" id="btnPay">
                             Proceed to Payment <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="paymentModal" class="pos-modal-overlay">
        <div class="pos-modal">
            <div class="pos-modal__header">
                <div class="pos-modal__title">
                    <div class="pos-modal__icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3>Checkout</h3>
                        <p>Complete your transaction</p>
                    </div>
                </div>
                <button class="pos-modal__close" onclick="closePaymentModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="pos-modal__body">
                <div style="background: #f8fafc; border-radius: 24px; padding: 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--pos-border);">
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Payable Amount</div>
                        <div id="modal_subtotal" style="font-size: 32px; font-weight: 900; color: var(--pos-primary);">$0.00</div>
                    </div>
                    <div style="text-align: right;">
                        <div id="clock_now" style="font-size: 14px; font-weight: 700; color: var(--pos-text-muted);"></div>
                    </div>
                </div>

                <label style="display: block; font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Payment Method</label>
                <div class="payment-method-grid">
                    <?php if ($settings['pos_method_cash_enabled'] == '1'): ?>
                    <div class="payment-method-item active" data-method="cash" onclick="selectPaymentMethod('cash')">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Cash</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['pos_method_khqr_enabled'] == '1'): ?>
                    <div class="payment-method-item" data-method="khqr" onclick="selectPaymentMethod('khqr')">
                        <i class="fas fa-qrcode"></i>
                        <span>KHQR</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['pos_method_card_enabled'] == '1'): ?>
                    <div class="payment-method-item" data-method="card" onclick="selectPaymentMethod('card')">
                        <i class="fas fa-credit-card"></i>
                        <span>Card</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['pos_method_transfer_enabled'] == '1'): ?>
                    <div class="payment-method-item" data-method="transfer" onclick="selectPaymentMethod('transfer')">
                        <i class="fas fa-university"></i>
                        <span>Bank</span>
                    </div>
                    <?php endif; ?>
                </div>

                <div id="cashAmountGroup">
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Cash Received</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 16px; top: 14px; font-weight: 800; color: var(--pos-text-muted); font-size: 18px;">$</span>
                        <input id="modal_cash_given" class="pos-input" type="number" step="0.01" min="0" placeholder="0.00" style="padding: 14px 14px 14px 35px; border-radius: 16px; width: 100%; font-size: 24px; font-weight: 800; border: 2px solid var(--pos-primary); outline: none; background: white;">
                    </div>
                    
                    <div id="changeGroup" style="margin-top: 16px; background: #ecfdf5; padding: 16px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #bbf7d0;">
                        <span style="font-weight: 800; color: #065f46; font-size: 14px;">Change to Return</span>
                        <span id="modal_change" style="font-size: 24px; font-weight: 900; color: #10b981;">$0.00</span>
                    </div>
                </div>
                
                <div id="khqrGroup" style="display: none; text-align: center; background: white; padding: 24px; border-radius: 24px; border: 2px dashed var(--pos-border);">
                    <div id="qrcode_container" style="background: white; padding: 12px; border-radius: 16px; border: 1px solid var(--pos-border); display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                       <!-- Canvas or Img will be injected here -->
                    </div>
                    <div style="margin-top: 16px;">
                        <div style="font-weight: 900; color: #E31E26; font-size: 16px; letter-spacing: 1px;">SCAN KHQR TO PAY</div>
                        <div style="font-size: 13px; color: var(--pos-text-muted); margin-top: 4px;">Powered by Bakong</div>
                    </div>
                </div>

                <div id="cardGroup" style="display: none; text-align: center; padding: 40px 20px; background: #f8fafc; border-radius: 20px; border: 1px solid var(--pos-border);">
                    <i class="fas fa-credit-card" style="font-size: 48px; color: var(--pos-primary); opacity: 0.5; margin-bottom: 20px;"></i>
                    <p style="font-weight: 800; color: var(--pos-text);">Insert or Swpie Card</p>
                    <p style="font-size: 13px; color: var(--pos-text-muted);">Please use the external card terminal</p>
                </div>
            </div>
            <div class="pos-modal__actions" style="padding: 0 30px 30px; border: none;">
                <button class="pos-modal-btn" onclick="closePaymentModal()" style="padding: 16px 24px; flex: 1; border-radius: 18px; font-size: 16px;">Discard</button>
                <button class="pos-modal-btn primary" onclick="confirmPayment()" style="padding: 16px 24px; flex: 2; border-radius: 18px; font-size: 16px;">
                    <i class="fas fa-check-circle"></i> Complete Order
                </button>
            </div>
        </div>
    </div>


    <script>
        const PRODUCTS = <?php echo json_encode(array_map(function($p) {
            return [
                'id' => (int)$p['id'],
                'name' => $p['name'],
                'sku' => $p['sku'] ?? '',
                'barcode' => $p['barcode'] ?? '',
                'price' => (float)$p['price'],
                'stock' => (int)$p['stock_quantity'],
                'category' => $p['category_name'] ?? 'No Category',
                'image' => $p['image'] ? ('/Mekong_CyberUnit/uploads/products/' . $p['image']) : '/Mekong_CyberUnit/public/images/no-image.svg'
            ];
        }, $products)); ?>;

        const cart = new Map(); // productId -> { product, qty }

        const RESUME = <?php
            $resumePayload = null;
            if (isset($resumeOrder) && $resumeOrder) {
                $resumePayload = [
                    'id' => (int)$resumeOrder['id'],
                    'customer_id' => $resumeOrder['customer_id'] !== null ? (int)$resumeOrder['customer_id'] : null,
                    'items' => array_map(function($it) {
                        return [
                            'product_id' => (int)($it['product_id'] ?? 0),
                            'quantity' => (int)($it['quantity'] ?? 0),
                        ];
                    }, $resumeOrder['items'] ?? [])
                ];
            }
            echo json_encode($resumePayload);
        ?>;

        const els = {
            products: document.getElementById('products'),
            cart: document.getElementById('cart'),
            search: document.getElementById('search'),
            category: document.getElementById('category'),
            subtotal: document.getElementById('subtotal'),
            subtotalPre: document.getElementById('subtotal_pre'),
            cartCount: document.getElementById('cartCount'),
            orderStatus: document.getElementById('order_status'),
            terminalOrderStatus: document.getElementById('terminal_order_status'),
            paymentMethod: document.getElementById('payment_method'),
            cashGiven: document.getElementById('cash_given'),
            checkoutForm: document.getElementById('checkoutForm'),
            btnPay: document.getElementById('btnPay'),
            paymentModal: document.getElementById('paymentModal'),
            modalPaymentMethod: document.getElementById('modal_payment_method'),
            modalCashGiven: document.getElementById('modal_cash_given'),
            modalSubtotal: document.getElementById('modal_subtotal'),
            modalChange: document.getElementById('modal_change')
        };

        function money(value) {
            return '$' + (Math.round(value * 100) / 100).toFixed(2);
        }

        function computeSubtotal() {
            let total = 0;
            for (const { product, qty } of cart.values()) {
                total += product.price * qty;
            }
            return total;
        }

        function renderCategories() {
            const set = new Set(PRODUCTS.map(p => p.category || 'No Category'));
            const cats = Array.from(set).sort((a,b) => a.localeCompare(b));
            for (const c of cats) {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                els.category.appendChild(opt);
            }
        }

        function filteredProducts() {
            const q = els.search.value.trim().toLowerCase();
            const cat = els.category.value;
            return PRODUCTS.filter(p => {
                const matchesCat = !cat || (p.category === cat);
                if (!matchesCat) return false;
                if (!q) return true;
                return (
                    (p.name || '').toLowerCase().includes(q) ||
                    (p.sku || '').toLowerCase().includes(q) ||
                    (p.barcode || '').toLowerCase().includes(q)
                );
            });
        }

        function renderProducts() {
            const list = filteredProducts();
            els.products.innerHTML = '';

            if (!list.length) {
                els.products.innerHTML = '<div style="grid-column: 1/-1; padding: 40px; text-align: center; color: var(--pos-muted); font-weight: 700;">No products found with those filters.</div>';
                return;
            }

            for (const p of list) {
                const div = document.createElement('div');
                div.className = 'pos-prod-card';
                const disabled = p.stock <= 0;

                div.innerHTML = `
                    <div class="pos-prod-card__stock" style="${disabled ? 'color: #ef4444;' : ''}">
                        ${disabled ? 'OUT OF STOCK' : p.stock + ' IN STOCK'}
                    </div>
                    <div class="pos-prod-card__img">
                        ${p.image && !p.image.includes('no-image.svg') ? `<img src="${p.image}" alt="${escapeHtml(p.name)}">` : `<i class="fas fa-image" style="font-size: 32px; color: #cbd5e1;"></i>`}
                    </div>
                    <div class="pos-prod-card__info">
                        <div class="pos-prod-card__name" title="${escapeHtml(p.name)}">${escapeHtml(p.name)}</div>
                        <div class="pos-prod-card__price">${money(p.price)}</div>
                    </div>
                `;

                if (!disabled) {
                    div.addEventListener('click', () => addToCart(p.id, 1));
                } else {
                    div.style.opacity = '0.6';
                    div.style.cursor = 'not-allowed';
                }
                els.products.appendChild(div);
            }
        }

        function renderCart() {
            els.cart.innerHTML = '';

            if (!cart.size) {
                els.cart.innerHTML = `
                    <div style="height: 100%; display: grid; place-items: center; text-align: center; color: var(--pos-muted);">
                        <div>
                            <i class="fas fa-shopping-cart" style="font-size: 40px; opacity: 0.1; margin-bottom: 16px;"></i>
                            <p style="font-weight: 700; font-size: 14px;">Cart is empty</p>
                        </div>
                    </div>
                `;
                els.btnPay.style.opacity = '0.6';
                els.btnPay.style.cursor = 'not-allowed';
            } else {
                els.btnPay.style.opacity = '';
                els.btnPay.style.cursor = '';
                for (const [productId, entry] of cart.entries()) {
                    const p = entry.product;
                    const qty = entry.qty;

                    const item = document.createElement('div');
                    item.className = 'pos-cart-item';
                    item.innerHTML = `
                        <div class="pos-cart-item__img">
                            ${p.image && !p.image.includes('no-image.svg') ? `<img src="${p.image}" style="width:100%;height:100%;object-fit:cover;">` : `<i class="fas fa-image" style="color: #cbd5e1; padding: 12px; font-size: 16px;"></i>`}
                        </div>
                        <div class="pos-cart-item__info">
                            <div class="pos-cart-item__name">${escapeHtml(p.name)}</div>
                            <div class="pos-cart-item__price">${money(p.price)}</div>
                        </div>
                        <div class="pos-cart-qty">
                            <button type="button" class="minus">-</button>
                            <span>${qty}</span>
                            <button type="button" class="plus">+</button>
                        </div>
                    `;

                    item.querySelector('.minus').addEventListener('click', () => setQty(productId, qty - 1));
                    item.querySelector('.plus').addEventListener('click', () => setQty(productId, qty + 1));

                    els.cart.appendChild(item);
                }
            }

            const subtotal = computeSubtotal();
            els.subtotal.textContent = money(subtotal);
            els.subtotalPre.textContent = money(subtotal);

            const itemCount = Array.from(cart.values()).reduce((acc, x) => acc + x.qty, 0);
            els.cartCount.textContent = itemCount + ' Items';

            syncFormItems();
        }

        function setQty(productId, qty) {
            const p = PRODUCTS.find(x => x.id === productId);
            if (!p) return;

            if (qty <= 0) {
                cart.delete(productId);
            } else {
                const maxQty = Math.max(0, p.stock);
                if (qty > maxQty) {
                    if (window.POSUI && window.POSUI.toast) {
                        window.POSUI.toast({ type: 'warning', title: 'Low Stock', message: 'Only ' + maxQty + ' available.' });
                    }
                    qty = maxQty;
                }
                cart.set(productId, { product: p, qty });
            }
            renderCart();
        }

        function addToCart(productId, deltaQty) {
            const p = PRODUCTS.find(x => x.id === productId);
            if (!p) return;
            const existing = cart.get(productId);
            const nextQty = (existing ? existing.qty : 0) + deltaQty;
            setQty(productId, nextQty);
        }

        function clearCart() {
            if (cart.size === 0) return;
            if (confirm('Are you sure you want to clear the cart?')) {
                cart.clear();
                renderCart();
            }
        }

        function tryQuickAddFirstMatch() {
            const list = filteredProducts();
            if (!list.length) return;
            const p = list[0];
            if (p.stock <= 0) return;
            addToCart(p.id, 1);
            els.search.value = '';
            renderProducts();
        }

        // Modal functions
        function showPaymentModal() {
            if (cart.size === 0) return;
            const subtotal = computeSubtotal();
            els.modalSubtotal.textContent = money(subtotal);
            
            // Start clock
            updateClock();
            window.paymentClock = setInterval(updateClock, 1000);
            
            els.modalCashGiven.value = '';
            updateModalChange();
            els.paymentModal.style.display = 'flex';
            selectPaymentMethod('cash'); // Default
        }

        function updateClock() {
            const clock = document.getElementById('clock_now');
            if (clock) {
                const now = new Date();
                clock.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }

        function selectPaymentMethod(method) {
            // Update UI
            document.querySelectorAll('.payment-method-item').forEach(el => {
                el.classList.remove('active');
                if (el.dataset.method === method) el.classList.add('active');
            });

            // Set hidden field
            els.paymentMethod.value = method;

            // Toggle groups
            const cashGroup = document.getElementById('cashAmountGroup');
            const khqrGroup = document.getElementById('khqrGroup');
            const cardGroup = document.getElementById('cardGroup');
            
            cashGroup.style.display = method === 'cash' ? 'block' : 'none';
            khqrGroup.style.display = method === 'khqr' ? 'block' : 'none';
            cardGroup.style.display = method === 'card' ? 'block' : 'none';

            if (method === 'khqr') generateDynamicKHQR();
            if (method === 'cash') els.modalCashGiven.focus();
        }

        function closePaymentModal() {
            els.paymentModal.style.display = 'none';
            if (window.paymentClock) clearInterval(window.paymentClock);
        }

        function generateDynamicKHQR() {
            const container = document.getElementById('qrcode_container');
            container.innerHTML = '';
            
            const amount = computeSubtotal();
            const details = {
                bakongId: "<?php echo $settings['bank_account'] ?? 'doem_socheat@bkrt'; ?>",
                name: "<?php echo $settings['merchant_name'] ?? 'Doem Socheat'; ?>",
                city: "<?php echo $settings['merchant_city'] ?? 'Phnom Penh'; ?>",
                phone: "<?php echo $settings['phone_number'] ?? '85516367859'; ?>",
                store: "<?php echo $settings['store_label'] ?? 'Mekong CyberUnit'; ?>",
                amount: amount,
                currency: "USD",
                bill: "POS" + Date.now().toString().slice(-8)
            };

            const khqrString = BakongKHQR.generateIndividual(details);
            
            new QRCode(container, {
                text: khqrString,
                width: 220,
                height: 220,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }

        function updateModalChange() {
            const subtotal = computeSubtotal();
            const cash = parseFloat(els.modalCashGiven.value || '0') || 0;
            const change = Math.max(0, cash - subtotal);
            els.modalChange.textContent = money(change);
        }

        function confirmPayment() {
            const method = els.paymentMethod.value;
            const cashGiven = els.modalCashGiven.value;

            if (method === 'cash') {
                const subtotal = computeSubtotal();
                const cash = parseFloat(cashGiven || '0') || 0;
                if (cash < subtotal) {
                    alert('Amount received is less than total payable.');
                    return;
                }
                els.cashGiven.value = cashGiven;
            } else {
                els.cashGiven.value = '';
            }
            els.orderStatus.value = 'completed';

            closePaymentModal();
            els.checkoutForm.submit();
        }

        function syncFormItems() {
            const items = [];
            for (const [id, entry] of cart.entries()) {
                items.push({ product_id: id, quantity: entry.qty });
            }
            
            // Remove existing hidden items
            els.checkoutForm.querySelectorAll('.item-input').forEach(el => el.remove());
            
            items.forEach((item, index) => {
                const idInp = document.createElement('input');
                idInp.type = 'hidden';
                idInp.name = `items[${index}][product_id]`;
                idInp.value = item.product_id;
                idInp.className = 'item-input';
                
                const qtyInp = document.createElement('input');
                qtyInp.type = 'hidden';
                qtyInp.name = `items[${index}][quantity]`;
                qtyInp.value = item.quantity;
                qtyInp.className = 'item-input';
                
                els.checkoutForm.appendChild(idInp);
                els.checkoutForm.appendChild(qtyInp);
            });
        }

        function applyResume() {
            if (!RESUME) return;
            cart.clear();
            for (const it of RESUME.items) {
                const p = PRODUCTS.find(x => x.id === it.product_id);
                if (p) cart.set(it.product_id, { product: p, qty: it.quantity });
            }
            if (RESUME.customer_id) els.checkoutForm.querySelector('#customer').value = RESUME.customer_id;
            renderCart();
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderCategories();
            renderProducts();
            applyResume();
            renderCart();

            els.search.addEventListener('input', renderProducts);
            els.category.addEventListener('change', renderProducts);

            els.btnPay.addEventListener('click', (e) => {
                e.preventDefault();
                showPaymentModal();
            });

            els.terminalOrderStatus.addEventListener('change', (e) => {
                els.orderStatus.value = e.target.value;
            });

            els.modalPaymentMethod.addEventListener('change', toggleCashInput);
            els.modalCashGiven.addEventListener('input', updateModalChange);

            els.search.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    tryQuickAddFirstMatch();
                }
            });

            els.checkoutForm.addEventListener('submit', (e) => {
                if (!cart.size) {
                    e.preventDefault();
                    if (window.POSUI && window.POSUI.toast) {
                        window.POSUI.toast({ type: 'warning', title: 'Cart Empty', message: 'Please add items to your cart before completing the sale.' });
                    } else {
                        alert('Cart is empty.');
                    }
                }
            });
        });
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
