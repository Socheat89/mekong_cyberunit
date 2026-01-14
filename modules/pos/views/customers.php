<?php
$customers = $customers ?? [];

$totalCustomers = count($customers);
$withEmail = 0;
$withPhone = 0;
$withAddress = 0;

foreach ($customers as $c) {
    if (!empty($c['email'])) $withEmail++;
    if (!empty($c['phone'])) $withPhone++;
    if (!empty($c['address'])) $withAddress++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Customers</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1220;
            --panel: rgba(255, 255, 255, 0.06);
            --panel2: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.12);
            --text: rgba(255, 255, 255, 0.92);
            --muted: rgba(255, 255, 255, 0.70);
            --brandA: #667eea;
            --brandB: #764ba2;
            --ok: #28a745;
            --warn: #ffc107;
            --danger: #dc3545;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(1200px 600px at 20% -10%, rgba(102, 126, 234, 0.35), transparent 55%),
                        radial-gradient(900px 600px at 100% 0%, rgba(118, 75, 162, 0.35), transparent 55%),
                        var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 18px 20px 26px;
        }

        .page-header {
            display: grid;
            gap: 10px;
            margin: 18px 0 16px;
        }

        .page-title {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .page-title h1 {
            font-size: 1.6rem;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .page-title h1 i { color: #66e08a; }
        .page-title p { color: var(--muted); margin-top: 6px; }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.10);
            color: var(--text);
            text-decoration: none;
            cursor: pointer;
            font-weight: 800;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); background: rgba(255, 255, 255, 0.14); }
        .btn.primary { background: #007bff; border-color: #007bff; }
        .btn.primary:hover { background: #0056b3; }
        .btn.success { background: #28a745; border-color: #28a745; }
        .btn.success:hover { background: #1e7e34; }
        .btn.danger { background: #dc3545; border-color: #dc3545; }
        .btn.danger:hover { background: #bd2130; }
        .btn.small { padding: 8px 10px; border-radius: 10px; font-weight: 800; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 4px;
        }
        .stat {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
        }
        .stat .label {
            color: var(--muted);
            font-weight: 800;
            font-size: 12px;
            display:flex;
            gap:8px;
            align-items:center;
        }
        .stat .value { font-size: 1.4rem; font-weight: 900; margin-top: 6px; }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            margin-top: 14px;
        }

        .filters {
            display: grid;
            grid-template-columns: 1fr 240px auto;
            gap: 10px;
            align-items: center;
        }

        .input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(255,255,255,0.08);
            color: var(--text);
            outline: none;
        }
        .input:focus { border-color: rgba(124,140,255,0.8); box-shadow: 0 0 0 3px rgba(124,140,255,0.15); }

        .table-wrap {
            margin-top: 12px;
            overflow: auto;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: rgba(0,0,0,0.20);
        }

        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        thead th {
            position: sticky;
            top: 0;
            background: rgba(18, 18, 18, 0.95);
            color: rgba(255,255,255,0.85);
            text-align: left;
            padding: 14px;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.90);
            vertical-align: top;
        }
        tbody tr:hover { background: rgba(255,255,255,0.06); }

        .muted { color: var(--muted); }
        .actions-cell { display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            border: 1px solid;
            white-space: nowrap;
        }
        .badge.ok { background: rgba(40,167,69,0.18); color: #66e08a; border-color: rgba(40,167,69,0.35); }
        .badge.warn { background: rgba(255,193,7,0.18); color: #ffd26a; border-color: rgba(255,193,7,0.35); }

        .empty {
            padding: 26px;
            text-align: center;
            color: var(--muted);
        }
        .empty h3 { color: var(--text); font-size: 1.1rem; margin-bottom: 6px; }

        @media (max-width: 980px) {
            .stats { grid-template-columns: repeat(2, minmax(160px, 1fr)); }
            .filters { grid-template-columns: 1fr; }
        }

        @media (max-width: 720px) {
            table { min-width: 0; }
            thead { display: none; }
            tbody tr {
                display: block;
                padding: 12px 14px;
                border-bottom: 1px solid rgba(255,255,255,0.10);
            }
            tbody td {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                padding: 10px 0;
                border-bottom: 1px dashed rgba(255,255,255,0.10);
            }
            tbody td:last-child { border-bottom: none; }
            tbody td::before {
                content: attr(data-label);
                color: rgba(255,255,255,0.65);
                font-weight: 900;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }
            .actions-cell { justify-content: flex-end; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'customers'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="page-header">
            <div class="page-title">
                <div>
                    <h1><i class="fas fa-users"></i> Customer Management</h1>
                    <p><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?> • Manage customer contacts & history</p>
                </div>
                <div class="toolbar">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/create" class="btn primary"><i class="fas fa-user-plus"></i> Add Customer</a>
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/dashboard" class="btn"><i class="fas fa-arrow-left"></i> Back to POS</a>
                </div>
            </div>

            <div class="stats">
                <div class="stat">
                    <div class="label"><i class="fas fa-users"></i> Total Customers</div>
                    <div class="value"><?php echo number_format($totalCustomers); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-envelope" style="color:#ffd26a;"></i> With Email</div>
                    <div class="value"><?php echo number_format($withEmail); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-phone" style="color:#66e08a;"></i> With Phone</div>
                    <div class="value"><?php echo number_format($withPhone); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-location-dot"></i> With Address</div>
                    <div class="value"><?php echo number_format($withAddress); ?></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="filters">
                <input type="text" id="searchInput" class="input" placeholder="Search by name, email, phone, address…" onkeyup="searchCustomers()">
                <select id="hasFilter" class="input" onchange="searchCustomers()">
                    <option value="">All customers</option>
                    <option value="email">Has email</option>
                    <option value="phone">Has phone</option>
                    <option value="address">Has address</option>
                </select>
                <button class="btn" type="button" onclick="clearFilters()"><i class="fas fa-eraser"></i> Clear</button>
            </div>

            <div class="table-wrap">
                <table id="customersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty">
                                        <h3>No customers yet</h3>
                                        <div>Create your first customer to speed up checkout.</div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                                <?php
                                $email = (string)($customer['email'] ?? '');
                                $phone = (string)($customer['phone'] ?? '');
                                $address = (string)($customer['address'] ?? '');
                                if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                                    $addressShort = mb_strlen($address) > 60 ? mb_substr($address, 0, 60) . '…' : $address;
                                } else {
                                    $addressShort = strlen($address) > 60 ? substr($address, 0, 60) . '…' : $address;
                                }
                                ?>
                                <tr>
                                    <td data-label="Name">
                                        <div style="font-weight: 900;">
                                            <?php echo htmlspecialchars($customer['name']); ?>
                                        </div>
                                        <div class="muted" style="margin-top: 6px; display:flex; gap:8px; flex-wrap:wrap;">
                                            <?php if (!empty($email)): ?><span class="badge ok"><i class="fas fa-envelope"></i> Email</span><?php else: ?><span class="badge warn"><i class="fas fa-envelope"></i> No email</span><?php endif; ?>
                                            <?php if (!empty($phone)): ?><span class="badge ok"><i class="fas fa-phone"></i> Phone</span><?php else: ?><span class="badge warn"><i class="fas fa-phone"></i> No phone</span><?php endif; ?>
                                        </div>
                                    </td>
                                    <td data-label="Email"><?php echo !empty($email) ? htmlspecialchars($email) : '<span class="muted">—</span>'; ?></td>
                                    <td data-label="Phone"><?php echo !empty($phone) ? htmlspecialchars($phone) : '<span class="muted">—</span>'; ?></td>
                                    <td data-label="Address"><?php echo !empty($addressShort) ? htmlspecialchars($addressShort) : '<span class="muted">—</span>'; ?></td>
                                    <td data-label="Actions" class="actions-cell">
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/<?php echo $customer['id']; ?>/edit" class="btn small"><i class="fas fa-pen"></i> Edit</a>
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers/<?php echo $customer['id']; ?>/delete" class="btn small danger" data-pos-confirm="Are you sure you want to delete this customer?"><i class="fas fa-trash"></i> Delete</a>
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
            const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
            const hasFilter = (document.getElementById('hasFilter')?.value || '').toLowerCase();

            const table = document.getElementById('customersTable');
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row) => {
                // Skip empty state row
                const tds = row.querySelectorAll('td');
                if (!tds.length) return;

                const rowText = row.textContent.toLowerCase();
                const matchesQuery = !query || rowText.includes(query);

                const emailCellText = (tds[1]?.textContent || '').trim();
                const phoneCellText = (tds[2]?.textContent || '').trim();
                const addressCellText = (tds[3]?.textContent || '').trim();

                let matchesHas = true;
                if (hasFilter === 'email') matchesHas = !!emailCellText && emailCellText !== '—';
                if (hasFilter === 'phone') matchesHas = !!phoneCellText && phoneCellText !== '—';
                if (hasFilter === 'address') matchesHas = !!addressCellText && addressCellText !== '—';

                row.style.display = (matchesQuery && matchesHas) ? '' : 'none';
            });
        }

        function clearFilters() {
            const search = document.getElementById('searchInput');
            const has = document.getElementById('hasFilter');
            if (search) search.value = '';
            if (has) has.value = '';
            searchCustomers();
        }
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>