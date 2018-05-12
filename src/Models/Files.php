<?php

namespace Acr\sf\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Files extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acr_sf_files';

    function files()
    {
        return $this->hasMany('App\Acr_files', 'acr_file_id', 'acr_file_id');
    }


}
