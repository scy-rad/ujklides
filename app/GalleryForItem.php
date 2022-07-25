<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryForItem extends Model
{
    function gallery()
    {
    return $this->hasOne(Gallery::class,'id','gallery_id');//->get()->first();
    }
}
