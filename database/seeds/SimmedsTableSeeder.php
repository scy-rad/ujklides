<?php
//php artisan make:controller RoomStoragesController --resource --model=RoomStorages
//php artisan db:seed --class=SimmedsTableSeeder
use Illuminate\Database\Seeder;

use App\TechnicianCharacter;
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

		function add_student_group($aF_student_group_name,$aF_student_group_code,$aF_centre, $aF_tech_char_def)
		{
			$zmEQ = new StudentGroup();
			$zmEQ->student_group_name = $aF_student_group_name;
			$zmEQ->student_group_code = $aF_student_group_code;
			$zmEQ->center_id = $aF_centre;
			$zmEQ->write_technician_character_default = $aF_tech_char_def;
			$zmEQ->save();
			return $zmEQ->id;
		}

		function add_groupsub($aF_id_group,$aF_prefix,$aF_count, $aF_tech_char)
		{
			for($i = 1; $i <= $aF_count; $i++)
			{
				$zmEQ = new StudentSubgroup();
				$zmEQ->student_group_id=$aF_id_group;
				$zmEQ->subgroup_name=$aF_prefix." ".str_pad($i, 2, 0, STR_PAD_LEFT);
				$zmEQ->write_technician_character=$aF_tech_char;
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
				echo "temat $aF_student_subject nie zosta?? znaleziony"."\n";
				return 0;
			}

			if (StudentGroup::where('student_group_name',$aF_student_group)->count()>0)
				$aF_student_group_id = StudentGroup::where('student_group_name',$aF_student_group)->first()->id;
			else
			{
				echo "grupa $aF_student_group nie zosta??a znaleziona"."\n";
				return 0;
			}

			if (Room::where('room_number',$aF_simmed_room)->count()>0)
				$aF_simmed_room_id = Room::where('room_number',$aF_simmed_room)->first()->id;
			else
			{
				echo "pok??j $aF_simmed_room nie zosta?? znaleziony"."\n";
				return 0;
			}

			if (User::where('name',$aF_simmed_leader)->count()>0)
				$aF_simmed_leader_id = User::where('name',$aF_simmed_leader)->first()->id;
			else
			{
				echo "instruktor $aF_simmed_leader nie zosta?? znaleziony"."\n";
				return 0;
			}


			if (User::where('name',$aF_simmed_technician)->count()>0)
				$aF_simmed_technician_id = User::where('name',$aF_simmed_technician)->first()->id;
			else
			{
				echo "instruktor $aF_simmed_technician nie zosta?? znaleziony"."\n";
				return 0;
			}

			$zmEQ = new Simmed();
			$zmEQ->simmed_date=$aF_simmed_date;
			$zmEQ->simmed_time_begin=$aF_simmed_time_begin;
			$zmEQ->simmed_time_end=$aF_simmed_time_end;
			$zmEQ->student_subject_id=$aF_student_subject_id;
			$zmEQ->student_group_id=$aF_student_group_id;
			$zmEQ->room_id=$aF_simmed_room_id;
			$zmEQ->simmed_technician_character_id=1;
			$zmEQ->simmed_leader_id=$aF_simmed_leader_id;
			$zmEQ->simmed_technician_id=$aF_simmed_technician_id;
			$zmEQ->simmed_technician_character_id=$aF_simmed_technician_character_id;
			$zmEQ->user_id=1;
			$zmEQ->save();
		}


		add_student_subject("_Temat testowy","Test subject");
		add_student_subject("Anestezjologia","Anesthesiology");
		add_student_subject("Anestezjologia i intensywna terapia","Anesthesiology and Intensive Care");
		add_student_subject("Anestezjologia i stany zagro??enia ??ycia","");
		add_student_subject("Chirurgia","Surgery");
		add_student_subject("Chirurgia dzieci??ca","");
		add_student_subject("Chirurgia i piel??gniarstwo chirurgiczne","");
		add_student_subject("Chirurgia og??lna","General Surgery");
		add_student_subject("Choroby wewn??trzne","Internal Medicine");
		add_student_subject("Choroby wewn??trzne z elementami onkologii","");
		add_student_subject("Choroby wewn??trzne-Propedeutyka Interny z elementami Kardiologii","Internal Medicine-propaedeutics in internal medicine with elements of cardiology");
		add_student_subject("Dermatologia i wenerologia","Dermatology and venerology");
		add_student_subject("Diagnostyka obrazowa","");
		add_student_subject("Endokrynologia","Endocrynology");
		add_student_subject("Farmakologia z toksykologi??","Pharmacology with toxicology");
		add_student_subject("Ginekologia","");
		add_student_subject("Ginekologia i po??o??nictwo","");
		add_student_subject("Intensywna terapia","");
		add_student_subject("Interna i piel??gniarstwo internistyczne","");
		add_student_subject("Ko??o naukowe","");
		add_student_subject("Medycyna katastrof","");
		add_student_subject("Medycyna ratunkowa","");
		add_student_subject("Medycyna Ratunkowa i Medycyna Katastrof","Emergency medicine and disaster medicine");
		add_student_subject("Medyczne czynno??ci ratunkowe","");
		add_student_subject("Nefrologia","Nefrology");
		add_student_subject("Neurologia","Neurology");
		add_student_subject("Opieka ginekologiczna","");
		add_student_subject("Opieka neonatologiczna","");
		add_student_subject("Opieka paliatywna","");
		add_student_subject("Opieka po??o??nicza","");
		add_student_subject("Ortopedia i traumatologia","");
		add_student_subject("Ortopedia i traumatologia narz??du ruchu","");
		add_student_subject("OSCE Piel??gniarstwo","");
		add_student_subject("OSCE Po??o??nictwo","");
		add_student_subject("Pediatria","Pediatrics");
		add_student_subject("Piel??gniarstwo chirurgiczne","");
		add_student_subject("Piel??gniarstwo geriatryczne","");
		add_student_subject("Piel??gniarstwo internistyczne","");
		add_student_subject("Piel??gniarstwo neurologiczne","");
		add_student_subject("Piel??gniarstwo opieki d??ugoterminowej","");
		add_student_subject("Piel??gniarstwo pediatryczne","");
		add_student_subject("Piel??gniarstwo po??o??niczo - ginekologiczne","");
		add_student_subject("Piel??gniarstwo psychiatryczne","");
		add_student_subject("Piel??gniarstwo w zagro??eniu ??ycia","");
		add_student_subject("Pierwsza pomoc z elementami piel??gniarstwa","");
		add_student_subject("Podstawowa opieka zdrowotna","");
		add_student_subject("Podstawowe zabiegi medyczne","");
		add_student_subject("Podstawy opieki po??o??niczej","");
		add_student_subject("Podstawy piel??gniarstwa","");
		add_student_subject("Podstawy ratownictwa medycznego","");
		add_student_subject("Procedury ratunkowe przedszpitalne","");
		add_student_subject("Procedury ratunkowe wewn??trzszpitalne","");
		add_student_subject("Propedeutyka medycyny","");
		add_student_subject("Psychiatria i piel??gniarstwo psychiatryczne","");
		add_student_subject("Rehabilitacja w po??o??nictwie, neonatologii i ginekologii","");
		add_student_subject("specjalizacje","");
		add_student_subject("Specjalno???? wybrana przez studenta","");
		add_student_subject("Techniki po??o??nicze i prowadzenie porodu","");
		




$id_group=add_student_group("RokTestowy","TST",1,1);	add_groupsub($id_group,"??wp",2,1);		
$id_group=add_student_group("LEK/Eng-Div-6/16/17","6L-ED",1,1);	add_groupsub($id_group,"??wpk",2,1);		
$id_group=add_student_group("LEK/Eng-Div-6/17/18","5L-ED",1,1);	add_groupsub($id_group,"??w",2,1);	add_groupsub($id_group,"??wp",2,1);	
$id_group=add_student_group("LEK/Eng-Div-6/18/19","4L-ED",1,1);	add_groupsub($id_group,"??wpk",3,1);		
$id_group=add_student_group("LEK/Eng-Div-6/19/20","3L-ED",1,1);	add_groupsub($id_group,"??wpk",6,1);		
$id_group=add_student_group("LEK/Eng-Div-6/20/21","2L-ED",1,1);	add_groupsub($id_group,"??w",2,1);		
$id_group=add_student_group("LEK/S/16/17","6L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"??wp",3,1);	
$id_group=add_student_group("LEK/S/17/18","5L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"??w",5,1);	
$id_group=add_student_group("LEK/S/18/19","4L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"??w",4,1);	
$id_group=add_student_group("LEK/S/19/20","3L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"sym",12,1);	add_groupsub($id_group,"??w",5,1);
$id_group=add_student_group("LEK/S/20/21","2L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"??w",5,1);	
$id_group=add_student_group("LEK/S/21/22","1L",1,1);	add_groupsub($id_group,"??wpk",20,1);	add_groupsub($id_group,"??w",6,1);	
$id_group=add_student_group("P/IIst./S/2020/2021","2PII",2,1);			
$id_group=add_student_group("P/Ist./S/19/20","3P",2,1);	add_groupsub($id_group,"??wp",12,1);	add_groupsub($id_group,"sym",12,1);	
$id_group=add_student_group("P/Ist./S/2020/2021","2P",2,1);	add_groupsub($id_group,"??wp",12,1);	add_groupsub($id_group,"sym",12,1);	add_groupsub($id_group,"??w",2,1);
$id_group=add_student_group("PIEL/IIst./S/2021/2022","1PII",2,1);	add_groupsub($id_group,"??w",3,1);		
$id_group=add_student_group("PIEL/Ist./S/2021/2022","1P",2,1);	add_groupsub($id_group,"??wp",16,1);	add_groupsub($id_group,"??w",2,1);	
$id_group=add_student_group("Po/IIst./S/2020/2021","2PoII",2,1);			
$id_group=add_student_group("Po/Is./S/19/20","3Po",2,1);	add_groupsub($id_group,"??wp",12,1);	add_groupsub($id_group,"sym",12,1);	
$id_group=add_student_group("Po/Ist./S/2020/2021","2Po",2,1);	add_groupsub($id_group,"??wp",6,1);	add_groupsub($id_group,"sym",11,1);	
$id_group=add_student_group("PO??/IIst./S/2021/2022","1PoII",2,1);	add_groupsub($id_group,"??w",2,1);		
$id_group=add_student_group("PO??/Ist./S/2021/2022","1Po",2,1);	add_groupsub($id_group,"??wp",12,1);	add_groupsub($id_group,"??w",3,1);	
$id_group=add_student_group("RM/I stopie??/niestacjonarne/2019/20","2RMx",3,0);	add_groupsub($id_group,"??wp",2,0);	add_groupsub($id_group,"??w",1,0);	
$id_group=add_student_group("RM/I stopie??/st/2019/20","3RM",3,0);	add_groupsub($id_group,"??wp",4,0);	add_groupsub($id_group,"??w",2,0);	
$id_group=add_student_group("RM/Ist/S/2020/2021","2RM",3,0);	add_groupsub($id_group,"??wp",5,0);	add_groupsub($id_group,"??w",2,0);	
$id_group=add_student_group("RM/Ist/S/2021/2022","1RM",3,0);	add_groupsub($id_group,"??wp",5,0);	add_groupsub($id_group,"??w",2,0);	
$id_group=add_student_group("RM/Ist/S/2021/2022/s","1RMx",3,0);	add_groupsub($id_group,"??wp",5,0);		



// $aF_simmed_date=date('Y-m-d');
// $aF_simmed_technician_character_id=TechnicianCharacter::where('character_short','stay')->first()->id;

// insert_sims($aF_simmed_date, '07:30', '13:00', '_Temat testowy', 'RokTestowy', 'D 0.10', 'mbaczek', 'sebek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '12:00', '15:30', '_Temat testowy', 'RokTestowy', 'C 2.07', 'mbaczek', 'paulina', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '07:30', '13:00', '_Temat testowy', 'RokTestowy', 'C 2.07', 'mbaczek', 'darek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '12:00', '15:30', '_Temat testowy', 'RokTestowy', 'C 2.10', 'mbaczek', 'wojtek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '09:30', '15:00', '_Temat testowy', 'RokTestowy', 'D 0.09', 'mbaczek', 'marcin', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '14:00', '17:30', '_Temat testowy', 'RokTestowy', 'D 0.10', 'mbaczek', 'bartek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '13:30', '19:00', '_Temat testowy', 'RokTestowy', 'D 0.09', 'mbaczek', 'marcin', $aF_simmed_technician_character_id, 1);

// $aF_simmed_technician_character_id=TechnicianCharacter::where('character_short','phone')->first()->id;
// insert_sims($aF_simmed_date, '13:00', '16:30', '_Temat testowy', 'RokTestowy', 'B 3.34', 'pkrzciuk', 'bartek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '13:00', '16:30', '_Temat testowy', 'RokTestowy', 'B 3.37', 'pkrzciuk', 'bartek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '14:00', '16:30', '_Temat testowy', 'RokTestowy', 'B 3.34', 'pkrzciuk', 'darek', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '14:00', '16:30', '_Temat testowy', 'RokTestowy', 'B 3.37', 'pkrzciuk', 'darek', $aF_simmed_technician_character_id, 1);


// $aF_simmed_technician_character_id=TechnicianCharacter::where('character_short','prep')->first()->id;
// insert_sims($aF_simmed_date, '09:30', '15:00', '_Temat testowy', 'RokTestowy', 'A 1.01', 'pkrzciuk', 'marcin', $aF_simmed_technician_character_id, 1);
// insert_sims($aF_simmed_date, '13:30', '17:00', '_Temat testowy', 'RokTestowy', 'A 1.02', 'pkrzciuk', 'bartek', $aF_simmed_technician_character_id, 1);

    }
}
