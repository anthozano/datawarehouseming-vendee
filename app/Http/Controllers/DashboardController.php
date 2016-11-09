<?php

namespace App\Http\Controllers;

use App\Acte;
use App\Enfant;
use App\Lieu;
use App\Marie;
use App\Personne;
use App\RawDeces;
use App\RawMariages;
use App\Type;
use Illuminate\Support\Facades\DB;

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

    public function naToNull($value) {
        return $value == 'n/a' ? null : $value;
    }

    public function processImport() {
        $counter = 1;
        $colNumbers = DB::table('raw_deces')->count();
        while ($counter < $colNumbers) {
        $rawDeces = RawDeces::find($counter++);
            if (!preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#", $rawDeces->dateNaissance)) {
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
            $personne->nom = $this->naToNull($rawDeces->nom);
            $personne->prenom = $this->naToNull($rawDeces->prenom);
            $personne->naissance = $this->naToNull($date_naissance);
            $personne->save();

            $mere = new Personne();
            $mere->nom = $this->naToNull($rawDeces->nomMere);
            $mere->prenom = $this->naToNull($rawDeces->prenomMere);
            $mere->sexe = 'F';

            $pere = new Personne();
            $pere->nom = $this->naToNull($rawDeces->nomPere);
            $pere->prenom = $this->naToNull($rawDeces->prenomPere);
            $pere->sexe = 'M';

            if (!is_null($pere->nom) && !is_null($pere->prenom)) {
                $pere->save();
                $parent2 = new Enfant();
                $parent2->id_enfant = $personne->id;
                $parent2->id_parent = $pere->id;
                $parent2->save();
            }
            if (!is_null($mere->nom) && !is_null($mere->prenom)) {
                $mere->save();
                $parent1 = new Enfant();
                $parent1->id_enfant = $personne->id;
                $parent1->id_parent = $mere->id;
                $parent1->save();
            }
            $lieu = new Lieu();
            $lieu->nom = $this->naToNull($rawDeces->lieu);
            $lieu->departement = intval($this->naToNull($rawDeces->dept));
            $lieu->save();

            $type = new Type();
            $type->nom = $this->naToNull($rawDeces->typeActe);
            $type->date = $this->naToNull($date_deces);
            $type->save();

            $acte = new Acte();
            $acte->numVue = $this->naToNull($rawDeces->numVue);
            $acte->id_lieu = $lieu->id;
            $acte->id_type_acte = $type->id;
            $acte->id_personne = $personne->id;
            $acte->save();
        }
        $counter = 1;
        $colNumbers = DB::table('raw_mariage')->count();
        while ($counter < $colNumbers) {
            $rawMariage = RawMariages::find($counter++);

            /*
             * EPOUX
             */

            $epoux = new Personne();
            $epoux->nom = $this->naToNull($rawMariage->epoux);
            $epoux->prenom = $this->naToNull($rawMariage->prenomEpoux);
            $epoux->sexe = 'M';
            $epoux->save();

            /*
             * PERE EPOUX
             */

            $pereEpoux = new Personne();
            $pereEpoux->nom = $this->naToNull($rawMariage->prenomPereEpoux);
            $pereEpoux->prenom = $this->naToNull($rawMariage->prenomPereEpoux);
            $pereEpoux->sexe = 'M';

            if (!is_null($pereEpoux->nom) && !is_null($pereEpoux->prenom)) {
                $pereEpoux->save();
                $parenteEpouxPere = new Enfant();
                $parenteEpouxPere->id_parent = $pereEpoux->id;
                $parenteEpouxPere->id_enfant = $epoux->id;
                $parenteEpouxPere->save();
            }

            /*
             * MERE EPOUX
             */

            $mereEpoux = new Personne();
            $mereEpoux->nom = $this->naToNull($rawMariage->prenomMereEpoux);
            $mereEpoux->prenom = $this->naToNull($rawMariage->prenomMereEpoux);
            $mereEpoux->sexe = 'M';

            if (!is_null($mereEpoux->nom) && !is_null($mereEpoux->prenom)) {
                $mereEpoux->save();
                $parenteEpouxMere = new Enfant();
                $parenteEpouxMere->id_parent = $mereEpoux->id;
                $parenteEpouxMere->id_enfant = $epoux->id;
                $parenteEpouxMere->save();
            }

            /*
             * EPOUSE
             */

            $epouse = new Personne();
            $epouse->nom = $this->naToNull($rawMariage->nom);
            $epouse->prenom = $this->naToNull($rawMariage->prenom);
            $epouse->sexe = 'F';
            $epouse->save();


            /*
             * PERE EPOUSE
             */

            $pereEpouse = new Personne();
            $pereEpouse->nom = $this->naToNull($rawMariage->prenomPereEpouse);
            $pereEpouse->prenom = $this->naToNull($rawMariage->prenomPereEpouse);
            $pereEpouse->sexe = 'M';

            if (!is_null($pereEpouse->nom) && !is_null($pereEpouse->prenom)) {
                $pereEpouse->save();
                $parenteEpousePere = new Enfant();
                $parenteEpousePere->id_parent = $pereEpouse->id;
                $parenteEpousePere->id_enfant = $epouse->id;
                $parenteEpousePere->save();
            }

            /*
             * MERE EPOUSE
             */

            $mereEpouse = new Personne();
            $mereEpouse->nom = $this->naToNull($rawMariage->prenomMereEpouse);
            $mereEpouse->prenom = $this->naToNull($rawMariage->prenomMereEpouse);
            $mereEpouse->sexe = 'M';

            if (!is_null($mereEpouse->nom) && !is_null($mereEpouse->prenom)) {
                $mereEpouse->save();
                $parenteEpouseMere = new Enfant();
                $parenteEpouseMere->id_parent = $mereEpouse->id;
                $parenteEpouseMere->id_enfant = $epouse->id;
                $parenteEpouseMere->save();
            }

            $maries = new Marie();
            $maries->id_epoux = $epoux->id;
            $maries->id_epouse = $epouse->id;
            $maries->save();

            $lieu = new Lieu();
            $lieu->nom = $this->naToNull($rawMariage->lieu);
            $lieu->departement = $this->naToNull($rawMariage->dept);
            $lieu->save();

            $date_mariage = new \DateTime(str_replace('/', '-', $rawMariage->dateNaissance));
            $date_mariage = $date_mariage->format('Y-m-d');

            $type = new Type();
            $type->nom = $this->naToNull($rawMariage->typeActe);
            $type->date = $this->naToNull($date_mariage);
            $type->save();

            $acte = new Acte();
            $acte->numVue = $this->naToNull($rawMariage->numVue);
            $acte->id_lieu = $lieu->id;
            $acte->id_type_acte = $type->id;
            $acte->id_personne = $epoux->id;
            $acte->id_personne_marie = $epouse->id;
            $acte->save();

        }
        return view('dashboard/import/result');
    }

}
