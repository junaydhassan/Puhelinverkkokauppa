<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhoneShop - Kuoret</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header> 
        <div class="menu-icon" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>  
        <div class="logo">PhoneShop</div> <!-- Logo keskitettynä -->
        <div class="header-right"> <!--Napit -->
            <a class="btn btn-login" href="login.php">Kirjaudu</a>
            <a class="btn btn-register" href="registry.php">Rekisteröidy</a>
            <div class="cart-icon" onclick="toggleCart()">
                <img src="ostoskori.png" alt="Ostoskori">
            </div>
        </div>
    </header>

    <!-- Haku palkki -->
    <section class="search-section">
        <button class="search-btn">Hae</button>
        <input type="text" class="search-input" placeholder="Tuote">
    </section>

    <!-- Kuoret osio -->
    <section class="products-section">
        <div class="products-grid">
            <!-- iPhone case black -->
            <div class="product-card">
                <div class="product-image">
                    <img src="mustakuori.png" alt="iPhone case black">
                </div>
                <div class="product-name">Iphone case black</div>
                <div class="product-hint">Hinta:</div>
            </div>

            <!-- iPhone case red -->
            <div class="product-card">
                <div class="product-image">
                    <img src="punainenkuori.png" alt="iPhone case red">
                </div>
                <div class="product-name">Iphone case red</div>
                <div class="product-hint">Hinta:</div>
            </div>

            <!-- iPhone case green -->
            <div class="product-card">
                <div class="product-image">
                    <img src="vihreakuori.png" alt="iPhone case green">
                </div>
                <div class="product-name">Iphone case green</div>
                <div class="product-hint">Hinta:</div>
            </div>

            <!-- iPhone case pink -->
            <div class="product-card">
                <div class="product-image">
                    <img src="pinkkikuori.png" alt="iPhone case pink">
                </div>
                <div class="product-name">Iphone case pink</div>
                <div class="product-hint">Hinta:</div>
            </div>
        </div>
    </section>

    <!-- Alatunniste -->
    <footer>
    </footer>

    <!-- Valikko sivupalkki -->
    <div id="menuSidebar" class="menu-sidebar">
        <nav class="menu-nav">
            <a href="index.html" class="menu-item">Puhelimet</a>
            <a href="kuoret.php" class="menu-item">Kuoret</a>
            <a href="#" class="menu-item">Laturit</a>
        </nav>
    </div>

    <!-- Ostoskori sivuvalikko -->
    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-content">
            <a href="ostoskori.php" class="cart-button">Siirry ostoskoriin</a>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('menuSidebar');
            sidebar.classList.toggle('active');
        }

        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            sidebar.classList.toggle('active');
        }

        // Sulje valikko kun klikataan sivuvalikon ulkopuolelle
        document.addEventListener('click', function(event) {
            const cartSidebar = document.getElementById('cartSidebar');
            const menuSidebar = document.getElementById('menuSidebar');
            const cartIcon = document.querySelector('.cart-icon');
            const menuIcon = document.querySelector('.menu-icon');
            
            if (!cartSidebar.contains(event.target) && !cartIcon.contains(event.target)) {
                cartSidebar.classList.remove('active');
            }
            
            if (!menuSidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                menuSidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
