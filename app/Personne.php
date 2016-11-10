<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    public $timestamps = false;
    protected $guardable = ['id'];
    protected $fillable = ['nom', 'prenom', 'sexe', 'naissance'];

    public function parents() {
        return $this->hasMany(__NAMESPACE__ . '\\Enfant');
    }

    public function marie() {
        return $this->hasMany(__NAMESPACE__ . '\\Marie');
    }

    public function acte() {
        return $this->hasOne(__NAMESPACE__ . '\\Acte', 'id_personne');
    }
}
