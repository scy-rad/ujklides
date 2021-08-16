<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryForRoom extends Model
{
    function gallery()
    {
    return $this->hasOne(Gallery::class,'id','galleries_id');//->get()->first();
    }
}
