<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{

    public static function MasterTypes(){
        return  ItemType::where('item_type_parent_id',0)->where('item_type_sort','>',0)->OrderBy('item_type_sort')->get(); 
    }
    public static function MenuTypes(){
        return  ItemType::where('item_type_code','<>','')->OrderBy('item_type_sort')->get(); 
    }
    public static function typepatcharray(Int $id_type) {
        
        if ($id_type>0)
            {
            $check=TRUE;
            
            $obiekcik=ItemType::where('id',$id_type)->get()->first(); 
            $ret['id']=$obiekcik->id;
            $ret['name']=$obiekcik->item_type_name;
            $return[]=$ret;
                
            if ($obiekcik->item_type_parent_id>0)
                do {
                    $obiekcik=ItemType::where('id','=',$obiekcik->item_type_parent_id)->get()->first();
                    //$echo=$obiekcik->item_type_name.' <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> '.$echo;
                    $ret['id']=$obiekcik->id;
                    $ret['name']=$obiekcik->item_type_name;
                    $return[]=$ret;
                    if ($obiekcik->item_type_parent_id==0) $check=FALSE;
                }
                while ($check);            
            }
        $ret['id']=0;
        $ret['name']='wszystko';
        $return[]=$ret;
        
        return array_reverse($return);
    }

    
    public function ParentType(){
        if ($this->item_type_parent_id>0)
            return  ItemType::where('item_type_parent_id',$this->id); 
        else
            return null;
    }
    public function itemgroups() {
        return ItemGroup::all()->whereIn('item_group_type_id',ItemType::where('item_type_master_id','=',$this->id)->pluck('id'));
    }

    public function subtypes() {
        return ItemType::all()->where('item_type_master_id','=',$this->id);
    }
    
    public function typepatch() {
/*
        $check=TRUE;
        $obiekcik=$this;
        $echo=$obiekcik->item_type_name;
        if ($obiekcik->item_type_parent_id>0)
        do {
            $obiekcik=ItemType::where('id','=',$obiekcik->item_type_parent_id)->get()->first();
            $echo=$obiekcik->item_type_name.' <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> '.$echo;
            if ($obiekcik->item_type_parent_id==0) $check=FALSE;
        }
        while ($check);
        
        return $echo;
        */
    }

    public function photo_OK() {
        if ($this->item_type_photo!='')
            return $this->item_type_photo;
        if ($this->item_type_parent_id>0)
            {
            $check=TRUE;
            $obiekcik=$this;
            do {
                $obiekcik=ItemType::where('id','=',$obiekcik->item_type_parent_id)->get()->first();
                if ($obiekcik->item_type_parent_id==0) $check=FALSE;
                if ($obiekcik->item_type_photo!='') return $obiekcik->item_type_photo;
            }
            while ($check);
        }
            return "_no_photo.png";
    }
    

    public function groupsmaster() {
        echo "<h1>$this->id</h1>";
        echo "<h1>".$this['item_type_master_id']."</h1>";
        $ret= ItemGroup::where('item_group_type_id','=',$this->id)->toSql();
        dump($ret);
        return ItemGroup::where('item_group_type_id','=',$this->id);
        //->where('item_type_sort','>',0)->OrderBy('item_type_sort')->get();class,'item_group_type_id','=','item_type_master_id');//->get();
    }

}
