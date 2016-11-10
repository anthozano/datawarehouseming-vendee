<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {
    public $timestamps = false;
    protected $guardable = ['id'];
    protected $fillable = ['nom', 'date'];

    public function actes() {
        return $this->hasMany(__NAMESPACE__ . '\\Acte');
    }
}
