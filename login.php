<?php
session_start();


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>WARE GAME</title>
</head>
<body>
    <form  method="POST" action="game.php">
        <div class="titre">
            <h1>WAR NUMBER</h1>
        </div>
        <div class="authentification">
            <label for="username">Nom d'utilisateur:</label><br>
            <input type="text" name="username" id="username"  placeholder="Entrer votre nom"  value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"required>
        </div>
        <div class="niv_difficulte">
            <label>Difficulté:</label>
            <div class="form-check">
               <input class="form-check-input" type="radio" name="difficulte"  value="F" <?php echo (isset($_POST['difficulte']) && $_POST['difficulte'] == 'F') ? 'checked' : ''; ?> required>
               <label class="form-check-label" for="F">Facile (20 tentatives)</label>
               <input class="form-check-input" type="radio" name="difficulte"  value="M" <?php echo (isset($_POST['difficulte']) && $_POST['difficulte'] == 'M') ? 'checked' : ''; ?> required>
               <label class="form-check-label" for="M">Moyen (10 tentatives)</label>
               <input class="form-check-input" type="radio" name="difficulte"  value="D" <?php echo (isset($_POST['difficulte']) && $_POST['difficulte'] == 'D') ? 'checked' : ''; ?> required>
               <label class="form-check-label" for="D">Difficile (5 tentatives)</label>
            </div>
        </div>
        <div class="btn">
          <button type="submit" id="btn-btn">Commencer le jeu</button>
        </div>
    </form>
</body>
</html>
