<img class="logo" src="https://www.meetic.fr/p/events/wp-content/uploads/2019/10/meetic.png" alt="">    
<p>Ne cherchez plus l'amour. Trouvez-le !</p>
<h1>Inscriptions | <a href="../main">Return Home</a>↩️</h1>
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
<?= $registerForm ?>