<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SimmedTempRoom extends Model
{

    public function room() {
        return $this->hasOne(Room::class,'id','room_id');//->get()->first();
    }

    public static function add_simmed_tmp_room(array $tmp_table)
    {      
        //dd($tmp_table);

        if (SimmedTempRoom::where('import_status',0)->get()->count()>0)
            $import_number=SimmedTempRoom::max('import_number');
        else
            $import_number=SimmedTempRoom::max('import_number')+1;

        foreach ($tmp_table['info']['room_id_tab'] as $room_imp)
        {
        $table=new SimmedTempRoom;
             $table->room_id             =   $room_imp;
             $table->simmed_date_begin   =   $tmp_table['info']['from'];
             $table->simmed_date_end     =   $tmp_table['info']['to'];
             if ($tmp_table['simmeds'] != NULL)
                $table->import_count        =   count($tmp_table['simmeds']);
             else
                $table->import_count        =   0;
             $table->import_number       =   $import_number;
             $table->import_status       =   0;
        $ret=$table->save();
        }
        
    }

    public static function update_simmed_tmp_room(array $tmp_table)
    {
        dd($tmp_table);
    }

    

    public static function check_simmed_tmp_room_remove()
    {
        SimmedTemp::where('tmp_status', '=', '0')->where('simmed_id', '>', '0')->update(['tmp_status' => 3]);
    }




}
