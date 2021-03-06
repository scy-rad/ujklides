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

		//DB::statement('SET FOREIGN_KEY_CHECKS=0');
		//DB::table('work_times')->truncate();
        //DB::table('work_time_types')->truncate();
		
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
                echo "instruktor $aF_user nie zostaĹ‚ znaleziony"."\n";
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
        $in_work  = 2;
        $out_work = 3;
        $no_work  = 4;

        // $ret_work_time  =add_work_time_type('work_time', $all_work, 'in_work', 'w pracy', 'czas pracy', 'czas, kiedy jest siÄ™ w pracy od godziny przyjĹ›cia, do godziny wyjĹ›cia - bez uwzglÄ™dnienia ewentualnych przerw');
        // $ret_work_break =add_work_time_type('work_break', $no_work, 'out_work', 'poza pracÄ…', 'wyjĹ›cie z pracy', 'czas, kiedy wychodzi siÄ™ z pracy (przerwa na wyjĹ›cie prywatne)');
        // $ret_work_busy  =add_work_time_type('work_busy', $in_work, 'busy_work', 'zadanie', 'wykonywanie zadania', 'czas, kiedy wykonuje siÄ™ absorbujÄ…ce zadania i nie chce siÄ™ byÄ‡ niepokojonym przez innych - wymaga krĂłtkiego opisu zadania');
        // $ret_free_time  =add_work_time_type('free_time', $out_work, 'out_work', 'wolne', 'wolne', 'wolny czas (np. urlop, opieka nad dzieckiem zdrowym)');
        // $ret_sick_time  =add_work_time_type('sick_time', $out_work, 'out_work', 'chorobowe', 'chorobowe', 'czas zwolnienia lekarskiego (np. L4, opieka nad dzieckiem chorym, kwarantanna)');
        // $ret_work_remote=add_work_time_type('work_remote', $out_work, 'home_work', 'praca zdalna', 'praca zdalna', 'praca wykonywana poza CSM (np. praca zdalna, delegacja)');
        // $ret_phone_duty =add_work_time_type('phone_duty', $in_work, 'phone_work', 'pod telefonem', 'dyĹĽur telefoniczny', 'czas, kiedy jest siÄ™ "pod telefonem" i jest siÄ™ gotowym do pomocy potrzebujÄ…cym technikom :)');
        
        $ret_work_time=1;

/*
            add_work_time('sebek',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-01','08:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-03','08:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-04','07:30','15:30','');	add_work_time('sebek',$ret_work_break,'2022-03-04','12:30','13:00','wyjście prywatne');		
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-08','08:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-10','07:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-03-14','07:30','16:00','+pakowanie rzeczy z pokoju');	add_work_time('sebek',$ret_work_break,'2022-03-14','12:30','13:00','wyjście prywatne');		
            add_work_time('sebek',$ret_work_time,'2022-03-15','08:30','15:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-16','07:30','15:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-17','08:30','15:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-22','08:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-23','07:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-24','08:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-25','07:30','15:30','wolne');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-03-29','08:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-30','07:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-03-31','08:30','14:00','');			
            add_work_time('sebek',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-08','08:30','15:30','8:00-12:30');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-04-11','07:30','14:00','12:30-17:00');			
            add_work_time('sebek',$ret_free_time,'2022-04-12','08:30','14:00','8:00-17:00 OSCE');			
            add_work_time('sebek',$ret_work_time,'2022-04-13','07:30','14:00','8:00-15:30');			
            add_work_time('sebek',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('sebek',$ret_work_time,'2022-04-21','07:30','15:30','8:00-12:30');			
            add_work_time('sebek',$ret_work_time,'2022-04-22','08:30','15:30','7:30-13:30 ZP');			
                        
                        
            add_work_time('sebek',$ret_work_time,'2022-04-25','08:30','15:30','7:30-13:30 ZP');			
            add_work_time('sebek',$ret_work_time,'2022-04-26','08:30','15:30','7:30-13:30 ZP');			
            add_work_time('sebek',$ret_work_time,'2022-04-27','08:30','15:30','7:30-13:30 ZP krew');			
            add_work_time('sebek',$ret_work_time,'2022-04-28','08:30','15:30','7:30-13:30 ZP krew');			
            add_work_time('sebek',$ret_work_time,'2022-04-29','08:30','15:30','7:30-13:30 ZP');			
                        
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-03-01','07:30','15:30','');	add_work_time('mateusz',$ret_phone_duty,'2022-03-01','07:30','15:30','');		
            add_work_time('mateusz',$ret_work_time,'2022-03-02','10:00','18:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-03-07','10:00','18:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-09','10:00','18:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-10','11:30','15:30','');	add_work_time('mateusz',$ret_free_time,'2022-03-10','07:30','11:30','Wizyta lekarska z dzieckiem');		
            add_work_time('mateusz',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-16','12:30','20:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-17','07:00','15:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-03-21','07:30','16:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-23','10:00','18:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-24','07:00','15:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-25','07:30','15:30','');	add_work_time('mateusz',$ret_work_busy,'2022-03-25','07:30','15:30','Przygotowanie przygotowanie OSCE');		
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-03-28','07:00','18:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-29','07:00','18:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-30','07:30','18:00','');			
            add_work_time('mateusz',$ret_work_time,'2022-03-31','07:00','18:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-01','07:30','15:30','');	add_work_time('mateusz',$ret_work_busy,'2022-04-01','11:30','15:30','Szykowanie modółów');		
                        
                        
            add_work_time('mateusz',$ret_free_time,'2022-04-04','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('mateusz',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('mateusz',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-01','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('paulina',$ret_free_time,'2022-03-10','07:30','15:30','');			
            add_work_time('paulina',$ret_work_remote,'2022-03-11','07:30','15:30','Konferencja w Poznaniu');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-15','12:30','20:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-16','12:00','20:00','');			
            add_work_time('paulina',$ret_work_time,'2022-03-17','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-18','12:30','20:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-23','12:00','20:00','');			
            add_work_time('paulina',$ret_work_time,'2022-03-24','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-25','12:30','20:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-29','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-03-31','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('paulina',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('paulina',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-01','12:30','20:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-10','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-03-14','12:30','20:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-16','07:30','17:00','');			
            add_work_time('darek',$ret_work_time,'2022-03-17','12:30','20:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-23','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-24','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-25','12:30','20:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-29','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-03-31','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('darek',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('darek',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
            add_work_time('marcin',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-01','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-10','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-16','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-17','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-22','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-23','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-24','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-25','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-29','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-03-31','12:30','20:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('marcin',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('marcin',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-01','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-10','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-16','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-17','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-23','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-24','07:30','15:30','');			
                        
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-29','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-03-31','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('bartek',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('bartek',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-01','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('angelika',$ret_free_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-08','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-10','07:30','15:30','');			
            add_work_time('angelika',$ret_work_remote,'2022-03-11','07:30','15:30','Konferencja - Poznań');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-16','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-17','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-18','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-03-21','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-23','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-24','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-25','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-29','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-03-31','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('angelika',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('angelika',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-02-28','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-01','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-02','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-03','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-04','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-03-07','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-08','12:30','20:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-09','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-10','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-11','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-03-14','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-15','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-16','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-17','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-18','11:00','19:00','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-03-21','12:30','20:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-22','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-23','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-24','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-25','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-03-28','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-29','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-30','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-03-31','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-01','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-04-04','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-05','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-06','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-07','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-08','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-04-11','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-12','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-13','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-14','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-15','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-04-18','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-19','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-20','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-21','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-22','07:30','15:30','');			
                        
                        
            add_work_time('wojtek',$ret_work_time,'2022-04-25','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-26','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-27','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-28','07:30','15:30','');			
            add_work_time('wojtek',$ret_work_time,'2022-04-29','07:30','15:30','');			
                        
*/

add_work_time('angelika',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('angelika',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('angelika',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('angelika',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('angelika',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('angelika',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('angelika',$ret_work_time,'2022-05-31','07:30','15:30','');

add_work_time('bartek',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('bartek',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('bartek',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('bartek',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('bartek',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('bartek',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('bartek',$ret_work_time,'2022-05-31','07:30','15:30','');


add_work_time('darek',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('darek',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('darek',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('darek',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('darek',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('darek',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('darek',$ret_work_time,'2022-05-31','07:30','15:30','');


add_work_time('sebek',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('sebek',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('sebek',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('sebek',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('sebek',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('sebek',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('sebek',$ret_work_time,'2022-05-31','07:30','15:30','');

add_work_time('marcin',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('marcin',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('marcin',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('marcin',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('marcin',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('marcin',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('marcin',$ret_work_time,'2022-05-31','07:30','15:30','');


add_work_time('mateusz',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('mateusz',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('mateusz',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('mateusz',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('mateusz',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('mateusz',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('mateusz',$ret_work_time,'2022-05-31','07:30','15:30','');



add_work_time('paulina',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('paulina',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('paulina',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('paulina',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('paulina',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('paulina',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('paulina',$ret_work_time,'2022-05-31','07:30','15:30','');


add_work_time('wojtek',$ret_work_time,'2022-05-02','07:30','15:30','');

add_work_time('wojtek',$ret_work_time,'2022-05-04','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-05','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-06','07:30','15:30','');


add_work_time('wojtek',$ret_work_time,'2022-05-09','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-10','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-11','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-12','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-13','07:30','15:30','');


add_work_time('wojtek',$ret_work_time,'2022-05-16','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-17','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-18','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-19','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-20','07:30','15:30','');


add_work_time('wojtek',$ret_work_time,'2022-05-23','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-24','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-25','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-26','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-27','07:30','15:30','');


add_work_time('wojtek',$ret_work_time,'2022-05-30','07:30','15:30','');
add_work_time('wojtek',$ret_work_time,'2022-05-31','07:30','15:30','');

            

    }
}
