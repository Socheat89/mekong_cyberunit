<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - New Sale</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: transparent;
            color: #111;
            min-height: 100vh;
        }

        .navbar {
            background: #1f1f1f;
            border-bottom: 1px solid #2e2e2e;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .nav-brand {
            color: #7c8cff;
            text-decoration: none;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-brand i { color: #28a745; }
        .nav-menu { list-style: none; display: flex; gap: 8px; flex-wrap: wrap; }
        .nav-menu a {
            color: #fff;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .wrap {
            max-width: 1400px;
            margin: 0 auto;
            padding: 18px 20px 26px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .title h1 {
            font-size: 1.5rem;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .title p { color: rgba(0,0,0,0.68); margin-top: 4px; font-weight: 650; }

        .layout {
            display: grid;
            grid-template-columns: 1.35fr 0.65fr;
            gap: 16px;
        }

        .panel {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 24px rgba(22,24,35,0.10);
        }

        .controls {
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 12px;
            margin-bottom: 14px;
        }

        .input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.12);
            background: rgba(255,255,255,0.95);
            color: #111;
            outline: none;
        }
        .input:focus { border-color: rgba(124,140,255,0.8); box-shadow: 0 0 0 3px rgba(124,140,255,0.15); }
        .input::placeholder { color: rgba(0,0,0,0.45); }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 12px;
        }

        .product {
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.10);
            background: rgba(0,0,0,0.06);
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-height: 150px;
        }
        .product .row {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .product img {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .product .name { font-weight: 800; }
        .product .meta { color: rgba(0,0,0,0.55); font-size: 12px; margin-top: 2px; }
        .product .price { font-weight: 900; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge.ok { background: rgba(40,167,69,0.18); color: #66e08a; border: 1px solid rgba(40,167,69,0.35); }
        .badge.low { background: rgba(255,193,7,0.18); color: #ffd26a; border: 1px solid rgba(255,193,7,0.35); }
        .badge.out { background: rgba(220,53,69,0.18); color: #ff7d8c; border: 1px solid rgba(220,53,69,0.35); }

        .btn {
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.12);
            background: rgba(0,0,0,0.06);
            color: #111;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn:hover { transform: translateY(-1px); background: rgba(0,0,0,0.08); }

        /* Light variant for top actions (better on light backgrounds) */
        .btn.light {
            color: #111;
            background: rgba(0,0,0,0.05);
            border-color: rgba(0,0,0,0.10);
        }
        .btn.light:hover { background: rgba(0,0,0,0.08); }
        .btn.primary { background: #007bff; border-color: #007bff; }
        .btn.primary:hover { background: #0056b3; }
        .btn.success { background: #28a745; border-color: #28a745; }
        .btn.success:hover { background: #1e7e34; }
        .btn.danger { background: #dc3545; border-color: #dc3545; }
        .btn.danger:hover { background: #b02a37; }
        .btn.primary, .btn.success, .btn.danger { color: #fff; }
        .btn.small { padding: 8px 10px; border-radius: 10px; font-size: 12px; }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .cart-items { display: flex; flex-direction: column; gap: 10px; max-height: 48vh; overflow: auto; padding-right: 4px; }
        .cart-item {
            border: 1px solid rgba(0,0,0,0.10);
            background: rgba(0,0,0,0.05);
            border-radius: 14px;
            padding: 10px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
        }
        .cart-item .title { font-weight: 800; }
        .cart-item .sub { color: rgba(0,0,0,0.55); font-size: 12px; margin-top: 2px; }

        .qty {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty .num {
            min-width: 36px;
            text-align: center;
            font-weight: 900;
        }

        .summary {
            margin-top: 12px;
            border-top: 1px solid rgba(0,0,0,0.10);
            padding-top: 12px;
            display: grid;
            gap: 10px;
        }

        .row2 { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .row2 .label { color: rgba(0,0,0,0.60); }
        .row2 .value { font-weight: 900; }

        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

        .note { color: rgba(0,0,0,0.55); font-size: 12px; margin-top: 8px; }

        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
            .controls { grid-template-columns: 1fr; }
            .cart-items { max-height: none; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'pos'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="wrap">
        <div class="topbar">
            <div class="title">
                <h1><i class="fas fa-basket-shopping"></i> <?php echo isset($resumeOrder) && $resumeOrder ? 'Resume Hold' : 'New POS'; ?></h1>
                <p><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?> • Create a sale quickly</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button class="btn light" type="button" onclick="clearCart()"><i class="fas fa-trash"></i> Clear</button>
                <a class="btn light" href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/dashboard"><i class="fas fa-arrow-left"></i> Back to POS</a>
                <a class="btn light" href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/holds"><i class="fas fa-pause"></i> Held Orders</a>
                <a class="btn light" href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders"><i class="fas fa-receipt"></i> Orders</a>
            </div>
        </div>

        <?php if (isset($resumeOrder) && $resumeOrder): ?>
            <div class="panel" style="margin-bottom: 14px; border-left: 6px solid rgba(106, 92, 255, 0.85);">
                <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px; flex-wrap:wrap;">
                    <div>
                        <div style="font-weight: 950;">Resuming held order #<?php echo (int)$resumeOrder['id']; ?></div>
                        <div style="color: rgba(0,0,0,0.62); font-weight: 650; margin-top: 4px;">You can edit items, then complete sale or hold again.</div>
                    </div>
                    <a class="btn light" href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/holds"><i class="fas fa-list"></i> Back to Held</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="layout">
            <div class="panel">
                <div class="controls">
                    <input id="search" class="input" type="text" placeholder="Search by name / SKU / barcode (press Enter to add first match)…" autocomplete="off">
                    <select id="category" class="input">
                        <option value="">All categories</option>
                    </select>
                </div>

                <div id="products" class="product-grid"></div>
                <div class="note">Tip: use the search box like a barcode scanner (type/scan then press Enter).</div>
            </div>

            <div class="panel">
                <div class="cart-header">
                    <div style="font-weight:900; font-size: 1.1rem;">Cart</div>
                    <div class="badge ok" id="cartCount">0 items</div>
                </div>

                <div id="cart" class="cart-items"></div>

                <form id="checkoutForm" method="POST" action="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/create">
                    <?php if (isset($resumeOrder) && $resumeOrder): ?>
                        <input type="hidden" name="resume_order_id" value="<?php echo (int)$resumeOrder['id']; ?>">
                    <?php endif; ?>
                    <div class="summary">
                        <div class="grid2">
                            <select name="customer_id" id="customer" class="input">
                                <option value="">Walk-in Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo (int)$customer['id']; ?>"><?php echo htmlspecialchars($customer['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select name="payment_method" id="payment_method" class="input">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div class="grid2">
                            <select name="order_status" id="order_status" class="input">
                                <option value="completed">Completed</option>
                                <option value="pending">Pending (Hold)</option>
                            </select>

                            <input id="cash_given" class="input" type="number" step="0.01" min="0" placeholder="Cash given (optional)">
                        </div>

                        <div class="row2">
                            <div class="label">Subtotal</div>
                            <div class="value" id="subtotal">$0.00</div>
                        </div>
                        <div class="row2">
                            <div class="label">Change</div>
                            <div class="value" id="change">$0.00</div>
                        </div>

                        <input type="hidden" id="itemsPayload" value="">

                        <div style="display:grid; grid-template-columns: 1fr; gap: 10px;">
                            <button class="btn success" type="submit" id="btnPay"><i class="fas fa-circle-check"></i> Complete Sale</button>
                            <button class="btn" type="button" onclick="holdOrder()"><i class="fas fa-pause"></i> Hold (Pending)</button>
                        </div>

                        <div class="note">Completing will reduce stock and generate a receipt automatically.</div>
                    </div>
                </form>
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
            change: document.getElementById('change'),
            cashGiven: document.getElementById('cash_given'),
            cartCount: document.getElementById('cartCount'),
            orderStatus: document.getElementById('order_status'),
            paymentMethod: document.getElementById('payment_method'),
            checkoutForm: document.getElementById('checkoutForm'),
            btnPay: document.getElementById('btnPay')
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

        function stockBadge(stock) {
            if (stock <= 0) return '<span class="badge out">Out</span>';
            if (stock < 10) return '<span class="badge low">Low: ' + stock + '</span>';
            return '<span class="badge ok">Stock: ' + stock + '</span>';
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
                els.products.innerHTML = '<div style="color: rgba(255,255,255,0.7);">No products found.</div>';
                return;
            }

            for (const p of list) {
                const div = document.createElement('div');
                div.className = 'product';
                const disabled = p.stock <= 0;

                div.innerHTML = `
                    <div class="row">
                        <img src="${p.image}" alt="${escapeHtml(p.name)}">
                        <div style="min-width:0; flex:1;">
                            <div class="name" title="${escapeHtml(p.name)}">${escapeHtml(p.name)}</div>
                            <div class="meta">${escapeHtml(p.category || 'No Category')}</div>
                        </div>
                    </div>
                    <div class="row" style="justify-content: space-between;">
                        <div>
                            <div class="price">${money(p.price)}</div>
                            <div class="meta">${(p.sku ? 'SKU: ' + escapeHtml(p.sku) : '')}</div>
                        </div>
                        <div style="text-align:right;">${stockBadge(p.stock)}</div>
                    </div>
                    <button class="btn primary" type="button" ${disabled ? 'disabled style="opacity:.55; cursor:not-allowed;"' : ''}>
                        <i class="fas fa-plus"></i> Add
                    </button>
                `;

                div.querySelector('button').addEventListener('click', () => addToCart(p.id, 1));
                els.products.appendChild(div);
            }
        }

        function renderCart() {
            els.cart.innerHTML = '';

            if (!cart.size) {
                els.cart.innerHTML = '<div style="color: rgba(255,255,255,0.7);">Cart is empty.</div>';
            } else {
                for (const [productId, entry] of cart.entries()) {
                    const p = entry.product;
                    const qty = entry.qty;
                    const lineTotal = p.price * qty;

                    const item = document.createElement('div');
                    item.className = 'cart-item';
                    item.innerHTML = `
                        <div>
                            <div class="title">${escapeHtml(p.name)}</div>
                            <div class="sub">${money(p.price)} each • Stock: ${p.stock}</div>
                            <div class="sub"><strong>${money(lineTotal)}</strong></div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end;">
                            <div class="qty">
                                <button class="btn small" type="button" aria-label="Decrease">-</button>
                                <div class="num">${qty}</div>
                                <button class="btn small" type="button" aria-label="Increase">+</button>
                            </div>
                            <button class="btn danger small" type="button"><i class="fas fa-xmark"></i> Remove</button>
                        </div>
                    `;

                    const [btnDec, btnInc] = item.querySelectorAll('.qty .btn');
                    const btnRemove = item.querySelector('.btn.danger');
                    btnDec.addEventListener('click', () => setQty(productId, qty - 1));
                    btnInc.addEventListener('click', () => setQty(productId, qty + 1));
                    btnRemove.addEventListener('click', () => removeFromCart(productId));

                    els.cart.appendChild(item);
                }
            }

            const subtotal = computeSubtotal();
            els.subtotal.textContent = money(subtotal);

            const cash = parseFloat(els.cashGiven.value || '0') || 0;
            const change = Math.max(0, cash - subtotal);
            els.change.textContent = money(change);

            const itemCount = Array.from(cart.values()).reduce((acc, x) => acc + x.qty, 0);
            els.cartCount.textContent = itemCount + ' items';

            syncFormItems();
        }

        function addToCart(productId, deltaQty) {
            const p = PRODUCTS.find(x => x.id === productId);
            if (!p) return;
            const existing = cart.get(productId);
            const nextQty = (existing ? existing.qty : 0) + deltaQty;
            setQty(productId, nextQty);
        }

        function setQty(productId, qty) {
            const p = PRODUCTS.find(x => x.id === productId);
            if (!p) return;

            if (qty <= 0) {
                cart.delete(productId);
                renderCart();
                return;
            }

            // Do not allow selling more than available stock for completed sales
            // (pending orders can exceed stock in some businesses; keep it consistent/safe here)
            const maxQty = Math.max(0, p.stock);
            if (qty > maxQty) qty = maxQty;
            if (qty === 0) {
                cart.delete(productId);
            } else {
                cart.set(productId, { product: p, qty });
            }
            renderCart();
        }

        function removeFromCart(productId) {
            cart.delete(productId);
            renderCart();
        }

        function clearCart() {
            cart.clear();
            els.cashGiven.value = '';
            renderCart();
        }

        function holdOrder() {
            els.orderStatus.value = 'pending';
            els.paymentMethod.value = 'cash';
            els.checkoutForm.requestSubmit();
        }

        function applyResume() {
            if (!RESUME || !RESUME.items || !Array.isArray(RESUME.items)) return;

            // Fill customer
            if (typeof RESUME.customer_id === 'number') {
                const opt = els.checkoutForm.querySelector('#customer option[value="' + RESUME.customer_id + '"]');
                if (opt) {
                    els.checkoutForm.querySelector('#customer').value = String(RESUME.customer_id);
                }
            }

            // Default to pending while resuming (user can switch to completed to pay)
            els.orderStatus.value = 'pending';

            // Rehydrate cart
            cart.clear();
            for (const it of RESUME.items) {
                const pid = parseInt(it.product_id || 0, 10);
                const qty = parseInt(it.quantity || 0, 10);
                if (!pid || qty <= 0) continue;
                const p = PRODUCTS.find(x => x.id === pid);
                if (!p) continue;
                cart.set(pid, { product: p, qty: qty });
            }

            if (window.POSUI && window.POSUI.toast) {
                window.POSUI.toast({ type: 'info', title: 'Hold resumed', message: 'Loaded held order #' + RESUME.id });
            }
        }

        function escapeHtml(str) {
            return String(str ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function syncFormItems() {
            // Remove previous item inputs
            const old = els.checkoutForm.querySelectorAll('input[name^="items["]');
            old.forEach(n => n.remove());

            // Add current items
            let i = 0;
            for (const { product, qty } of cart.values()) {
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = `items[${i}][product_id]`;
                inputId.value = product.id;

                const inputQty = document.createElement('input');
                inputQty.type = 'hidden';
                inputQty.name = `items[${i}][quantity]`;
                inputQty.value = qty;

                els.checkoutForm.appendChild(inputId);
                els.checkoutForm.appendChild(inputQty);
                i++;
            }

            // Button state
            els.btnPay.disabled = cart.size === 0;
            if (els.btnPay.disabled) {
                els.btnPay.style.opacity = '0.6';
                els.btnPay.style.cursor = 'not-allowed';
            } else {
                els.btnPay.style.opacity = '';
                els.btnPay.style.cursor = '';
            }
        }

        function tryQuickAddFirstMatch() {
            const list = filteredProducts();
            if (!list.length) return;
            const p = list[0];
            if (p.stock <= 0) return;
            addToCart(p.id, 1);
            els.search.select();
        }

        els.search.addEventListener('input', () => renderProducts());
        els.category.addEventListener('change', () => renderProducts());
        els.cashGiven.addEventListener('input', () => renderCart());

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
                    window.POSUI.toast({ type: 'warning', title: 'Cart', message: 'Cart is empty.' });
                } else {
                    alert('Cart is empty.');
                }
                return;
            }

            // If completing, require stock-safe quantities already enforced in setQty
            // This keeps server-side logic simple and avoids fatal errors from missing items.
        });

        // Init
        renderCategories();
        renderProducts();
        applyResume();
        renderCart();
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
