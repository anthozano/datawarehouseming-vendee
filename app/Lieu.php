<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lieu extends Model {
    public $timestamps = false;
    protected $guardable = ['id'];
    protected $fillable = ['nom', 'departement'];
}
