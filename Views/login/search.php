<img class="logo" src="https://www.meetic.fr/p/events/wp-content/uploads/2019/10/meetic.png" alt="">    
<p>Ne cherchez plus l'amour. Trouvez-le !</p>
<h1>Rechercher son âme soeur &#128525; | <a href="../form/compte">Go back</a>↩️</h1>
     <table border="2">
             <tr>
                 <td class='titre'>Genre</td>
                 <td class='titre'>Nom</td>
                 <td class='titre'>Prénom</td>
                 <td class='titre'>Ville</td>
                 <td class='titre'>Loisir</td>

             </tr>
     <?php foreach($users as $user): ?>
         <tr>
         <td class="result"><?= $user->genre ?></td>
         <td class="result"><?= $user->nom ?></td>
         <td class="result"><?= $user->prenom ?></td>
         <td class="result"><?= $user->ville ?></td>
         <td class="result"><?= $user->loisir ?></td>

         </tr>

     <?php endforeach; ?>

         </table>
         <?= $form ?>