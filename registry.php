<?php

session_start();

// Remove the getDatabaseConnection function
 
$message = '';
$messageType = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tunnus = trim($_POST['tunnus']);
    $nimi = trim($_POST['nimi']);
    $email = trim($_POST['gmail']);
    $salasana = $_POST['salasana'];
    $rooli = $_POST['rooli'];
    
    // Validate input
    if (empty($tunnus) || empty($nimi) || empty($email) || empty($salasana)) {
        $message = 'Kaikki kentät ovat pakollisia!';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Virheellinen sähköpostiosoite!';
        $messageType = 'error';
    } elseif (strlen($salasana) < 6) {
        $message = 'Salasanan tulee olla vähintään 6 merkkiä pitkä!';
        $messageType = 'error';
    }
        elseif (!in_array($rooli, ['asiakas', 'admin'])) {
        $message = 'Virheellinen käyttäjärooli!';
        $messageType = 'error';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM puh_kayttajat WHERE kayttajatunnus = ? OR gmail = ?");
            $stmt->execute([$tunnus, $email]);
            
            if ($stmt->rowCount() > 0) {
                $message = 'Käyttäjätunnus tai sähköposti on jo käytössä!';
                $messageType = 'error';
            } else {
                // Hash password and insert new user
                $hashedPassword = password_hash($salasana, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO puh_kayttajat (kayttajatunnus, nimi, gmail, salasana, rooli, kayttaja_luotu) VALUES (?, ?, ?, ?, ?, NOW())");
                
                if ($stmt->execute([$tunnus, $nimi, $email, $hashedPassword, $rooli])) {
                    $message = 'Käyttäjä luotu onnistuneesti! Voit nyt kirjautua sisään.';
                    $messageType = 'success';
                    
                    // Redirect to login after 2 seconds
                    header("refresh:2;url=login.php");
                } else {
                    $message = 'Virhe käyttäjän luonnissa!';
                    $messageType = 'error';
                }
            } 
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry error
                $message = 'Käyttäjätunnus tai sähköposti on jo käytössä!';
            } else {
                $message = 'Tietokantavirhe: ' . $e->getMessage();
            }
            $messageType = 'error';
        }
    }
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
    <title>PhoneShop - Rekisteröidy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /*  */
        body.registry-page {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #6b1fa3 0%, #f5f1ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .registry-header {
            background-color: #6b1fa3;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 120px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .registry-back {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .registry-back a {
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

        .registry-title {
            font-size: 26px;
            font-weight: 700;
            font-style: italic;
            color: #ffffff;
            margin: 0;
        }

        .registry-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px 60px;
        }

        .registry-card {
            background: #6b1fa3;
            border-radius: 30px;
            border: 3px solid #5a52c0;
            padding: 40px 50px 50px;
            max-width: 500px;
            width: 100%;
            color: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }

        .registry-card h1 {
            text-align: center;
            margin: 0 0 30px;
            font-size: 26px;
            font-weight: 700;
        }

        .registry-field {
            display: grid;
            grid-template-columns: auto 1fr;
            column-gap: 14px;
            justify-content: center;
            align-items: center;
            margin-bottom: 22px;
            font-size: 20px;
            font-weight: 600;
        }

        .registry-field label {
            text-align: right;
            width: 130px;
        }

        .registry-field input,
        .registry-field select {
            width: 220px;
            padding: 12px 10px;
            font-size: 18px;
            border: 1px solid #dcdcdc;
            border-radius: 4px;
            background: #fff;
            color: #000;
        }

        .registry-actions {
            text-align: center;
            margin-top: 20px;
        }

        .registry-submit {
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

        .registry-submit:hover {
            filter: brightness(0.95);
        }

        .registry-links {
            margin-top: 12px;
            font-size: 15px;
        }

        .registry-links a {
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

        .registry-footer {
            background-color: #6b1fa3;
            padding: 50px 20px;
            text-align: center;
            margin-top: auto;
        }

        @media (max-width: 540px) {
            .registry-card {
                padding: 30px 24px 36px;
                border-radius: 22px;
            }

            .registry-field {
                flex-direction: column;
                align-items: flex-start;
            }

            .registry-field input,
            .registry-field select {
                width: 100%;
            }
        }
    </style>
</head>
<body class="registry-page">
    <header class="registry-header">
        <div class="registry-back"><a href="index.html">Takaisin</a></div>
        <h2 class="registry-title">PhoneShop</h2>
    </header>

    <main class="registry-wrapper">
        <div class="registry-card">
            <form method="POST" action="">
                <h1>Rekisteröidy</h1>

                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="registry-field">
                    <label for="tunnus">Käyttäjätunnus:</label>
                    <input type="text" name="tunnus" id="tunnus" required 
                        value="<?php echo isset($_POST['tunnus']) ? htmlspecialchars($_POST['tunnus']) : ''; ?>">
                </div>

                <div class="registry-field">
                    <label for="nimi">Nimi:</label>
                    <input type="text" name="nimi" id="nimi" required 
                        value="<?php echo isset($_POST['nimi']) ? htmlspecialchars($_POST['nimi']) : ''; ?>">
                </div>

                <div class="registry-field">
                    <label for="gmail">Sähköposti:</label>
                    <input type="email" name="gmail" id="gmail" required 
                        value="<?php echo isset($_POST['gmail']) ? htmlspecialchars($_POST['gmail']) : ''; ?>">
                </div>

                <div class="registry-field">
                    <label for="salasana">Salasana:</label>
                    <input type="password" name="salasana" id="salasana" required 
                        placeholder="Min. 6 merkkiä">
                </div>

                <div class="registry-field">
                    <label for="rooli">Rooli:</label>
                    <select name="rooli" id="rooli" required>
                        <option value="asiakas" selected>Asiakas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="registry-actions">
                    <input class="registry-submit" type="submit" value="Rekisteröidy">
                    <div class="registry-links">
                        Onko sinulla jo tili? <a href="login.php">Kirjaudu sisään</a>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer class="registry-footer">
    </footer>
</body>
</html>