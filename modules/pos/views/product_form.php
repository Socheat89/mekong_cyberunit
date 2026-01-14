<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - <?php echo isset($product) ? 'Edit' : 'Add'; ?> Product</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
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
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="header">
            <h1><?php echo isset($product) ? 'Edit Product' : 'Add New Product'; ?></h1>
        </div>

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <h2 class="section-title">📦 Basic Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="required">Product Name</label>
                            <div class="input-group name">
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <div class="input-group sku">
                                <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">💰 Pricing & Inventory</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="required">Price</label>
                            <div class="input-group price">
                                <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price'] ?? ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity" class="required">Stock Quantity</label>
                            <div class="input-group stock">
                                <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo $product['stock_quantity'] ?? 0; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">🏷️ Classification</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="">No Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($product) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="active" <?php echo (!isset($product) || $product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (isset($product) && $product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">📋 Additional Details</h2>
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <div class="image-upload" onclick="document.getElementById('image').click()">
                            <div>📸 Click to upload image or drag & drop<br><small>Images will be automatically converted to WebP format</small></div>
                            <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" style="display: none;" onchange="previewImage(event)">
                            <?php if (isset($product) && $product['image']): ?>
                                <div class="image-preview">
                                    <img src="/Mekong_CyberUnit/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="Current image" class="current-image">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="barcode">Barcode</label>
                        <div class="input-group barcode">
                            <input type="text" id="barcode" name="barcode" value="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Enter product description..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn"><?php echo isset($product) ? '✏️ Update Product' : '➕ Add Product'; ?></button>
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.image-preview') || document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="current-image">`;
                    
                    const uploadDiv = document.querySelector('.image-upload');
                    if (!uploadDiv.querySelector('.image-preview')) {
                        uploadDiv.appendChild(preview);
                    } else {
                        uploadDiv.querySelector('.image-preview').innerHTML = `<img src="${e.target.result}" alt="Preview" class="current-image">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Drag and drop functionality
        const imageUpload = document.querySelector('.image-upload');
        imageUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUpload.classList.add('dragover');
        });
        imageUpload.addEventListener('dragleave', () => {
            imageUpload.classList.remove('dragover');
        });
        imageUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUpload.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('image').files = files;
                previewImage({ target: { files: files } });
            }
        });
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>