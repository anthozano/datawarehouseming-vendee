# Projet d'organization des bases de données de marriages et déces pour le departement de la Vendée

## Problèmatiques:
* Inconsistence de données (memes noms et mots écrits de manieres differentes)
* Données manquantes
* Creation d'un tableau de bord

## Déroulement Probable
* Normalization des données
* Création d'un tableau de bord en utilisant un framework PHP (laravel)

## Normalization des données
### Création des tables
* Table pour:
    * Lieux
        * id (int)
        * nom (varchar)
        * dept (int)
    * Acte
        * id (int)
        * numVue (varchar)
    * TypeActe
        * id (int)
        * nom (varchar)
    * Personne
        * id (int)
        * nom (varchar)
        * prenom (varchar)
        * sexe (char)
        * naissance (date)
     * *EtreMarié*
        * date (date)
    * *EtreParent*