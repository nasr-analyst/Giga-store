<?php
session_start();

// Restrict to admins only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: ../views/login.php');
    exit;
}

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

$productModel = new ProductModel();
$categoryModel = new CategoryModel();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        // Handle create
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if (empty($name) || $price <= 0 || $categoryId <= 0) {
            $_SESSION['error'] = 'Please fill all required fields with valid data';
            header('Location: ../views/products.php');
            exit;
        }

        if ($productModel->createProduct($name, $description, $price, $imageUrl, $categoryId)) {
            $_SESSION['success'] = 'Product created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create product';
        }
        header('Location: ../views/products.php');
        exit;
    } elseif ($action === 'update') {
        // Handle update
        $productId = (int) ($_POST['product_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if ($productId <= 0 || empty($name) || $price <= 0 || $categoryId <= 0) {
            $_SESSION['error'] = 'Please fill all required fields with valid data';
            header('Location: ../views/products.php');
            exit;
        }

        if ($productModel->updateProduct($productId, $name, $description, $price, $imageUrl, $categoryId)) {
            $_SESSION['success'] = 'Product updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update product';
        }
        header('Location: ../views/products.php');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        // Handle delete
        $productId = (int) ($_GET['product_id'] ?? 0);

        if ($productId <= 0) {
            $_SESSION['error'] = 'Invalid product ID';
            header('Location: ../views/products.php');
            exit;
        }

        if ($productModel->deleteProduct($productId)) {
            $_SESSION['success'] = 'Product deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete product';
        }
        header('Location: ../views/products.php');
        exit;
    }
}

// Default: redirect back if no valid action
header('Location: ../views/products.php');
exit;
?>