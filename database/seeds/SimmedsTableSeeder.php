<?php
//php71-cli artisan make:controller RoomStoragesController --resource --model=RoomStorages
//php71-cli artisan db:seed --class=SimmedsTableSeeder
use Illuminate\Database\Seeder;

use App\StudentSubject;
use App\StudentGroup;
use App\Simmed;
use App\Room;
use App\User;


class SimmedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('simmeds')->truncate();
		DB::table('student_subjects')->truncate();
		DB::table('student_groups')->truncate();
		
		function add_student_group($aF_student_group_name)
		{
        $zmEQ = new StudentGroup();
        $zmEQ->student_group_name = $aF_student_group_name;
        $zmEQ->save();
		return $zmEQ->id;
		}

		function add_student_subject($aF_student_subject_name)
		{
        $zmEQ = new StudentSubject();
        $zmEQ->student_subject_name = $aF_student_subject_name;
        $zmEQ->save();
		return $zmEQ->id;
		}


		function insert_sims($aF_simmed_date, $aF_simmed_time_begin, $aF_simmed_time_end, $aF_student_subject, $aF_student_group, $aF_simmed_room, $aF_simmed_leader, $aF_simmed_technician, $aF_simmed_status)
			{
			if (StudentSubject::where('student_subject_name',$aF_student_subject)->count()>0)
				$aF_student_subject_id = StudentSubject::where('student_subject_name',$aF_student_subject)->first()->id;
			else
				{
				echo "temat $aF_student_subject nie został znaleziony"."\n";
				return 0;
				}

			if (StudentGroup::where('student_group_name',$aF_student_group)->count()>0)
				$aF_student_group_id = StudentGroup::where('student_group_name',$aF_student_group)->first()->id;
			else
				{
				echo "grupa $aF_student_group nie została znaleziona"."\n";
				return 0;
				}

			if (Room::where('room_number',$aF_simmed_room)->count()>0)
				$aF_simmed_room_id = Room::where('room_number',$aF_simmed_room)->first()->id;
			else
				{
				echo "pokój $aF_simmed_room nie został znaleziony"."\n";
				return 0;
				}
			
			if (User::where('name',$aF_simmed_leader)->count()>0)
				$aF_simmed_leader_id = User::where('name',$aF_simmed_leader)->first()->id;
			else
				{
				echo "instruktor $aF_simmed_leader nie został znaleziony"."\n";
				return 0;
				}


			if (User::where('name',$aF_simmed_technician)->count()>0)
				$aF_simmed_technician_id = User::where('name',$aF_simmed_technician)->first()->id;
			else
				{
				echo "instruktor $aF_simmed_technician nie został znaleziony"."\n";
				return 0;
				}

	
			$zmEQ = new Simmed();
			$zmEQ->simmed_date=$aF_simmed_date;
			$zmEQ->simmed_time_begin=$aF_simmed_time_begin;
			$zmEQ->simmed_time_end=$aF_simmed_time_end;
			$zmEQ->student_subject_id=$aF_student_subject_id;
			$zmEQ->student_group_id=$aF_student_group_id;
			$zmEQ->room_id=$aF_simmed_room_id;
			$zmEQ->simmed_leader_id=$aF_simmed_leader_id;
			$zmEQ->simmed_technician_id=$aF_simmed_technician_id;
			$zmEQ->save();
			}


add_student_subject("_przedmiot nieokreślony");
add_student_subject("Szkolenie trenerów");
add_student_subject("egzaminy OSCE");
add_student_subject("Interna i pielęgniarstwo internistyczne");
add_student_subject("Opieka ginekologiczna");
add_student_subject("Opieka neonatologiczna");
add_student_subject("Opieka paliatywna");
add_student_subject("Pielęgniarstwo chirurgiczne");
add_student_subject("Pielęgniarstwo geriatryczne");
add_student_subject("Pielęgniarstwo internistyczne");
add_student_subject("Pielęgniarstwo neurologiczne");
add_student_subject("Pielęgniarstwo pediatryczne");
add_student_subject("Pielęgniarstwo położniczo - ginekologiczne");
add_student_subject("Pielęgniarstwo psychiatryczne");
add_student_subject("Pielęgniarstwo w anestezjologii i stanach zagrożenia życia");
add_student_subject("Pielęgniarstwo w zagrożeniu życia");
add_student_subject("Pielęgnowanie niepełnosprawnych");
add_student_subject("Podstawowa opieka zdrowotna");
add_student_subject("Podstawy opieki położniczej");
add_student_subject("Techniki położnicze i prowadzenie porodu");

add_student_group("_grupa nieokreślona");
add_student_group("OSCE");
add_student_group("Zajęcia Praktyczne");
add_student_group("1 Po/Ist./S/2020/2021 - zp 03");
add_student_group("3 P/Ist./S/19/20 - sym 01");
add_student_group("3 P/Ist./S/19/20 - sym 02");
add_student_group("3 P/Ist./S/19/20 - sym 03");
add_student_group("3 P/Ist./S/19/20 - sym 04");
add_student_group("3 P/Ist./S/19/20 - sym 05");
add_student_group("3 P/Ist./S/19/20 - sym 06");
add_student_group("3 P/Ist./S/19/20 - sym 07");
add_student_group("3 P/Ist./S/19/20 - sym 08");
add_student_group("3 P/Ist./S/19/20 - sym 09");
add_student_group("3 P/Ist./S/19/20 - sym 10");
add_student_group("3 P/Ist./S/19/20 - sym 11");
add_student_group("3 P/Ist./S/19/20 - sym 12");
add_student_group("3 P/Ist./S/19/20 - zp 01");
add_student_group("3 P/Ist./S/19/20 - zp 11");
add_student_group("3 P/Ist./S/19/20 - zp 12");
add_student_group("3 P/Ist./S/19/20 - zp04");
add_student_group("3 Po/Is./S/19/20 - sym 01");
add_student_group("3 Po/Is./S/19/20 - sym 02");
add_student_group("3 Po/Is./S/19/20 - sym 03");
add_student_group("3 Po/Is./S/19/20 - sym 04");
add_student_group("3 Po/Is./S/19/20 - sym 05");
add_student_group("3 Po/Is./S/19/20 - sym 06");
add_student_group("3 Po/Is./S/19/20 - sym 07");
add_student_group("3 Po/Is./S/19/20 - sym 08");
add_student_group("3 Po/Is./S/19/20 - zp 01");
add_student_group("3 Po/Is./S/19/20 - zp 02");
add_student_group("4 P/Ist./S/19/20 - sym 01");
add_student_group("4 P/Ist./S/19/20 - sym 02");
add_student_group("4 P/Ist./S/19/20 - sym 03");
add_student_group("4 P/Ist./S/19/20 - sym 04");
add_student_group("4 P/Ist./S/19/20 - sym 05");
add_student_group("4 P/Ist./S/19/20 - sym 06");
add_student_group("4 P/Ist./S/19/20 - sym 07");
add_student_group("4 P/Ist./S/19/20 - sym 08");
add_student_group("4 P/Ist./S/19/20 - sym 09");
add_student_group("4 P/Ist./S/19/20 - sym 10");
add_student_group("4 P/Ist./S/19/20 - sym 11");
add_student_group("4 P/Ist./S/19/20 - sym 12");
add_student_group("5 P/Ist./S/18/19 - sym 01");
add_student_group("5 P/Ist./S/18/19 - sym 02");
add_student_group("5 P/Ist./S/18/19 - sym 03");
add_student_group("5 P/Ist./S/18/19 - sym 04");
add_student_group("5 P/Ist./S/18/19 - sym 05");
add_student_group("5 P/Ist./S/18/19 - sym 06");
add_student_group("5 P/Ist./S/18/19 - sym 07");
add_student_group("5 P/Ist./S/18/19 - sym 08");
add_student_group("5 P/Ist./S/18/19 - sym 09");
add_student_group("5 P/Ist./S/18/19 - sym 10");
add_student_group("5 P/Ist./S/18/19 - sym 11");
add_student_group("5 P/Ist./S/18/19 - zp 03");
add_student_group("5 Po/Ist./S/18/19 - sym 01");
add_student_group("5 Po/Ist./S/18/19 - sym 02");
add_student_group("5 Po/Ist./S/18/19 - sym 03");
add_student_group("5 Po/Ist./S/18/19 - sym 04");
add_student_group("5 Po/Ist./S/18/19 - sym 05");
add_student_group("5 Po/Ist./S/18/19 - sym 06");
add_student_group("5 Po/Ist./S/18/19 - sym 07");
add_student_group("5 Po/Ist./S/18/19 - sym 08");


insert_sims("2020-11-02","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 01","B 1.05","kwojcik","alewandowska",1);
insert_sims("2020-11-02","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 07","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2020-11-02","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 11","B 1.15","ekaminska","biwan",1);
insert_sims("2020-11-02","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 02","B 1.05","kwojcik","alewandowska",1);
insert_sims("2020-11-02","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 02","B 1.01","azaworska","sdudek",1);
insert_sims("2020-11-03","07:30","13:30","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 04","B 1.15","ekamusinska","alewandowska",1);
insert_sims("2020-11-03","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 10","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2020-11-03","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 05","B 1.01","ejachymczyk","biwan",1);
insert_sims("2020-11-04","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 01","B 1.01","akaleta","anonim",1);
insert_sims("2020-11-04","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 03","B 1.01","azaworska","jwlodarczyk",1);
insert_sims("2020-11-05","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 05","B 1.01","enowak","sdudek",1);
insert_sims("2020-11-05","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","awencel","alewandowska",1);
insert_sims("2020-11-05","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 01","B 1.15","ekamusinska","biwan",1);
insert_sims("2020-11-05","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 07","B 1.01","enowak","sdudek",1);
insert_sims("2020-11-05","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 02","B 1.01","ejachymczyk","jwlodarczyk",1);
insert_sims("2020-11-06","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","5 P/Ist./S/18/19 - sym 05","B 1.05","awencel","alewandowska",1);
insert_sims("2020-11-06","08:30","11:30","Pielęgniarstwo geriatryczne","5 P/Ist./S/18/19 - sym 11","B 1.15","mkaczmarczyk","jwlodarczyk",1);
insert_sims("2020-11-06","11:30","14:30","Pielęgniarstwo geriatryczne","5 P/Ist./S/18/19 - sym 07","B 1.15","mkaczmarczyk","jwlodarczyk",1);
insert_sims("2020-11-09","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 11","B 1.01","pjasek","jwlodarczyk",1);
insert_sims("2020-11-09","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 05","B 1.05","mdurlik","alewandowska",1);
insert_sims("2020-11-09","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 06","B 1.05","mdurlik","alewandowska",1);
insert_sims("2020-11-09","11:00","17:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 07","B 1.01","jsikorska","anonim",1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2020-11-10","07:30","13:30","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 03","B 1.15","ekamusinska","jwlodarczyk",1);
insert_sims("2020-11-10","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 04","B 1.01","plon","biwan",1);
insert_sims("2020-11-10","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","5 P/Ist./S/18/19 - sym 07","B 1.05","awencel","alewandowska",1);
insert_sims("2020-11-10","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 01","B 1.01","plon","biwan",1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2020-11-12","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",1);
insert_sims("2020-11-12","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 07","B 1.05","awencel","alewandowska",1);
insert_sims("2020-11-12","08:00","15:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 08","B 1.15","ekamusinska","anonim",1);
insert_sims("2020-11-12","11:30","14:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 02","B 1.01","plon","biwan",1);
insert_sims("2020-11-12","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 05","B 1.01","azaworska","alewandowska",1);
insert_sims("2020-11-13","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 02","B 1.15","ekamusinska","biwan",1);
insert_sims("2020-11-13","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 04","B 1.01","ejachymczyk","jwlodarczyk",1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2020-11-16","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 10","B 1.01","molczyk ","jwlodarczyk",1);
insert_sims("2020-11-16","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 08","B 1.05","awencel","alewandowska",1);
insert_sims("2020-11-16","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 04","B 1.01","azaworska","sdudek",1);
insert_sims("2020-11-17","07:30","13:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 12","B 1.01","sglowala","anonim",1);
insert_sims("2020-11-17","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 08","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-17","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 09","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-17","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 09","B 1.01","pjasek","sdudek",1);
insert_sims("2020-11-18","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 05","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-18","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 02","B 1.01","akaleta","anonim",1);
insert_sims("2020-11-18","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 08","B 1.05","jsikorska","alewandowska",1);
insert_sims("2020-11-18","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 06","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-19","08:00","11:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 05","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-19","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 06","B 1.01","azaworska","alewandowska",1);
insert_sims("2020-11-19","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 08","B 1.15","adunkowska","biwan",1);
insert_sims("2020-11-20","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 03","B 1.01","akaleta","anonim",1);
insert_sims("2020-11-20","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 11","B 1.05","jsikorska","alewandowska",1);
insert_sims("2020-11-20","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 01","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2020-11-20","13:00","17:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 05","B 1.01","mdudek","jwlodarczyk",1);
insert_sims("2020-11-23","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 08","B 1.01","asaluga","jwlodarczyk",1);
insert_sims("2020-11-23","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 05","B 1.05","mkaczmarczyk","biwan",1);
insert_sims("2020-11-23","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 08","B 1.01","enowak","jwlodarczyk",1);
insert_sims("2020-11-23","11:00","14:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","mkaczmarczyk","biwan",1);
insert_sims("2020-11-23","14:00","17:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 06","B 1.01","enowak","sdudek",1);
insert_sims("2020-11-23","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 09","B 1.15","adunkowska","sdudek",1);
insert_sims("2020-11-23","17:00","20:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 06","B 1.01","ejachymczyk","sdudek",1);
insert_sims("2020-11-24","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 06","B 1.01","pzajac","biwan",1);
insert_sims("2020-11-24","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 09","B 1.05","jsikorska","alewandowska",1);
insert_sims("2020-11-24","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 10","B 1.15","adunkowska","sdudek",1);
insert_sims("2020-11-24","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 03","B 1.01","ejachymczyk","jwlodarczyk",1);
insert_sims("2020-11-25","07:30","12:00","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 01","B 1.05","kwojcik","mlenard",1);
insert_sims("2020-11-25","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 04","B 1.01","akaleta","biwan",1);
insert_sims("2020-11-25","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 02","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2020-11-25","13:15","17:45","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 06","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2020-11-25","12:00","16:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 02","B 1.05","kwojcik","mlenard",1);
insert_sims("2020-11-25","15:00","19:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 11","B 1.01","molczyk ","biwan",1);
insert_sims("2020-11-26","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 07","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-26","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 05","B 1.01","akaleta","mlenard",1);
insert_sims("2020-11-26","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 04","B 1.01","mdudek","jwlodarczyk",1);
insert_sims("2020-11-26","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 06","B 1.15","adunkowska","biwan",1);
insert_sims("2020-11-27","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 01","B 1.01","plon","jwlodarczyk",1);
insert_sims("2020-11-27","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 07","B 1.05","mkaczmarczyk","biwan",1);
insert_sims("2020-11-27","11:00","14:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 08","B 1.05","mkaczmarczyk","biwan",1);
insert_sims("2020-11-27","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",1);
insert_sims("2020-11-27","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 11","B 1.15","adunkowska","mlenard",1);
insert_sims("2020-11-30","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 01","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-30","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 07","B 1.01","pzajac","alewandowska",1);
insert_sims("2020-11-30","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 02","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-11-30","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",1);
insert_sims("2020-12-01","07:30","13:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 12","B 1.05","sglowala","anonim",1);
insert_sims("2020-12-01","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 08","B 1.01","pzajac","biwan",1);
insert_sims("2020-12-01","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 09","B 1.15","mkordyzon","jwlodarczyk",1);
insert_sims("2020-12-01","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 06","B 1.01","ezawierucha","sdudek",1);
insert_sims("2020-12-02","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 01","B 1.01","enowak","jwlodarczyk",1);
insert_sims("2020-12-02","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 03","B 1.15","blelonek","mlenard",1);
insert_sims("2020-12-02","11:00","14:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 02","B 1.01","enowak","jwlodarczyk",1);
insert_sims("2020-12-02","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 04","B 1.15","blelonek","mlenard",1);
insert_sims("2020-12-02","14:00","17:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 03","B 1.01","enowak","biwan",1);
insert_sims("2020-12-02","15:00","18:00","Pielęgniarstwo psychiatryczne","_grupa nieokreślona","B 1.15","adunkowska","mlenard",1);
insert_sims("2020-12-03","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 02","B 1.01","plon","alewandowska",1);
insert_sims("2020-12-03","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",1);
insert_sims("2020-12-04","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",1);
insert_sims("2020-12-04","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 07","B 1.05","mdurlik","alewandowska",1);
insert_sims("2020-12-04","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 02","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-12-04","11:00","14:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 04","B 1.01","plon","biwan",1);
insert_sims("2020-12-04","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 08","B 1.05","mdurlik","alewandowska",1);
insert_sims("2020-12-04","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 03","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-12-04","14:00","18:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 12","B 1.01","molczyk ","mlenard",1);
insert_sims("2020-12-07","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 04","B 1.01","asaluga","biwan",1);
insert_sims("2020-12-07","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",1);
insert_sims("2020-12-08","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 10","B 1.15","adunkowska","mlenard",1);
insert_sims("2020-12-08","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 07","B 1.01","ezawierucha","sdudek",1);
insert_sims("2020-12-09","07:30","12:00","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 03","B 1.05","kwojcik","mlenard",1);
insert_sims("2020-12-09","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 01","B 1.01","mpierzak","biwan",1);
insert_sims("2020-12-09","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 04","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2020-12-09","12:00","16:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 04","B 1.05","kwojcik","mlenard",1);
insert_sims("2020-12-09","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 02","B 1.01","mpierzak","biwan",1);
insert_sims("2020-12-09","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 11","B 1.15","adunkowska","alewandowska",1);
insert_sims("2020-12-10","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 10","B 1.01","pjasek","mlenard",1);
insert_sims("2020-12-10","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 06","B 1.01","enowak","jwlodarczyk",1);
insert_sims("2020-12-10","14:00","17:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 08","B 1.01","enowak","jwlodarczyk",1);
insert_sims("2020-12-10","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 12","B 1.15","adunkowska","biwan",1);
insert_sims("2020-12-11","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 06","B 1.01","pjasek","biwan",1);
insert_sims("2020-12-14","07:30","15:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp04","B 1.05","akaleta","anonim",1);
insert_sims("2020-12-14","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 01","B 1.15","blelonek","mlenard",1);
insert_sims("2020-12-14","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 09","B 1.01","pzajac","alewandowska",1);
insert_sims("2020-12-14","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 04","B 1.15","blelonek","mlenard",1);
insert_sims("2020-12-14","12:30","15:30","Opieka paliatywna","5 P/Ist./S/18/19 - sym 10","B 1.01","alesiak","sdudek",1);
insert_sims("2020-12-14","15:30","18:30","Opieka paliatywna","5 P/Ist./S/18/19 - sym 09","B 1.01","alesiak","sdudek",1);
insert_sims("2020-12-15","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 09","B 1.01","sglowala","jwlodarczyk",1);
insert_sims("2020-12-15","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 03","B 1.05","esiczek","anonim",1);
insert_sims("2020-12-15","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 10","B 1.01","sglowala","sdudek",1);
insert_sims("2020-12-16","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 06","B 1.01","mpierzak","mlenard",1);
insert_sims("2020-12-16","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","mkaczmarczyk","anonim",1);
insert_sims("2020-12-16","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 03","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2020-12-16","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 03","B 1.01","mpierzak","mlenard",1);
insert_sims("2020-12-17","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 01","B 1.01","pzajac","anonim",1);
insert_sims("2020-12-17","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 05","B 1.05","asaluga","jwlodarczyk",1);
insert_sims("2020-12-18","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 07","B 1.15","mkordyzon","alewandowska",1);
insert_sims("2020-12-18","08:30","13:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 08","B 1.01","mdudek","jwlodarczyk",1);
insert_sims("2020-12-18","13:00","17:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 07","B 1.01","mdudek","mlenard",1);
insert_sims("2020-12-18","13:00","17:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 08","B 1.15","mkordyzon","alewandowska",1);
insert_sims("2020-12-21","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 08","B 1.01","pjasek","jwlodarczyk",1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2020-12-21","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 05","B 1.05","awencel","alewandowska",1);
insert_sims("2020-12-21","11:00","14:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 07","B 1.01","pjasek","jwlodarczyk",1);
insert_sims("2020-12-21","14:30","17:30","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 04","B 1.05","esiczek","sdudek",1);
insert_sims("2020-12-22","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 05","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-12-22","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 11","B 1.01","sglowala","sdudek",1);
insert_sims("2020-12-22","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 06","B 1.15","blelonek","jwlodarczyk",1);
insert_sims("2020-12-22","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 12","B 1.01","sglowala","sdudek",1);
insert_sims("2021-01-04","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 01","B 1.05","esiczek","jwlodarczyk",1);
insert_sims("2021-01-04","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 06","B 1.01","pzajac","biwan",1);
insert_sims("2021-01-04","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 12","B 1.15","mkordyzon","alewandowska",1);
insert_sims("2021-01-04","14:30","17:30","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 05","B 1.01","pjasek","sdudek",1);
insert_sims("2021-01-04","14:30","17:30","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 02","B 1.05","esiczek","jwlodarczyk",1);
insert_sims("2021-01-05","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 04","B 1.01","plon","jwlodarczyk",1);
insert_sims("2021-01-05","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",1);
insert_sims("2021-01-07","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-07","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 11","B 1.01","ebaranska","biwan",1);
insert_sims("2021-01-08","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-08","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 05","B 1.15","mdudek","jwlodarczyk",1);
insert_sims("2021-01-08","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 08","B 1.01","ebaranska","mlenard",1);
insert_sims("2021-01-11","07:30","15:00","Podstawy opieki położniczej","1 Po/Ist./S/2020/2021 - zp 03","B 1.01","akaleta","anonim",1);
insert_sims("2021-01-11","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 06","B 1.01","ezawierucha","sdudek",1);
insert_sims("2021-01-12","08:00","11:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 10","B 1.01","ebaranska","jwlodarczyk",1);
insert_sims("2021-01-13","07:30","14:15","Podstawy opieki położniczej","1 Po/Ist./S/2020/2021 - zp 03","B 1.01","akaleta","anonim",1);
insert_sims("2021-01-13","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 01","B 1.01","plon","biwan",1);
insert_sims("2021-01-14","07:30","12:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-14","14:00","20:15","Pielęgniarstwo internistyczne","_grupa nieokreślona","B 1.01","mdudek","anonim",1);
insert_sims("2021-01-15","07:30","20:15","Pielęgniarstwo internistyczne","_grupa nieokreślona","B 1.01","mdudek","anonim",1);
insert_sims("2021-01-19","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-20","08:00","11:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 07","B 1.01","ebaranska","mlenard",1);
insert_sims("2021-01-21","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",1);
insert_sims("2021-01-22","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 02","B 1.01","plon","alewandowska",1);
insert_sims("2021-01-26","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",1);
insert_sims("2021-01-26","14:15","20:15","Techniki położnicze i prowadzenie porodu","3 Po/Is./S/19/20 - zp 01","B 1.05","awencel","anonim",1);
insert_sims("2021-01-26","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 09","B 1.01","ebaranska","sdudek",1);
insert_sims("2021-01-27","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-27","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 07","B 1.15","blelonek","mlenard",1);
insert_sims("2021-01-27","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 08","B 1.15","blelonek","mlenard",1);
insert_sims("2021-01-28","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",1);
insert_sims("2021-01-29","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",1);
insert_sims("2021-02-01","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",1);
insert_sims("2021-02-02","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2021-02-03","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",1);
insert_sims("2021-02-03","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 10","B 1.15","mkordyzon","biwan",1);
insert_sims("2021-02-03","12:30","17:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 11","B 1.15","mkordyzon","alewandowska",1);
insert_sims("2021-02-04","07:30","12:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",1);
insert_sims("2021-02-04","14:00","18:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2021-02-05","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",1);
insert_sims("2021-03-04","12:00","15:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 01","B 1.15","mkaczmarczyk","jwlodarczyk",1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 01","B 1.01","mdudek","jwlodarczyk",1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 02","B 1.05","dszmalec","alewandowska",1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 05","B 1.15","akaleta","jwlodarczyk",1);
insert_sims("2021-03-05","11:00","14:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 02","B 1.01","sglowala","mlenard",1);
insert_sims("2021-03-05","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 01","B 1.05","dszmalec","alewandowska",1);
insert_sims("2021-03-05","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 06","B 1.15","akaleta","jwlodarczyk",1);
insert_sims("2021-03-05","14:00","17:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 03","B 1.01","sglowala","mlenard",1);
insert_sims("2021-03-05","14:00","17:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 04","B 1.05","dszmalec","alewandowska",1);
insert_sims("2021-03-05","14:30","17:30","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 10","B 1.15","ekamusinska","biwan",1);
insert_sims("2021-03-09","12:00","15:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 04","B 1.01","sglowala","jwlodarczyk",1);
insert_sims("2021-03-09","12:00","15:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 07","B 1.15","akaleta","alewandowska",1);
insert_sims("2021-03-11","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 09","B 1.05","kwojcik","alewandowska",1);
insert_sims("2021-03-11","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 08","B 1.15","akaleta","mlenard",1);
insert_sims("2021-03-11","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 07","B 1.01","mdudek","jwlodarczyk",1);
insert_sims("2021-03-11","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 08","B 1.05","kwojcik","alewandowska",1);
insert_sims("2021-03-11","13:00","16:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 09","B 1.15","ekamusinska","anonim",1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 01","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 06","B 1.05","dszmalec","alewandowska",1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 05","B 1.15","akaleta","biwan",1);
insert_sims("2021-03-12","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 02","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2021-03-12","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 03","B 1.05","dszmalec","alewandowska",1);
insert_sims("2021-03-12","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 06","B 1.15","akaleta","biwan",1);
insert_sims("2021-03-18","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 10","B 1.05","kwojcik","alewandowska",1);
insert_sims("2021-03-18","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 07","B 1.15","akaleta","anonim",1);
insert_sims("2021-03-18","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 09","B 1.01","sglowala","mlenard",1);
insert_sims("2021-03-18","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 11","B 1.05","kwojcik","alewandowska",1);
insert_sims("2021-03-18","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 08","B 1.15","akaleta","anonim",1);
insert_sims("2021-03-19","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 03","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2021-03-19","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 01","B 1.15","mkaczmarczyk","biwan",1);
insert_sims("2021-03-19","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 04","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2021-03-19","11:00","14:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 02","B 1.15","mkaczmarczyk","alewandowska",1);
insert_sims("2021-03-25","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 12","B 1.05","kwojcik","alewandowska",1);
insert_sims("2021-03-25","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 08","B 1.01","sglowala","anonim",1);
insert_sims("2021-03-25","13:00","16:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 09","B 1.15","ekamusinska","anonim",1);
insert_sims("2021-03-26","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 01","B 1.01","jsikorska","anonim",1);
insert_sims("2021-03-26","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 02","B 1.15","mkaczmarczyk","anonim",1);
insert_sims("2021-03-26","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 05","B 1.05","mdudek","alewandowska",1);
insert_sims("2021-03-26","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 02","B 1.01","jsikorska","jwlodarczyk",1);
insert_sims("2021-03-26","11:00","14:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 03","B 1.15","mkaczmarczyk","anonim",1);
			
			
       
    }
}
