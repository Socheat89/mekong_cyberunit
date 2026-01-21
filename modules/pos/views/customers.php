<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Customers</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
    <style>
        .cust-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .cust-row { background: white; border-radius: 12px; transition: all 0.2s; box-shadow: var(--pos-shadow-sm); }
        .cust-row:hover { transform: translateY(-2px); box-shadow: var(--pos-shadow-md); }
        .cust-td { padding: 20px; border: none; }
        .cust-th { padding: 12px 20px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); font-weight: 800; letter-spacing: 0.5px; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'customers'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 24px;">
            <div class="pos-title">
                <h1 class="text-gradient">Customers</h1>
                <p>Manage your client database and relationships.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/create" class="pos-pill" style="padding: 12px 24px;">
                    <i class="fas fa-plus"></i> Add Customer
                </a>
            </div>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 24px;">
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Total Customers</div>
                <div class="v"><?php echo count($customers); ?></div>
                <div class="chip" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="pos-card pos-shadow-sm" style="padding: 30px; border: none;">
            <div class="pos-topbar__search" style="max-width: 100%; margin-bottom: 24px; background: #f8fafc; border: 1px solid var(--pos-border);">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search customers by name, email or phone..." onkeyup="searchCustomers()" style="font-weight: 700;">
            </div>

            <div style="overflow:auto;">
                <table class="cust-table" id="customersTable">
                    <thead>
                        <tr>
                            <th class="cust-th">Customer</th>
                            <th class="cust-th">Contact Details</th>
                            <th class="cust-th">Address</th>
                            <th class="cust-th" style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="4" style="padding: 60px; text-align: center; color: var(--pos-muted);">
                                    <i class="fas fa-users" style="font-size: 40px; opacity: 0.2; margin-bottom: 16px; display: block;"></i>
                                    <p style="font-weight: 700;">No customers found yet.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr class="cust-row">
                                    <td class="cust-td" style="border-radius: 12px 0 0 12px;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #eff6ff; color: #3b82f6; display: grid; place-items: center; font-weight: 900;">
                                                <?php echo strtoupper(substr($customer['name'], 0, 1)); ?>
                                            </div>
                                            <div style="font-weight: 800; font-size: 15px; color: var(--pos-text);"><?php echo htmlspecialchars($customer['name']); ?></div>
                                        </div>
                                    </td>
                                    <td class="cust-td">
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            <?php if (!empty($customer['email'])): ?>
                                                <span style="font-size: 13px; font-weight: 600; color: var(--pos-text);"><i class="far fa-envelope" style="width: 16px; color: var(--pos-muted);"></i> <?php echo htmlspecialchars($customer['email']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($customer['phone'])): ?>
                                                <span style="font-size: 13px; font-weight: 600; color: var(--pos-text);"><i class="fas fa-phone" style="width: 16px; color: var(--pos-muted);"></i> <?php echo htmlspecialchars($customer['phone']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="cust-td">
                                        <span style="font-size: 13px; font-weight: 600; color: var(--pos-muted);"><?php echo !empty($customer['address']) ? htmlspecialchars($customer['address']) : '—'; ?></span>
                                    </td>
                                    <td class="cust-td" style="text-align: right; border-radius: 0 12px 12px 0;">
                                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/<?php echo $customer['id']; ?>/edit" class="pos-icon-btn" style="width: 36px; height: 36px; color: var(--pos-brand-a); border-color: rgba(99, 102, 241, 0.1);">
                                                <i class="fas fa-pen" style="font-size: 14px;"></i>
                                            </a>
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/<?php echo $customer['id']; ?>/delete" class="pos-icon-btn" style="width: 36px; height: 36px; color: #ef4444; border-color: rgba(239, 68, 68, 0.1);" data-pos-confirm="Are you sure you want to delete this customer?">
                                                <i class="fas fa-trash" style="font-size: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchCustomers() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('customersTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const tdName = tr[i].getElementsByTagName('td')[0];
                const tdContact = tr[i].getElementsByTagName('td')[1];
                
                if (tdName || tdContact) {
                    const txtValueName = tdName ? (tdName.textContent || tdName.innerText) : "";
                    const txtValueContact = tdContact ? (tdContact.textContent || tdContact.innerText) : "";
                    
                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueContact.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
