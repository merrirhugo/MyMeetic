<?php

namespace App\Controllers;

use App\Core\Form;
use App\Models\FormModel;

class FormController extends Controller
{

    public function login()
    {
        //On vérifie si le formulaire est complet
        if(Form::validator($_POST, ['email', 'password'])) {
            //Le formulaire est complet
            //On va chercher dans la base de données l'utilisateur avec l'email entré
            $FormModel = new FormModel;
            $userArray = $FormModel->findOneByEmail(strip_tags($_POST['email']));
            
            //Si l'utilisateur n'existe pas
            if(empty($userArray)) {
                //On envoie un message de session
                $_SESSION['erreur'] = "L'adresse e-mail et/ou le mot de passe est incorrect";
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/login');
            }
            
            //L'utilisateur existe
            
            $user = $FormModel->hydrate($userArray);
            
            //On vérifie si le mot de passe est correct
            if(password_verify($_POST['password'], $user->getPassword())){
                //Le mot de passe est bon
                //On crée la session
                $user->setSession();
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/compte');
                
            }else{
                //Mauvais mot de passe
                $_SESSION['erreur'] = "L'adresse e-mail et/ou le mot de passe est incorrect";
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/login');
                exit;

            }
        }

        $form = new Form;

        $form->debutForm()
            ->ajoutLabelFor('email', ' ')
            ->ajoutInput('email', 'email', ['id' => 'email', 'placeholder' => 'Entrer votre adresse mail'])
            ->ajoutLabelFor('password', " ")
            ->ajoutInput('password', 'password', ['id' => 'password', 'placeholder' => 'Entrer votre mot de passe'])
            ->ajoutBouton('Connexion &#128520')
            ->finForm();
            
        $this->render('login/login', ['loginForm' => $form->create()]);
    }

    /**
     * Inscription des utilisateurs
     *
     * @return void
     */
    public function register()
    {

        //On vérifie si le formulaire est valide
        if(Form::validator($_POST, ['email', 'password', 'date', 'nom', 'prenom',
        'ville', 'genre', 'loisir'])) {
            //Le formulaire est valide
            //On "nettoie" l'adresse email
            $email = strip_tags($_POST['email']);

            $FormModel = new FormModel;
            $userArray = $FormModel->findOneByEmail(strip_tags($_POST['email']));
            
            //Si l'adresse mail existe déjà
            if($userArray) {
                //On envoie un message de session
                $_SESSION['erreur_mail'] = "Cette adresse mail est déjà utilisée.";
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/register');
                exit;
            }

            //On chiffre le mot de passe
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
            
            $date = $_POST['date'];

            if($date > 2004-02-02) {
                $_SESSION['erreur_date'] = "Vous devez avoir 18 ans pour accèder à ce site.";
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/register');
                exit;
            }

            $nom = strip_tags($_POST['nom']);

            $prenom = strip_tags($_POST['prenom']);

            $ville = strip_tags($_POST['ville']);

            $genre = strip_tags($_POST['genre']);

            $loisir = strip_tags($_POST['loisir']);

            //On hydrate l'utilisateur en base de données
            $user = new FormModel;

            $user->setEmail($email)
                ->setPassword($password)
                ->setDate($date)
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setVille($ville)
                ->setGenre($genre)
                ->setLoisir($loisir);

            //On stocke l'utilisateur
            $user->create();

            $_SESSION['success'] = "Votre inscription est validée, veuillez-vous connecter pour accèder à nos services.";

        }else{

            $_SESSION['erreur_globale'] = !empty($_POST) ? "Le formulaire est incomplet" : '';

            $email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
            $date = isset($_POST['date']) ? strip_tags($_POST['date']) : '';
            $prenom = isset($_POST['prenom']) ? strip_tags($_POST['prenom']) : '';
            $nom = isset($_POST['nom']) ? strip_tags($_POST['nom']) : '';
            $ville = isset($_POST['ville']) ? strip_tags($_POST['ville']) : '';
        }
        
        $form = new Form;

        //Création du formulaire d'inscription

        $form->debutForm('post', '#', ['classe' => 'form'])
        ->ajoutLabelFor('genre', 'Genre :')
        ->ajoutSelect('genre', ['Homme' => 'Homme', 'Femme' => 'Femme', 'Autre' => 'Autre'], [])
            ->ajoutLabelFor('nom', ' ')
            ->ajoutInput('texte', 'nom', ['id' => 'nom', 'placeholder' => 'Entrer votre Nom', 'value' => $nom])
            ->ajoutLabelFor('prenom', ' ')
            ->ajoutInput('texte', 'prenom', ['id' => 'prenom', 'placeholder' => 'Entrer votre Prénom', 'value' => $prenom])
            ->ajoutLabelFor('email', ' ')
            ->ajoutInput('email', 'email', ['id' => 'email', 'placeholder' => 'Entrer votre adresse mail', 'value' => $email])
            ->ajoutLabelFor('pass', ' ')
            ->ajoutInput('password', 'password', ['id' => 'pass', 'placeholder' => 'Entrer votre mot de passe'])
            ->ajoutLabelFor('ville', ' ')
            ->ajoutInput('texte', 'ville', ['id' => 'ville', 'placeholder' => 'Entrer votre ville', 'value' => $ville])
            ->ajoutLabelFor('date', 'Date de naissance :')
            ->ajoutInput('date', 'date', ['id' => 'date', 'value' => $date])
            ->ajoutLabelFor('loisir', 'Loisirs :')
            ->ajoutSelect('loisir', ['Jeux Vidéo' => 'Jeux Vidéo', 'Lecture' => 'Lecture', 'Sport' => 'Sport',
             'Boire un verre' => 'Boire un verre', 'Musique' => 'Musique', 'Voyager' => 'Voyager',
             'Art' => 'Art'], [])
            ->ajoutBouton("M'inscrire &#128579")
            ->finForm();
            //Envoyer le formulaire à la vue 

        $this->render('login/register', ['registerForm' => $form->create()]);
    }

    /**
     * Affiche le compte de l'utilisateur
     * @return void
     */
    public function compte() {

        //On traite le formulaire
        if(Form::validator($_POST, ['date', 'nom', 'prenom',
        'ville', 'genre', 'loisir'])) {
            //Le formulaire est valide

            $date = $_POST['date'];

            if($date > 2004-02-02) {
                $_SESSION['erreur_date'] = "Vous devez avoir 18 ans pour accèder à ce site.";
                header('Location: http://localhost/GitHub/MyMeetic/Public/form/compte');
                exit;
            }

            $nom = strip_tags($_POST['nom']);

            $prenom = strip_tags($_POST['prenom']);

            $ville = strip_tags($_POST['ville']);

            $genre = strip_tags($_POST['genre']);

            $loisir = strip_tags($_POST['loisir']);

            //On hydrate l'utilisateur en base de données
            $user = new FormModel;

               $user->setDate($date)
               ->setId($_SESSION['user']['id'])
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setVille($ville)
                ->setGenre($genre)
                ->setLoisir($loisir);

            //On met à jour l'utilisateur
            $user->update();

            $message = "Vos informations ont été modifiée. Veuillez-vous reconnecter.";
            echo "<script type='text/javascript'>alert('$message'); document.location.href='http://localhost/GitHub/MyMeetic/Public/form/logout';</script>";

        }else{

            $_SESSION['erreur_globale'] = !empty($_POST) ? "Le formulaire était incomplet" : '';

        }

        $form = new Form;

        $form->debutForm('post', '#', ['classe' => 'form'])
        ->ajoutLabelFor('genre', 'Genre :')
        ->ajoutSelect('genre', ['value' => $_SESSION['user']['genre'], 'Homme' => 'Homme', 'Femme' => 'Femme', 'Autre' => 'Autre'])
            ->ajoutLabelFor('nom', ' ')
            ->ajoutInput('texte', 'nom', ['id' => 'nom', 'placeholder' => 'Entrer votre Nom', 'value' => $_SESSION['user']['nom']])
            ->ajoutLabelFor('prenom', ' ')
            ->ajoutInput('texte', 'prenom', ['id' => 'prenom', 'placeholder' => 'Entrer votre Prénom', 'value' => $_SESSION['user']['prenom']])
            ->ajoutLabelFor('email', ' ')
            ->ajoutInput('email', 'email', ['id' => 'email', 'placeholder' => 'Entrer votre adresse mail', 'value' => $_SESSION['user']['email'], 'disabled' => ''])
            ->ajoutLabelFor('ville', ' ')
            ->ajoutInput('texte', 'ville', ['id' => 'ville', 'placeholder' => 'Entrer votre ville', 'value' => $_SESSION['user']['ville']])
            ->ajoutLabelFor('date', 'Date de naissance :')
            ->ajoutInput('date', 'date', ['id' => 'date', 'value' => $_SESSION['user']['date']])
            ->ajoutLabelFor('loisir', 'Loisirs :')
            ->ajoutSelect('loisir', ['value' => $_SESSION['user']['loisir'], 'Jeux Vidéo' => 'Jeux Vidéo', 'Lecture' => 'Lecture', 'Sport' => 'Sport',
             'Boire un verre' => 'Boire un verre', 'Musique' => 'Musique', 'Voyager' => 'Voyager',
             'Art' => 'Art'])
            ->ajoutBouton("Modifier &#128519;")
            ->finForm();

            $this->render('login/compte', ['form' => $form->create()], 'compte_template');

    }

    /**
     * Déconnecter l'utilisateur
     * @return exit
     */
    public function logout() {
        unset($_SESSION['user']);
        header('Location: http://localhost/GitHub/MyMeetic/Public/main');
        exit;
    }

    public function search() {

        if(empty($_POST))
        {
            $_POST['search_genre'] = "";
            $_POST['search_ville'] = "";
            $_POST['search_loisir'] = "";
        }


        $form = new Form;

        $form->debutForm('post', '#', ['classe' => 'form'])
            ->ajoutLabelFor('genre', 'Rechercher par genre: ')
            ->ajoutInput('texte', 'search_genre', ['value' => "$_POST[search_genre]"])
            ->ajoutLabelFor('ville', 'Rechercher par ville: ')
            ->ajoutInput('texte', 'search_ville', ['value' => "$_POST[search_ville]"])
            ->ajoutLabelFor('loisir', 'Rechercher par loisir: ')
            ->ajoutInput('texte', 'search_loisir', ['value' => "$_POST[search_loisir]"])
            ->ajoutBouton('Rechercher')
            ->finForm();

        //On instancie le modèle correspondant à la table 'users'
        $usersModel = new FormModel;

        //On va chercher les utilisateurs correspondant à la recherche
        $users = $usersModel->findBy(['genre' => "$_POST[search_genre]", 'ville' => "$_POST[search_ville]", 'loisir' => "$_POST[search_loisir]" ]);


        $this->render('login/search', ['form' => $form->create(), 'users' => $users]);

    }

}