<?php
//php71-cli artisan make:controller RoomStoragesController --resource --model=RoomStorages
use Illuminate\Database\Seeder;

use App\ThingTypes;
use App\Thing;
use App\Rooms;


class ThingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'łóżko szpitalne';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'leżanka stacjonarna';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'wózek do przewożenia chorych';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'wózek inwalidzki';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'wózek wielofunkcyjny reanimacyjny';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'szafka przyłóżkowa';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'stojak do kroplówki';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'wózek oddziałowy';
        $zmEQ->save();

        $zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'wózek zabiegowy';
        $zmEQ->save();
		
		$zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'pompa infuzyjna';
        $zmEQ->save();
		
		$zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'ssak elektryczny';
        $zmEQ->save();
		
		$zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'aparat EKG';
        $zmEQ->save();
		
		$zmEQ = new ThingTypes();
        $zmEQ->thing_types_name = 'respirator';
        $zmEQ->save();

    }
}
