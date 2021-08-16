<?php

use Illuminate\Database\Seeder;

use App\Libraries;

class LibrariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*###########################*\
        |  DEVICE                     |
        \*###########################*/
        
        $zmEQ = new Libraries();
        $zmEQ->lib_type = 1;    // device type
        $zmEQ->lib_sort = 1;
        $zmEQ->lib_name = 'symulator multi';
        $zmEQ->lib_description = 'Symulator interdyscyplinarny';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 1;    // device type
        $zmEQ->lib_sort = 2;
        $zmEQ->lib_name = 'symulator mono';
        $zmEQ->lib_description = 'Symulator specjalistyczny';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 1;    // device type
        $zmEQ->lib_sort = 3;
        $zmEQ->lib_name = 'trenażer';
        $zmEQ->lib_description = 'trenażer - opis długi';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 1;    // device type
        $zmEQ->lib_sort = 4;
        $zmEQ->lib_name = 'pozoracje';
        $zmEQ->lib_description = 'elementy oraz zestawy do pozoracji ran, niepełnosprawności';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 1;    // device type
        $zmEQ->lib_sort = 5;
        $zmEQ->lib_name = 'inne';
        $zmEQ->lib_description = 'inne - opis długi';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        /*###########################*\
        |  ACADEMIC TITLE             |
        \*###########################*/
        
        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 1;
        $zmEQ->lib_name = ' ';
        $zmEQ->lib_description = 'brak informacji';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 10;
        $zmEQ->lib_name = 'mgr piel.';
        $zmEQ->lib_description = 'mgr piel.';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 11;
        $zmEQ->lib_name = 'dr';
        $zmEQ->lib_description = 'dr';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 20;
        $zmEQ->lib_name = 'lek. med.';
        $zmEQ->lib_description = 'lek. med.';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        
        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 30;
        $zmEQ->lib_name = 'mgr';
        $zmEQ->lib_description = 'mgr';
        $zmEQ->lib_status = 1;
        $zmEQ->save();

        
        $zmEQ = new Libraries();
        $zmEQ->lib_type = 2;    // academic title
        $zmEQ->lib_sort = 31;
        $zmEQ->lib_name = 'mgr inż.';
        $zmEQ->lib_description = 'mgr inż.';
        $zmEQ->lib_status = 1;
        $zmEQ->save();



    }
}
