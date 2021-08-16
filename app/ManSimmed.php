<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inventory;
use App\Item;
use App\Room;
use App\InventoryItem;

use App\StudentSubject;
use Illuminate\Support\Facades\Auth;

class ManSimmed extends Model
{
    
    function get_subjects()
    {
    //return DB::table('student_subjects')->get();
    return StudentSubject::all(); //get own items for room
    }

    public static function add_simmed(array $simmed_table)
    {
        $table=new Simmed;
            $table->simmed_date         =   $simmed_table['simmed_date'];
            $table->simmed_time_begin   =   $simmed_table['simmed_time_begin'];
			$table->simmed_time_end     =   $simmed_table['simmed_time_end'];
			$table->student_subject_id  =   $simmed_table['student_subject_id'];
            $table->student_group_id    =   $simmed_table['student_group_id'];
            $table->student_subgroup_id =   $simmed_table['student_subgroup_id'];
            $table->room_id             =   $simmed_table['room_id'];
            $table->simmed_leader_id    =   $simmed_table['simmed_leader_id'];
            $table->simmed_status       =1;
			$table->simmed_status2      =1;
            $ret=$table->save();
            echo $ret.'<br>';

		//status1:
        // 1 - zajęcia ze studentami
        // 2 - szkolenie
        // 3 - prace serwisowe
        // 4 - wyłączenie z użytkowania
        
        // 1 - rezerwacja
        // 2 - potwierdzone
		// 3 - zrealizowane
        // 4 - odwołane
        

        print_r($simmed_table);
        /*
        $zmEQ = new Inventory();
        $zmEQ->room_id = $RoomID;
        $zmEQ->inventory_name = 'nazwa inwentaryzacji';
        $zmEQ->inventory_date = '2020-12-16';
        $zmEQ->inventory_description = 'opis inwentaryzacji';
        $zmEQ->inventory_status = '1';  //rozpoczęta
        $zmEQ->save();
        return $zmEQ;
        */

    }




 

    

}
