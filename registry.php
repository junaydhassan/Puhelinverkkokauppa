<?php
require_once 'config.php';
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
    <title>Phone shop - Register</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
        <nav class="kirjaudut">
            <form method="POST" action="">
                <h1>Käyttäjän luonti</h1>
                
                <?php if (!empty($message)): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
                
                <label for="tunnus">Käyttäjätunnus:</label>
                <input type="text" name="tunnus" id="tunnus" required 
                       value="<?php echo isset($_POST['tunnus']) ? htmlspecialchars($_POST['tunnus']) : ''; ?>">
                <br><br>
                <label for="nimi">Nimi:</label>
                <input type="text" name="nimi" id="nimi" required 
                       value="<?php echo isset($_POST['nimi']) ? htmlspecialchars($_POST['nimi']) : ''; ?>">
                <br><br>
                <label for="gmail">Sähköposti:</label>
                <input type="email" name="gmail" id="gmail" required 
                       value="<?php echo isset($_POST['gmail']) ? htmlspecialchars($_POST['gmail']) : ''; ?>">
                <br><br>
                <label for="salasana">Salasana:</label>
                <input type="password" name="salasana" id="salasana" required 
                       placeholder="Vähintään 6 merkkiä">
                <input type="checkbox" onclick="showPassword('user')"> Näytä salasana
                <br><br>
                <label for="rooli">Rooli</label>
                <select name="rooli" id="rooli" required>
                    <option value="asiakas" selected>Asiakas</option>
                    <option value="admin">Admin</option>
                </select>
                <br><br>
                <input type="submit" value="Luo käyttäjä">
                <br><br>
                <div>
                    <p>Onko sinulla jo käyttäjätili? <a href="login.php">Kirjaudu sisään</a></p>
                </div>
            </form>
        </nav>
    </div>
</body>
</html>