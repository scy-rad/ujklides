<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
     public $fillable = ['docs_title', 'docs_subtitle', 'docs_description', 'docs_date', 'docs_status' ];


     public function item_group() {
          //return $this->belongsToMany(ItemGroup::class, 'item_docs', 'item_docs_doc_id', 'item_docs_item_group_id')->withTimestamps();
          return $this->belongsToMany(DocForGroup::class, 'doc_for_groups', 'doc_id', 'item_group_id')->withTimestamps();          
      }
}
