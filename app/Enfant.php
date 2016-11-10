<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enfant extends Model {
    public $timestamps = false;
    protected $fillable = ['id_enfant', 'id_parent'];

    public function parent() {
        return $this->hasOne(__NAMESPACE__ . '\\Personne', 'id_parent');
    }

    public function enfant() {
        return $this->hasOne(__NAMESPACE__ . '\\Personne', 'id_enfant');
    }
}
