<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ItemType extends Model
{

    public function GetMaster($id)
    {   //funkcja zwraca głównego rodzica typu o podanym ID
        $curr=ItemType::where('id',$id)->first()->item_type_parent_id;              //sprawdź, czy podane id ma rodzica
        $return=$id;                                                                //domyślnie zwróć bieżące id (w przypadku jeśli bieżące id nie ma rodzica)
        $a=false;                                                                   //ustaw zmienną a na false (dla pętli sprawdzającej)
        if ($curr>0)                                                                //jeśli jest rodzic
        {        
            while ($a==false)                                                       //sprawdzaj w pętli
            {
                $return=$curr;                                                      //zapisz bieżące id jako zwracana
                $curr=ItemType::where('id',$curr)->first()->item_type_parent_id;    //sprawdź czy bieąće id ma rodzica                
                if ($curr==0)                                                       //jeśli nie ma rodzica
                    $a=true;                                                        //ustaw zmienną a na true (dla wyjścia  pętli sprawdzającej)
            }
        }
        return $return;
    }
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
    
    public function typepatch() 
    {
        $check=TRUE;
        $obiekcik=$this;
        $echo='<a href="'.route('itemtypes.index', $obiekcik->id).'">';
        $echo.=$obiekcik->item_type_name;
        $echo.='</a>';
        if ($obiekcik->item_type_parent_id>0)
        do 
        {
            $obiekcik=ItemType::where('id','=',$obiekcik->item_type_parent_id)->get()->first();
            $echo='<a href="'.route('itemtypes.index', $obiekcik->id).'">'.
                $obiekcik->item_type_name.
                '</a>'.
                ' <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> '.
                $echo;
            if ($obiekcik->item_type_parent_id==0) $check=FALSE;
        }
        while ($check);
        //add first elemet - "wszystko":
        $echo='<a href="'.route('itemtypes.index', 0).'">wszystko</a>'.
        ' <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> '.
        $echo;

        return $echo;
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
    

    // public function groupsmaster() {
    //     echo "<h1>$this->id</h1>";
    //     echo "<h1>".$this['item_type_master_id']."</h1>";
    //     $ret= ItemGroup::where('item_group_type_id','=',$this->id)->toSql();
    //     dump($ret);
    //     return ItemGroup::where('item_group_type_id','=',$this->id);
    //     //->where('item_type_sort','>',0)->OrderBy('item_type_sort')->get();class,'item_group_type_id','=','item_type_master_id');//->get();
    // }

    public static function recalculate_masters() 
    {   // Funkcja ponownie przypisuje każdemu typowi jego głównego rodzica (item_type_master_id)

        function recur($recur_id,$recur_table)
        {   //funkcja rekurencyjna do przechodzenia w głąb dzieci
            $next_recur=ItemType::whereIn('item_type_parent_id',$recur_table)->select('id')->get(); //pobierz tabelę dzieci dla podanej tabeli rodiców ($recur_table)

            if ($next_recur->count() > 0)                                                           // jeżeli tabela dzieci nie jest pusta
            {
                // ItemType::whereIn('item_type_parent_id',$recur_table)->update(['item_type_master_id' => $recur_id]);     //ten zapis eloquenta nie zadziałał, ale jest tożsamy z poniższym
                DB::table('item_types')
                    ->whereIn('item_type_parent_id',$recur_table)
                    ->update(['item_type_master_id' => $recur_id]);                                 // przypisz wszystkim dzieciom ID głównego rodzica: item_type_master_id = $recur_id 
                recur($recur_id,$next_recur);                                                       // wywołaj ponownie funkcję, gdzie nowymi rodzicami będą obecne dzieci 
            }
        }

        $matersy=ItemType::where('item_type_parent_id',0)->select('id')->get();             // pobierz wszystkie typy "główne" - nie posiadające rodziców

        foreach($matersy as $maters_one)                                                    // dla każdego rodzica głównego
            recur($maters_one->id,$maters_one);                                             // przypisz jego ID jako rodzica głównego wszystkim jego potomkom
    }

}
