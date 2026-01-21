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
    <style>
        :root {
            --pos-terminal-sidebar: 380px;
        }
        
        /* Overrides for POS full-screen layout */
        .pos-page { padding: 0 !important; max-width: none !important; margin: 0 !important; }
        .pos-footer { display: none !important; }
        
        .pos-terminal { display: flex; height: calc(100vh - 72px); overflow: hidden; }
        .pos-terminal__products { flex: 1; padding: 24px; overflow-y: auto; background: #f8fafc; }
        .pos-terminal__cart { width: var(--pos-terminal-sidebar); background: white; border-left: 1px solid var(--pos-border); display: flex; flex-direction: column; box-shadow: -10px 0 30px rgba(0,0,0,0.02); }
        
        .pos-search-bar { background: white; border: 1px solid var(--pos-border); border-radius: 16px; padding: 12px 20px; display: flex; align-items: center; gap: 12px; margin-bottom: 24px; box-shadow: var(--pos-shadow-sm); }
        .pos-search-bar i { color: var(--pos-muted); }
        .pos-search-bar input { border: none; outline: none; width: 100%; font-size: 15px; font-weight: 700; color: var(--pos-text); }
        
        .pos-prod-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; }
        .pos-prod-card { background: white; border-radius: 20px; padding: 16px; border: 1px solid var(--pos-border); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; display: flex; flex-direction: column; gap: 12px; position: relative; overflow: hidden; }
        .pos-prod-card:hover { transform: translateY(-4px); border-color: var(--pos-brand-a); box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.1), 0 10px 10px -5px rgba(99, 102, 241, 0.04); }
        .pos-prod-card__img { width: 100%; aspect-ratio: 1; border-radius: 14px; background: #f1f5f9; display: grid; place-items: center; overflow: hidden; }
        .pos-prod-card__img img { width: 100%; height: 100%; object-fit: cover; }
        .pos-prod-card__info { display: flex; flex-direction: column; gap: 4px; }
        .pos-prod-card__name { font-weight: 800; font-size: 14px; color: var(--pos-text); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px; }
        .pos-prod-card__price { font-weight: 900; font-size: 16px; color: var(--pos-brand-a); }
        .pos-prod-card__stock { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 4px 8px; border-radius: 8px; font-size: 10px; font-weight: 800; border: 1px solid var(--pos-border); }

        .pos-cart-header { padding: 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--pos-border); }
        .pos-cart-header h2 { font-size: 18px; font-weight: 900; color: var(--pos-text); }
        .pos-cart-items { flex: 1; overflow-y: auto; padding: 12px 24px; display: flex; flex-direction: column; gap: 12px; }
        .pos-cart-item { background: #f8fafc; border-radius: 16px; padding: 12px; display: flex; gap: 12px; align-items: center; animation: slideInRight 0.3s ease-out; }
        .pos-cart-item__img { width: 44px; height: 44px; border-radius: 10px; background: white; border: 1px solid var(--pos-border); overflow: hidden; flex-shrink: 0; }
        .pos-cart-item__info { flex: 1; min-width: 0; }
        .pos-cart-item__name { font-weight: 800; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pos-cart-item__price { font-size: 12px; font-weight: 700; color: var(--pos-muted); }
        .pos-cart-qty { display: flex; align-items: center; gap: 8px; background: white; border: 1px solid var(--pos-border); border-radius: 10px; padding: 4px; }
        .pos-cart-qty button { width: 24px; height: 24px; border-radius: 6px; border: none; background: #f1f5f9; color: var(--pos-text); cursor: pointer; font-weight: 900; display: grid; place-items: center; }
        .pos-cart-qty span { font-weight: 800; font-size: 12px; min-width: 20px; text-align: center; }

        .pos-cart-footer { padding: 24px; background: white; border-top: 1px solid var(--pos-border); display: flex; flex-direction: column; gap: 16px; }
        .pos-cart-totals { display: flex; flex-direction: column; gap: 8px; }
        .pos-cart-total-row { display: flex; justify-content: space-between; font-size: 14px; color: var(--pos-muted); font-weight: 700; }
        .pos-cart-total-row.grand { font-size: 20px; color: var(--pos-text); font-weight: 900; margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--pos-border); }
        .pos-cart-total-row.grand span:last-child { color: var(--pos-brand-a); }

        .pos-btn-pay { width: 100%; background: var(--pos-gradient-indigo); border: none; color: white; padding: 18px; border-radius: 16px; font-weight: 900; font-size: 16px; cursor: pointer; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .pos-btn-pay:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3); }
        .pos-btn-pay:active { transform: translateY(0); }
        
        /* Aggressive centering for the payment modal */
        .pos-modal-overlay { 
            display: none; 
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(15, 23, 42, 0.75) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            z-index: 999999 !important;
            display: none; /* JS will set to flex */
            align-items: center !important;
            justify-content: center !important;
        }
        
        .pos-modal {
            margin: auto !important;
            max-width: 500px !important;
            width: 90% !important;
            position: relative !important;
            animation: posModalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }
        
        @keyframes slideInRight { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        @media (max-width: 1024px) {
            .pos-terminal { flex-direction: column; height: auto; }
            .pos-terminal__cart { width: 100%; border-left: none; border-top: 1px solid var(--pos-border); position: sticky; bottom: 0; }
            .pos-cart-items { max-height: 300px; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'pos'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="pos-terminal">
        <div class="pos-terminal__products">
            <div class="pos-search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="search" placeholder="Search products by name, SKU or scan barcode..." autocomplete="off">
                <div style="width: 1px; height: 24px; background: var(--pos-border); margin: 0 8px;"></div>
                <select id="category" style="border: none; outline: none; font-weight: 800; font-size: 14px; background: transparent; cursor: pointer;">
                    <option value="">All Categories</option>
                </select>
            </div>

            <?php if (isset($resumeOrder) && $resumeOrder): ?>
                <div class="pos-stat pos-shadow-sm" style="margin-bottom: 24px; border: 1px solid #4f46e5; background: #eef2ff;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="chip" style="background: #4f46e5; color: white;"><i class="fas fa-history"></i></div>
                        <div>
                            <div class="k">Resuming held order</div>
                            <div class="v" style="font-size: 14px; color: #4338ca;">#<?php echo (int)$resumeOrder['id']; ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="products" class="pos-prod-grid"></div>
        </div>

        <div class="pos-terminal__cart">
            <div class="pos-cart-header">
                <h2>Cart</h2>
                <div class="pos-pill" style="padding: 6px 12px; font-size: 12px; background: #eef2ff; color: #4338ca;" id="cartCount">0 Items</div>
            </div>

            <div id="cart" class="pos-cart-items">
                <!-- Cart items added here via JS -->
                 <div style="height: 100%; display: grid; place-items: center; text-align: center; color: var(--pos-muted);">
                    <div>
                        <i class="fas fa-shopping-cart" style="font-size: 40px; opacity: 0.1; margin-bottom: 16px;"></i>
                        <p style="font-weight: 700; font-size: 14px;">Cart is empty</p>
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

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--pos-muted); text-transform: uppercase; margin-bottom: 6px;">Customer</label>
                            <select name="customer_id" id="customer" class="pos-input" style="padding: 10px; border-radius: 12px; font-size: 13px; width: 100%;">
                                <option value="">Walk-in Customer</option>
                                <?php foreach ($customers as $customer1): ?>
                                    <option value="<?php echo (int)$customer1['id']; ?>"><?php echo htmlspecialchars($customer1['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--pos-muted); text-transform: uppercase; margin-bottom: 6px;">Status</label>
                            <select id="terminal_order_status" class="pos-input" style="padding: 10px; border-radius: 12px; font-size: 13px; width: 100%;">
                                <option value="completed">Completed</option>
                                <option value="pending">On Hold</option>
                            </select>
                        </div>
                    </div>

                    <div class="pos-cart-totals">
                        <div class="pos-cart-total-row">
                            <span>Subtotal</span>
                            <span id="subtotal_pre">$0.00</span>
                        </div>
                        <div class="pos-cart-total-row grand">
                            <span>Total</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button class="pos-icon-btn" type="button" style="width: 54px; height: 54px; flex-shrink: 0;" onclick="clearCart()">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="pos-btn-pay" type="button" id="btnPay">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="pos-modal-overlay">
        <div class="pos-modal pos-modal--info">
            <div class="pos-modal__header">
                <div class="pos-modal__title">
                    <div class="pos-modal__icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <h3>Complete Sale</h3>
                        <p>Select payment method</p>
                    </div>
                </div>
                <button class="pos-modal__close" onclick="closePaymentModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="pos-modal__body">
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--pos-muted); text-transform: uppercase; margin-bottom: 6px;">Payment Method</label>
                        <select id="modal_payment_method" class="pos-input" style="padding: 12px; border-radius: 12px; width: 100%;">
                            <?php if ($settings['pos_method_cash_enabled'] == '1'): ?>
                            <option value="cash">💵 Cash</option>
                            <?php endif; ?>
                            
                            <?php if ($settings['pos_method_khqr_enabled'] == '1'): ?>
                            <option value="khqr">📲 KHQR</option>
                            <?php endif; ?>
                            
                            <?php if ($settings['pos_method_card_enabled'] == '1'): ?>
                            <option value="card">💳 Card</option>
                            <?php endif; ?>
                            
                            <?php if ($settings['pos_method_transfer_enabled'] == '1'): ?>
                            <option value="transfer">🏦 Bank Transfer</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div id="cashAmountGroup">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--pos-muted); text-transform: uppercase; margin-bottom: 6px;">Cash Received</label>
                        <input id="modal_cash_given" class="pos-input" type="number" step="0.01" min="0" placeholder="0.00" style="padding: 12px; border-radius: 12px; width: 100%; font-size: 18px; font-weight: 800;">
                    </div>
                    
                    <div id="khqrGroup" style="display: none; text-align: center; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px dashed var(--pos-border);">
                        <div id="qrcode_container" style="background: white; padding: 12px; border-radius: 12px; border: 1px solid var(--pos-border); display: inline-block; box-shadow: var(--pos-shadow-sm);">
                           <!-- Canvas or Img will be injected here -->
                        </div>
                        <div style="margin-top: 12px; font-weight: 800; color: #E31E26; font-size: 14px;">SCAN KHQR TO PAY</div>
                    </div>

                    <div style="background: #f1f5f9; padding: 16px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 800; color: var(--pos-muted);">Total Amount</span>
                        <span id="modal_subtotal" style="font-size: 20px; font-weight: 900; color: var(--pos-brand-a);">$0.00</span>
                    </div>

                    <div id="changeGroup" style="background: #ecfdf5; padding: 16px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 800; color: #065f46;">Change due</span>
                        <span id="modal_change" style="font-size: 20px; font-weight: 900; color: #10b981;">$0.00</span>
                    </div>
                </div>
            </div>
            <div class="pos-modal__actions">
                <button class="pos-modal-btn" onclick="closePaymentModal()">Cancel</button>
                <button class="pos-modal-btn primary" onclick="confirmPayment()">
                    <i class="fas fa-check"></i> Complete Sale
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
            
            // Pick first available method
            if (els.modalPaymentMethod.options.length > 0) {
                els.modalPaymentMethod.selectedIndex = 0;
            }
            
            els.modalCashGiven.value = '';
            updateModalChange();
            els.paymentModal.style.display = 'flex';
            toggleCashInput();
        }

        function closePaymentModal() {
            els.paymentModal.style.display = 'none';
        }

        function toggleCashInput() {
            const method = els.modalPaymentMethod.value;
            const cashGroup = document.getElementById('cashAmountGroup');
            const changeGroup = document.getElementById('changeGroup');
            const khqrGroup = document.getElementById('khqrGroup');
            
            if (method === 'cash') {
                cashGroup.style.display = 'block';
                changeGroup.style.display = 'flex';
                khqrGroup.style.display = 'none';
            } else if (method === 'khqr') {
                cashGroup.style.display = 'none';
                changeGroup.style.display = 'none';
                khqrGroup.style.display = 'block';
                generateDynamicKHQR();
            } else {
                cashGroup.style.display = 'none';
                changeGroup.style.display = 'none';
                khqrGroup.style.display = 'none';
            }
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
                width: 200,
                height: 200,
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
            const method = els.modalPaymentMethod.value;
            const cashGiven = els.modalCashGiven.value;

            // Set form values
            els.paymentMethod.value = method;
            if (method === 'cash') {
                els.cashGiven.value = cashGiven;
            } else {
                els.cashGiven.value = '';
            }
            els.orderStatus.value = 'completed';

            closePaymentModal();

            // Show processing message
            if (window.POSUI && window.POSUI.toast) {
                window.POSUI.toast({
                    type: 'info',
                    title: 'Processing Sale',
                    message: 'Completing your transaction...',
                    timeout: 2000
                });
            }

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
