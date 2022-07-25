<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryForGroup extends Model
{
    function gallery()
    {
    return $this->hasOne(Gallery::class,'id','gallery_id');//->get()->first();
    }
}
