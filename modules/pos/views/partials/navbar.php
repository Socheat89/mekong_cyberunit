<?php
// Shared POS layout shell (sidebar + topbar).
// Expected optional vars from caller:
//   - $activeNav: one of dashboard|pos|holds|products|orders|customers|reports

$basePath = '/Mekong_CyberUnit';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

$tenantSlug = null;
$tenantName = 'POS';
if (class_exists('Tenant')) {
    $currentTenant = Tenant::getCurrent();
    if (is_array($currentTenant)) {
        if (!empty($currentTenant['subdomain'])) {
            $tenantSlug = $currentTenant['subdomain'];
        }
        if (!empty($currentTenant['name'])) {
            $tenantName = $currentTenant['name'];
        }
    }
}

// Detect if we are on the development direct POS route: /Mekong_CyberUnit/pos/...
$devPosPrefix = $basePath . '/pos/';
$isDevPos = (strpos($requestPath, $devPosPrefix) === 0);

$posBase = $basePath;
if ($isDevPos) {
    $posBase .= '/pos';
} elseif ($tenantSlug) {
    $posBase .= '/' . $tenantSlug . '/pos';
} else {
    // Fallback.
    $posBase .= '/pos';
}

$logoutUrl = $tenantSlug && !$isDevPos
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

<div class="pos-shell" id="posShell">
    <div class="pos-overlay" id="posOverlay" aria-hidden="true"></div>

    <aside class="pos-sidebar" style="background: #1e1b4b; border-right: 1px solid rgba(255,255,255,0.05); box-shadow: 10px 0 30px rgba(0,0,0,0.1);">
        <div class="pos-sidebar__brand" style="margin-bottom: 20px;">
            <a class="pos-brand" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>" style="padding: 10px;">
                <span class="pos-brand__logo" style="background: var(--pos-gradient-indigo); border: none; box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4);"><i class="fas fa-layer-group" style="color: white; font-size: 20px;"></i></span>
                <span class="pos-brand__text">
                    <span class="pos-brand__title" style="font-size: 18px; font-weight: 900; letter-spacing: -0.5px; color: white;">POS ADMIN</span>
                    <span class="pos-brand__sub" style="color: rgba(255,255,255,0.6); font-weight: 700;"><?php echo htmlspecialchars($tenantName); ?></span>
                </span>
            </a>
        </div>

        <nav class="pos-side-nav">
            <?php
            $posLevel = 0;
            if (class_exists('Tenant')) {
               $posLevel = Tenant::getPosLevel();
            }
            if ($isDevPos) $posLevel = 3; // Dev mode gets full access
            ?>

            <a class="pos-side-link <?php echo $activeClass('dashboard'); ?>" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-grid-2" style="font-size: 16px;"></i><span>Dashboard</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('pos'); ?>" href="<?php echo htmlspecialchars($posUrl('pos')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-cart-shopping" style="font-size: 16px;"></i><span>New Sale</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('holds'); ?>" href="<?php echo htmlspecialchars($posUrl('holds')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-pause-circle" style="font-size: 16px;"></i><span>Held Orders</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('orders'); ?>" href="<?php echo htmlspecialchars($posUrl('orders')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-receipt" style="font-size: 16px;"></i><span>Orders</span>
            </a>
            
            <?php if ($posLevel >= 1): ?>
            <a class="pos-side-link <?php echo $activeClass('products'); ?>" href="<?php echo htmlspecialchars($posUrl('products')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-box-open" style="font-size: 16px;"></i><span>Products</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('customers'); ?>" href="<?php echo htmlspecialchars($posUrl('customers')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-users-gear" style="font-size: 16px;"></i><span>Customers</span>
            </a>
            <?php endif; ?>

            <?php if ($posLevel >= 3): ?>
            <a class="pos-side-link <?php echo $activeClass('reports'); ?>" href="<?php echo htmlspecialchars($posUrl('reports')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-chart-pie" style="font-size: 16px;"></i><span>Reports</span>
            </a>
            <?php endif; ?>

            <?php if ($posLevel >= 1): ?>
            <a class="pos-side-link <?php echo $activeClass('settings'); ?>" href="<?php echo htmlspecialchars($posUrl('settings')); ?>" style="margin: 2px 0; border: none; font-size: 14px;">
                <i class="fas fa-sliders" style="font-size: 16px;"></i><span>Settings</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="pos-sidebar__footer" style="border-top: 1px solid rgba(255,255,255,0.08);">
            <a class="pos-side-link" href="<?php echo htmlspecialchars($logoutUrl); ?>" style="color: rgba(255,255,255,0.6); font-size: 14px;">
                <i class="fas fa-power-off"></i><span>Sign Out</span>
            </a>
        </div>
    </aside>

    <main class="pos-main">
        <header class="pos-topbar" style="background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--pos-border);">
            <div class="pos-header-left">
                <button class="pos-icon-btn pos-sidebar-toggle" type="button" aria-label="Toggle navigation" onclick="window.__posToggleSidebar && window.__posToggleSidebar()">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                <div class="pos-breadcrumb" style="background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid var(--pos-border); font-size: 13px;">
                    <span style="opacity: 0.7; color: var(--pos-muted);"><?php echo htmlspecialchars($tenantName); ?></span>
                    <i class="fas fa-chevron-right" style="font-size: 8px; opacity: 0.3;"></i>
                    <span class="pos-breadcrumb-active" style="color: var(--pos-brand-a);">
                        <?php 
                        echo htmlspecialchars($pageTitle ?? ucfirst($activeNav === 'pos' ? 'New Sale' : ($activeNav ?: 'Dashboard'))); 
                        ?>
                    </span>
                </div>
            </div>

            <div class="pos-header-right">
                <div class="pos-time-widget" style="background: #eef2ff; color: #4338ca; border: none; padding: 10px 18px; border-radius: 14px;">
                    <i class="far fa-clock" style="color: #4338ca;"></i>
                    <span id="posClock" style="font-weight: 800; font-variant-numeric: tabular-nums;"><?php echo date('H:i'); ?></span>
                </div>

                <div class="pos-user-profile" style="background: white; border: 1px solid var(--pos-border); padding: 6px 16px 6px 6px; border-radius: 16px;">
                    <div class="pos-avatar" style="width: 36px; height: 36px; border-radius: 12px; background: var(--pos-gradient-indigo);">TU</div>
                    <div class="pos-user-info" style="margin-left: 10px;">
                        <span class="pos-user-name" style="font-weight: 800;">Administrator</span>
                        <span class="pos-user-role" style="font-size: 11px; opacity: 0.6; font-weight: 700;">Owner Level</span>
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
