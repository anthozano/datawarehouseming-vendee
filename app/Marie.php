<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marie extends Model {
    public $timestamps = false;
    protected $fillable = ['id_epoux', 'id_epouse'];
}
