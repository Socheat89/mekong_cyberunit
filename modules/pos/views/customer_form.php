<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - <?php echo htmlspecialchars($tenantName ?? 'POS'); ?></title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .form-card { background: white; border-radius: 24px; padding: 40px; border: 1.5px solid var(--pos-border); max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'customers'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; background: rgba(16, 185, 129, 0.1); color: var(--pos-success); padding: 8px 16px; border-radius: 12px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                <i class="fas fa-users-cog"></i> Client Relations
            </div>
            <h1 style="font-size: 36px; font-weight: 900; color: var(--pos-text); margin: 0;"><?php echo isset($customer) ? 'Profile Update' : 'New Client Registration'; ?></h1>
            <p style="color: var(--pos-text-muted); margin-top: 8px; font-size: 16px;">Keep your customer records accurate for personalized service.</p>
        </div>

        <div class="form-card pos-shadow-xl">
            <form method="POST">
                
                <section style="margin-bottom: 32px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 24px; height: 1.5px; background: var(--pos-primary);"></span>
                        Personal Identity
                    </h3>
                    <div class="pos-form-group">
                        <label class="pos-form-label">Full Display Name <span style="color:red;">*</span></label>
                        <input type="text" name="name" class="pos-form-control" value="<?php echo htmlspecialchars($customer['name'] ?? ''); ?>" required placeholder="e.g. Johnathan Doe">
                    </div>
                </section>

                <section style="margin-bottom: 32px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 24px; height: 1.5px; background: var(--pos-primary);"></span>
                        Communication
                    </h3>
                    <div class="pos-grid cols-2">
                        <div class="pos-form-group">
                            <label class="pos-form-label">Email Address</label>
                            <input type="email" name="email" class="pos-form-control" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>" placeholder="client@company.com">
                        </div>
                        <div class="pos-form-group">
                            <label class="pos-form-label">Primary Phone</label>
                            <input type="tel" name="phone" class="pos-form-control" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" placeholder="+00 000 000 000">
                        </div>
                    </div>
                </section>

                <section>
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 24px; height: 1.5px; background: var(--pos-primary);"></span>
                        Delivery & Location
                    </h3>
                    <div class="pos-form-group">
                        <label class="pos-form-label">Full Physical Address</label>
                        <textarea name="address" class="pos-form-control" rows="4" style="resize: none;" placeholder="Building, Street, City, State..."><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
                    </div>
                </section>

                <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 48px; border-top: 1.5px solid var(--pos-border); padding-top: 32px;">
                    <a href="<?php echo htmlspecialchars($posUrl('customers')); ?>" class="btn btn-outline" style="min-width: 140px;">
                        Discard
                    </a>
                    <button type="submit" class="btn btn-primary" style="min-width: 200px; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i> <?php echo isset($customer) ? 'Update Profile' : 'Register Client'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
