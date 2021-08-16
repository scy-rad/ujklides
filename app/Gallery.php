<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    function photos()
    {
        return $this->hasMany(GalleryPhoto::class,'gallery_id');//->get();
    }
}
