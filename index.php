<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pendu</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <?php 
    session_start();

    // Mot secret
    $reponse = "cheval";

    // Initialisation session
    if (!isset($_SESSION['mot'])) {
        $_SESSION['mot'] = $reponse;
        $_SESSION['erreurs'] = 0;
        $_SESSION['lettres_trouvees'] = [];
        $_SESSION['lettres_testees'] = [];
    }

    $nbrErreur = $_SESSION['erreurs'];
    $lettresTrouvees = $_SESSION['lettres_trouvees'];
    $lettresTestees = $_SESSION['lettres_testees'];

    // Traitement du formulaire
    if (!empty($_POST['lettre'])) {
        $lettre = strtolower($_POST['lettre']);

        // Vérifier si déjà testée
        if (!in_array($lettre, $lettresTestees)) {

            $_SESSION['lettres_testees'][] = $lettre;

            if (strpos($reponse, $lettre) !== false) {
                $_SESSION['lettres_trouvees'][] = $lettre;
            } else {
                $_SESSION['erreurs']++;
            }
        }
    }

    // Mise à jour des variables
    $nbrErreur = $_SESSION['erreurs'];
    $lettresTrouvees = $_SESSION['lettres_trouvees'];

    // Création affichage du mot
    $motAffiche = "";
    for ($i = 0; $i < strlen($reponse); $i++) {
        if (in_array($reponse[$i], $lettresTrouvees)) {
            $motAffiche .= $reponse[$i] . " ";
        } else {
            $motAffiche .= "_ ";
        }
    }

    // Vérification victoire
    $gagne = !str_contains($motAffiche, "_");
    $perdu = $nbrErreur >= 6;

  
  ?>

  <div class="pendu">
    <div class="potence">
      <div class="base"></div>
      <div class="pilier"></div>
      <div class="barre"></div>
      <div class="corde"></div>
    </div>
    <div class="bonhomme">
      <?php if ($nbrErreur >= 1) {
        echo '<div class="tete"></div>';
      } ?>
      <?php if ($nbrErreur >= 2) {
        echo '<div class="corps"></div>';
      } ?>
      <?php if ($nbrErreur >= 3) {
        echo '<div class="bras bras-gauche"></div>';
      } ?>
      <?php if ($nbrErreur >= 4) {
        echo '<div class="bras bras-droit"></div>';
      } ?>
      <?php if ($nbrErreur >= 5) {
        echo '<div class="jambe jambe-gauche"></div>';
      } ?>
      <?php if ($nbrErreur == 6) {
        echo '<div class="jambe jambe-droit"></div>';
      } ?>
    </div>

    

  </div>
  <div class="reponse">
    <form method="post">
        <label for="lettre">Votre lettre :</label>
        <input name="lettre" id="lettre" type="text" maxlength="1" required>
        <button type="submit">Envoyer</button>
    </form>

    <div class="mot"><?= $motAffiche ?></div>
    <div class="erreurs">Erreurs : <?= $nbrErreur ?>/6</div>
    <div class="testees">
        Lettres testées : <?= implode(", ", $_SESSION['lettres_testees']) ?>
    </div>

    <?php if ($gagne): ?>
        <h2> Bravo ! Vous avez gagné !</h2>
        <?php session_destroy(); ?>
    <?php elseif ($perdu): ?>
        <h2> Perdu ! Le mot était : <?= $reponse ?></h2>
        <?php session_destroy(); ?>
    <?php endif; ?>
</div>

</body>

</html>