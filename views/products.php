<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

$productModel = new ProductModel();
$categoryModel = new CategoryModel();

$products = $productModel->getAllProducts();
$editId = (int)($_GET['edit'] ?? 0);
$editing = $editId ? $productModel->getProductById($editId) : null;

$categories = $categoryModel->getAllCategories();
$categoryMap = [];
foreach ($categories as $c) {
    $categoryMap[$c['id']] = $c['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/store-style.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/pages.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css"> 
</head>
<body>
    <header class="top-header">
        <div class="logo">
            <img src="../assets/images/logo.png" alt="Giga">
        </div>
        <nav class="nav-links">
            <a href="index.php" class="nav-link-btn">Home</a>
            <a href="orders.php" class="nav-link-btn">Orders</a>
            <a href="dashboard.php" class="nav-link-btn">Users</a>
            <a href="../controllers/AuthController.php?action=logout" class="nav-link-btn logout">Logout</a>
        </nav>
        <button id="theme-toggle-btn" class="theme-toggle" aria-pressed="false" title="Toggle theme"></button>
    </header>

    <main class="app-main admin-container">
        <div class="page-header">
            <h1 class="page-title">Products Management</h1>
            <p class="page-description">
                Add, edit, and remove products. Organize items using categories.
            </p>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Add/Edit Form Card -->
        <section class="card form-card">
            <h2 class="card-title">
                <?= $editing ? 'Edit Product #' . (int)$editing['id'] : 'Add New Product' ?>
            </h2>
            <form action="../controllers/ProductController.php?action=<?= $editing ? 'update' : 'create' ?>" method="post" class="product-form">
                <?php if ($editing): ?>
                    <input type="hidden" name="product_id" value="<?= (int)$editing['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($editing['name'] ?? '') ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Price <span class="required">*</span></label>
                        <input type="text" id="price" name="price" required value="<?= htmlspecialchars($editing['price'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($editing['image_url'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category <span class="required">*</span></label>
                        <select id="category_id" name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= ($editing && (int)$editing['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?= $editing ? 'Save Changes' : 'Add Product' ?>
                    </button>
                    <?php if ($editing): ?>
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <!-- Products Table -->
        <section class="card table-card">
            <h2 class="card-title">All Products</h2>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="empty-state">No products found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td><?= (int)$p['id'] ?></td>
                                    <td class="product-name"><?= htmlspecialchars($p['name']) ?></td>
                                    <td>$<?= htmlspecialchars(number_format((float)$p['price'], 2)) ?></td>
                                    <td><?= htmlspecialchars($categoryMap[$p['category_id']] ?? 'Unknown') ?></td>
                                    <td class="actions">
                                        <a href="products.php?edit=<?= (int)$p['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                        <a href="../controllers/ProductController.php?action=delete&product_id=<?= (int)$p['id'] ?>"
                                           onclick="return confirm('Are you sure you want to delete this product?');"
                                           class="btn btn-sm btn-delete">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="../assets/js/theme.js"></script>
</body>
</html>