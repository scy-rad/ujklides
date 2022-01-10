<?php
//php artisan db:seed --class=TechnicianCharactersTableSeeder
use Illuminate\Database\Seeder;

use App\TechnicianCharacter;


class TechnicianCharactersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


$zmEQ = new TechnicianCharacter();
$zmEQ->id=1;
$zmEQ->character_name='do ustalenia';
$zmEQ->character_short='look';
$zmEQ->save();
$zmEQ = new TechnicianCharacter();
$zmEQ->character_name='zajęcia bez obsługi';
$zmEQ->character_short='free';
$zmEQ->save();
$zmEQ = new TechnicianCharacter();
$zmEQ->character_name='przygotowanie';
$zmEQ->character_short='prep';
$zmEQ->save();
$zmEQ = new TechnicianCharacter();
$zmEQ->character_name='dostępny telefonicznie';
$zmEQ->character_short='phon';
$zmEQ->save();
$zmEQ = new TechnicianCharacter();
$zmEQ->character_name='obecność';
$zmEQ->character_short='stay';
$zmEQ->save();



    }
}
