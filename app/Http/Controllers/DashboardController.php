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
        $nbPers = Personne::count();
        $nbMaries = Marie::count();
        $nbMorts = Acte::where('id_personne_marie', null)->count();
        $ageMoyenDeces = DB::select(
            DB::raw("
                SELECT ROUND(AVG(DATEDIFF(t.date, p.naissance)/365)) AS age, YEAR(t.date) AS annee
                FROM personnes AS p
                INNER JOIN actes AS a ON a.id_personne = p.id
                INNER JOIN types AS t ON t.id = a.id_type_acte
                WHERE a.id_personne_marie IS NULL
                GROUP BY YEAR(t.date)
                HAVING ROUND(AVG(DATEDIFF(t.date, p.naissance)/365)) > 0
            ")
        );
        $ageMoyenDeces = Personne::whereNotNull('naissance')
            ->has('acte.type')
            ->get();
//        var_dump($ageMoyenDeces[0]);
        var_dump($ageMoyenDeces[0]->acte->type);
        die();

        return view('dashboard/stats', compact("nbPers", "nbMaries", "nbMorts", "ageMoyenDeces"));
    }

    public function import() {
        $nbraw = RawMariages::count() + RawDeces::count();
        return view('dashboard/import/panel', compact('nbraw'));
    }

    private function naToNull($value) {
        return $value == 'n/a' ? null : $value;
    }

    private function getDateInterval($age) {
        if (preg_match('#^n/a$#', $age)) {
            $interval = null;
        } else {
            $ans = $mois = $jours = 0;
            if (preg_match('#^ans?$#i', $age)) {
                $ans = explode(" ans", $age);
                $ans = intval($ans[0]);
            } elseif (preg_match('#^mois?$#i', $age)) {
                $mois = explode(" mois", $age);
                $mois = intval($mois[0]);
            } elseif (preg_match('#^jours?$#', $age)) {
                $jours = explode(" jours", $age);
                $jours = intval($jours[0]);
            }
            $interval = new \DateInterval("P{$ans}Y{$mois}M{$jours}D");
        }
        return $interval;
    }

    private function getDateFromAge($age, $naissance) {
        if (!is_null($age) && !is_null($naissance) && !is_null($this->checkDateFormat($naissance))) {
            $date = \DateTime::createFromFormat('d/m/Y', $naissance);
            $dateInterval = $this->getDateInterval($age);
            return is_null($dateInterval) ? null : $date->add($this->getDateInterval($age));
        } else {
            return null;
        }

    }

    function processDate($date) {
        if (preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#", $date)) {
            $date = \DateTime::createFromFormat('d/m/Y', $date);
        } else {
            $date = null;
        }
        return $date;
    }

    private function checkDateFormat($date, $format = "#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#") {
        return preg_match($format, $date) ? true : null;
    }

    private function createParent($nom, $prenom, $sexe, Personne $enfant) {
        if (strcmp($nom, 'n/a') == 0 && strcmp($prenom, 'n/a') == 0) {
            $parent = Personne::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'sexe' => $sexe
            ]);
            Enfant::create([
                'id_enfant' => $enfant->id,
                'id_parent' => $parent->id
            ]);
        }
    }

    public function processImport() {
        foreach (RawDeces::cursor() as $rawDece) {

            $personne = Personne::create([
                'nom' => $this->naToNull($rawDece->nom),
                'prenom' => $this->naToNull($rawDece->prenom),
                'naissance' => $this->processDate($rawDece->dateNaissance)
            ]);

            $this->createParent($rawDece->nomMere, $rawDece->prenomMere, 'F', $personne);
            $this->createParent($rawDece->nomPere, $rawDece->prenonPere, 'M', $personne);

            $lieu = Lieu::create([
                'nom' => $this->naToNull($rawDece->lieu),
                'departement' => intval($this->naToNull($rawDece->dept))
            ]);

            $type = Type::create([
                'nom' => $this->naToNull($rawDece->typeActe),
                'date' => $this->getDateFromAge($rawDece->age, $rawDece->dateNaissance),
            ]);

            Acte::create([
                'numVue' => $this->naToNull($rawDece->numVue),
                'id_type_acte' => $type->id,
                'id_lieu' => $lieu->id,
                'id_personne' => $personne->id
            ]);

            $rawDece->delete();

        };

        foreach (RawMariages::cursor() as $rawMariage) {

            $epoux = Personne::create(['nom' => $this->naToNull($rawMariage->epoux), 'prenom' => $this->naToNull($rawMariage->prenomEpoux), 'sexe' => 'M']);
            $this->createParent($rawMariage->nomEpouxPere, $rawMariage->prenomEpouxPere, 'M', $epoux);
            $this->createParent($rawMariage->nomEpouxMere, $rawMariage->prenomEpouxMere, 'F', $epoux);

            $epouse = Personne::create(['nom' => $this->naToNull($rawMariage->epoux), 'prenom' => $this->naToNull($rawMariage->prenomEpouse), 'sexe' => 'F']);
            $this->createParent($rawMariage->nomEpouseMere, $rawMariage->prenomEpouseMere, 'F', $epouse);
            $this->createParent($rawMariage->nomEpousePere, $rawMariage->prenomEpousePere, 'M', $epouse);

            Marie::create([
                'id_epoux' => $epoux->id,
                'id_epouse' => $epouse->id
            ]);

            $lieu = Lieu::create([
                'nom' => $this->naToNull($rawMariage->lieu),
                'departement' => $this->naToNull($rawMariage->dept)
            ]);

            $type = Type::create([
                'nom' => $this->naToNull($rawMariage->typeActe),
                'date' => $this->processDate($rawMariage->dates)
            ]);

            Acte::create([
                'numVue' => $this->naToNull($rawMariage->numVue),
                'id_lieu' => $lieu->id,
                'id_type_acte' => $type->id,
                'id_personne' => $epoux->id,
                'id_personne_marie' => $epouse->id
            ]);

            $rawMariage->delete();
        };

        return view('dashboard/import/result');
    }

}
