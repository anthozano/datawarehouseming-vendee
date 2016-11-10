<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acte extends Model {
    public $timestamps = false;
    protected $guardable = ['id'];
    protected $fillable = ['numVue', 'id_lieu', 'id_type_acte', 'id_personne', 'id_personne_marie'];

    public function lieu() {
        return $this->hasOne(__NAMESPACE__ . '\\\Lieu', 'id_lieu');
    }

    public function type() {
        return $this->hasOne(__NAMESPACE__ . '\\Type', 'id');
    }

    public function personne() {
        return $this->hasOne(__NAMESPACE__ . '\\Personne', 'id_personne');
    }

    public function personneMarie() {
        return $this->hasOne(__NAMESPACE__ . '\\Personne', 'id_personne_marie');
    }
}
