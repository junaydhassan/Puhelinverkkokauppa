<?php
session_start();


 
$message = '';
$messageType = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tunnus = trim($_POST['tunnus']);
    $salasana = $_POST['salasana'];
    
    if (empty($tunnus) || empty($salasana)) {
        $message = 'Käyttäjätunnus ja salasana ovat pakollisia!';
        $messageType = 'error';
    } else {
        try {
            // Use $pdo from config.php
            $stmt = $pdo->prepare("SELECT * FROM puh_kayttajat WHERE kayttajatunnus = ?");
            $stmt->execute([$tunnus]);
            $user = $stmt->fetch();
            
            // Check password (both hashed and plain text for compatibility)
            if ($user && password_verify($salasana, $user['salasana'])) {
                $_SESSION['kirjautunut'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['nimi'] = $user['nimi'];        // Store the display name
                $_SESSION['kayttajatunnus'] = $user['kayttajatunnus']; // Store the login username  
                $_SESSION['rooli'] = $user['rooli'];
                
                header("Location: index.php");
                exit();
            } else {
                $message = 'Väärä käyttäjätunnus tai salasana!';
                $messageType = 'error';
            }
        } catch(PDOException $e) {
            $message = 'Tietokantavirhe: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// If already logged in, redirect
if (isset($_SESSION['kirjautunut']) && $_SESSION['kirjautunut'] === true) {
    header("Location: index.php");
    exit();
}
?>
<script>
    function showPassword(type) {
        const passwordField = document.getElementById('salasana');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    } 
</script>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhoneShop - Kirjaudu</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Login-sivun erilliset tyylit */
        body.login-page {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #6b1fa3 0%, #f5f1ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-header {
            background-color: #6b1fa3;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 120px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .login-back {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .login-back a {
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

        .login-title {
            font-size: 26px;
            font-weight: 700;
            font-style: italic;
            color: #ffffff;
            margin: 0;
        }

        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px 60px;
        }

        .login-card {
            background: #6b1fa3;
            border-radius: 30px;
            border: 3px solid #5a52c0;
            padding: 40px 50px 50px;
            max-width: 500px;
            width: 100%;
            color: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }

        .login-card h1 {
            text-align: center;
            margin: 0 0 30px;
            font-size: 26px;
            font-weight: 700;
        }

        .login-field {
            display: grid;
            grid-template-columns: auto 1fr;
            column-gap: 14px;
            justify-content: center;
            align-items: center;
            margin-bottom: 22px;
            font-size: 20px;
            font-weight: 600;
        }

        .login-field label {
            text-align: right;
            width: 130px;
        }

        .login-field input {
            width: 220px;
            padding: 12px 10px;
            font-size: 18px;
            border: 1px solid #dcdcdc;
            border-radius: 4px;
        }

        .login-actions {
            text-align: center;
            margin-top: 20px;
        }

        .login-submit {
            background: #ffffff;
            color: #6b1fa3;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .login-submit:hover {
            filter: brightness(0.95);
        }

        .login-links {
            margin-top: 12px;
            font-size: 15px;
        }

        .login-links a {
            color: #ffd6ff;
            text-decoration: underline;
            font-weight: 600;
        }

        .message {
            margin-bottom: 16px;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.15);
        }

        .login-footer {
            background-color: #6b1fa3;
            padding: 50px 20px;
            text-align: center;
            margin-top: auto;
        }

        @media (max-width: 540px) {
            .login-card {
                padding: 30px 24px 36px;
                border-radius: 22px;
            }

            .login-field {
                flex-direction: column;
                align-items: flex-start;
            }

            .login-field input {
                width: 100%;
            }
        }
    </style>
</head>
<body class="login-page">
    <header class="login-header">
        <div class="login-back"><a href="index.html">Takaisin</a></div>
        <h2 class="login-title">PhoneShop</h2>
    </header>

    <main class="login-wrapper">
        <div class="login-card">
            <form action="login.php" method="post">
                <h1>Kirjaudu</h1>

                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="login-field">
                    <label for="gmail">Sähköposti:</label>
                    <input type="text" name="gmail" id="gmail" required 
                        value="<?php echo isset($_POST['gmail']) ? htmlspecialchars($_POST['gmail']) : ''; ?>">
                </div>

                <div class="login-field">
                    <label for="salasana">Salasana:</label>
                    <input type="password" name="salasana" id="salasana" required>
                </div>

                <div class="login-actions">
                    <input class="login-submit" type="submit" value="Kirjaudu">
                    <div class="login-links">
                        Eikö ole käyttäjää? <a href="registry.php">Tee käyttäjä</a>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer class="login-footer">
    </footer>
</body>
</html>