<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhoneShop - Ostoskori</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Ostoskori-sivun tyylit */
        body.cart-page {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #e8e8e8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .cart-header {
            background-color: #7c3aed;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 120px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .cart-back {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .cart-back a {
            display: inline-block;
            background: #ffffff;
            color: #1f1f1f;
            text-decoration: none;
            font-weight: 600;
            font-size: 20px;
            padding: 14px 26px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .cart-header-right {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 15px;
        }

        .cart-header-right .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            background-color: #ffffff;
            color: #1f1f1f;
        }

        .cart-title {
            font-size: 26px;
            font-weight: 700;
            font-style: italic;
            color: #ffffff;
            margin: 0;
        }

        .cart-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 60px;
        }

        .cart-heading {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-decoration: underline;
        }

        .cart-box {
            background: #7c3aed;
            border-radius: 50px;
            padding: 60px;
            width: 100%;
            max-width: 900px;
            min-height: 400px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .order-button-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: auto;
        }

        .order-button {
            background: #ffffff;
            color: #333;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 14px 30px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .order-button:hover {
            filter: brightness(0.95);
        }

        .cart-footer {
            background-color: #7c3aed;
            padding: 50px 20px;
            text-align: center;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .cart-box {
                padding: 40px 30px;
                border-radius: 30px;
            }

            .cart-heading {
                font-size: 26px;
            }

            .cart-header-right {
                flex-direction: column;
                gap: 8px;
            }

            .cart-header-right .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body class="cart-page">
    <header class="cart-header">
        <div class="cart-back"><a href="index.html">Takaisin</a></div>
        <h2 class="cart-title">PhoneShop</h2>
        <div class="cart-header-right">
            <a class="btn" href="login.php">Kirjaudu</a>
            <a class="btn" href="registry.php">Rekisteröidy</a>
        </div>
    </header>

    <main class="cart-main">
        <h1 class="cart-heading">Ostoskori</h1>
        <div class="cart-box">
            <div class="order-button-wrapper">
                <button class="order-button">Tilaa</button>
            </div>
        </div>
    </main>

    <footer class="cart-footer">
    </footer>
</body>
</html>
