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
        // $moyAgeMorts = Personne::where();
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

    public function processImport() {
        foreach (RawDeces::cursor() as $rawDece) {

            $personne = Personne::create([
                'nom' => $this->naToNull($rawDece->nom),
                'prenom' => $this->naToNull($rawDece->prenom),
                'naissance' => $this->processDate($rawDece->dateNaissance)
            ]);

            if (strcmp($rawDece->nomMere, 'n/a') == 0 && strcmp($rawDece->prenomMere, 'n/a') == 0) {
                $mere = Personne::create([
                    'nom' => $this->naToNull($rawDece->nomMere),
                    'prenom' => $this->naToNull($rawDece->prenomMere),
                    'sexe' => 'F'
                ]);
                Enfant::create([
                    'id_enfant' => $personne->id,
                    'id_parent' => $mere->id
                ]);
            }
            
            if (strcmp($rawDece->nomPere, 'n/a') == 0 && strcmp($rawDece->prenomPere, 'n/a') == 0) {
                $pere = Personne::create([
                    'nom' => $this->naToNull($rawDece->nomPere),
                    'prenom' => $this->naToNull($rawDece->prenomPere),
                    'sexe' => 'M'
                ]);
                Enfant::create([
                    'id_enfant' => $personne->id,
                    'id_parent' => $pere->id
                ]);
            }

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
            $epouxPere = Personne::create(['nom' => $this->naToNull($rawMariage->nomEpouxPere), 'prenom' => $this->naToNull($rawMariage->prenomEpouxPere), 'sexe' => 'M']);
            $epouxMere = Personne::create(['nom' => $this->naToNull($rawMariage->nomEpouxMere), 'prenom' => $this->naToNull($rawMariage->prenomEpouxMere), 'sexe' => 'F']);

            Enfant::create(['id_enfant' => $epoux->id, 'id_parent' => $epouxPere->id]);
            Enfant::create(['id_enfant' => $epoux->id, 'id_parent' => $epouxMere->id]);

            $epouse = Personne::create(['nom' => $this->naToNull($rawMariage->epoux), 'prenom' => $this->naToNull($rawMariage->prenomEpouse), 'sexe' => 'F']);
            $epousePere = Personne::create(['nom' => $this->naToNull($rawMariage->nomEpousePere), 'prenom' => $this->naToNull($rawMariage->prenomEpousePere), 'sexe' => 'M']);
            $epouseMere = Personne::create(['nom' => $this->naToNull($rawMariage->nomEpouseMere), 'prenom' => $this->naToNull($rawMariage->prenomEpouseMere), 'sexe' => 'F']);

            Enfant::create(['id_enfant' => $epouse->id, 'id_parent' => $epousePere->id]);
            Enfant::create(['id_enfant' => $epouse->id, 'id_parent' => $epouseMere->id]);

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
