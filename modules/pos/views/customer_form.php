<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - <?php echo isset($customer) ? 'Edit' : 'Add'; ?> Customer</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-card { background: white; border-radius: 20px; padding: 40px; border: 1px solid var(--pos-border); max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 13px; font-weight: 800; color: var(--pos-text); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-input { width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid var(--pos-border); background: #f8fafc; font-size: 15px; font-weight: 600; color: var(--pos-text); transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: var(--pos-brand-a); background: white; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .required-star { color: #ef4444; margin-left: 2px; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'customers'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 32px; justify-content: center; text-align: center;">
            <div class="pos-title">
                <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 8px;">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers" class="pos-icon-btn" style="width: 36px; height: 36px;"><i class="fas fa-arrow-left"></i></a>
                    <span class="pos-pill" style="font-size: 12px; background: #eef2ff; color: #4338ca;">Directories</span>
                </div>
                <h1 class="text-gradient"><?php echo isset($customer) ? 'Edit Customer' : 'New Customer Profile'; ?></h1>
                <p>Ensure accurate details for better relationship management.</p>
            </div>
        </div>

        <div class="form-card pos-shadow-sm">
            <form method="POST">
                
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user-circle"></i> Identity Details
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required-star">*</span></label>
                        <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($customer['name'] ?? ''); ?>" required placeholder="e.g. John Doe">
                    </div>
                </div>

                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-address-card"></i> Contact Information
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>" placeholder="john@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-input" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" placeholder="+1 234 567 8900">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 40px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Street Address</label>
                        <textarea name="address" class="form-input" rows="3" style="resize: none;" placeholder="Enter complete address..."><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 32px; border-top: 1px solid var(--pos-border);">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers" class="pos-pill" style="background: white; color: var(--pos-text); border: 1px solid var(--pos-border); padding: 14px 28px;">
                        Cancel
                    </a>
                    <button type="submit" class="pos-pill" style="padding: 14px 40px; border: none; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);">
                        <?php echo isset($customer) ? 'Save Changes' : 'Create Profile'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
