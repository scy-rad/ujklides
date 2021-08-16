<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    //

    public function docs() {
        return $this->belongsToMany(Doc::class, 'doc_for_groups', 'item_group_id', 'doc_id')->withTimestamps();//->get();
    }
    public function type() {
        return $this->hasOne(ItemType::class,'id','item_type_id');//->get()->first();
    }
    public function items() {
        return $this->hasMany(Item::class,'item_group_id');//->get();
    }
    public function count() {
        return count(Item::all()->where('item_group_id','=',$this->id));
    }
    public function files() {
        //dump($this->id);
        //dump($this->belongsToMany(Gallery::class, 'gallery_for_items', 'items_id', 'galleries_id')->toSql());
        return $this->belongsToMany(Plik::class, 'plik_for_groups', 'item_group_id', 'plik_id')->withTimestamps();//->get();
    }

    public function review_templates() {
        return $this->hasMany(ReviewTemplate::class,'item_group_id');//->get();
    }
    
    public function review_choose($review_type) {
        if ($review_type<50)
            return $this->hasMany(ReviewTemplate::class,'item_group_id')->where('review_type','<',50)->get();
        else
            return $this->hasMany(ReviewTemplate::class,'item_group_id')->where('review_type','=',$review_type)->get();
    }

    public function photo_OK() {
        if ($this->item_group_photo!='')
            return $this->item_group_photo;
        if ($this->type()->item_type_photo!='')
            return $this->type()->item_type_photo;
        
            return "_no_photo.png";
    }

}

