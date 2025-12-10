<?php
require_once 'config.php';
session_start();

// Remove the getDatabaseConnection function and use $pdo from config.php directly

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
    <title>Phone shop - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
  </div>
        <nav class="kirjaudut">
            <form action="login.php" method="post">
                <h1>Kirjaudu sisään</h1>
                
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <i class="fa fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <label for="gmail">Sähköposti:</label>
                <input type="text" name="gmail" id="gmail" required 
                       value="<?php echo isset($_POST['gmail']) ? htmlspecialchars($_POST['gmail']) : ''; ?>">
                <br><br>
                <label for="salasana">Salasana:</label>
                <input type="password" name="salasana" id="salasana" required>
                <input type="checkbox" onclick="showPassword('user')"> Näytä salasana
                <br><br>
                <input type="submit" value="Kirjaudu sisään"><br><br>
                <span>Eikö ole käyttäjää? </span><a class="linkki" href="registry.php" style="color: var(--primary-color);">Tee käyttäjä</a>
            </form>
        </nav>
    </div>
</body>
</html>