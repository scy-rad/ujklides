<?php
//php artisan db:seed --class=ScenariosTableSeeder
use Illuminate\Database\Seeder;
use App\Scenario;
use App\User;
use App\StudentSubject;
use App\ScenarioForSubject;
use App\ScenarioFile;
use App\Simmed;
use App\ScenarioForSimmed;

class ScenariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		function add_scenario($aF_scenario_author, $aF_scenario_type, $aF_scenario_name, $aF_scenario_code, $aF_scenario_main_problem, $aF_scenario_description, $aF_for_subjects, $aF_files)
			{
			$author_id = User::where('name',$aF_scenario_author)->first()->id;
			//$subject_id = StudentSubject::where('student_subject_name','Opieka paliatywna')->first()->id;

			$zmEQ = new Scenario();
			$zmEQ->scenario_author_id=$author_id;
			$zmEQ->scenario_type=$aF_scenario_type;
			$zmEQ->scenario_name=$aF_scenario_name;
			$zmEQ->scenario_code=$aF_scenario_code;
			$zmEQ->scenario_main_problem=$aF_scenario_main_problem;
			$zmEQ->scenario_description=$aF_scenario_description;
			$zmEQ->save();

			foreach ($aF_for_subjects as $aF_subjects)
				{
				$student_subject_id = StudentSubject::where('student_subject_name',$aF_subjects)->first()->id;

				$zmSU = new ScenarioForSubject();
				$zmSU->scenario_id=$zmEQ->id;
				$zmSU->student_subject_id=$student_subject_id;
				$zmSU->save();
				}

			$type=1;
			/*
			foreach ($aF_files as $aF_file)
				{
				$zmEF = new ScenarioFile();
				$zmEF->scenario_id=$zmEQ->id;
				$zmEF->scenario_file_filename=$aF_file;
				$zmEF->scenario_file_title=substr($aF_file, 0, -5);
				$zmEF->scenario_file_type=$type++;
				$zmEF->save();
				}
			*/

			}

		function SimScen($aF_date,$aF_time,$aF_leader,$aF_simcode)
			{
			$leader_id = User::where('name',$aF_leader)->first()->id;
			$simmed_id = Simmed::where('simmed_date',$aF_date)->where('simmed_time_begin',$aF_time)->where('simmed_leader_id',$leader_id)->first()->id;

			foreach ($aF_simcode as $aF_code)
				{
				$scenario_id=Scenario::where('scenario_code',$aF_code)->first()->id;

				$zmEQ = new ScenarioForSimmed();
				$zmEQ->scenario_id=$scenario_id;
				$zmEQ->simmed_id=$simmed_id;
				$zmEQ->save();

				}
			}




    }
}
