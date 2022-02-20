<img class="logo" src="https://www.meetic.fr/p/events/wp-content/uploads/2019/10/meetic.png" alt="">    
<p>Ne cherchez plus l'amour. Trouvez-le !</p>
<div>
<h1>Se Connecter | <a href="../main">Return Home </a>↩️</h1>
<?php if(!empty($_SESSION['erreur'])): ?>
    <p style="color: red;"><?php echo $_SESSION['erreur']; unset($_SESSION['erreur']);?></p>
<?php endif; ?>
<?= $loginForm ?>
</div>

