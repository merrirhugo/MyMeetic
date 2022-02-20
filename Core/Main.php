<?php

namespace App\Core;

use App\Controllers\MainController;

/**
 * Routeur principal
 */
class Main
{
    public function start()
    {
        //On démarre la session
        session_start();
        //On retire le trailing slash éventuel de l'url
        // On récupère l'url
        $uri = $_SERVER['REQUEST_URI'];

        //On verifie que l'uri n'est pas vide et se termine par un slash
        if(!empty($uri) && $uri != '/' && $uri[-1] === "/") {
            //On enlève le /
            $uri = substr($uri, 0, -1);

            //On envoie un code de redirection permanente
            http_response_code(301);

            //On redirige vers l'url sans /

            //On crée une variable pour éviter l'erreur de chargement
            $url = 'http://localhost/GitHub/MyMeetic/Public/main';
            header('Location: '.$url);
        }

        // On gère les paramètres
        // p=controleur/methode/paramètres
        // On sépare les paramètres dans un tableau
        $params = [];
        if(isset($_GET['p']))
            $params = explode('/', $_GET['p']);

        //var_dump($params);
        if($params[0] != '') {
            //On a au moins 1 paramètre
            //On récupère le nom du controlleur à instancier
            //On met une majuscule en première lettre
            //On ajoute le namespace complet avant et on ajoute "controller" après
            $controller = '\\App\\Controllers\\'.ucfirst(array_shift($params)).'Controller';

            //On instancie le contrôleur
            $controller = new $controller();

            //On récupére le deuxieme paramètre d'URL
            $action = (isset($params[0])) ? array_shift($params) : 'index';

            if(method_exists($controller, $action)) {
                //Si il reste des paramètres on les passe à la méthode
                (isset($params[0])) ? call_user_func_array([$controller, $action],
                $params) : $controller->$action();
            }else {
                http_response_code(404);
                echo "La page recherchée n'existe pas";
            }
 
        } else {
            //On n'a pas de paramètres
            //On instancie le controleur par défault
            $controller = new MainController;

            //On appelle la méthode index
            $controller->index();
        }

    }
}