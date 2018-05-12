<?php

namespace Acr\sf\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Duyuru_user extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acr_sf_duyuru_user';

    function user()
    {
        return $this->belongsTo('App\User');
    }
}
