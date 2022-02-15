<?php
//php artisan db:seed --class=WorkTimesTableSeeder

use Illuminate\Database\Seeder;

class WorkTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('work_times')->truncate();
        DB::table('work_time_types')->truncate();
		
        function add_work_time_type($aF_code, $aF_time_character, $aF_colour, $aF_short_name, $aF_long_name, $aF_description)
		{
            $table = new \App\WorkTimeType();

            $table->code=$aF_code;
            $table->time_character=$aF_time_character;  // 1 - all work
                                                        // 2 - in work
                                                        // 3 - out work
            $table->colour = $aF_colour;
            $table->short_name = $aF_short_name;
            $table->long_name = $aF_long_name;
            $table->description = $aF_description;
            $table->status = 1; // 1 - active

            $table->save();
		    return $table->id;
		}

        function add_work_time($aF_user, $aF_work_time_types_id, $aF_date, $aF_time_begin, $aF_time_end, $aF_description)
        {
            if (\App\User::where('name',$aF_user)->count()>0)
                $aF_user_id = \App\User::where('name',$aF_user)->first()->id;
            else
            {
                echo "instruktor $aF_user nie został znaleziony"."\n";
                return 0;
            }

            $table = new \App\WorkTime();
            $table->user_id         =$aF_user_id;
            $table->work_time_types_id=$aF_work_time_types_id;
            $table->date            =$aF_date;
            $table->time_begin      =$aF_time_begin;
			$table->time_end        =$aF_time_end;
            $table->description     =$aF_description;
            $table->status          =1; // 1 - active
            $table->save();
            return $table; 
        }

    
        $all_work = 1;
        $in_work = 2;
        $out_work = 3;

        $ret_work_time  =add_work_time_type('work_time', $all_work, 'in_work', 'w pracy', 'czas pracy', 'czas, kiedy jest się w pracy od godziny przyjścia, do godziny wyjścia - bez uwzględnienia ewentualnych przerw');
        $ret_work_brake =add_work_time_type('work_brake', $out_work, 'out_work', 'poza pracą', 'wyjście z pracy', 'czas, kiedy wychodzi się z pracy (przerwa na wyjście prywatne');
        $ret_work_busy  =add_work_time_type('work_busy', $in_work, 'busy_work', 'zadanie', 'wykonywanie zadania', 'czas, kiedy wykonuje się absorbujące zadania i nie chce się być niepokojonym przez innych - wymaga krótkiego opisu zadania');
        $ret_free_time  =add_work_time_type('free_time', $out_work, 'out_work', 'wolne', 'wolne', 'wolny czas (np. urlop, opieka nad dzieckiem zdrowym)');
        $ret_sick_time  =add_work_time_type('sick_time', $out_work, 'out_work', 'chorobowe', 'chorobowe', 'czas zwolnienia lekarskiego (np. L4, opieka nad dzieckiem chorym, kwarantanna)');
        $ret_work_remote=add_work_time_type('work_remote', $out_work, 'home_work', 'praca zdalna', 'praca zdalna', 'praca wykonywana poza CSM (np. praca zdalna, delegacja)');
        $ret_phone_duty =add_work_time_type('phone_duty', $in_work, 'phone_work', 'pod telefonem', 'dyżur telefoniczny', 'czas, kiedy jest się "pod telefonem" i jest się gotowym do pomocy potrzebującym technikom :)');


        $aF_date=date('Y-m-d');
        add_work_time('sebek', $ret_work_time, $aF_date, '07:30', '16:30', '');
        add_work_time('sebek', $ret_work_brake, $aF_date, '14:30', '15:30', '');        
        add_work_time('sebek', $ret_work_busy, $aF_date, '13:30', '14:30', 'opracowanie grafiku');

        add_work_time('darek', $ret_work_time, $aF_date, '07:30', '15:30', '');
        add_work_time('darek', $ret_work_busy, $aF_date, '13:00', '14:00', 'kontrola paszportów technicznych');

        add_work_time('wojtek', $ret_work_time, $aF_date, '07:30', '17:30', '');
        add_work_time('wojtek', $ret_phone_duty, $aF_date, '07:30', '10:00', '');
        add_work_time('wojtek', $ret_work_brake, $aF_date, '10:00', '12:00', '');

        add_work_time('marcin', $ret_work_time, $aF_date, '07:30', '15:30', '');
        add_work_time('marcin', $ret_work_busy, $aF_date, '07:30', '09:30', 'obsługa Instagrama');

        add_work_time('bartek', $ret_work_time, $aF_date, '12:30', '17:30', '');
        add_work_time('bartek', $ret_free_time, $aF_date, '09:30', '12:30', '');
        
        add_work_time('paulina', $ret_sick_time, $aF_date, '07:30', '15:30', '');
        add_work_time('paulina', $ret_work_remote, $aF_date, '15:30', '17:30', 'reorganizacja scenariuszy symulacji');
        
        


    }
}
