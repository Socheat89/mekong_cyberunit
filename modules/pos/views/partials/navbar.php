<?php
// Shared POS layout shell (sidebar + topbar).
// Expected optional vars from caller:
//   - $activeNav: one of dashboard|pos|holds|products|orders|customers|reports

$host = $_SERVER['HTTP_HOST'] ?? '';
$isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
$basePath = $isProduction ? '' : '/Mekong_CyberUnit';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

$tenantSlug = null;
$tenantName = 'Mekong';

if (class_exists('Tenant')) {
    $currentTenant = Tenant::getCurrent();
    if (is_array($currentTenant)) {
        $tenantSlug = $currentTenant['subdomain'] ?? null;
        $tenantName = $currentTenant['name'] ?? 'Mekong';
    }
}

// Build the correct POS base URL
// Production: /socheatcofe/pos
// Local: /Mekong_CyberUnit/socheatcofe/pos
if ($tenantSlug) {
    $posBase = $basePath . '/' . $tenantSlug . '/pos';
} else {
    // Fallback if no tenant detected
    $posBase = $basePath . '/pos';
}

$logoutUrl = $tenantSlug
    ? ($basePath . '/' . $tenantSlug . '/logout')
    : ($basePath . '/public/logout.php');

$activeNav = $activeNav ?? '';

$posUrl = function (string $path) use ($posBase): string {
    $path = ltrim($path, '/');
    return $posBase . '/' . $path;
};

$activeClass = function (string $key) use ($activeNav): string {
    return ($activeNav === $key) ? 'active' : '';
};
?>

    <style>
        :root {
            --pos-sidebar-width: 280px;
            --pos-topbar-height: 80px;
            --pos-primary: #6366f1;
            --pos-secondary: #8b5cf6;
            --pos-bg: #f8fafc;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --pos-border: #e2e8f0;
            --pos-sidebar-bg: #0f172a;
        }

        .pos-sidebar {
            background: var(--pos-sidebar-bg);
            border-right: 1px solid rgba(255,255,255,0.05);
            box-shadow: 20px 0 50px rgba(0,0,0,0.2);
            width: var(--pos-sidebar-width);
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .pos-main {
            margin-left: var(--pos-sidebar-width);
            min-height: 100vh;
            background: var(--pos-bg);
            transition: all 0.3s ease;
        }

        .pos-side-link {
            padding: 14px 16px;
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            margin-bottom: 4px;
        }

        .pos-side-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .pos-side-link.active {
            background: linear-gradient(135deg, var(--pos-primary) 0%, var(--pos-secondary) 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        .pos-side-link.active i {
            color: white;
        }

        .pos-topbar {
            height: var(--pos-topbar-height);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--pos-border);
            display: flex;
            align-items: center;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .pos-sidebar-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: white;
            border: 1px solid var(--pos-border);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--pos-text);
        }

        @media (max-width: 980px) {
            .pos-sidebar {
                transform: translateX(-100%);
            }
            .pos-shell--open .pos-sidebar {
                transform: translateX(0);
            }
            .pos-main {
                margin-left: 0;
            }
            .pos-sidebar-toggle {
                display: flex;
            }
        }
    </style>

    <aside class="pos-sidebar">
        <div class="pos-sidebar__brand" style="margin-bottom: 40px; padding: 0 8px;">
            <a class="pos-brand" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>" style="display: flex; align-items: center; gap: 14px; text-decoration: none;">
                <div class="pos-brand__logo" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 14px; display: grid; place-items: center; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);">
                    <i class="fas fa-terminal" style="color: white; font-size: 20px;"></i>
                </div>
                <div class="pos-brand__text">
                    <span class="pos-brand__title" style="font-size: 20px; font-weight: 900; letter-spacing: -0.5px; color: white; display: block;">Mekong</span>
                    <span class="pos-brand__sub" style="color: rgba(255,255,255,0.5); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Cyber Unit POS</span>
                </div>
            </a>
        </div>

        <nav class="pos-side-nav" style="flex: 1; overflow-y: auto;">
            <?php
            $posLevel = 0;
            if (class_exists('Tenant')) {
               $posLevel = Tenant::getPosLevel();
            }
            if ($isDevPos) $posLevel = 3; 
            ?>

            <a class="pos-side-link <?php echo $activeClass('dashboard'); ?>" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>">
                <i class="fas fa-chart-pie" style="width: 20px; text-align: center;"></i><span>Overview</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('pos'); ?>" href="<?php echo htmlspecialchars($posUrl('pos')); ?>">
                <i class="fas fa-desktop" style="width: 20px; text-align: center;"></i><span>Point of Sale</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('holds'); ?>" href="<?php echo htmlspecialchars($posUrl('holds')); ?>">
                <i class="fas fa-clock-rotate-left" style="width: 20px; text-align: center;"></i><span>On Hold</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('orders'); ?>" href="<?php echo htmlspecialchars($posUrl('orders')); ?>">
                <i class="fas fa-list-ul" style="width: 20px; text-align: center;"></i><span>Orders</span>
            </a>
            
            <?php if ($posLevel >= 1): ?>
                <div style="margin: 20px 16px 10px; font-size: 10px; font-weight: 800; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1px;">Management</div>
                <a class="pos-side-link <?php echo $activeClass('products'); ?>" href="<?php echo htmlspecialchars($posUrl('products')); ?>">
                    <i class="fas fa-boxes-stacked" style="width: 20px; text-align: center;"></i><span>Inventory</span>
                </a>
                <a class="pos-side-link <?php echo $activeClass('customers'); ?>" href="<?php echo htmlspecialchars($posUrl('customers')); ?>">
                    <i class="fas fa-user-group" style="width: 20px; text-align: center;"></i><span>Customers</span>
                </a>
                <?php if ($posLevel >= 3): ?>
                <a class="pos-side-link <?php echo $activeClass('reports'); ?>" href="<?php echo htmlspecialchars($posUrl('reports')); ?>">
                    <i class="fas fa-chart-line" style="width: 20px; text-align: center;"></i><span>Analytics</span>
                </a>
                <?php endif; ?>
                <a class="pos-side-link <?php echo $activeClass('settings'); ?>" href="<?php echo htmlspecialchars($posUrl('settings')); ?>">
                    <i class="fas fa-gear" style="width: 20px; text-align: center;"></i><span>Settings</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="pos-sidebar__footer" style="padding-top: 24px; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05);">
            <a class="pos-side-link" href="<?php echo htmlspecialchars($logoutUrl); ?>" style="color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.1); background: rgba(239, 68, 68, 0.05);">
                <i class="fas fa-right-from-bracket" style="width: 20px; text-align: center;"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="pos-main">
        <header class="pos-topbar">
            <div class="pos-header-left" style="display: flex; align-items: center; gap: 15px;">
                <button class="pos-sidebar-toggle" type="button" onclick="window.__posToggleSidebar && window.__posToggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);"></div>
                    <span style="font-weight: 800; font-size: 15px; color: var(--pos-text); text-transform: capitalize; letter-spacing: -0.2px;">
                        <?php echo htmlspecialchars($pageTitle ?? ucfirst($activeNav === 'pos' ? 'Terminal' : ($activeNav ?: 'Dashboard'))); ?>
                    </span>
                </div>
            </div>

            <div class="pos-header-right" style="margin-left: auto; display: flex; align-items: center; gap: 30px;">
                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                     <div id="posClock" style="font-size: 18px; font-weight: 900; color: var(--pos-text); letter-spacing: -0.5px;"><?php echo date('H:i'); ?></div>
                     <div style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;"><?php echo date('F d, Y'); ?></div>
                </div>

                <div class="header-divider" style="width: 1px; height: 32px; background: var(--pos-border);"></div>

                <div class="pos-user-profile" style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <div style="text-align: right;">
                        <span class="pos-user-name" style="display: block; font-weight: 800; font-size: 14px; color: var(--pos-text);">Administrator</span>
                        <span class="pos-user-role" style="display: block; font-size: 11px; font-weight: 700; color: #6366f1;">Super Admin</span>
                    </div>
                    <div class="pos-avatar" style="width: 44px; height: 44px; border-radius: 14px; background: #eef2ff; color: #6366f1; display: grid; place-items: center; font-weight: 900; font-size: 14px; border: 1px solid #c7d2fe;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>


            <script>
                // Simple clock
                setInterval(() => {
                    const now = new Date();
                    document.getElementById('posClock').textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                }, 1000);
            </script>
        </header>

        <div class="pos-page">

<script>
(function() {
    function setOpen(open) {
        var shell = document.getElementById('posShell');
        var overlay = document.getElementById('posOverlay');
        if (!shell || !overlay) return;
        shell.classList.toggle('pos-shell--open', !!open);
        overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    function toggle() {
        var shell = document.getElementById('posShell');
        if (!shell) return;
        setOpen(!shell.classList.contains('pos-shell--open'));
    }

    window.__posToggleSidebar = toggle;

    var overlay = document.getElementById('posOverlay');
    if (overlay) {
        overlay.addEventListener('click', function() { setOpen(false); });
    }

    // Close sidebar on resize up (desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 980) setOpen(false);
    });
})();
</script>

<script>
(function() {
    if (window.POSUI) return;

    function ensureToastHost() {
        var host = document.querySelector('.pos-toast-host');
        if (host) return host;
        host = document.createElement('div');
        host.className = 'pos-toast-host';
        document.body.appendChild(host);
        return host;
    }

    function toast(opts) {
        opts = opts || {};
        var type = opts.type || 'info';
        var title = opts.title || (type === 'danger' ? 'Error' : type === 'warning' ? 'Warning' : type === 'success' ? 'Success' : 'Info');
        var message = opts.message || '';
        var timeout = typeof opts.timeout === 'number' ? opts.timeout : 2800;

        var host = ensureToastHost();
        var el = document.createElement('div');
        el.className = 'pos-toast pos-toast--' + type;
        el.innerHTML =
            '<span class="pos-toast__dot"></span>' +
            '<div style="min-width:0;">' +
                '<p class="pos-toast__title">' + String(title) + '</p>' +
                '<p class="pos-toast__msg">' + String(message) + '</p>' +
            '</div>';
        host.appendChild(el);

        setTimeout(function() {
            if (el && el.parentNode) el.parentNode.removeChild(el);
        }, timeout);
    }

    function buildModal(opts, isConfirm) {
        opts = opts || {};
        var type = opts.type || 'info';
        var title = opts.title || (isConfirm ? 'Please confirm' : 'Message');
        var subtitle = opts.subtitle || '';
        var message = opts.message || '';
        var okText = opts.okText || (isConfirm ? 'Yes' : 'OK');
        var cancelText = opts.cancelText || 'Cancel';

        var existing = document.querySelector('.pos-modal-overlay');
        if (existing) existing.remove();

        var overlay = document.createElement('div');
        overlay.className = 'pos-modal-overlay';

        var modal = document.createElement('div');
        modal.className = 'pos-modal pos-modal--' + type;
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');

        var icon = '<i class="fas fa-circle-info"></i>';
        if (type === 'danger') icon = '<i class="fas fa-triangle-exclamation"></i>';
        if (type === 'warning') icon = '<i class="fas fa-triangle-exclamation"></i>';
        if (type === 'success') icon = '<i class="fas fa-circle-check"></i>';

        modal.innerHTML =
            '<div class="pos-modal__header">' +
                '<div class="pos-modal__title">' +
                    '<span class="pos-modal__icon">' + icon + '</span>' +
                    '<div style="min-width:0;">' +
                        '<h3>' + String(title) + '</h3>' +
                        (subtitle ? '<p>' + String(subtitle) + '</p>' : '') +
                    '</div>' +
                '</div>' +
                '<button class="pos-modal__close" type="button" aria-label="Close"><i class="fas fa-xmark"></i></button>' +
            '</div>' +
            '<div class="pos-modal__body">' + String(message) + '</div>' +
            '<div class="pos-modal__actions">' +
                (isConfirm ? '<button class="pos-modal-btn" type="button" data-pos-cancel="1">' + String(cancelText) + '</button>' : '') +
                '<button class="pos-modal-btn primary" type="button" data-pos-ok="1">' + String(okText) + '</button>' +
            '</div>';

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        function close() {
            if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
        }

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) close();
        });
        modal.querySelector('.pos-modal__close').addEventListener('click', close);

        return { overlay: overlay, modal: modal, close: close };
    }

    function alertModal(opts) {
        opts = opts || {};
        var m = buildModal(opts, false);
        var ok = m.modal.querySelector('[data-pos-ok="1"]');
        ok.addEventListener('click', function() {
            m.close();
            if (typeof opts.onOk === 'function') opts.onOk();
        });
    }

    function confirmModal(opts) {
        opts = opts || {};
        var m = buildModal(opts, true);

        var ok = m.modal.querySelector('[data-pos-ok="1"]');
        var cancel = m.modal.querySelector('[data-pos-cancel="1"]');

        ok.addEventListener('click', function() {
            m.close();
            if (typeof opts.onOk === 'function') opts.onOk();
        });
        cancel.addEventListener('click', function() {
            m.close();
            if (typeof opts.onCancel === 'function') opts.onCancel();
        });
    }

    window.POSUI = {
        toast: toast,
        alert: alertModal,
        confirm: confirmModal
    };

    // Auto-handle confirm links (replace native confirm())
    document.addEventListener('click', function(e) {
        var el = e.target && e.target.closest ? e.target.closest('[data-pos-confirm]') : null;
        if (!el) return;

        var msg = el.getAttribute('data-pos-confirm') || 'Are you sure?';
        var href = el.getAttribute('href');
        if (!href) return;

        e.preventDefault();
        confirmModal({
            type: 'warning',
            title: 'Confirm action',
            message: msg,
            okText: 'Yes',
            cancelText: 'Cancel',
            onOk: function() { window.location.href = href; }
        });
    });
})();
</script>
