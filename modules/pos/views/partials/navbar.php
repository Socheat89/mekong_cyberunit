<?php
// Shared POS layout shell (sidebar + topbar).
// Expected optional vars from caller:
//   - $activeNav: one of dashboard|pos|holds|products|orders|customers|reports

$host = $_SERVER['HTTP_HOST'] ?? '';
$basePath = '/Mekong_CyberUnit';
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

    <aside class="pos-sidebar">
        <div class="pos-sidebar__brand">
            <a class="pos-brand" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>">
                <div class="pos-brand__logo">
                    <i class="fas fa-terminal"></i>
                </div>
                <div class="pos-brand__text">
                    <span class="pos-brand__title"><?php echo htmlspecialchars($tenantName); ?></span>
                    <span class="pos-brand__sub">Cyber Unit POS</span>
                </div>
            </a>
        </div>

        <nav class="pos-side-nav">
            <?php
            $posLevel = 0;
            if (class_exists('Tenant')) {
               $posLevel = Tenant::getPosLevel();
            }
            if ($isDevPos) $posLevel = 3; 
            ?>

            <a class="pos-side-link <?php echo $activeClass('dashboard'); ?>" href="<?php echo htmlspecialchars($posUrl('dashboard')); ?>">
                <i class="fas fa-chart-pie"></i><span>Overview</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('pos'); ?>" href="<?php echo htmlspecialchars($posUrl('pos')); ?>">
                <i class="fas fa-desktop"></i><span>Point of Sale</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('holds'); ?>" href="<?php echo htmlspecialchars($posUrl('holds')); ?>">
                <i class="fas fa-clock-rotate-left"></i><span>On Hold</span>
            </a>
            <a class="pos-side-link <?php echo $activeClass('orders'); ?>" href="<?php echo htmlspecialchars($posUrl('orders')); ?>">
                <i class="fas fa-list-ul"></i><span>Orders</span>
            </a>
            
            <?php if ($posLevel >= 1): ?>
                <div style="margin: 24px 16px 8px; font-size: 10px; font-weight: 800; color: rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 1.5px;">Management</div>
                <a class="pos-side-link <?php echo $activeClass('products'); ?>" href="<?php echo htmlspecialchars($posUrl('products')); ?>">
                    <i class="fas fa-boxes-stacked"></i><span>Inventory</span>
                </a>
                <a class="pos-side-link <?php echo $activeClass('customers'); ?>" href="<?php echo htmlspecialchars($posUrl('customers')); ?>">
                    <i class="fas fa-user-group"></i><span>Customers</span>
                </a>
                <?php if ($posLevel >= 3): ?>
                <a class="pos-side-link <?php echo $activeClass('reports'); ?>" href="<?php echo htmlspecialchars($posUrl('reports')); ?>">
                    <i class="fas fa-chart-line"></i><span>Analytics</span>
                </a>
                <?php endif; ?>
                <a class="pos-side-link <?php echo $activeClass('settings'); ?>" href="<?php echo htmlspecialchars($posUrl('settings')); ?>">
                    <i class="fas fa-gear"></i><span>Settings</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="pos-sidebar__footer">
            <a class="pos-side-link" href="<?php echo htmlspecialchars($logoutUrl); ?>">
                <i class="fas fa-right-from-bracket"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="pos-main">
        <header class="pos-topbar">
            <div class="pos-header-left">
                <button class="pos-sidebar-toggle" type="button" onclick="window.__posToggleSidebar && window.__posToggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="pos-status-indicator">
                    <div class="pos-status-dot"></div>
                    <span class="pos-status-text">
                        <?php echo htmlspecialchars($pageTitle ?? ucfirst($activeNav === 'pos' ? 'Terminal' : ($activeNav ?: 'Dashboard'))); ?>
                    </span>
                </div>
            </div>

            <div class="pos-header-right">
                <div class="pos-clock-widget">
                     <div id="posClock" class="pos-clock-time"><?php echo date('H:i'); ?></div>
                     <div class="pos-clock-date"><?php echo date('F d, Y'); ?></div>
                </div>

                <div class="pos-header-divider"></div>

                <div class="pos-user-profile">
                    <div class="pos-user-details">
                        <?php 
                        $user = Auth::user(); 
                        $userName = $user ? (isset($user['first_name']) && $user['first_name'] ? $user['first_name'] . ' ' . ($user['last_name'] ?? '') : ($user['username'] ?? 'Administrator')) : 'Administrator';
                        $roleName = $user['role_name'] ?? 'Super Admin';
                        ?>
                        <span class="pos-user-name"><?php echo htmlspecialchars($userName); ?></span>
                        <span class="pos-user-role"><?php echo htmlspecialchars($roleName); ?></span>
                    </div>
                    <div class="pos-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>

            <script>
                setInterval(() => {
                    const now = new Date();
                    const clock = document.getElementById('posClock');
                    if(clock) clock.textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});
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
