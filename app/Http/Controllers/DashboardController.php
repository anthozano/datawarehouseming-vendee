<?php

namespace App\Http\Controllers;

use App\Acte;
use App\Enfant;
use App\Lieu;
use App\Personne;
use App\RawDeces;
use App\Type;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function home() {
        return view('dashboard/home');
    }

    public function stats() {
        return view('dashboard/stats');
    }

    public function import() {
        return view('dashboard/import/panel');
    }

    public function processImport(Request $request) {
        $arrayRawDeces = RawDeces::take(10)->get();

        foreach ($arrayRawDeces as $rawDeces) {
            if ($rawDeces->dateNaissance == "n/a") {
                $date_naissance = null;
                $date_deces = null;
            } else {
                $tmp = true;
                $ans = $mois = $jours = 0;
                if (preg_match('/ans/', $rawDeces->age)) {
                    $ans = explode(" ans", $rawDeces->age);
                    $ans = intval($ans[0]);
                } elseif (preg_match('/mois/', $rawDeces->age)) {
                    $mois = explode(" mois", $rawDeces->age);
                    $mois = intval($mois[0]);
                } elseif (preg_match('/jours/', $rawDeces->age)) {
                    $jours = explode(" jours", $rawDeces->age);
                    $jours = intval($jours[0]);
                } elseif (preg_match('#^/$#', $rawDeces->age)) {
                    $tmp = null;
                }
                if (is_null($tmp)) {
                    $age_deces = null;
                } else {
                    $age_deces = new \DateInterval("P{$ans}Y{$mois}M{$jours}D");
                $date_deces = new \DateTime(str_replace('/', '-', $rawDeces->dateNaissance));
                $date_deces->format('Y-m-d');
                $date_deces->add($age_deces);
            }
                $date_naissance = new \DateTime(str_replace('/', '-', $rawDeces->dateNaissance));
                $date_naissance = $date_naissance->format('Y-m-d');
            }


            $personne = new Personne();
            $personne->nom = $rawDeces->nom;
            $personne->prenom = $rawDeces->prenom;
            $personne->naissance = $date_naissance;
            $personne->save();

            $mere = new Personne();
            $mere->nom = $rawDeces->nomMere;
            $mere->prenom = $rawDeces->prenomMere;
            $mere->sexe = 'F';
            $mere->save();

            $pere = new Personne();
            $pere->nom = $rawDeces->nomPere;
            $pere->prenom = $rawDeces->prenomPere;
            $pere->sexe = 'M';
            $pere->save();

            $parent1 = new Enfant();
            $parent1->id_enfant = $personne->id;
            $parent1->id_parent = $mere->id;
            $parent1->save();

            $parent2 = new Enfant();
            $parent2->id_enfant = $personne->id;
            $parent2->id_parent = $pere->id;
            $parent2->save();

            $lieu = new Lieu();
            $lieu->nom = $rawDeces->lieu;
            $lieu->departement = $rawDeces->dept;
            $lieu->save();

            $type = new Type();
            $type->nom = $rawDeces->typeActe;
            $type->date = $date_deces;
            $type->save();

            $acte = new Acte();
            $acte->numVue = $rawDeces->numVue;
            $acte->id_lieu = $lieu->id;
            $acte->id_type_acte = $type->id;
            $acte->id_personne = $personne->id;
            $acte->save();
        }
        return view('dashboard/import/result');
    }

}
