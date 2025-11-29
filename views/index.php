<?php
require_once __DIR__ . '/../models/ProductModel.php';
$productModel = new ProductModel();
$products = $productModel->getAllProducts();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1200" />
    <title>Giga Store</title>
    <link rel="stylesheet" href="../assets/css/store-style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

    <div class="page">
        <header class="header">
            <div class="brand">
                <img src="../assets/images/logo.png" alt="logo" class="logo">
            </div>

            <nav class="nav">
                <div class="search">
                    <input type="text" placeholder="Search for a product">
                    <button class="btn btn-search">Search</button>
                </div>

                <div>Home</div>
                <div>Shop</div>
                <div>Blog</div>
            </nav>
        </header>

        <section class="hero">
            <div class="title">Give All You Need</div>
        </section>


        <main class="content">

            <aside class="sidebar">
                <h3>Categories</h3>
                <ul class="categories">
                    <li class="category" id="0">All Products <span style="font-size: 25px;">▸</span></li>
                    <li class="category" id="1">Home</li>
                    <li class="category" id="2">Mobile</li>
                    <li class="category" id="3">Music</li>
                    <li class="category" id="4">Other</li>
                </ul>
            </aside>


            <section class="main">
                <div class="filters">
                    <div class="section-title">Products</div>

                </div>

                <div class="products-grid">
                    <?php foreach ($products as $p):
                        $img = $p['image_url'] ?? '';
                        if ($img && strpos($img, 'http') !== 0 && strpos($img, '/') !== 0) {
                            $img = '../' . ltrim($img, '/');
                        } elseif (!$img) {
                            $img = '../assets/images/holder.jpg';
                        }
                        ?>
                        <article class="product" id="<?= (int) ($p['category_id'] ?? 0) ?>">
                            <div class="img-wrap"><img src="<?= htmlspecialchars($img) ?>"
                                    alt="<?= htmlspecialchars($p['name'] ?? '') ?>"></div>
                            <div class="meta">
                                <div class="name"><?= htmlspecialchars($p['name'] ?? 'Unnamed') ?></div>
                                <div class="price">$<?= number_format((float) ($p['price'] ?? 0), 2) ?></div>
                            </div>
                            <small class="muted"><?= htmlspecialchars($p['description'] ?? '') ?></small>
                            <div class="actions">
                                <button class="btn btn-add">Add to Cart</button>
                                <button class="btn btn-buy">Buy Now</button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>

        <section class="recommendations">
            <div class="section-title" style="padding-left:36px;">Our Recommendations</div>

            <div class="rec-grid">
                <div class="rec-card">
                    <img src="../assets/images/bujug.jpg" alt="">
                    <div class="rec-name">TWS Bujug</div>
                    <small class="muted">$29.90</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
                <div class="rec-card">
                    <img src="../assets/images/baptis.jpg" alt="">
                    <div class="rec-name">Headsound Baptis</div>
                    <small class="muted">$12.00</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
                <div class="rec-card">
                    <img src="../assets/images/cleaner.jpg" alt="">
                    <div class="rec-name">Adudu Cleaner</div>
                    <small class="muted">$29.90</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
                <div class="rec-card">
                    <img src="../assets/images/mouse.avif" alt="">
                    <div class="rec-name">Wireless Mouse</div>
                    <small class="muted">$14.50</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
                <div class="rec-card">
                    <img src="../assets/images/lamp.jpg" alt="">
                    <div class="rec-name">Smart Lamp</div>
                    <small class="muted">$18.00</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
                <div class="rec-card">
                    <img src="../assets/images/projector.jpg" alt="">
                    <div class="rec-name">Mini Projector</div>
                    <small class="muted">$59.00</small>
                    <button class="btn btn-buy">Buy Now</button>
                </div>
            </div>
        </section>

        <div class="cta">
            <div class="left">Ready to Get Our Products</div>
            <div class="form">
                <input type="email" placeholder="Your Email">
                <button>Send</button>
            </div>
        </div>

        <footer class="site-footer">
            <div class="footer-col">
                <strong>About</strong>
                <div>Blog<br>Meet The Team<br>Contact Us</div>
            </div>
            <div class="footer-col">
                <strong>Support</strong>
                <div>Contact Us<br>Shipping<br>Returns<br>FAQ</div>
            </div>
            <div class="footer-col">
                <strong>Social</strong>
                <div class="social">
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"> <i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-square-instagram"></i></a>
                    <a href="#"> <i class="fa-brands fa-linkedin"></i></a>
                </div>
                <div style="margin-top:12px; color:#aaa; font-size:13px;">© 2025 gigastore</div>
            </div>
        </footer>
    </div>
    <script src="../assets/js/main.js"></script>
</body>

</html>