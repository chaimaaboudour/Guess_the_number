<?php
session_start();
//aller vers page login si jms les champs saont vide
if(!($_SERVER["REQUEST_METHOD"]=="POST")){
    if( !(isset($POST["username"])&& isset($POST["difficulte"]))){  
        echo "<script>
                let msg = 'vous ne pouver pas y accesder sans remplir les champs necessaire !';
                alert(msg);
                window.location.href = 'login.php';
                </script>";
        exit();
    }
}

if(isset($_SESSION["hstrq"])){ 
    $hstrq=$_SESSION["hstrq"] ;
}else{
    $hstrq = [];
}  


// le nbr doit etre uniqe au niv des chiffre
function RandomNumber() {
    $Nbr = "";
    do {
        $Nbr = sprintf("%04d", mt_rand(1000, 9999));
        $chfr = str_split($Nbr);
        $unq = count(array_unique($chfr)) === count($chfr);
    } while (!$unq);
    return $Nbr;
}

// recuperer les donnees
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["difficulte"]) && isset($_POST["username"])) {
        $Nbr = RandomNumber();
        $_SESSION["Nbr"] = $Nbr;
        $_SESSION["hstrq"] = [];
        if ($_POST["difficulte"] == "F") {
            $_SESSION["tent-res"] = 20;
        } elseif ($_POST["difficulte"] == "M") {
            $_SESSION["tent-res"] = 10;
        } else {
            $_SESSION["tent-res"] = 5;
        }
        setcookie("username", $_POST["username"], time() + (86400 * 30), "/");
        setcookie("tent-res", $_SESSION["tent-res"], time() + (86400 * 30), "/");
    } elseif (isset($_POST["guess"]) && preg_match('/^[0-9]{4}$/', $_POST["guess"]) && isset($_SESSION["tent-res"])) {//verification de nbr entrer
        $guess = $_POST["guess"];
        $guess_unq = array_unique(str_split($guess));
        $Nbr = $_SESSION["Nbr"];
        if (count($guess_unq) == 4) {
            $gus_ch = str_split($guess);
            $nbr_chfr = str_split($Nbr);
            $m = 0;
            $b = 0;
            foreach ($gus_ch as $key => $chfr) {
                if ($chfr == $nbr_chfr[$key]) {
                    $m++;
                } elseif (in_array($chfr, $nbr_chfr)) {
                    $b++;
                }
            }
            $_SESSION["hstrq"][] = ["nombre" => $guess, "morts" => $m, "blesse" => $b];//stocke les variable dans historique
        } else {
            echo "Le nombre entré ne doit pas avoir de chiffres repétes.";
        }

        $_SESSION["tent-res"]--;
        setcookie("tent-res", $_SESSION["tent-res"], time() + (86400 * 30), "/");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>WAR NUMBER</title>
</head>

<body>
    <form method="POST">
        <div class="titre">
            <h1>WAR NUMBER</h1>
            <h3>Bonjour
                <?php
                if (isset($_COOKIE["username"])) {
                    echo $_COOKIE["username"];
                } else {
                    echo "Utilisateur inconnu.";
                }
                ?>
            </h3>
        </div>
        <div>
            <p>
                Les tentatives restantes :
                <?php
                if (isset($_SESSION["tent-res"])) {
                    echo $_SESSION["tent-res"];
                } 
                ?>
            </p>
            <br>
        </div>
        <label for="guess">Devinez le nombre:</label><br>
        <?php
        if (isset($_SESSION["tent-res"]) && $_SESSION["tent-res"] > 0) {
            echo '<input class="form-input" type="number" name="guess" min="1000" max="9999" value="' . (isset($_POST["guess"]) ? $_POST["guess"] : "") . '" required>';
            echo '<div class="btn"><button type="submit">Essayer</button></div>';
        } else {
            echo '<input class="form-input" type="number" name="guess" min="1000" max="9999"  required disabled>';
            echo '<div class="btn"><button type="button" disabled> Essayer</button></div>';
        }
        ?>
        <div>
            <h3>Historique des tentatives</h3>
            <ul>
                <?php foreach ($_SESSION["hstrq"] as $key => $tent) : ?>
                    <li>Tentative <?= $key + 1 ?> - Nombre: <?= $tent["nombre"] ?> | Morts: <?= $tent["morts"] ?> | Blessés: <?= $tent["blesse"] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </form>
    <?php
    //le msg de reussice ou  d echec
    if ($_SESSION["tent-res"] == 0) {
        if ($m == 4) {
            echo "<script>
                let msg = 'BRAVO vous avez deviné le nombre';
                alert(msg);
                window.location.href = 'login.php';
                </script>";
        } else {
            echo "<script>
                let msg = 'Dommage! Vous avez perdu. Le nombre était $Nbr';
                alert(msg);
                window.location.href = 'login.php';
                </script>";
        }
    }
    ?>
</body>
</html>
