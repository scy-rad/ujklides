<?php

use Illuminate\Database\Seeder;

use App\Param;
use App\User;




class ParamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $zmEQ = new Param();

        $leader_id = User::where('name','nikt')->first()->id;
        $technician_id = User::where('name','nikt')->first()->id;

        $zmEQ->leader_for_simmed = $leader_id;
        $zmEQ->technician_for_simmed = $technician_id;

        $zmEQ->save();
        return $zmEQ;


    }
}
