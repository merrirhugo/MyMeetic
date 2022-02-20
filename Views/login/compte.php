<!-- Si l'utilisateur n'est pas connecté -->
<?php if(empty($_SESSION['user'])): ?>
    <p style="color: red;"><?php echo "Veuillez vous connecter pour accèder à cette page."; unset($_SESSION['erreur']);?></p>
<?php endif; ?>

<!-- Si l'utilisateur est connecté -->
<?php if(!empty($_SESSION['user'])): ?>
<img class="logo" src="https://www.meetic.fr/p/events/wp-content/uploads/2019/10/meetic.png" alt="">    
<p>Ne cherchez plus l'amour. Trouvez-le !</p>
<h1>Mon Compte | <a href="../form/logout">Se déconnecter</a> &#10060; | <a href="../form/search"> Rechercher son âme soeur</a> &#128525;</h1>
<?php if(!empty($_SESSION['erreur_globale'])): ?>
    <p class="alert" style="color: red;"><?php echo $_SESSION['erreur_globale']; unset($_SESSION['erreur_globale']);?></p>
<?php endif; ?>
<?php if(!empty($_SESSION['success'])): ?>
    <p class="alert" style="color: rgb(53, 167, 53);"><?php echo $_SESSION['success']; unset($_SESSION['success']);?></p>
<?php endif; ?>
<?php if(!empty($_SESSION['erreur_mail'])): ?>
    <p class="alert" style="color: red;"><?php echo $_SESSION['erreur_mail']; unset($_SESSION['erreur_mail']);?></p>
<?php endif; ?>
<?php if(!empty($_SESSION['erreur_date'])): ?>
    <p class="alert" style="color: red;"><?php echo $_SESSION['erreur_date']; unset($_SESSION['erreur_date']);?></p>
<?php endif; ?>

<h2>Bienvenue, <?= $_SESSION['user']['prenom'] . " ". $_SESSION['user']['nom'] ?> </h2>
<?= $form ?>
<?php endif; ?>
