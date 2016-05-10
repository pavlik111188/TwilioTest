<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'call_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number'
    ];

    public $timestamps = false;
}
