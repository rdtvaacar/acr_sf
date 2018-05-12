<?php

namespace Acr\sf\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Duyuru extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acr_sf_duyuru';

    function user()
    {
        return $this->belongsTo('App\User');
    }

    function duyuru_user()
    {
        return $this->hasOne('Acr\sf\Models\Duyuru_user', 'duyuru_id', 'id');
    }

    function duyuru_users()
    {
        return $this->hasMany('Acr\sf\Models\Duyuru_user', 'duyuru_id', 'id');
    }

}
