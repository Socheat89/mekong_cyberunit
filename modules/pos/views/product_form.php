<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - <?php echo isset($product) ? 'Edit' : 'Add'; ?> Product</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #fff;
            color: #333;
            padding: 30px 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 600;
        }
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .btn {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s, color 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            background: #f0f0f0;
        }
        .btn-primary {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        .image-upload {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .image-upload:hover {
            border-color: #007bff;
            background: #f0f8ff;
        }
        .image-upload.dragover {
            border-color: #007bff;
            background: #e7f3ff;
        }
        .current-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            display: block;
        }
        .image-preview {
            margin-top: 10px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .required::after {
            content: ' *';
            color: #dc3545;
        }
        .btn-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .section-title {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: #007bff;
            border-radius: 2px;
        }
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0;
        }
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            max-width: none;
            margin: 0;
        }
        .nav-brand {
            font-size: 1.5em;
            font-weight: 600;
            color: #333;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 20px;
        }
        .nav-links li a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links li a:hover {
            background: #f8f9fa;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .header { padding: 25px 20px; }
            .header h1 { font-size: 2em; }
            .form-container { padding: 20px; }
            .form-row { grid-template-columns: 1fr; gap: 15px; }
            .btn-container { flex-direction: column; align-items: center; }
            .btn { width: 100%; max-width: 300px; }
            .nav-links { gap: 15px; }
            .nav-links li a { padding: 8px 12px; font-size: 14px; }
        }
    </style>
    <style>
        .form-card { background: white; border-radius: 24px; padding: 40px; border: 1px solid var(--pos-border); max-width: 900px; margin: 0 auto; }
        .form-label { display: block; font-size: 13px; font-weight: 800; color: var(--pos-text); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-input { width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid var(--pos-border); background: #f8fafc; font-size: 15px; font-weight: 600; color: var(--pos-text); transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: var(--pos-brand-a); background: white; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .required-star { color: #ef4444; margin-left: 2px; }
        
        .upload-zone { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px; text-align: center; background: #f8fafc; transition: all 0.2s; cursor: pointer; position: relative; overflow: hidden; }
        .upload-zone:hover { border-color: var(--pos-brand-a); background: #f1f5f9; }
        .upload-zone.dragover { border-color: var(--pos-brand-a); background: #e0e7ff; }
        .preview-img { max-width: 100%; border-radius: 12px; margin-top: 15px; box-shadow: var(--pos-shadow-sm); }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 32px; justify-content: center; text-align: center;">
            <div class="pos-title">
                <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 8px;">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products" class="pos-icon-btn" style="width: 36px; height: 36px;"><i class="fas fa-arrow-left"></i></a>
                    <span class="pos-pill" style="font-size: 12px; background: #eef2ff; color: #4338ca;">Inventory Management</span>
                </div>
                <h1 class="text-gradient"><?php echo isset($product) ? 'Edit Product' : 'Catalog New Item'; ?></h1>
                <p>Maintain your product catalog with detailed specifications.</p>
            </div>
        </div>

        <div class="form-card pos-shadow-sm">
            <form method="POST" enctype="multipart/form-data">
                
                <section style="margin-bottom: 40px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i> General Information
                    </h3>
                    <div class="form-row">
                        <div style="grid-column: span 2;">
                            <label class="form-label">Product Name <span class="required-star">*</span></label>
                            <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required placeholder="e.g. Wireless Headset">
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-input">
                                <option value="">Uncategorized</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($product) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Availability Status</label>
                            <select name="status" class="form-input">
                                <option value="active" <?php echo (!isset($product) || $product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (isset($product) && $product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section style="margin-bottom: 40px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-tag"></i> Pricing & Inventory
                    </h3>
                    <div class="form-row">
                        <div>
                            <label class="form-label">Selling Price ($) <span class="required-star">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-input" value="<?php echo $product['price'] ?? ''; ?>" required placeholder="0.00">
                        </div>
                        <div>
                            <label class="form-label">Current Stock <span class="required-star">*</span></label>
                            <input type="number" name="stock_quantity" class="form-input" value="<?php echo $product['stock_quantity'] ?? 0; ?>" required placeholder="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label class="form-label">SKU / Code</label>
                            <input type="text" name="sku" class="form-input" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>" placeholder="PROD-001">
                        </div>
                        <div>
                            <label class="form-label">Barcode (UPC/EAN)</label>
                            <input type="text" name="barcode" class="form-input" value="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>" placeholder="Scan or type barcode">
                        </div>
                    </div>
                </section>

                <section style="margin-bottom: 40px;">
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--pos-brand-a); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-image"></i> Visuals & Description
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Product Image</label>
                        <div class="upload-zone" onclick="document.getElementById('image-input').click()">
                            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                            <div id="upload-placeholder" style="<?php echo (isset($product) && $product['image']) ? 'display:none;' : ''; ?>">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: var(--pos-brand-a); margin-bottom: 15px;"></i>
                                <div style="font-weight: 800; color: var(--pos-text);">Tap to upload or drag image here</div>
                                <div style="font-size: 12px; color: var(--pos-muted); margin-top: 5px;">Supports: JPG, PNG, WebP (Max 2MB)</div>
                            </div>
                            <div id="image-preview-container" style="<?php echo (isset($product) && $product['image']) ? '' : 'display:none;'; ?>">
                                <?php if (isset($product) && $product['image']): ?>
                                    <img src="/Mekong_CyberUnit/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" class="preview-img" style="max-height: 250px;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 24px;">
                        <label class="form-label">Detailed Description</label>
                        <textarea name="description" class="form-input" rows="4" style="resize: none;" placeholder="Provide features, notes, or raw materials..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                </section>

                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 32px; border-top: 1px solid var(--pos-border);">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products" class="pos-pill" style="background: white; color: var(--pos-text); border: 1px solid var(--pos-border); padding: 14px 28px;">
                        Discard
                    </a>
                    <button type="submit" class="pos-pill" style="padding: 14px 40px; border: none; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);">
                        <?php echo isset($product) ? 'Update Item' : 'Add to Catalog'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const placeholder = document.getElementById('upload-placeholder');
                    const previewCont = document.getElementById('image-preview-container');
                    
                    placeholder.style.display = 'none';
                    previewCont.style.display = 'block';
                    previewCont.innerHTML = `<img src="${e.target.result}" class="preview-img" style="max-height: 250px;">`;
                };
                reader.readAsDataURL(file);
            }
        }

        const zone = document.querySelector('.upload-zone');
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('dragover');
        });
        zone.addEventListener('dragleave', () => {
            zone.classList.remove('dragover');
        });
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('image-input').files = files;
                previewImage({ files: files });
            }
        });
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>