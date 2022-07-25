<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    function photos()
    {
        return $this->hasMany(GalleryPhoto::class,'gallery_id');//->get();
    }
    function forgroups()
    {
        return $this->hasMany(GalleryForGroup::class,'gallery_id');//->get();
    }
    function foritems()
    {
        return $this->hasMany(GalleryForItem::class,'gallery_id');//->get();
    }
    function forrooms()
    {
        return $this->hasMany(GalleryForRoom::class,'gallery_id');//->get();
    }



    public function make_wonderfull_gallery($option)
    {
        foreach ($this->photos as $photo)
        {
            ?>
            <?php echo '<a href="'.$photo->gallery_photo_directory.'/'.$photo->gallery_photo_name.'">'; ?>
            <div class="tile" style="margin-bottom: 50px;">
                <?php echo '<img src="'.$photo->gallery_photo_directory.'/'.$photo->gallery_photo_name.'" class="tile">'; ?>
                <div class="tiletitle">
                    <?php echo $photo->gallery_photo_title; ?>
                </div>
                </a>
                <?php if ($option=='with_edit') { ?>
                    <button type="button" class="btn btn-sm btn-info col-sm-12" onClick="javascript:showEditPhotoModalForm('<?php echo $photo->id; ?>')">edycja zdjęcia</span>
                <?php }?>
            </div>
            
            <?php
        }
    }
}
