# Création de bases de données et API pour les archives départementales de la région Vendéene.

---
$$Hugo~Moracchini~et~Anthony~Lozano $$

---

## Introduction
### Choix d'outils
Afin de pouvoir mieux travailler,  nous avons décidé de choisir un ensemble d'outils commun. 

* Pour notre serveur local PHP nous avons choisi *WampServer* pour ca simplicité d'utilisation.
* Pour synchroniser nos modification et gérer le versioning nous avons crée un projet sur *gitlab* et nous avons utilisé *git* par ligne de commande ou intégré avec notre IDE.
* L'IDE que nous avions choisi est *PHPStorm* car il est extrêmement puissant et est gratuit pour les étudiants.
* Pour les bases de données nous avons utilisé *MySQL workbench* pour créer les schema de la base de donnes.
* Finalement pour créer l'API nous avons utilisé *Laravel* comme framework PHP car c'est un framework puissant, flexible, et adapté aux petits projets.

---
### Sommaire
* Analyse des données
* Création des schémas de la base de données
* Ajout et organisation des données dans la base
* l'API
	* Statistiques
	* Requêtes
	* Insertions
	* Modifications
	* Gestion de Roles
* Conclusion

---
 
## Analyse des données
Tout simplement: afin de savoir ce qu'on doit faire, il faut qu'on sache exactement ce qu'on a. En regardant les données que nous avions reçus on a vite découvert qu'il y avait des erreurs dans les tuples, des données manquantes, et un manque de congruence dans certaines catégories. Un exemple de cela est les ages de décès: certains était en ans, d'autres en mois, d'autres en ans et mois. Cela nous a permis d'anticiper les taches que nous allons avoir lors du remplissage des schémas.

## Création des schémas de la base de données
Nous avons décidé de créer une seul base unie contenant des tables pour les décès et les marriages. L'outil MySQL workbench nous a permis de tout créer avec une interface graphique et donc il était inutile de taper une seule ligne de SQL.
<img src="https://i.imgur.com/N1exGLC.png">
"Enfants" et "maries" représentent les relations entre des personnes, avec la cardinalité représentante. La table "actes" regroupe les actes de décès et de mariage et la table "types" permet de différencier entre les deux.

## Ajout et organization des données dans la base
Cette étape était la plus dure, surtout car c'était notre première utilisation de Laravel et donc nous avions eu beaucoup à apprendre en peu de temps. Laravel est fourni avec un ORM (nommé Eloquent). Eloquent est un ORM très pratique qui nous a permis de facilement manipuler nos données dans notre base. Cela nous a permis de créer une page dédié a la mise en base des données que nous avons reçu en format SDL. Pour cela nous avons crée un algorithme qui prend chaque ligne du tableau SDL et qui entre les valeurs dans les bonnes tables et colones de la base SQL. Cet algorithme mets les données en ordre et converti les dates de chaines de caractères en format dédié date. L'algorithme nous permet aussi de calculer la date de décès en convertissant les ages de mort en utilisant une expression régulière. 
``` PHP
private function getDateInterval($age) {
        if (preg_match('#^n/a$#', $age)) {
            $interval = null;
        } else {
            if (preg_match('/ans/', $age)) {
                $ans = explode(" ans", $age);
                $ans = intval($ans[0]);
            } elseif (preg_match('/mois/', $age)) {
                $mois = explode(" mois", $age);
                $mois = intval($mois[0]);
            } elseif (preg_match('/jours/', $age)) {
                $jours = explode(" jours", $age);
                $jours = intval($jours[0]);
            }
            $interval = new \DateInterval("P{$ans}Y{$mois}M{$jours}D");
        }
        return $interval;
    }
```
L'ajout du fichier SDL ce fait en interne et prend un temps assez important a cause du calcul a faire pour chaque ligne multiplié par le très grand nombre de lignes.

## L'API
### Statistiques
Une des fonctionalitées de l'API est le fait de pouvoir avoir des statistiques a propos des données. Un exemple de ces stats est le nombre de personnes mariées, morts, et total dans la base de données. Cela est fait en utilisant Laravel pour agir comme liaison entre les bases de donées et l'interface graphique. 
``` PHP
$nbPers = Personne::count();
        $nbMaries = Marie::count();
        $nbMorts = Acte::where('id_personne_marie','=',null)->count();
        return view('dashboard/stats',compact("nbPers","nbMaries","nbMorts"));
```
Le code si dessus est situé dans le controller et envoie les variables calculées aux fichiers de vue. Une fois dans la vue, l'insertion de données dans le code HTML se fait très simplement avec une syntaxe nommée *Blade*.
``` PHP
<th>{{$nbPers}}</th>
```
La syntaxe Blade est similaire a la syntaxe Razor (utilisé avec ASP.NET pour le code C#) ou elle consiste d'une façon d'insérer des valeurs dans la vue qui sont calculées dans le controller. La page de statistiques affiche aussi un graphique montrant le nombre de décès par an.

### CRUD
CRUD (Create Read Update Delete)

### Administration/Roles

## Conclusion
En récapitulatif, pour ce projet nous avons décidé d'utiliser un maximum de technologies existantes afin de pouvoir découvrir de nouvelles méthodes de travail qui sont plus adaptées a du veritable génie logiciel. L'utilisation de framework Laravel nous a simultanément ralenti et accéléré, d'une part a cause challenge d’apprendre un nouveau framework en 2 semaines, et d'une autre car sa puissance nous a évité d'écrire beaucoup de code. La structure des bases de données était un choix assez complexe car il fallait répartir plusieurs variante d'un type de donné (les personnes étant mariées, morts, ou des parents de mariées). Une autre tache était de gérer le nombre gigantesque d’erreurs et de discontinuités dans les fichiers SDL qui nous ont été donnés. Ce projet nous a donc fait travailler: le SQL, les expressions régulières, le PHP avec Laravel, les models MVC, les méthodes de CRUD, et au plus important: de développer dans un cas moins théorique que d'habitude.
Datamining Vendee
Ouvrir avec
Affichage de Datamining Vendee