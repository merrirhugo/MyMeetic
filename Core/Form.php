<?php

namespace App\Core;

class Form
{
    private $formCode = '';

    /**
     * Génère le formulaire HTML
     * @return string
     */
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Valide si tous les champs proposés sont remplis
     *
     * @param array $form Tableau issu du formulaire ($_POST, $_GET)
     * @param array $champs Tableau listant les champs obligatoires
     * @return bool
     */
    public static function validator(array $form, array $champs)
    {
        //On parcourt les champs
        foreach($champs as $champ) {
            //Si le champ est absent ou vide dans le formulaire
            if(!isset($form[$champ]) || empty($form[$champ])) {
                //On sort en retournant false
                return false;
            }
        }
        return true;
    }

    /**
     * Ajoute les attributs envoyés à la balise
     *
     * @param array $attributs Tableau associatif ['class' => 'form-control', 'required' => true]
     * @return string Chaine de caractères générée
     */
    private function ajoutAttributs(array $attributs): string
    {
        //On initialise une chaine de caractères
        $str = '';

        //On liste les attributs "courts" sans valeurs
        $courts = ['checked', 'disabled', 'readonly', 'multiple', 'required',
        'autofocus', 'novalidate', 'formnovalidate'];

        //On boucle sur le tableau d'attributs
        foreach($attributs as $attribut => $valeur) {
            //Si l'attribut est dans la liste des attributs courts
            if(in_array($attribut, $courts) && $valeur == true) {
                $str .= " $attributs";
            }else {
                //On ajoute attribut='valeur'
                $str .= " $attribut='$valeur'";
            }
        }

        return $str;
    }

    /**
     * Balise d'ouverture du formulaire
     * @param string $methode Méthode du formulaire (post ou get)
     * @param string $action Action du formulaire
     * @param array $attributs Attributs du formulaire
     * @return Form
     */
    public function debutForm(string $methode = 'post', string $action = '#', array
    $attributs = []): self
    {
        //On crée la balise <form>
        $this->formCode .= "<form action='$action' method='$methode'";

        //On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs).'>' : '>';

        return $this;
    }

    /**
     * Balise de fermeture du formulaire
     * @return Form
     */
    public function finForm(): self
    {
        $this->formCode .= '</form>';
        return $this;
    }

    /**
     * Ajout d'un label
     * @param string $for
     * @param string $texte
     * @param array $attributs
     * @return self
     */
    public function ajoutLabelFor(string $for, string $texte, array $attributs = []): self
    {
        //On ouvre la balise
        $this->formCode .= "<label for='$for'";

        //On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) : '';

        //On ajoute le texte
        $this->formCode .= ">$texte</label>";
        
        return $this;
    }

    public function ajoutInput(string $type, string $name, array $attributs = []):self
    {
        //On ouvre la balise
        $this->formCode .= "<input type='$type' name='$name'";

        //On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs).'>' : '>';

        return $this;
    }

    public function ajoutSelect(string $name, array $options, array $attributs = []):self
    {
        //On crée le select
        $this->formCode .= "<select name='$name'";

        //On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs).'>' : '>';

        //On ajoute les options
        foreach($options as $valeur => $texte) {
            $this->formCode .= "<option value='$valeur'>$texte</option>";
        }

        //On ferme le select
        $this->formCode .= "</select>";

        return $this;
    }

    public function ajoutBouton(string $texte, array $attributs = []):self
    {
        $this->formCode .= '<button ';
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) : '';
        $this->formCode .= ">$texte</button>";

        return $this;
    }

}