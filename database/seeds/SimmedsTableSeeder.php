<?php
//php artisan make:controller RoomStoragesController --resource --model=RoomStorages
//php artisan db:seed --class=SimmedsTableSeeder
use Illuminate\Database\Seeder;

use App\StudentSubject;
use App\StudentGroup;
use App\StudentSubgroup;
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
		DB::table('student_subgroups')->truncate();
		DB::table('student_groups')->truncate();

		function add_student_group($aF_student_group_name)
		{
        $zmEQ = new StudentGroup();
        $zmEQ->student_group_name = $aF_student_group_name;
        $zmEQ->save();
		return $zmEQ->id;
		}

		function add_groupsub($aF_id_group,$aF_prefix,$aF_count)
		{
		for($i = 1; $i <= $aF_count; $i++)
			{
			$zmEQ = new StudentSubgroup();
			$zmEQ->student_group_id=$aF_id_group;
            $zmEQ->subgroup_name=$aF_prefix." ".str_pad($i, 2, 0, STR_PAD_LEFT);
			$zmEQ->save();
			}
		}

		function add_student_subject($aF_student_subject_name,$aF_student_subject_name_en)
		{
        $zmEQ = new StudentSubject();
        $zmEQ->student_subject_name = $aF_student_subject_name;
		$zmEQ->student_subject_name_en = $aF_student_subject_name_en;
        $zmEQ->save();
		return $zmEQ->id;
		}


		function insert_sims($aF_simmed_date, $aF_simmed_time_begin, $aF_simmed_time_end, $aF_student_subject, $aF_student_group, $aF_simmed_room, $aF_simmed_leader, $aF_simmed_technician, $aF_simmed_technician_character_id, $aF_simmed_status)
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
			$zmEQ->simmed_technician_character_id=$aF_simmed_technician_character_id;
			$zmEQ->save();
			}


add_student_subject("_przedmiot nieokreślony","");
add_student_subject("Szkolenie trenerów","");
add_student_subject("egzaminy OSCE","");
add_student_subject("Interna i pielęgniarstwo internistyczne","");
add_student_subject("Opieka ginekologiczna","");
add_student_subject("Opieka neonatologiczna","");
add_student_subject("Opieka paliatywna","");
add_student_subject("Pielęgniarstwo chirurgiczne","");
add_student_subject("Pielęgniarstwo geriatryczne","");
add_student_subject("Pielęgniarstwo internistyczne","");
add_student_subject("Pielęgniarstwo neurologiczne","");
add_student_subject("Pielęgniarstwo pediatryczne","");
add_student_subject("Pielęgniarstwo położniczo - ginekologiczne","");
add_student_subject("Pielęgniarstwo psychiatryczne","");
add_student_subject("Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","");
add_student_subject("Pielęgniarstwo w zagrożeniu życia","");
add_student_subject("Pielęgnowanie niepełnosprawnych","");
add_student_subject("Podstawowa opieka zdrowotna","");
add_student_subject("Podstawy opieki położniczej","");
add_student_subject("Techniki położnicze i prowadzenie porodu","");

add_student_subject("Anestezjologia i intensywna terapia","Anesthesiology and Intensive Care");
add_student_subject("Anestezjologia i stany zagrożenia życia","");
add_student_subject("Chirurgia","Surgery");
add_student_subject("Chirurgia dziecięca","");
add_student_subject("Chirurgia i pielęgniarstwo chirurgiczne","");
add_student_subject("Choroby przenoszone drogą krwi","");
add_student_subject("Choroby wewnętrzne","Internal Medicine");
add_student_subject("Choroby wewnętrzne z elementami onkologii","");
add_student_subject("Choroby wewnętrzne-Propedeutyka Interny z elementami Kardiologii","Internal Medicine-propaedeutics in internal medicine with elements of cardiology");
add_student_subject("Ginekologia i położnictwo","");
add_student_subject("Intensywna terapia","");
add_student_subject("Medycyna katastrof","");
add_student_subject("Medycyna ratunkowa","");
add_student_subject("Medycyna Ratunkowa i Medycyna Katastrof","");
add_student_subject("Medyczne czynności ratunkowe","");
add_student_subject("Neurologia","Neurology");
add_student_subject("Opieka położnicza","");
add_student_subject("Pediatria","Pediatrics");
add_student_subject("Pielęgniarstwo opieki długoterminowej","");
add_student_subject("Pierwsza pomoc","");
add_student_subject("Podstawowe zabiegi medyczne","");
add_student_subject("Podstawy ratownictwa medycznego","");
add_student_subject("Procedury ratunkowe przedszpitalne","");
add_student_subject("Procedury ratunkowe wewnątrzszpitalne","");
add_student_subject("Propedeutyka medycyny","");
add_student_subject("Rehabilitacja w położnictwie, neonatologii i ginekologii","");


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



$id_group=add_student_group("LEK/Eng-Div-6/16/17");	add_groupsub($id_group,"ćwpk",2);	
$id_group=add_student_group("LEK/Eng-Div-6/17/18");	add_groupsub($id_group,"ćw",1);	
$id_group=add_student_group("LEK/Eng-Div-6/18/19");	add_groupsub($id_group,"ćwpk",3);	
$id_group=add_student_group("LEK/Eng-Div-6/19/20");	add_groupsub($id_group,"ćwpk",6);	
$id_group=add_student_group("LEK/S/16/17");	add_groupsub($id_group,"ćwpk",20);	
$id_group=add_student_group("LEK/S/17/18");	add_groupsub($id_group,"ćwpk",20);	
$id_group=add_student_group("LEK/S/18/19");	add_groupsub($id_group,"ćwpk",20);	
$id_group=add_student_group("LEK/S/19/20");	add_groupsub($id_group,"ćwpk",20);	add_groupsub($id_group,"sym",12);
$id_group=add_student_group("P/Ist./S/19/20");	add_groupsub($id_group,"ćwp",12);	add_groupsub($id_group,"sym",12);
$id_group=add_student_group("P/Ist./S/2020/2021");	add_groupsub($id_group,"ćwp",12);	add_groupsub($id_group,"sym",12);
$id_group=add_student_group("Po/Is./S/19/20");	add_groupsub($id_group,"ćwp",12);	add_groupsub($id_group,"sym",12);
$id_group=add_student_group("Po/Ist./S/2020/2021");	add_groupsub($id_group,"ćwp",6);	add_groupsub($id_group,"sym",11);
$id_group=add_student_group("POŁ/II st./S/2021/2");	add_groupsub($id_group,"zp",3);	
$id_group=add_student_group("RM/I stopień/niesta");	add_groupsub($id_group,"ćwp",2);	add_groupsub($id_group,"ćw",1);
$id_group=add_student_group("RM/I stopień/st/201");	add_groupsub($id_group,"ćwp",4);	add_groupsub($id_group,"ćw",2);
$id_group=add_student_group("RM/Ist/S/2020/2021");	add_groupsub($id_group,"ćwp",5);	add_groupsub($id_group,"ćw",2);
$id_group=add_student_group("RM/Ist/S/2021/2022/s");	add_groupsub($id_group,"ćwp",5);	






insert_sims("2020-11-02","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 01","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2020-11-02","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 07","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2020-11-02","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 11","B 1.15","ekaminska","biwan",3,1);
insert_sims("2020-11-02","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 02","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2020-11-02","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 02","B 1.01","azaworska","sdudek",3,1);
insert_sims("2020-11-03","07:30","13:30","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 04","B 1.15","ekamusinska","alewandowska",3,1);
insert_sims("2020-11-03","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 10","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2020-11-03","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 05","B 1.01","ejachymczyk","biwan",3,1);
insert_sims("2020-11-04","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 01","B 1.01","akaleta","anonim",3,1);
insert_sims("2020-11-04","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 03","B 1.01","azaworska","jwlodarczyk",3,1);
insert_sims("2020-11-05","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 05","B 1.01","enowak","sdudek",3,1);
insert_sims("2020-11-05","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-11-05","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 01","B 1.15","ekamusinska","biwan",3,1);
insert_sims("2020-11-05","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 07","B 1.01","enowak","sdudek",3,1);
insert_sims("2020-11-05","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 02","B 1.01","ejachymczyk","jwlodarczyk",3,1);
insert_sims("2020-11-06","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","5 P/Ist./S/18/19 - sym 05","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-11-06","08:30","11:30","Pielęgniarstwo geriatryczne","5 P/Ist./S/18/19 - sym 11","B 1.15","mkaczmarczyk","jwlodarczyk",3,1);
insert_sims("2020-11-06","11:30","14:30","Pielęgniarstwo geriatryczne","5 P/Ist./S/18/19 - sym 07","B 1.15","mkaczmarczyk","jwlodarczyk",3,1);
insert_sims("2020-11-09","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 11","B 1.01","pjasek","jwlodarczyk",3,1);
insert_sims("2020-11-09","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 05","B 1.05","mdurlik","alewandowska",3,1);
insert_sims("2020-11-09","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 06","B 1.05","mdurlik","alewandowska",3,1);
insert_sims("2020-11-09","11:00","17:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 07","B 1.01","jsikorska","anonim",3,1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2020-11-10","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2020-11-10","07:30","13:30","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 03","B 1.15","ekamusinska","jwlodarczyk",3,1);
insert_sims("2020-11-10","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 04","B 1.01","plon","biwan",3,1);
insert_sims("2020-11-10","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","5 P/Ist./S/18/19 - sym 07","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-11-10","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 01","B 1.01","plon","biwan",3,1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2020-11-12","07:00","14:00","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2020-11-12","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",3,1);
insert_sims("2020-11-12","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 07","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-11-12","08:00","15:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 08","B 1.15","ekamusinska","anonim",3,1);
insert_sims("2020-11-12","11:30","14:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 02","B 1.01","plon","biwan",3,1);
insert_sims("2020-11-12","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 05","B 1.01","azaworska","alewandowska",3,1);
insert_sims("2020-11-13","08:00","14:00","Pielęgnowanie niepełnosprawnych","5 P/Ist./S/18/19 - sym 02","B 1.15","ekamusinska","biwan",3,1);
insert_sims("2020-11-13","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 04","B 1.01","ejachymczyk","jwlodarczyk",3,1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2020-11-16","07:00","18:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2020-11-16","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 10","B 1.01","molczyk ","jwlodarczyk",3,1);
insert_sims("2020-11-16","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 08","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-11-16","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 04","B 1.01","azaworska","sdudek",3,1);
insert_sims("2020-11-17","07:30","13:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 12","B 1.01","sglowala","anonim",3,1);
insert_sims("2020-11-17","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 08","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-17","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 09","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-17","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 09","B 1.01","pjasek","sdudek",3,1);
insert_sims("2020-11-18","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 05","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-18","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 02","B 1.01","akaleta","anonim",3,1);
insert_sims("2020-11-18","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 08","B 1.05","jsikorska","alewandowska",3,1);
insert_sims("2020-11-18","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 06","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-19","08:00","11:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 05","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-19","14:45","20:45","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 06","B 1.01","azaworska","alewandowska",3,1);
insert_sims("2020-11-19","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 08","B 1.15","adunkowska","biwan",3,1);
insert_sims("2020-11-20","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 03","B 1.01","akaleta","anonim",3,1);
insert_sims("2020-11-20","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 11","B 1.05","jsikorska","alewandowska",3,1);
insert_sims("2020-11-20","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 01","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2020-11-20","13:00","17:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 05","B 1.01","mdudek","jwlodarczyk",3,1);
insert_sims("2020-11-23","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 08","B 1.01","asaluga","jwlodarczyk",3,1);
insert_sims("2020-11-23","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 05","B 1.05","mkaczmarczyk","biwan",3,1);
insert_sims("2020-11-23","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 08","B 1.01","enowak","jwlodarczyk",3,1);
insert_sims("2020-11-23","11:00","14:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","mkaczmarczyk","biwan",3,1);
insert_sims("2020-11-23","14:00","17:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 06","B 1.01","enowak","sdudek",3,1);
insert_sims("2020-11-23","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 09","B 1.15","adunkowska","sdudek",3,1);
insert_sims("2020-11-23","17:00","20:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 06","B 1.01","ejachymczyk","sdudek",3,1);
insert_sims("2020-11-24","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 06","B 1.01","pzajac","biwan",3,1);
insert_sims("2020-11-24","08:00","14:00","Pielęgniarstwo pediatryczne","5 P/Ist./S/18/19 - sym 09","B 1.05","jsikorska","alewandowska",3,1);
insert_sims("2020-11-24","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 10","B 1.15","adunkowska","sdudek",3,1);
insert_sims("2020-11-24","16:00","19:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 03","B 1.01","ejachymczyk","jwlodarczyk",3,1);
insert_sims("2020-11-25","07:30","12:00","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 01","B 1.05","kwojcik","mlenard",3,1);
insert_sims("2020-11-25","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 04","B 1.01","akaleta","biwan",3,1);
insert_sims("2020-11-25","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 02","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2020-11-25","13:15","17:45","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 06","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2020-11-25","12:00","16:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 02","B 1.05","kwojcik","mlenard",3,1);
insert_sims("2020-11-25","15:00","19:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 11","B 1.01","molczyk ","biwan",3,1);
insert_sims("2020-11-26","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 07","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-26","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 05","B 1.01","akaleta","mlenard",3,1);
insert_sims("2020-11-26","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 04","B 1.01","mdudek","jwlodarczyk",3,1);
insert_sims("2020-11-26","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 06","B 1.15","adunkowska","biwan",3,1);
insert_sims("2020-11-27","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 01","B 1.01","plon","jwlodarczyk",3,1);
insert_sims("2020-11-27","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 07","B 1.05","mkaczmarczyk","biwan",3,1);
insert_sims("2020-11-27","11:00","14:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 08","B 1.05","mkaczmarczyk","biwan",3,1);
insert_sims("2020-11-27","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",3,1);
insert_sims("2020-11-27","15:00","18:00","Pielęgniarstwo psychiatryczne","5 P/Ist./S/18/19 - sym 11","B 1.15","adunkowska","mlenard",3,1);
insert_sims("2020-11-30","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 01","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-30","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 07","B 1.01","pzajac","alewandowska",3,1);
insert_sims("2020-11-30","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 02","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-11-30","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",3,1);
insert_sims("2020-12-01","07:30","13:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 12","B 1.05","sglowala","anonim",3,1);
insert_sims("2020-12-01","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 08","B 1.01","pzajac","biwan",3,1);
insert_sims("2020-12-01","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 09","B 1.15","mkordyzon","jwlodarczyk",3,1);
insert_sims("2020-12-01","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 06","B 1.01","ezawierucha","sdudek",3,1);
insert_sims("2020-12-02","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 01","B 1.01","enowak","jwlodarczyk",3,1);
insert_sims("2020-12-02","08:00","11:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 03","B 1.15","blelonek","mlenard",3,1);
insert_sims("2020-12-02","11:00","14:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 02","B 1.01","enowak","jwlodarczyk",3,1);
insert_sims("2020-12-02","11:00","14:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 04","B 1.15","blelonek","mlenard",3,1);
insert_sims("2020-12-02","14:00","17:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 03","B 1.01","enowak","biwan",3,1);
insert_sims("2020-12-02","15:00","18:00","Pielęgniarstwo psychiatryczne","_grupa nieokreślona","B 1.15","adunkowska","mlenard",3,1);
insert_sims("2020-12-03","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 02","B 1.01","plon","alewandowska",3,1);
insert_sims("2020-12-03","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",3,1);
insert_sims("2020-12-04","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",3,1);
insert_sims("2020-12-04","08:00","11:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 07","B 1.05","mdurlik","alewandowska",3,1);
insert_sims("2020-12-04","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 02","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-12-04","11:00","14:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 04","B 1.01","plon","biwan",3,1);
insert_sims("2020-12-04","11:00","14:00","Opieka ginekologiczna","5 Po/Ist./S/18/19 - sym 08","B 1.05","mdurlik","alewandowska",3,1);
insert_sims("2020-12-04","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 03","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-12-04","14:00","18:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 12","B 1.01","molczyk ","mlenard",3,1);
insert_sims("2020-12-07","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 04","B 1.01","asaluga","biwan",3,1);
insert_sims("2020-12-07","14:15","20:15","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - zp 03","B 1.01","asaluga","anonim",3,1);
insert_sims("2020-12-08","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 10","B 1.15","adunkowska","mlenard",3,1);
insert_sims("2020-12-08","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 07","B 1.01","ezawierucha","sdudek",3,1);
insert_sims("2020-12-09","07:30","12:00","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 03","B 1.05","kwojcik","mlenard",3,1);
insert_sims("2020-12-09","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 01","B 1.01","mpierzak","biwan",3,1);
insert_sims("2020-12-09","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 04","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2020-12-09","12:00","16:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 04","B 1.05","kwojcik","mlenard",3,1);
insert_sims("2020-12-09","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 02","B 1.01","mpierzak","biwan",3,1);
insert_sims("2020-12-09","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 11","B 1.15","adunkowska","alewandowska",3,1);
insert_sims("2020-12-10","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 10","B 1.01","pjasek","mlenard",3,1);
insert_sims("2020-12-10","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 06","B 1.01","enowak","jwlodarczyk",3,1);
insert_sims("2020-12-10","14:00","17:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 08","B 1.01","enowak","jwlodarczyk",3,1);
insert_sims("2020-12-10","15:00","18:00","Pielęgniarstwo psychiatryczne","3 P/Ist./S/19/20 - sym 12","B 1.15","adunkowska","biwan",3,1);
insert_sims("2020-12-11","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 06","B 1.01","pjasek","biwan",3,1);
insert_sims("2020-12-14","07:30","15:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp04","B 1.05","akaleta","anonim",3,1);
insert_sims("2020-12-14","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 01","B 1.15","blelonek","mlenard",3,1);
insert_sims("2020-12-14","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 09","B 1.01","pzajac","alewandowska",3,1);
insert_sims("2020-12-14","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 04","B 1.15","blelonek","mlenard",3,1);
insert_sims("2020-12-14","12:30","15:30","Opieka paliatywna","5 P/Ist./S/18/19 - sym 10","B 1.01","alesiak","sdudek",3,1);
insert_sims("2020-12-14","15:30","18:30","Opieka paliatywna","5 P/Ist./S/18/19 - sym 09","B 1.01","alesiak","sdudek",3,1);
insert_sims("2020-12-15","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 09","B 1.01","sglowala","jwlodarczyk",3,1);
insert_sims("2020-12-15","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 03","B 1.05","esiczek","anonim",3,1);
insert_sims("2020-12-15","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 10","B 1.01","sglowala","sdudek",3,1);
insert_sims("2020-12-16","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 06","B 1.01","mpierzak","mlenard",3,1);
insert_sims("2020-12-16","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 06","B 1.05","mkaczmarczyk","anonim",3,1);
insert_sims("2020-12-16","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 03","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2020-12-16","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 03","B 1.01","mpierzak","mlenard",3,1);
insert_sims("2020-12-17","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 01","B 1.01","pzajac","anonim",3,1);
insert_sims("2020-12-17","08:00","11:00","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 05","B 1.05","asaluga","jwlodarczyk",3,1);
insert_sims("2020-12-18","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 07","B 1.15","mkordyzon","alewandowska",3,1);
insert_sims("2020-12-18","08:30","13:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 08","B 1.01","mdudek","jwlodarczyk",3,1);
insert_sims("2020-12-18","13:00","17:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 07","B 1.01","mdudek","mlenard",3,1);
insert_sims("2020-12-18","13:00","17:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 08","B 1.15","mkordyzon","alewandowska",3,1);
insert_sims("2020-12-21","08:00","11:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 08","B 1.01","pjasek","jwlodarczyk",3,1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2020-12-21","08:00","15:00","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2020-12-21","08:00","12:30","Opieka ginekologiczna","3 Po/Is./S/19/20 - sym 05","B 1.05","awencel","alewandowska",3,1);
insert_sims("2020-12-21","11:00","14:00","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 07","B 1.01","pjasek","jwlodarczyk",3,1);
insert_sims("2020-12-21","14:30","17:30","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 04","B 1.05","esiczek","sdudek",3,1);
insert_sims("2020-12-22","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 05","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-12-22","08:00","12:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 11","B 1.01","sglowala","sdudek",3,1);
insert_sims("2020-12-22","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 06","B 1.15","blelonek","jwlodarczyk",3,1);
insert_sims("2020-12-22","12:30","17:00","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - sym 12","B 1.01","sglowala","sdudek",3,1);
insert_sims("2021-01-04","08:00","11:00","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 01","B 1.05","esiczek","jwlodarczyk",3,1);
insert_sims("2021-01-04","08:00","12:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - sym 06","B 1.01","pzajac","biwan",3,1);
insert_sims("2021-01-04","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 12","B 1.15","mkordyzon","alewandowska",3,1);
insert_sims("2021-01-04","14:30","17:30","Pielęgniarstwo w anestezjologii i stanach zagrożenia życia","5 Po/Ist./S/18/19 - sym 05","B 1.01","pjasek","sdudek",3,1);
insert_sims("2021-01-04","14:30","17:30","Opieka neonatologiczna","3 Po/Is./S/19/20 - sym 02","B 1.05","esiczek","jwlodarczyk",3,1);
insert_sims("2021-01-05","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 04","B 1.01","plon","jwlodarczyk",3,1);
insert_sims("2021-01-05","11:00","14:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 03","B 1.01","plon","biwan",3,1);
insert_sims("2021-01-07","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-07","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 11","B 1.01","ebaranska","biwan",3,1);
insert_sims("2021-01-08","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-08","08:30","13:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 05","B 1.15","mdudek","jwlodarczyk",3,1);
insert_sims("2021-01-08","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 08","B 1.01","ebaranska","mlenard",3,1);
insert_sims("2021-01-11","07:30","15:00","Podstawy opieki położniczej","1 Po/Ist./S/2020/2021 - zp 03","B 1.01","akaleta","anonim",3,1);
insert_sims("2021-01-11","15:30","18:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - sym 06","B 1.01","ezawierucha","sdudek",3,1);
insert_sims("2021-01-12","08:00","11:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 10","B 1.01","ebaranska","jwlodarczyk",3,1);
insert_sims("2021-01-13","07:30","14:15","Podstawy opieki położniczej","1 Po/Ist./S/2020/2021 - zp 03","B 1.01","akaleta","anonim",3,1);
insert_sims("2021-01-13","14:30","17:30","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 01","B 1.01","plon","biwan",3,1);
insert_sims("2021-01-14","07:30","12:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-14","14:00","20:15","Pielęgniarstwo internistyczne","_grupa nieokreślona","B 1.01","mdudek","anonim",3,1);
insert_sims("2021-01-15","07:30","20:15","Pielęgniarstwo internistyczne","_grupa nieokreślona","B 1.01","mdudek","anonim",3,1);
insert_sims("2021-01-19","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-20","08:00","11:00","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 07","B 1.01","ebaranska","mlenard",3,1);
insert_sims("2021-01-21","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",3,1);
insert_sims("2021-01-22","08:00","11:00","Pielęgniarstwo w zagrożeniu życia","5 P/Ist./S/18/19 - sym 02","B 1.01","plon","alewandowska",3,1);
insert_sims("2021-01-26","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",3,1);
insert_sims("2021-01-26","14:15","20:15","Techniki położnicze i prowadzenie porodu","3 Po/Is./S/19/20 - zp 01","B 1.05","awencel","anonim",3,1);
insert_sims("2021-01-26","14:30","17:30","Pielęgniarstwo neurologiczne","5 P/Ist./S/18/19 - sym 09","B 1.01","ebaranska","sdudek",3,1);
insert_sims("2021-01-27","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-27","08:00","11:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 07","B 1.15","blelonek","mlenard",3,1);
insert_sims("2021-01-27","11:00","14:00","Pielęgniarstwo psychiatryczne","5 Po/Ist./S/18/19 - sym 08","B 1.15","blelonek","mlenard",3,1);
insert_sims("2021-01-28","07:30","13:30","Interna i pielęgniarstwo internistyczne","3 Po/Is./S/19/20 - zp 02","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-01-29","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",3,1);
insert_sims("2021-02-01","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",3,1);
insert_sims("2021-02-02","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2021-02-02","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2021-02-03","07:30","13:30","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 12","B 1.01","asaluga","anonim",3,1);
insert_sims("2021-02-03","08:00","12:30","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 10","B 1.15","mkordyzon","biwan",3,1);
insert_sims("2021-02-03","12:30","17:00","Podstawowa opieka zdrowotna","3 P/Ist./S/19/20 - sym 11","B 1.15","mkordyzon","alewandowska",3,1);
insert_sims("2021-02-04","07:30","12:00","Pielęgniarstwo internistyczne","3 P/Ist./S/19/20 - zp 01","B 1.01","enowak","anonim",3,1);
insert_sims("2021-02-04","14:00","18:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",3,1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2021-02-04","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2021-02-05","07:30","19:30","Pielęgniarstwo chirurgiczne","3 P/Ist./S/19/20 - zp 11","B 1.01","sglowala","anonim",3,1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.15","anonim","anonim",3,1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.16","anonim","anonim",3,1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.17","anonim","anonim",3,1);
insert_sims("2021-02-05","07:30","15:30","egzaminy OSCE","_grupa nieokreślona","B 1.18","anonim","anonim",3,1);
insert_sims("2021-03-04","12:00","15:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 01","B 1.15","mkaczmarczyk","jwlodarczyk",3,1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 01","B 1.01","mdudek","jwlodarczyk",3,1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 02","B 1.05","dszmalec","alewandowska",3,1);
insert_sims("2021-03-05","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 05","B 1.15","akaleta","jwlodarczyk",3,1);
insert_sims("2021-03-05","11:00","14:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 02","B 1.01","sglowala","mlenard",3,1);
insert_sims("2021-03-05","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 01","B 1.05","dszmalec","alewandowska",3,1);
insert_sims("2021-03-05","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 06","B 1.15","akaleta","jwlodarczyk",3,1);
insert_sims("2021-03-05","14:00","17:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 03","B 1.01","sglowala","mlenard",3,1);
insert_sims("2021-03-05","14:00","17:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 04","B 1.05","dszmalec","alewandowska",3,1);
insert_sims("2021-03-05","14:30","17:30","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 10","B 1.15","ekamusinska","biwan",3,1);
insert_sims("2021-03-09","12:00","15:00","Pielęgniarstwo neurologiczne","4 P/Ist./S/19/20 - sym 04","B 1.01","sglowala","jwlodarczyk",3,1);
insert_sims("2021-03-09","12:00","15:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 07","B 1.15","akaleta","alewandowska",3,1);
insert_sims("2021-03-11","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 09","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2021-03-11","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 08","B 1.15","akaleta","mlenard",3,1);
insert_sims("2021-03-11","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 07","B 1.01","mdudek","jwlodarczyk",3,1);
insert_sims("2021-03-11","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 08","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2021-03-11","13:00","16:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 09","B 1.15","ekamusinska","anonim",3,1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 01","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 06","B 1.05","dszmalec","alewandowska",3,1);
insert_sims("2021-03-12","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 05","B 1.15","akaleta","biwan",3,1);
insert_sims("2021-03-12","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 02","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2021-03-12","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 03","B 1.05","dszmalec","alewandowska",3,1);
insert_sims("2021-03-12","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 06","B 1.15","akaleta","biwan",3,1);
insert_sims("2021-03-18","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 10","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2021-03-18","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 07","B 1.15","akaleta","anonim",3,1);
insert_sims("2021-03-18","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 09","B 1.01","sglowala","mlenard",3,1);
insert_sims("2021-03-18","11:00","14:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 11","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2021-03-18","11:15","14:15","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 08","B 1.15","akaleta","anonim",3,1);
insert_sims("2021-03-19","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 03","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2021-03-19","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 01","B 1.15","mkaczmarczyk","biwan",3,1);
insert_sims("2021-03-19","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 04","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2021-03-19","11:00","14:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 02","B 1.15","mkaczmarczyk","alewandowska",3,1);
insert_sims("2021-03-25","08:00","11:00","Pielęgniarstwo położniczo - ginekologiczne","4 P/Ist./S/19/20 - sym 12","B 1.05","kwojcik","alewandowska",3,1);
insert_sims("2021-03-25","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 08","B 1.01","sglowala","anonim",3,1);
insert_sims("2021-03-25","13:00","16:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 09","B 1.15","ekamusinska","anonim",3,1);
insert_sims("2021-03-26","08:00","11:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 01","B 1.01","jsikorska","anonim",3,1);
insert_sims("2021-03-26","08:00","11:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 02","B 1.15","mkaczmarczyk","anonim",3,1);
insert_sims("2021-03-26","08:00","12:30","Pielęgniarstwo chirurgiczne","4 P/Ist./S/19/20 - sym 05","B 1.05","mdudek","alewandowska",3,1);
insert_sims("2021-03-26","11:00","14:00","Pielęgniarstwo pediatryczne","4 P/Ist./S/19/20 - sym 02","B 1.01","jsikorska","jwlodarczyk",3,1);
insert_sims("2021-03-26","11:00","14:00","Pielęgniarstwo geriatryczne","4 P/Ist./S/19/20 - sym 03","B 1.15","mkaczmarczyk","anonim",3,1);



    }
}
